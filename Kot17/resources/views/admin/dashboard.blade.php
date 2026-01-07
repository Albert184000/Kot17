@extends('layouts.admin')

@section('content')
<div class="space-y-6 md:space-y-8 mb-12 px-2 md:px-0">
    @php
    // ១. Collections & Defaults
    $admin       = $admin ?? null;

    $mahaTheras  = collect($mahaTheras ?? []);
    $seniorMonks = collect($seniorMonks ?? []);
    $monks       = collect($monks ?? []);

    // ✅ 3 officers
    $treasurer   = $treasurer ?? null;              // role: treasurer
    $collectors  = collect($collectors ?? []);      // role: collector
    $utilities   = collect($utilities ?? ($utilitiesTreasurers ?? [])); // role: utility

    // ✅ students below all
    $students    = collect($students ?? []);

    // ២. Sort monks by vassa desc then name
    $sortFn = fn($x) => [-(int)($x->vassa ?? 0), (string)($x->name ?? '')];
    $seniorMonks = $seniorMonks->sortBy($sortFn)->values();
    $monks       = $monks->sortBy($sortFn)->values();

    // ៣. counts
    $leftCount  = $mahaTheras->count() + $seniorMonks->count() + $monks->count();
    $rightCount = ($treasurer ? 1 : 0) + $utilities->count() + $collectors->count() + $students->count();
    $totalOrg   = $leftCount + $rightCount + ($admin ? 1 : 0);
@endphp


    {{-- ================= ផ្នែកស្ថិតិសង្ខេប (TOP STATS) ================= --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6">
        @php
            $topStats = [
                ['label' => 'សមាជិកសរុប', 'value' => number_format($totalMembers ?? 0), 'icon' => 'fa-users', 'color' => 'blue'],
                ['label' => 'សាមណេរ/ភិក្ខុ', 'value' => $monks->count(), 'icon' => 'fa-user-graduate', 'color' => 'orange'],
                ['label' => 'អ្នកកាន់លុយទឹកភ្លើង', 'value' => $utilities->count(), 'icon' => 'fa-bolt', 'color' => 'emerald'],

                ['label' => 'វត្តមានឆាន់ថ្ងៃនេះ', 'value' => ($presentCount ?? 0).' នាក់', 'icon' => 'fa-utensils', 'color' => 'rose'],
            ];
        @endphp

        @foreach($topStats as $s)
            <div class="bg-white p-4 md:p-5 rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-slate-50 flex flex-col md:flex-row items-center md:items-start gap-3 text-center md:text-left">
                <div class="p-3 bg-{{ $s['color'] == 'emerald' ? 'emerald' : ($s['color'] == 'rose' ? 'red' : $s['color']) }}-50 text-{{ $s['color'] == 'emerald' ? 'emerald' : ($s['color'] == 'rose' ? 'red' : $s['color']) }}-600 rounded-2xl">
                    <i class="fas {{ $s['icon'] }} text-lg"></i>
                </div>
                <div>
                    <p class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $s['label'] }}</p>
                    <p class="text-base md:text-xl font-black text-slate-800">{{ $s['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ================= រចនាសម្ពន្ធគ្រប់គ្រង (ORG CHART) ================= --}}
    <div class="bg-white p-6 md:p-12 rounded-[2rem] md:rounded-[3rem] shadow-sm border border-slate-100">
        {{-- Header Section --}}
        <div class="flex flex-col items-center mb-12">
            <h2 class="text-xl md:text-2xl font-black text-slate-800 uppercase tracking-[0.2em] mb-3">រចនាសម្ពន្ធគ្រប់គ្រងកុដិ</h2>
            <div class="h-1.5 w-20 bg-gradient-to-r from-orange-400 to-orange-600 rounded-full mb-4"></div>
            <span class="px-5 py-1.5 bg-slate-50 border border-slate-100 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest">
                Total Members: {{ $totalOrg }}
            </span>
        </div>

        {{-- 1. ROOT (Head of Organization) --}}
        <div class="flex flex-col items-center mb-16">
            <div class="relative group">
                <div class="p-1.5 rounded-full bg-white ring-4 ring-orange-500/10 shadow-xl group-hover:scale-105 transition-transform duration-500">
                    <div class="rounded-full ring-2 ring-white overflow-hidden">
                        {!! avatar($admin ?? auth()->user(), 'w-24 h-24 md:w-32 md:h-32 object-cover') !!}
                    </div>
                </div>
                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[8px] font-black px-4 py-1.5 rounded-full shadow-xl border border-white/20">
                    ADMIN
                </div>
            </div>
            <div class="mt-6 text-center">
                <h3 class="font-black text-slate-800 text-lg md:text-xl">{{ ($admin ?? auth()->user())->name }}</h3>
                <p class="text-[10px] md:text-xs font-bold text-orange-600 uppercase tracking-[0.3em] mt-1">ប្រធានកុដិ / មេកុដិ</p>
            </div>
            <div class="h-12 w-px bg-gradient-to-b from-orange-500/50 to-transparent mt-4"></div>
        </div>

        {{-- 2. DUAL COLUMNS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
            
            {{-- LEFT: ផ្នែកព្រះសង្ឃ --}}
            <div class="space-y-10">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="h-px flex-1 bg-slate-100"></div>
                    <span class="px-6 py-2 bg-orange-600 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-200">
                        <i class="fas fa-dharmachakra mr-2"></i> ផ្នែកព្រះសង្ឃ
                    </span>
                    <div class="h-px flex-1 bg-slate-100"></div>
                </div>

                {{-- Maha Thera Card --}}
                @foreach($mahaTheras as $mt)
                <div class="bg-white p-6 rounded-[2rem] border border-orange-100 shadow-sm flex flex-col items-center text-center max-w-sm mx-auto">
                    <div class="ring-4 ring-orange-50 shadow-md rounded-full mb-4">
                        {!! avatar($mt, 'w-20 h-20') !!}
                    </div>
                    <h4 class="font-black text-slate-800 text-base">{{ $mt->name }}</h4>
                    <p class="text-[9px] font-bold text-orange-600 uppercase mt-1">ព្រះមហាថេរ</p>
                </div>
                @endforeach

                {{-- Bhikkhu & Novice Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($seniorMonks->merge($monks) as $m)
                    <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100 flex flex-col items-center hover:bg-white hover:shadow-md transition duration-300">
                        {!! avatar($m, 'w-12 h-12') !!}
                        <p class="text-[10px] font-black text-slate-700 mt-3 truncate w-full text-center">{{ $m->name }}</p>
                        <p class="text-[8px] font-bold text-orange-500 uppercase">{{ $m->monk_rank == 'senior_monk' ? 'ព្រះភិក្ខុ' : 'សាមណេរ' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT: មន្ត្រីកុដិ និងសិស្ស (ដូចក្នុងរូបភាព Screenshot) --}}
            {{-- RIGHT: មន្ត្រីកុដិ (៣ role) + កូនសិស្សនៅក្រោម --}}
<div class="space-y-10">
    <div class="flex items-center justify-center gap-3 mb-6">
        <div class="h-px flex-1 bg-slate-100"></div>
        <span class="px-6 py-2 bg-blue-600 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-200">
            <i class="fas fa-users mr-2"></i> គណៈកម្មាការកុដិ
        </span>
        <div class="h-px flex-1 bg-slate-100"></div>
    </div>

    {{-- ================== OFFICERS (3 ROLES) ================== --}}
    <div class="space-y-8">

        {{-- 1) Treasurer (single main card) --}}
        @if($treasurer)
            <div class="bg-blue-50/30 p-6 rounded-[2.5rem] border border-blue-100 flex flex-col items-center text-center max-w-sm mx-auto">
                <div class="ring-4 ring-white shadow-xl rounded-full mb-4 overflow-hidden">
                    {!! avatar($treasurer, 'w-20 h-20') !!}
                </div>
                <h4 class="font-black text-slate-800 text-base">{{ $treasurer->name }}</h4>
                <p class="text-[9px] font-bold text-blue-600 uppercase mt-1">ហេរញ្ញិកកុដិ</p>
            </div>
        @endif

        {{-- 2) Utility + 3) Collectors (same row grid) --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">

            {{-- Utility (can be 1 or many) --}}
            @forelse($utilities as $u)
                <div class="bg-white p-5 rounded-[2rem] border border-emerald-100 shadow-sm flex flex-col items-center hover:shadow-lg transition duration-500 group">
                    <div class="w-16 h-16 rounded-full overflow-hidden ring-4 ring-emerald-50 mb-3 group-hover:scale-110 transition">
                        {!! avatar($u, 'w-full h-full object-cover') !!}
                    </div>
                    <h5 class="font-black text-slate-700 text-xs text-center truncate w-full">{{ $u->name }}</h5>
                    <p class="text-[8px] font-black text-emerald-600 uppercase tracking-tighter mt-1">Utility (ទឹក / ភ្លើង)</p>
                </div>
            @empty
                {{-- optional empty state --}}
            @endforelse

            {{-- Collectors (can be many) --}}
            @forelse($collectors as $c)
                <div class="bg-white p-5 rounded-[2rem] border border-amber-100 shadow-sm flex flex-col items-center hover:shadow-lg transition duration-500 group">
                    <div class="w-16 h-16 rounded-full overflow-hidden ring-4 ring-amber-50 mb-3 group-hover:scale-110 transition">
                        {!! avatar($c, 'w-full h-full object-cover') !!}
                    </div>
                    <h5 class="font-black text-slate-700 text-xs text-center truncate w-full">{{ $c->name }}</h5>
                    <p class="text-[8px] font-black text-amber-600 uppercase mt-1">អ្នកប្រមូលលុយ</p>
                </div>
            @empty
                {{-- optional empty state --}}
            @endforelse
        </div>
    </div>

    {{-- ================== STUDENTS (ALL BELOW) ================== --}}
    <div class="pt-6">
        <div class="flex items-center justify-center gap-3 mb-4">
            <div class="h-px flex-1 bg-slate-100"></div>
            <span class="px-5 py-1.5 bg-slate-50 border border-slate-100 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest">
                កូនសិស្ស ({{ $students->count() }})
            </span>
            <div class="h-px flex-1 bg-slate-100"></div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @forelse($students as $st)
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center hover:shadow-lg transition duration-500 group">
                    <div class="w-14 h-14 rounded-full overflow-hidden ring-4 ring-slate-50 mb-3 group-hover:scale-110 transition">
                        {!! avatar($st, 'w-full h-full object-cover') !!}
                    </div>
                    <h5 class="font-black text-slate-700 text-[11px] text-center truncate w-full">{{ $st->name }}</h5>
                    <p class="text-[8px] font-black text-slate-400 uppercase mt-1">សិស្សកុដិ</p>
                </div>
            @empty
                <div class="col-span-2 sm:col-span-3 text-center text-slate-300 italic py-8">
                    គ្មានកូនសិស្ស
                </div>
            @endforelse
        </div>
    </div>
</div>


        </div>
    </div>

    {{-- ================= សកម្មភាពបច្ច័យ (TRANSACTIONS) ================= --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-lg font-black text-slate-800">សកម្មភាពបច្ច័យចូលចុងក្រោយ</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Real-time Transaction History</p>
            </div>
            <a href="{{ route('treasurer.donations.index') }}" class="bg-slate-900 text-white px-8 py-3 rounded-2xl text-[10px] font-black hover:bg-orange-600 transition shadow-xl uppercase tracking-widest">
                View All Records <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="overflow-x-auto p-4">
            <table class="w-full border-separate border-spacing-y-3">
                <tbody>
                    @forelse($recentDonations as $donation)
                        <tr class="bg-slate-50/50 hover:bg-white transition shadow-sm rounded-2xl overflow-hidden group">
                            <td class="py-4 px-6 rounded-l-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-green-600">
                                        <i class="fas fa-arrow-down text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-700 text-sm truncate max-w-[150px]">
                                            {{ $donation->user->name ?? ($donation->donor_name ?? 'សប្បុរសជន') }}
                                        </p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase">{{ optional($donation->created_at)->format('d M, Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right rounded-r-2xl">
                                <span class="text-green-600 font-black text-base md:text-lg">
                                    +${{ number_format($donation->amount ?? 0, 2) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-16 text-center text-slate-300 italic">
                                <i class="fas fa-receipt text-4xl mb-4 block opacity-20"></i>
                                គ្មានសកម្មភាពបច្ច័យថ្មីៗ
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* បន្ថែមស្ទីលបន្តិចបន្តួចសម្រាប់ភាពរលូន */
    .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>
@endsection