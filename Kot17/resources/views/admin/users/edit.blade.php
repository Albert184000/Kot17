    @extends('layouts.admin')
    @section('title', 'កែប្រែអ្នកប្រើប្រាស់')

    @section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-800">កែប្រែអ្នកប្រើប្រាស់</h3>
            <a href="{{ route('admin.users.index') }}" class="text-slate-600 hover:text-orange-600 font-bold transition">
                <i class="fas fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Errors --}}
            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-100 rounded-xl text-red-700">
                    <ul class="list-disc ml-5 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Avatar --}}
            <div class="flex items-center gap-5">
                @php
                    $avatarUrl = $user->avatar
                        ? asset('storage/'.$user->avatar)
                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random';
                @endphp
                <img id="avatarPreview" src="{{ $avatarUrl }}" class="w-20 h-20 rounded-full object-cover border border-gray-200 shadow-sm">
                <div class="flex-1">
                    <label class="text-sm font-bold text-slate-700">រូប Profile (Avatar)</label>
                    <input type="file" name="avatar" accept="image/*"
                        class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2 bg-white focus:ring-4 focus:ring-orange-100 outline-none">
                    <p class="text-xs text-slate-400 mt-2">JPG/PNG/WebP • Max 2MB</p>
                </div>
            </div>

            {{-- General --}}
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="text-sm font-bold text-slate-700">ឈ្មោះ *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-orange-100 outline-none">
                </div>
                <div>
                    <label class="text-sm font-bold text-slate-700">អ៊ីមែល *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-orange-100 outline-none">
                </div>
                <div>
                    <label class="text-sm font-bold text-slate-700">លេខទូរស័ព្ទ</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-orange-100 outline-none">
                </div>

                {{-- ✅ Role (ADD utility) --}}
                <div>
                    <label class="text-sm font-bold text-slate-700">តួនាទី (Role)</label>
                    @php
                        $roles = [
                            'admin'     => 'ADMIN',
                            'treasurer' => 'TREASURER',
                            'collector' => 'COLLECTOR',
                            'utility'   => 'UTILITY',
                            'member'    => 'MEMBER',
                        ];
                        $currentRole = old('role', $user->role);
                    @endphp

                    <select name="role" id="roleSelect"
                            class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-orange-100 outline-none">
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" @selected($currentRole === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ✅ Utilities section (show only when role=utility) --}}
            <div id="utilitiesSection"
                class="p-5 bg-blue-50/50 rounded-2xl border border-blue-100 grid md:grid-cols-2 gap-5 {{ $currentRole === 'utility' ? '' : 'hidden' }}">
                <div>
                    <label class="text-sm font-bold text-slate-700">ការទទួលខុសត្រូវ Utilities</label>
                    <div class="mt-3 flex gap-6">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_water_manager" id="isWater" value="1"
                                @checked((bool) old('is_water_manager', $user->is_water_manager))
                                class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-200">
                            <label for="isWater" class="text-sm font-medium text-slate-700 cursor-pointer">អ្នកកាន់ទឹក</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_electric_manager" id="isElectric" value="1"
                                @checked((bool) old('is_electric_manager', $user->is_electric_manager))
                                class="w-5 h-5 rounded border-gray-300 text-yellow-600 focus:ring-yellow-200">
                            <label for="isElectric" class="text-sm font-medium text-slate-700 cursor-pointer">អ្នកកាន់ភ្លើង</label>
                        </div>
                    </div>
                </div>
                <div class="flex items-end">
                    <p class="text-xs text-slate-500 italic">ចំណាំ៖ ជ្រើសរើសក្នុងករណី Role = Utility។</p>
                </div>
            </div>

            {{-- Person type --}}
            <div class="grid md:grid-cols-2 gap-5 p-5 bg-orange-50/50 rounded-2xl border border-orange-100">
                <div>
                    <label class="text-sm font-bold text-slate-700">ប្រភេទបុគ្គល *</label>
                    <select name="person_type" id="personType" required
                            class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-orange-100 outline-none">
                        <option value="lay" @selected(old('person_type', $user->person_type) === 'lay')>គ្រហស្ថ / កូនសិស្ស</option>
                        <option value="monk" @selected(old('person_type', $user->person_type) === 'monk')>ព្រះសង្ឃ</option>
                    </select>
                </div>

                <div id="monkRankSection" class="{{ old('person_type', $user->person_type) === 'monk' ? '' : 'hidden' }}">
                    <label class="text-sm font-bold text-slate-700">លំដាប់ព្រះសង្ឃ *</label>
                    <select name="monk_rank"
                            class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-orange-100 outline-none">
                        <option value="">-- ជ្រើសរើស --</option>
                        
<option value="maha_thera"  @selected(old('monk_rank', $user->monk_rank) === 'maha_thera')>ព្រះមហាថេរ</option>
<option value="bhikkhu"     @selected(old('monk_rank', $user->monk_rank) === 'bhikkhu')>ព្រះភិក្ខុ</option>
<option value="samanera"    @selected(old('monk_rank', $user->monk_rank) === 'samanera')>សាមណេរ</option>

                    </select>
                </div>

                <div id="vassaSection" class="{{ old('person_type', $user->person_type) === 'monk' ? '' : 'hidden' }}">
                    <label class="text-sm font-bold text-slate-700">ចំនួនវស្សា</label>
                    <input type="number" name="vassa" value="{{ old('vassa', $user->vassa) }}"
                        class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-orange-100 outline-none" placeholder="0">
                </div>
            </div>

            {{-- Password --}}
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="text-sm font-bold text-slate-700">លេខសម្ងាត់ថ្មី (ទុកទទេបើមិនប្តូរ)</label>
                    <input type="password" name="password"
                        class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-orange-100 outline-none">
                </div>
                <div>
                    <label class="text-sm font-bold text-slate-700">បញ្ជាក់លេខសម្ងាត់</label>
                    <input type="password" name="password_confirmation"
                        class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:ring-4 focus:ring-orange-100 outline-none">
                </div>
            </div>

            {{-- Active --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="isActive" value="1"
                    @checked((bool) old('is_active', $user->is_active))
                    class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-200">
                <label for="isActive" class="font-bold text-slate-700 cursor-pointer">សកម្ម (Active)</label>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-xl font-bold transition shadow-sm">
                    <i class="fas fa-save mr-2"></i> រក្សាទុកការកែប្រែ
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-8 py-3 rounded-xl font-bold border border-gray-200 text-slate-700 hover:bg-gray-50 transition">
                    បោះបង់
                </a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Preview avatar
        const avatarInput = document.querySelector('input[name="avatar"]');
        const preview = document.getElementById('avatarPreview');
        if (avatarInput && preview) {
            avatarInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) preview.src = URL.createObjectURL(file);
            });
        }

        // Monk fields
        const personTypeSelect = document.getElementById('personType');
        const monkRankSection = document.getElementById('monkRankSection');
        const vassaSection = document.getElementById('vassaSection');

        function toggleMonkFields() {
            if (personTypeSelect.value === 'monk') {
                monkRankSection.classList.remove('hidden');
                vassaSection.classList.remove('hidden');
            } else {
                monkRankSection.classList.add('hidden');
                vassaSection.classList.add('hidden');
                const monkSelect = monkRankSection.querySelector('select');
                const vassaInput = vassaSection.querySelector('input');
                if (monkSelect) monkSelect.value = "";
                if (vassaInput) vassaInput.value = "";
            }
        }

        // ✅ Utilities show/hide by role
        const roleSelect = document.getElementById('roleSelect');
        const utilitiesSection = document.getElementById('utilitiesSection');

        function toggleUtilities() {
            if (roleSelect.value === 'utility') {
                utilitiesSection.classList.remove('hidden');
            } else {
                utilitiesSection.classList.add('hidden');
                // reset checkboxes when not utility
                document.getElementById('isWater').checked = false;
                document.getElementById('isElectric').checked = false;
            }
        }

        personTypeSelect.addEventListener('change', toggleMonkFields);
        roleSelect.addEventListener('change', toggleUtilities);

        // ✅ init on load
        toggleMonkFields();
        toggleUtilities();
    });
    </script>
    @endsection
