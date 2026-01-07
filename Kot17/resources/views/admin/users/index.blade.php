@extends('layouts.admin')

@section('content')
<div class="space-y-6 pb-20 md:pb-6">
    
    {{-- ================= របារស្វែងរក និង Filter ================= --}}
    <form action="{{ route('admin.users.index') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកឈ្មោះ ឬ អ៊ីមែល..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-orange-500 transition">
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <select name="person_type" onchange="this.form.submit()" class="flex-1 md:flex-none bg-slate-50 border-none rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-orange-500 cursor-pointer font-bold text-slate-600">
                <option value="">ប្រភេទសមាជិកទាំងអស់</option>
                <option value="monk" {{ request('person_type') == 'monk' ? 'selected' : '' }}>ព្រះសង្ឃ</option>
                <option value="lay" {{ request('person_type') == 'lay' ? 'selected' : '' }}>គ្រហស្ថ</option>
            </select>

            @if(request('search') || request('person_type'))
                <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-red-500 transition px-2">
                    <i class="fas fa-times-circle text-lg"></i>
                </a>
            @endif

            {{-- ប៊ូតុងធុងសម្រាម - រៀបចំថ្មីឲ្យស្អាត --}}
            <a href="{{ route('admin.users.trash') }}" class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl font-bold transition">
                <i class="fas fa-trash-alt text-slate-400"></i>
                <span class="hidden sm:inline">ធុងសម្រាម</span>
                <span class="bg-slate-500 text-white text-[10px] px-1.5 py-0.5 rounded-md leading-none">{{ \App\Models\User::onlyTrashed()->count() }}</span>
            </a>

            <a href="{{ route('admin.users.create') }}" class="hidden md:flex bg-orange-600 hover:bg-orange-700 text-white px-6 py-2.5 rounded-xl font-bold transition shadow-lg shadow-orange-100 items-center">
                <i class="fas fa-plus mr-2"></i> បន្ថែមថ្មី
            </a>
        </div>
    </form>

    {{-- ================= បញ្ជីសមាជិក (Desktop Table Mode) ================= --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 uppercase text-[11px] font-black tracking-widest border-b border-gray-50 bg-gray-50/30">
                        <th class="py-4 px-6">ព័ត៌មានសមាជិក</th>
                        <th class="py-4 px-4">តួនាទី</th>
                        <th class="py-4 px-4 text-center">ប្រភេទ/ឋានៈ</th>
                        <th class="py-4 px-4 text-center">ស្ថានភាព</th>
                        <th class="py-4 px-6 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        @php
                            $isOffline = $user->todayAttendance ? true : false;
                            
                            $rolesKh = [
                                'admin' => 'អ្នកគ្រប់គ្រង', 
                                'treasurer' => 'ហេរញ្ញិក', 
                                'collector' => 'អ្នកប្រមូល', 
                                'utilities_treasurer' => 'អ្នកកាន់លុយទឹកភ្លើង', 
                                'member' => 'សមាជិក'
                            ];
                            $roleName = $rolesKh[$user->role] ?? $user->role;

                            $ranksKh = [
                                'maha_thera'  => 'ព្រះមហាថេរ',
                                'senior_monk' => 'ព្រះភិក្ខុ',
                                'junior_monk' => 'សាមណេរ',
                                'monk'        => 'សាមណេរ'
                            ];
                            $rankName = $ranksKh[$user->monk_rank] ?? ($user->person_type === 'monk' ? 'សាមណេរ' : '');

                            $roleColors = [
                                'admin' => 'bg-purple-50 text-purple-600 border-purple-100',
                                'treasurer' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'collector' => 'bg-orange-50 text-orange-600 border-orange-100',
                                'utilities_treasurer' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'member' => 'bg-slate-50 text-slate-500 border-slate-100',
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition group">
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="relative">
                                        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" 
                                             class="w-11 h-11 rounded-full object-cover border-2 {{ $isOffline ? 'border-red-200 opacity-60' : 'border-green-400' }}">
                                        <span class="absolute bottom-0 right-0 w-3 h-3 {{ $isOffline ? 'bg-red-500' : 'bg-green-500 animate-pulse' }} border-2 border-white rounded-full"></span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-black text-slate-700 group-hover:text-orange-600 transition">{{ $user->name }}</div>
                                        <div class="text-[11px] text-slate-400 font-medium">{{ $user->phone ?? 'គ្មានលេខទូរស័ព្ទ' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black border uppercase {{ $roleColors[$user->role] ?? $roleColors['member'] }}">
                                    {{ $roleName }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($user->person_type === 'monk')
                                    <span class="text-orange-700 font-black text-[12px] block">{{ $rankName }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 italic">វស្សា: {{ $user->vassa ?? 0 }}</span>
                                @else
                                    <span class="text-blue-500 font-bold text-[11px] bg-blue-50 px-3 py-1 rounded-full border border-blue-100">គ្រហស្ថ</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center px-3 py-1.5 {{ $isOffline ? 'bg-red-50 text-red-600 border-red-100' : 'bg-green-50 text-green-700 border-green-100' }} rounded-full text-[10px] font-black border">
                                    {{ $isOffline ? 'មិននៅកុដិ' : 'នៅកុដិ' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:text-blue-600 transition border border-transparent hover:border-blue-100 shadow-sm"><i class="fas fa-edit text-xs"></i></a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបបញ្ចូលធុងសម្រាម?')">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:text-red-600 transition border border-transparent hover:border-red-100 shadow-sm"><i class="fas fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-20 text-center text-slate-300">មិនមានទិន្នន័យ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= បញ្ជីសមាជិក (Mobile Card Mode) ================= --}}
    <div class="md:hidden space-y-3">
        @foreach($users as $user)
            @php
                $isOffline = $user->todayAttendance ? true : false;
                $roleKhMob = [
                    'admin' => 'អ្នកគ្រប់គ្រង', 
                    'treasurer' => 'ហេរញ្ញិក', 
                    'collector' => 'អ្នកប្រមូល', 
                    'utilities_treasurer' => 'អ្នកកាន់លុយទឹកភ្លើង',
                    'member' => 'សមាជិក'
                ][$user->role] ?? $user->role;
                
                $rankKhMob = [
                    'maha_thera' => 'ព្រះមហាថេរ', 
                    'senior_monk' => 'ព្រះភិក្ខុ', 
                    'junior_monk' => 'សាមណេរ', 
                    'monk' => 'សាមណេរ'
                ][$user->monk_rank] ?? ($user->person_type === 'monk' ? 'សាមណេរ' : '');
            @endphp
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between active:scale-[0.98] transition-transform">
                <div class="flex items-center">
                    <div class="relative">
                        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" 
                             class="w-14 h-14 rounded-full object-cover border-2 {{ $isOffline ? 'border-red-200 opacity-60' : 'border-green-400' }}">
                        <span class="absolute bottom-0 right-1 w-3.5 h-3.5 {{ $isOffline ? 'bg-red-500' : 'bg-green-500' }} border-2 border-white rounded-full"></span>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-black text-slate-800 text-[15px] leading-tight">{{ $user->name }}</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ $user->phone ?? 'គ្មានលេខទូរស័ព្ទ' }}</p>
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            <span class="px-2 py-0.5 bg-slate-50 text-slate-500 rounded text-[9px] font-black uppercase border border-slate-100">
                                {{ $roleKhMob }}
                            </span>
                            @if($user->person_type === 'monk')
                                <span class="px-2 py-0.5 bg-orange-50 text-orange-600 rounded text-[9px] font-black border border-orange-100">
                                    {{ $rankKhMob }} ({{ $user->vassa ?? 0 }} វស្សា)
                                </span>
                            @else
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-[9px] font-black border border-blue-100">គ្រហស្ថ</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 flex items-center justify-center bg-slate-50 rounded-full text-slate-300">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $users->appends(request()->query())->links() }}
    </div>

    {{-- Floating Action Button (Mobile) --}}
    <a href="{{ route('admin.users.create') }}" class="md:hidden fixed bottom-6 right-6 w-14 h-14 bg-orange-600 text-white rounded-2xl shadow-2xl shadow-orange-200 flex items-center justify-center text-xl z-50 transition-all">
        <i class="fas fa-plus"></i>
    </a>
</div>
@endsection