<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ✅ Central roles list (one source of truth)
    private const ROLES = ['admin', 'treasurer', 'utility', 'collector', 'member'];
    private const PERSON_TYPES = ['monk', 'lay'];
    private const MONK_RANKS = ['maha_thera', 'senior_monk', 'junior_monk', 'monk'];

    public function index(Request $request)
    {
        $search = $request->input('search');
        $personType = $request->input('person_type');

        $users = User::with(['todayAttendance'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($personType, function ($query, $personType) {
                return $query->where('person_type', $personType);
            })
            ->orderByRaw("
                CASE 
                    WHEN person_type = 'monk' AND monk_rank = 'maha_thera' THEN 1
                    WHEN person_type = 'monk' AND monk_rank = 'senior_monk' THEN 2
                    WHEN person_type = 'monk' AND monk_rank = 'monk' THEN 3
                    WHEN person_type = 'monk' AND monk_rank = 'junior_monk' THEN 4
                    ELSE 5
                END
            ")
            ->orderByDesc('vassa')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $activeUsers = User::where('is_active', true)->get();

        return view('admin.users.index', [
            'users'      => $users,
            'admin'      => $activeUsers->where('role', 'admin')->first(),
            'treasurer'  => $activeUsers->where('role', 'treasurer')->first(),
            'utility'    => $activeUsers->where('role', 'utility')->first(), // ✅ show utility
            'collectors' => $activeUsers->where('role', 'collector'),
            'members'    => $activeUsers->where('role', 'member'),
        ]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'       => ['nullable', 'string', 'max:50'],

            // ✅ FIX: role now supports utility
            'role'        => ['required', Rule::in(self::ROLES)],

            'is_active'   => ['nullable', 'boolean'],
            'person_type' => ['required', Rule::in(self::PERSON_TYPES)],
            'monk_rank'   => ['nullable', 'required_if:person_type,monk', Rule::in(self::MONK_RANKS)],
            'vassa'       => ['nullable', 'required_if:person_type,monk', 'integer', 'min:0'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['password'] = Hash::make($data['password']);

        // Clean monk fields if lay
        if (($data['person_type'] ?? null) === 'lay') {
            $data['monk_rank'] = null;
            $data['vassa'] = null;
        }

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'បានបង្កើតអ្នកប្រើប្រាស់ថ្មីរួចរាល់ ✅');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'       => ['nullable', 'string', 'max:50'],

            // ✅ FIX: no nested validate, supports utility
            'role'        => ['required', Rule::in(self::ROLES)],

            'is_active'   => ['nullable', 'boolean'],
            'person_type' => ['required', Rule::in(self::PERSON_TYPES)],
            'monk_rank'   => ['nullable', 'required_if:person_type,monk', Rule::in(self::MONK_RANKS)],
            'vassa'       => ['nullable', 'required_if:person_type,monk', 'integer', 'min:0'],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['is_active'] = $request->has('is_active');

        // Password update only if filled
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        // Avatar update
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Clean monk fields if lay
        if (($data['person_type'] ?? null) === 'lay') {
            $data['monk_rank'] = null;
            $data['vassa'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'កែប្រែទិន្នន័យបានជោគជ័យ!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'អ្នកមិនអាចលុបគណនីកំពុងប្រើប្រាស់បានទេ!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'បានដាក់អ្នកប្រើប្រាស់ចូលក្នុងធុងសម្រាមរួចរាល់!');
    }

    public function trash()
    {
        $users = User::onlyTrashed()->orderByDesc('deleted_at')->get();
        return view('admin.users.trash', compact('users'));
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')->with('success', 'គណនីត្រូវបានយកមកវិញជោគជ័យ!');
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        try {
            DB::transaction(function () use ($user) {
                // Move donations ownership to current admin to keep balance safe
                DB::table('donations')->where('user_id', $user->id)->update(['user_id' => auth()->id()]);

                // Delete avatar from storage
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $user->forceDelete();
            });

            return back()->with('success', 'គណនីត្រូវបានលុបចេញពីប្រព័ន្ធជាស្ថាពរ!');
        } catch (\Exception $e) {
            return back()->with('error', 'មិនអាចលុបបាន៖ ' . $e->getMessage());
        }
    }
}
