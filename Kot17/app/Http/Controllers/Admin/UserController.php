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
    private const ROLES = ['admin', 'treasurer', 'utility', 'collector', 'member'];
    private const PERSON_TYPES = ['monk', 'lay'];
    private const MONK_RANKS = ['maha_thera', 'bhikkhu', 'samanera'];

    // ✅ Normalize any incoming/old values into the 3 allowed ranks
    private function normalizeMonkRank(?string $rank): ?string
    {
        $rank = (string)($rank ?? '');
        $rank = trim($rank);

        // remove unicode spaces + invisible chars
        $rank = preg_replace('/[\p{Z}\p{Cf}\s]+/u', '', $rank);
        $r = mb_strtolower($rank);

        // ❌ remove samaneri in any form
        if (str_contains($r, 'សាមណេរី') || str_contains($r, 'samaneri')) {
            return null;
        }

        // ✅ map old/khmer -> new
        $map = [
            // Maha Thera
            'maha_thera' => 'maha_thera',
            'maha-thera' => 'maha_thera',
            'មហាថេរ' => 'maha_thera',
            'ព្រះមហាថេរ' => 'maha_thera',

            // Bhikkhu
            'bhikkhu' => 'bhikkhu',
            'monk' => 'bhikkhu',
            'senior_monk' => 'bhikkhu',
            'senior-monk' => 'bhikkhu',
            'ភិក្ខុ' => 'bhikkhu',
            'ព្រះភិក្ខុ' => 'bhikkhu',

            // Samanera
            'samanera' => 'samanera',
            'samoner' => 'samanera',
            'novice' => 'samanera',
            'novicemonk' => 'samanera',
            'novice_monk' => 'samanera',
            'novice-monk' => 'samanera',
            'junior_monk' => 'samanera',
            'junior-monk' => 'samanera',
            'សាមណេរ' => 'samanera',
            'ព្រះសាមណេរ' => 'samanera',
        ];

        if (isset($map[$r])) return $map[$r];

        // fallback contains
        if (str_contains($r, 'maha') || str_contains($r, 'មហាថេរ')) return 'maha_thera';
        if (str_contains($r, 'bhikkhu') || str_contains($r, 'ភិក្ខុ') || str_contains($r, 'senior')) return 'bhikkhu';
        if (str_contains($r, 'saman') || str_contains($r, 'novice') || str_contains($r, 'សាមណេ')) return 'samanera';

        return null;
    }

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
            ->when($personType, fn($query, $personType) => $query->where('person_type', $personType))
            ->orderByRaw("
                CASE
                    WHEN person_type = 'monk' AND monk_rank = 'maha_thera' THEN 1
                    WHEN person_type = 'monk' AND monk_rank = 'bhikkhu' THEN 2
                    WHEN person_type = 'monk' AND monk_rank = 'samanera' THEN 3
                    ELSE 4
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
            'utility'    => $activeUsers->where('role', 'utility')->first(), // if you want many utilities, use ->where('role','utility')
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
        // ✅ normalize BEFORE validate
        $request->merge([
            'monk_rank' => $this->normalizeMonkRank($request->input('monk_rank')),
        ]);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'       => ['nullable', 'string', 'max:50'],

            'role'        => ['required', Rule::in(self::ROLES)],
            'is_active'   => ['nullable', 'boolean'],

            'person_type' => ['required', Rule::in(self::PERSON_TYPES)],
            'monk_rank'   => ['nullable', 'required_if:person_type,monk', Rule::in(self::MONK_RANKS)],
            'vassa'       => ['nullable', 'required_if:person_type,monk', 'integer', 'min:0'],

            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'monk_rank.required_if' => 'សូមជ្រើសថ្នាក់ព្រះសង្ឃ (មហាថេរ/ភិក្ខុ/សាមណេរ)',
            'monk_rank.in'          => 'ថ្នាក់ព្រះសង្ឃមិនត្រឹមត្រូវ (ត្រូវជា មហាថេរ/ភិក្ខុ/សាមណេរ)',
            'vassa.required_if'     => 'សូមបញ្ចូលវស្សា (សម្រាប់ព្រះសង្ឃ)',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['password'] = Hash::make($data['password']);

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
        // ✅ normalize old DB values so edit form won’t break
        if (!empty($user->monk_rank)) {
            $user->monk_rank = $this->normalizeMonkRank($user->monk_rank);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->merge([
            'monk_rank' => $this->normalizeMonkRank($request->input('monk_rank')),
        ]);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'       => ['nullable', 'string', 'max:50'],

            'role'        => ['required', Rule::in(self::ROLES)],
            'is_active'   => ['nullable', 'boolean'],

            'person_type' => ['required', Rule::in(self::PERSON_TYPES)],
            'monk_rank'   => ['nullable', 'required_if:person_type,monk', Rule::in(self::MONK_RANKS)],
            'vassa'       => ['nullable', 'required_if:person_type,monk', 'integer', 'min:0'],

            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'monk_rank.required_if' => 'សូមជ្រើសថ្នាក់ព្រះសង្ឃ (មហាថេរ/ភិក្ខុ/សាមណេរ)',
            'monk_rank.in'          => 'ថ្នាក់ព្រះសង្ឃមិនត្រឹមត្រូវ (ត្រូវជា មហាថេរ/ភិក្ខុ/សាមណេរ)',
            'vassa.required_if'     => 'សូមបញ្ចូលវស្សា (សម្រាប់ព្រះសង្ឃ)',
        ]);

        $data['is_active'] = $request->has('is_active');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

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
                DB::table('donations')->where('user_id', $user->id)->update(['user_id' => auth()->id()]);

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
