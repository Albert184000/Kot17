@extends('layouts.admin')

@section('content')
@php
    // ===============================
    // ✅ SAFE DEFAULTS
    // ===============================
    $admin      = $admin ?? null;
    $treasurer  = $treasurer ?? null;

    // Utilities: support either $utility (single) or $utilities (collection)
    $utilities = collect($utilities ?? []);
    if (!empty($utility ?? null)) {
        $utilities = $utilities->prepend($utility);
    }
    $utilities = $utilities->filter()->values();

    // Monks
    $mahaTheras  = collect($mahaTheras ?? [])->filter()->values();
    $seniorMonks = collect($seniorMonks ?? [])->filter()->values(); // bhikkhu
    $juniors     = collect($juniors ?? [])->filter()->values();     // samanera

    // Others
    $collectors  = collect($collectors ?? [])->filter()->values();
    $students    = collect($students ?? [])->filter()->values();

    // ===============================
    // ✅ STATUS SOURCE (IMPORTANT)
    // offline = has report today (Donation amount=0) -> controller must send $offlineUserIds
    // ===============================
    $offlineUserIds = $offlineUserIds ?? [];

    $isOnline = function($u) use ($offlineUserIds) {
        if (!$u) return false;
        return !in_array($u->id, $offlineUserIds);
    };

    // ===============================
    // ✅ TOTALS
    // ===============================
    $totalOrg = isset($totalOrg)
        ? (int)$totalOrg
        : ($mahaTheras->count() + $seniorMonks->count() + $juniors->count()
            + ($admin ? 1 : 0) + ($treasurer ? 1 : 0)
            + $collectors->count() + $utilities->count() + $students->count());

    $totalMonks     = $mahaTheras->count() + $seniorMonks->count() + $juniors->count();
    $officersCount  = ($treasurer ? 1 : 0) + $collectors->count() + $utilities->count();

    // Online / Offline (based on offlineUserIds)
    $offlineCount = isset($offlineCount) ? (int)$offlineCount : count($offlineUserIds);
    $onlineCount  = isset($onlineCount)  ? (int)$onlineCount  : max(0, $totalOrg - $offlineCount);

    // ===============================
    // ✅ STATS CARDS
    // ===============================
    $stats = [
        ['label' => 'សមាជិកសរុប',  'value' => $totalOrg,     'icon' => 'fa-users',        'bg' => 'bg-blue-100',    'text' => 'text-blue-600'],
        ['label' => 'ព្រះសង្ឃសរុប', 'value' => $totalMonks,   'icon' => 'fa-dharmachakra', 'bg' => 'bg-orange-100',  'text' => 'text-orange-600'],
        ['label' => 'មន្ត្រីកុដិ', 'value' => $officersCount, 'icon' => 'fa-user-shield',  'bg' => 'bg-emerald-100', 'text' => 'text-emerald-600'],
        ['label' => 'កូនសិស្ស',    'value' => $students->count(), 'icon' => 'fa-user-graduate', 'bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
    ];
@endphp

<div class="max-w-[1400px] mx-auto mb-20 px-4 py-8">

  {{-- ================= 1) STATS (ALL IN ONE + POPUP) ================= --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-6 mb-12">
    @foreach($stats as $s)

        @if($s['label'] === 'សមាជិកសរុប')
            {{-- ✅ Clickable card --}}
            <button type="button" id="openTotalModal"
                class="text-left bg-white/70 backdrop-blur-md p-5 rounded-[2rem] border border-white shadow-sm flex items-center gap-4
                       transition-transform hover:scale-105 duration-300 focus:outline-none focus:ring-4 focus:ring-blue-200">
                <div class="w-12 h-12 rounded-2xl {{ $s['bg'] }} {{ $s['text'] }} flex items-center justify-center shadow-inner">
                    <i class="fas {{ $s['icon'] }} text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $s['label'] }}</p>
                    <p class="text-xl font-black text-slate-800 leading-none mt-1">{{ $s['value'] }}</p>
                    <p class="text-[10px] font-bold text-slate-400 mt-2">ចុចដើម្បីមើល Online/Offline</p>
                </div>
            </button>
        @else
            {{-- Normal card --}}
            <div class="bg-white/70 backdrop-blur-md p-5 rounded-[2rem] border border-white shadow-sm flex items-center gap-4 transition-transform hover:scale-105 duration-300">
                <div class="w-12 h-12 rounded-2xl {{ $s['bg'] }} {{ $s['text'] }} flex items-center justify-center shadow-inner">
                    <i class="fas {{ $s['icon'] }} text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $s['label'] }}</p>
                    <p class="text-xl font-black text-slate-800 leading-none mt-1">{{ $s['value'] }}</p>
                </div>
            </div>
        @endif

    @endforeach
</div>

@php
    // ===============================
    // ✅ COUNT Online/Offline for Monks + Students
    // ===============================
    $totalMonks = (int)($totalMonks ?? 0);
    $studentsTotal = $students->count();

    $monksOnline = $totalMonks;
    $monksOffline = 0;

    foreach ($mahaTheras as $m)  { if(!$isOnline($m)) { $monksOffline++; $monksOnline--; } }
    foreach ($seniorMonks as $m) { if(!$isOnline($m)) { $monksOffline++; $monksOnline--; } }
    foreach ($juniors as $m)     { if(!$isOnline($m)) { $monksOffline++; $monksOnline--; } }

    $studentsOnline = $studentsTotal;
    $studentsOffline = 0;

    foreach ($students as $st) { if(!$isOnline($st)) { $studentsOffline++; $studentsOnline--; } }
@endphp

{{-- ================= POPUP MODAL (Online/Offline) ================= --}}
<div id="totalModal" class="fixed inset-0 z-[9999] hidden">
    {{-- overlay --}}
    <div id="totalModalOverlay" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    {{-- dialog --}}
    <div class="relative w-full h-full flex items-center justify-center p-4">
        <div class="w-full max-w-xl bg-white rounded-[2rem] shadow-2xl border border-white overflow-hidden">

            {{-- header --}}
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ស្ថិតិថ្ងៃនេះ</p>
                    <h3 class="text-lg font-black text-slate-800">សមាជិក Online / Offline</h3>
                </div>

                <button type="button" id="closeTotalModal"
                        class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-500 flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- body --}}
            <div class="p-6 space-y-4">

                {{-- Overall Online/Offline --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-green-100 bg-green-50">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-green-700 uppercase tracking-widest">នៅកុដិ (Online)</p>
                                <p class="text-[11px] font-bold text-slate-500">សមាជិកសរុប</p>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-green-700">{{ $onlineCount }}</p>
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-2xl border border-red-100 bg-red-50">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-red-100 text-red-700 flex items-center justify-center">
                                <i class="fas fa-walking"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-red-700 uppercase tracking-widest">មិននៅកុដិ (Offline)</p>
                                <p class="text-[11px] font-bold text-slate-500">សមាជិកសរុប</p>
                            </div>
                        </div>
                        <p class="text-2xl font-black text-red-700">{{ $offlineCount }}</p>
                    </div>
                </div>

                {{-- ✅ Monks + Students Online/Offline --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">

                    {{-- Monks --}}
                    <div class="p-4 rounded-2xl border border-orange-100 bg-orange-50">
                        <p class="text-[10px] font-black text-orange-700 uppercase tracking-widest">
                            <i class="fas fa-dharmachakra mr-1"></i> ព្រះសង្ឃ (សរុប {{ $totalMonks }})
                        </p>

                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="rounded-xl bg-white border border-emerald-100 p-3">
                                <p class="text-[9px] font-black text-emerald-700 uppercase">នៅកុដិ</p>
                                <p class="text-xl font-black text-emerald-700 mt-1">{{ $monksOnline }}</p>
                            </div>
                            <div class="rounded-xl bg-white border border-red-100 p-3">
                                <p class="text-[9px] font-black text-red-700 uppercase">មិននៅកុដិ</p>
                                <p class="text-xl font-black text-red-700 mt-1">{{ $monksOffline }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Students --}}
                    <div class="p-4 rounded-2xl border border-purple-100 bg-purple-50">
                        <p class="text-[10px] font-black text-purple-700 uppercase tracking-widest">
                            <i class="fas fa-user-graduate mr-1"></i> កូនសិស្ស (សរុប {{ $studentsTotal }})
                        </p>

                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="rounded-xl bg-white border border-emerald-100 p-3">
                                <p class="text-[9px] font-black text-emerald-700 uppercase">នៅកុដិ</p>
                                <p class="text-xl font-black text-emerald-700 mt-1">{{ $studentsOnline }}</p>
                            </div>
                            <div class="rounded-xl bg-white border border-red-100 p-3">
                                <p class="text-[9px] font-black text-red-700 uppercase">មិននៅកុដិ</p>
                                <p class="text-xl font-black text-red-700 mt-1">{{ $studentsOffline }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <button type="button" id="okTotalModal"
                    class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-2xl font-black tracking-wider active:scale-95 transition">
                    OK
                </button>

                <p class="text-[10px] text-slate-400 text-center">
                    * Offline = អ្នកដែលបានរាយការណ៍ (amount=0) នៅថ្ងៃនេះ
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ================= JS (NO PUSH, ALL IN THIS FILE) ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal    = document.getElementById('totalModal');
    const openBtn  = document.getElementById('openTotalModal');
    const closeBtn = document.getElementById('closeTotalModal');
    const okBtn    = document.getElementById('okTotalModal');
    const overlay  = document.getElementById('totalModalOverlay');

    if (!modal || !openBtn) return;

    function openModal() {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (okBtn) okBtn.addEventListener('click', closeModal);
    if (overlay) overlay.addEventListener('click', closeModal);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
});
</script>



    {{-- ================= 2) ORG CHART ================= --}}
    <div class="bg-[#f8fafc] p-6 md:p-20 rounded-[4rem] border border-white shadow-2xl relative overflow-hidden">

        {{-- ===== Level 1: Admin ===== --}}
        <div class="flex flex-col items-center mb-20 relative z-10">
            @php $adminOnline = $admin ? $isOnline($admin) : true; @endphp

            <div class="relative group">
                <div class="p-3 rounded-full bg-gradient-to-tr from-orange-400 to-yellow-300 shadow-2xl ring-4 ring-white relative">
                    <div class="rounded-full ring-4 ring-white overflow-hidden bg-slate-200 shadow-inner {{ $adminOnline ? '' : 'grayscale opacity-70' }}">
                        @if($admin)
                            {!! avatar($admin, 'w-32 h-32 md:w-44 md:h-44 transition-transform hover:scale-110 duration-500') !!}
                        @else
                            <div class="w-32 h-32 md:w-44 md:h-44 flex items-center justify-center text-slate-400 font-black">
                                NO ADMIN
                            </div>
                        @endif
                    </div>

                    {{-- ✅ Status dot --}}
                    @if($admin)
                        <span class="absolute bottom-3 right-3 w-5 h-5 rounded-full border-4 border-white shadow
                            {{ $adminOnline ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}">
                        </span>
                    @endif
                </div>

                <div class="absolute -bottom-5 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-black px-8 py-2.5 rounded-full border-4 border-white shadow-xl tracking-[0.2em]">
                    ADMIN
                </div>
            </div>

            <div class="mt-10 text-center">
                <h3 class="font-black text-slate-800 text-2xl md:text-3xl tracking-tight">
                    {{ $admin->name ?? 'មិនទាន់មាន Admin' }}
                </h3>
                <p class="text-blue-600 font-black uppercase text-xs tracking-widest mt-2">ប្រធានមេកុដិ</p>
                {{-- @if($admin)
                    <span class="inline-flex items-center gap-2 mt-3 px-4 py-1.5 rounded-full text-[10px] font-black border
                        {{ $adminOnline ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                        <span class="w-2.5 h-2.5 rounded-full {{ $adminOnline ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        {{ $adminOnline ? 'នៅកុដិ' : 'មិននៅកុដិ' }}
                    </span>
                @endif --}}
            </div>

            <div class="h-16 w-px bg-gradient-to-b from-orange-300 to-transparent mt-8"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 relative">

            {{-- ================= LEFT: MONKS ================= --}}
            <div class="flex flex-col items-center space-y-12">
                <span class="px-8 py-2.5 bg-white shadow-sm border border-orange-100 text-orange-600 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em]">
                    ផ្នែកព្រះសង្ឃ
                </span>

                {{-- 1) Maha Thera --}}
                
<div class="w-full flex justify-center">
    <div class="grid {{ $mahaTheras->count() > 1 ? 'grid-cols-2' : 'grid-cols-1' }} gap-6">
        @forelse($mahaTheras as $mt)
            @php $mtOnline = $isOnline($mt); @endphp

            <div class="bg-white p-6 rounded-[2.5rem] shadow-xl flex flex-col items-center min-w-[220px]
                        transition-transform hover:-translate-y-2 border-b-4
                        {{ $mtOnline ? 'border-emerald-600' : 'border-red-600' }}">

                {{-- Label (same style as Treasurer) --}}
                <span class="text-[9px] font-black uppercase tracking-[0.2em] mb-4 px-4 py-1 rounded-full
                             {{ $mtOnline ? 'text-emerald-700 bg-emerald-50' : 'text-red-700 bg-red-50' }}">
                    ព្រះមហាថេរ
                </span>

                {{-- Avatar (same style as Treasurer) --}}
                <div class="relative">
                    <div class="{{ $mtOnline ? '' : 'grayscale opacity-70' }}">
                        {!! avatar($mt, 'w-24 h-24 rounded-full ring-4 ' . ($mtOnline ? 'ring-emerald-50' : 'ring-red-50')) !!}
                    </div>

                    {{-- Status dot (same position as Treasurer) --}}
                    <div class="absolute bottom-0 right-0 w-6 h-6 rounded-full border-4 border-white
                                {{ $mtOnline ? 'bg-emerald-500' : 'bg-red-500' }}">
                    </div>
                </div>

                <h4 class="font-black text-slate-800 text-base mt-4 text-center">
                    {{ $mt->name ?? '-' }}
                </h4>

                {{-- ✅ remove vassa on profile (as requested) --}}
                {{-- <span class="text-[8px] text-slate-400 font-bold mt-1 uppercase">
                    វស្សា {{ (int)($mt->vassa ?? 0) }}
                </span> --}}
            </div>

        @empty
            <p class="text-[10px] text-slate-300 italic text-center">មិនមានទិន្នន័យមហាថេរ</p>
        @endforelse
    </div>
</div>


                {{-- 2) Bhikkhu --}}
                <div class="w-full pt-8 border-t border-slate-100 space-y-10">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        @forelse($seniorMonks as $sm)
                            @php $smOnline = $isOnline($sm); @endphp
                            <div class="flex flex-col items-center group">
                                <span class="text-[8px] text-slate-400 font-black uppercase mb-3 tracking-tighter">ភិក្ខុ</span>

                                <div class="p-1 rounded-full transition-colors border relative
                                    {{ $smOnline ? 'bg-emerald-50 border-emerald-100' : 'bg-red-50 border-red-100' }}">

                                    <div class="{{ $smOnline ? '' : 'grayscale opacity-70' }}">
                                        {!! avatar($sm, 'w-16 h-16 rounded-full shadow-sm') !!}
                                    </div>

                                    {{-- ✅ status dot --}}
                                    <span class="absolute bottom-1 right-1 w-4 h-4 rounded-full border-2 border-white shadow
                                        {{ $smOnline ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}">
                                    </span>

                                    {{-- vassa badge --}}
                                    {{-- <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-full flex items-center justify-center text-[8px] font-bold text-orange-600 shadow-sm border border-orange-100">
                                        {{ (int)($sm->vassa ?? 0) }}
                                    </div> --}}
                                </div>

                                <p class="text-[11px] font-bold mt-3 text-center truncate max-w-[120px]
                                    {{ $smOnline ? 'text-slate-700' : 'text-red-600' }}">
                                    {{ $sm->name ?? '-' }}
                                </p>
                            </div>
                        @empty
                            <p class="text-[10px] text-slate-300 italic col-span-3 text-center">មិនមានទិន្នន័យភិក្ខុ</p>
                        @endforelse
                    </div>

                    {{-- 3) Samanera --}}
<div class="w-full pt-8 border-t border-slate-100 space-y-10">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($juniors as $jr)
            @php $jrOnline = $isOnline($jr); @endphp
            <div class="flex flex-col items-center group">
                {{-- Label cloned from Bhikkhu style --}}
                <span class="text-[8px] text-slate-400 font-black uppercase mb-3 tracking-tighter">សាមណេរ</span>

                {{-- Circular Ring Container --}}
                <div class="p-1 rounded-full transition-colors border relative
                    {{ $jrOnline ? 'bg-emerald-50 border-emerald-100' : 'bg-red-50 border-red-100' }}">

                    <div class="{{ $jrOnline ? '' : 'grayscale opacity-70' }}">
                        {{-- Changed from rounded-2xl to rounded-full to match Bhikkhu --}}
                        {!! avatar($jr, 'w-14 h-14 rounded-full shadow-sm') !!}
                    </div>

                    {{-- ✅ status dot --}}
                    <span class="absolute bottom-1 right-1 w-4 h-4 rounded-full border-2 border-white shadow
                        {{ $jrOnline ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}">
                    </span>
                </div>

                <p class="text-[11px] font-bold mt-3 text-center truncate max-w-[120px]
                    {{ $jrOnline ? 'text-slate-700' : 'text-red-600' }}">
                    {{ $jr->name ?? '-' }}
                </p>

                {{-- Optional Vassa text --}}
                @if((int)($jr->vassa ?? 0) > 0)
                    <span class="text-[8px] font-bold text-slate-400 mt-0.5">
                        វស្សា {{ (int)($jr->vassa ?? 0) }}
                    </span>
                @endif
            </div>
        @empty
            <p class="text-[10px] text-slate-300 italic col-span-3 text-center">មិនមានទិន្នន័យសាមណេរ</p>
        @endforelse
    </div>
</div>

                </div>
            </div>

            {{-- ================= RIGHT: OFFICERS ================= --}}
            <div class="flex flex-col items-center space-y-12">
                <span class="px-8 py-2.5 bg-white shadow-sm border border-blue-100 text-blue-600 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em]">
                    គណៈកម្មការកុដិ ១៧
                </span>

                {{-- Treasurer --}}
                @if($treasurer)
                    @php $trOnline = $isOnline($treasurer); @endphp
                    <div class="w-full flex justify-center">
                        <div class="bg-white p-6 rounded-[2.5rem] border-b-4 shadow-xl flex flex-col items-center min-w-[220px] transition-transform hover:-translate-y-2
                            {{ $trOnline ? 'border-blue-600' : 'border-red-400' }}">
                            <span class="text-[9px] text-blue-600 font-black uppercase tracking-[0.2em] mb-4 bg-blue-50 px-4 py-1 rounded-full">
                                ហិរញ្ញិកកុដិ
                            </span>

                            <div class="relative">
                                <div class="{{ $trOnline ? '' : 'grayscale opacity-70' }}">
                                    {!! avatar($treasurer, 'w-24 h-24 rounded-full ring-4 ring-blue-50') !!}
                                </div>

                                {{-- ✅ status dot --}}
                                <span class="absolute bottom-0 right-0 w-6 h-6 rounded-full border-4 border-white
                                    {{ $trOnline ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}">
                                </span>
                            </div>

                            <h4 class="font-black text-slate-800 text-base mt-4">{{ $treasurer->name ?? '-' }}</h4>

                            
                        </div>
                    </div>
                @else
                    <p class="text-[10px] text-slate-300 italic text-center">មិនទាន់មាន ហិរញ្ញិកកុដិ</p>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

                    {{-- Collectors --}}
<div class="bg-white/60 p-6 rounded-[3rem] border border-slate-100 flex flex-col items-center">
    <p class="text-[9px] font-black text-slate-900 uppercase tracking-widest mb-5">អ្នកប្រមូលលុយ</p>

    <div class="flex flex-wrap justify-center gap-8">
        @forelse($collectors as $c)
            @php $cOnline = $isOnline($c); @endphp

            <div class="text-center">
                {{-- avatar (clone like picture) --}}
                <div class="relative inline-block">
                    {{-- outer soft ring --}}
                    <div class="p-2 rounded-full {{ $cOnline ? 'bg-emerald-50/80' : 'bg-red-50/80' }}">
                        {{-- inner ring --}}
                        <div class="p-2 rounded-full bg-white ring-4 shadow-sm
                            {{ $cOnline ? 'ring-emerald-200' : 'ring-red-200' }}">
                            <div class="{{ $cOnline ? '' : 'grayscale opacity-70' }}">
                                {!! avatar($c, 'w-20 h-20 md:w-24 md:h-24 rounded-full object-cover') !!}
                            </div>
                        </div>
                    </div>

                    {{-- BIG status dot --}}
                    <span class="absolute bottom-1 right-1 w-7 h-7 rounded-full border-[5px] border-white shadow
                        {{ $cOnline ? 'bg-emerald-500' : 'bg-red-500' }}">
                    </span>
                </div>

                <p class="text-[12px] font-black mt-4 max-w-[140px] mx-auto truncate
                    {{ $cOnline ? 'text-slate-800' : 'text-red-700' }}">
                    {{ $c->name ?? '-' }}
                </p>

                {{-- <p class="text-[10px] font-bold mt-1
                    {{ $cOnline ? 'text-emerald-600' : 'text-red-500' }}">
                    {{ $cOnline ? 'នៅកុដិ' : 'មិននៅកុដិ' }}
                </p> --}}
            </div>
        @empty
            <p class="text-[10px] text-slate-300 italic">មិនមានអ្នកប្រមូលលុយ</p>
        @endforelse
    </div>
</div>



                    {{-- Utilities --}}
<div class="bg-white/60 p-6 rounded-[3rem] border border-slate-100 flex flex-col items-center">
    <p class="text-[9px] font-black text-slate-900 uppercase tracking-widest mb-5">កាន់ទឹកភ្លើង</p>

    <div class="flex flex-wrap justify-center gap-8">
        @forelse($utilities as $u)
            @php $uOnline = $isOnline($u); @endphp

            <div class="text-center">
                <div class="relative inline-block">
                    <div class="p-2 rounded-full {{ $uOnline ? 'bg-emerald-50/80' : 'bg-red-50/80' }}">
                        <div class="p-2 rounded-full bg-white ring-4 shadow-sm
                            {{ $uOnline ? 'ring-emerald-200' : 'ring-red-200' }}">
                            <div class="{{ $uOnline ? '' : 'grayscale opacity-70' }}">
                                {!! avatar($u, 'w-20 h-20 md:w-24 md:h-24 rounded-full object-cover') !!}
                            </div>
                        </div>
                    </div>

                    <span class="absolute bottom-1 right-1 w-7 h-7 rounded-full border-[5px] border-white shadow
                        {{ $uOnline ? 'bg-emerald-500' : 'bg-red-500' }}">
                    </span>
                </div>

                <p class="text-[12px] font-black mt-4 max-w-[140px] mx-auto truncate
                    {{ $uOnline ? 'text-slate-800' : 'text-red-700' }}">
                    {{ $u->name ?? '-' }}
                </p>

                {{-- <p class="text-[10px] font-bold mt-1
                    {{ $uOnline ? 'text-emerald-600' : 'text-red-500' }}">
                    {{ $uOnline ? 'នៅកុដិ' : 'មិននៅកុដិ' }}
                </p> --}}
            </div>
        @empty
            <p class="text-[10px] text-slate-300 italic">មិនមានអ្នកកាន់ទឹកភ្លើង</p>
        @endforelse
    </div>
</div>


                </div>
            </div>

        </div>

        {{-- ===== Level 3: Students ===== --}}
        <div class="mt-24 pt-16 border-t border-slate-200/60 relative text-center">
            <span class="absolute -top-4 left-1/2 -translate-x-1/2 bg-[#f8fafc] px-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.5em]">
                កូនសិស្សកុដិ ({{ $students->count() }})
            </span>

            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-6">
                @forelse($students as $st)
                    @php $stOnline = $isOnline($st); @endphp
                    <div class="flex flex-col items-center group">
                        <div class="relative {{ $stOnline ? '' : 'grayscale opacity-70' }}">
                            {!! avatar($st, 'w-12 h-12 rounded-2xl group-hover:scale-110 transition-transform shadow-sm') !!}
                            <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 rounded-full border-2 border-white shadow
                                {{ $stOnline ? 'bg-emerald-500' : 'bg-red-500' }}">
                            </span>
                        </div>

                        <span class="text-[9px] font-bold mt-2 truncate w-full text-center transition-colors
                            {{ $stOnline ? 'text-slate-400 group-hover:text-slate-800' : 'text-red-500 group-hover:text-red-700' }}">
                            {{ $st->name ?? '-' }}
                        </span>
                    </div>
                @empty
                    <p class="text-[10px] text-slate-300 italic col-span-10">មិនមានទិន្នន័យកូនសិស្ស</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
