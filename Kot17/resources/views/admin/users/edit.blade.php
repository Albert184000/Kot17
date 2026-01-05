@extends('layouts.admin')
@section('title', 'កែប្រែអ្នកប្រើប្រាស់')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <!-- Header -->
    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
        <h3 class="text-xl font-bold text-slate-800">កែប្រែអ្នកប្រើប្រាស់</h3>

        <a href="{{ route('admin.users.index') }}"
           class="text-slate-600 hover:text-orange-600 font-bold transition">
            <i class="fas fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.users.update', $user->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="p-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- Error --}}
        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-100 rounded-xl text-red-700">
                <ul class="list-disc ml-5 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        
        {{-- ✅ Avatar --}}
        <div class="flex items-center gap-5">
            @php
                $avatarUrl = $user->avatar
                    ? asset('storage/'.$user->avatar)
                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random';
            @endphp

            <img id="avatarPreview"
                 src="{{ $avatarUrl }}"
                 class="w-20 h-20 rounded-full object-cover border border-gray-200"
                 alt="avatar">

            <div class="flex-1">
                <label class="text-sm font-bold text-slate-700">រូប Profile (Avatar)</label>
                <input type="file"
                       name="avatar"
                       accept="image/*"
                       class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 bg-white
                              focus:outline-none focus:ring-4 focus:ring-orange-100">
                <p class="text-xs text-slate-400 mt-2">JPG/PNG/WebP • Max 2MB</p>
                @error('avatar') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Basic info -->
        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-bold text-slate-700">ឈ្មោះ</label>
                <input type="text" name="name"
                       value="{{ old('name', $user->name) }}" required
                       class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3
                              focus:outline-none focus:ring-4 focus:ring-orange-100">
            </div>

            <div>
                <label class="text-sm font-bold text-slate-700">អ៊ីមែល</label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}" required
                       class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3
                              focus:outline-none focus:ring-4 focus:ring-orange-100">
            </div>

            <div>
                <label class="text-sm font-bold text-slate-700">លេខទូរស័ព្ទ</label>
                <input type="text" name="phone"
                       value="{{ old('phone', $user->phone) }}"
                       placeholder="012 xxx xxx"
                       class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3
                              focus:outline-none focus:ring-4 focus:ring-orange-100">
            </div>

            <div>
                <label class="text-sm font-bold text-slate-700">តួនាទី</label>
                <select name="role"
                        class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3
                               focus:outline-none focus:ring-4 focus:ring-orange-100">
                    @foreach(['admin','treasurer','collector','member'] as $role)
                        <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>
                            {{ strtoupper($role) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Password -->
        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-bold text-slate-700">
                    Password ថ្មី <span class="text-xs text-slate-400">(optional)</span>
                </label>
                <input type="password" name="password"
                       placeholder="បើមិនប្ដូរ ទុកវាឱ្យទទេ"
                       class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3
                              focus:outline-none focus:ring-4 focus:ring-orange-100">
            </div>

            <div>
                <label class="text-sm font-bold text-slate-700">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3
                              focus:outline-none focus:ring-4 focus:ring-orange-100">
            </div>
        </div>

        <!-- Active -->
        <div class="flex items-center gap-3 pt-2">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $user->is_active))
                   class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-200">
            <span class="font-bold text-slate-700">សកម្ម (Active)</span>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="bg-orange-600 hover:bg-orange-700 text-white
                           px-6 py-3 rounded-xl font-bold transition shadow-sm">
                <i class="fas fa-save mr-2"></i> រក្សាទុក
            </button>

            <a href="{{ route('admin.users.index') }}"
               class="px-6 py-3 rounded-xl font-bold border border-gray-200
                      text-slate-700 hover:bg-gray-50 transition">
                បោះបង់
            </a>
        </div>
    </form>
</div>

{{-- ✅ Preview JS --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('input[name="avatar"]');
    const preview = document.getElementById('avatarPreview');

    if (!input || !preview) return;

    input.addEventListener('change', (e) => {
        const file = e.target.files && e.target.files[0];
        if (!file) return;
        preview.src = URL.createObjectURL(file);
    });
});
</script>
@endsection
