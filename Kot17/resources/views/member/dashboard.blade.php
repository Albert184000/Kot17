@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 text-slate-800 font-kantumruy">

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-2xl border border-green-200 font-bold flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- 1) Status --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 mb-8 overflow-hidden relative">
        <div class="absolute top-0 right-0 p-4">
            <span class="flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $myReport ? 'bg-red-400' : 'bg-green-400' }} opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 {{ $myReport ? 'bg-red-500' : 'bg-green-500' }}"></span>
            </span>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-2xl overflow-hidden border-4 border-slate-50 shadow-lg {{ $myReport ? 'grayscale' : '' }}">
                    {!! avatar(auth()->user(), 'w-20 h-20') !!}
                </div>

                <div>
                    <h1 class="text-2xl font-black text-slate-800">សួស្តី, {{ auth()->user()->name }}!</h1>
                    <p class="text-sm font-bold {{ $myReport ? 'text-red-500' : 'text-green-600' }}">
                        បច្ចុប្បន្នភាព៖ {{ $myReport ? 'លោកកំពុងនៅខាងក្រៅ (Offline)' : 'លោកកំពុងនៅកុដិ (Online)' }}
                    </p>
                </div>
            </div>

            <div class="w-full md:w-auto">
                @if(!$myReport)
                    <form action="{{ route('member.skip_meal') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reason" value="ចេញក្រៅ/មិននៅកុដិ">
                        <button type="submit" name="status" value="skip"
                                class="w-full bg-slate-800 hover:bg-red-600 text-white px-10 py-4 rounded-2xl font-black shadow-xl transition-all active:scale-95 flex items-center justify-center gap-3">
                            <i class="fas fa-power-off"></i> ចុចដើម្បីបិទ (OFFLINE)
                        </button>
                    </form>
                @else
                    <form action="{{ route('member.cancel_skip', $myReport->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-green-500 hover:bg-green-600 text-white px-10 py-4 rounded-2xl font-black shadow-xl transition-all active:scale-95 flex items-center justify-center gap-3">
                            <i class="fas fa-sign-in-alt"></i> ចុចដើម្បីបើក (ONLINE)
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- 2) Org Structure (KEEP ONLY ONE) --}}
    <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100 mb-10 text-center">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-widest m-0">
                រចនាសម្ពន្ធគ្រប់គ្រងកុដិ
            </h2>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                Total: {{ ($members->count() ?? 0) + ($collectors->count() ?? 0) + ($admin ? 1 : 0) + ($treasurer ? 1 : 0) }}
            </span>
        </div>

        <div class="flex flex-col items-center">

            {{-- Admin --}}
            @if($admin)
                <div class="flex flex-col items-center">
                    <div class="inline-flex rounded-full ring-4 ring-orange-100">
                        {!! avatar($admin, 'w-24 h-24') !!}
                    </div>

                    <div class="mt-3 bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-100 w-56">
                        <h4 class="font-black text-slate-800 text-sm truncate">{{ $admin->name }}</h4>
                        <span class="text-[10px] font-black text-orange-600 uppercase">ប្រធានកុដិ</span>
                    </div>

                    <div class="h-10 w-px bg-slate-200"></div>
                </div>
            @endif

            {{-- Treasurer --}}
            @if($treasurer)
                <div class="flex flex-col items-center mb-10">
                    <div class="inline-flex rounded-full ring-4 ring-blue-100">
                        {!! avatar($treasurer, 'w-20 h-20') !!}
                    </div>

                    <div class="mt-3 bg-white px-5 py-2 rounded-2xl shadow-sm border border-slate-100 w-52">
                        <h4 class="font-black text-slate-700 text-xs truncate">{{ $treasurer->name }}</h4>
                        <span class="text-[9px] font-black text-blue-600 uppercase">ហេរញ្ញិក</span>
                    </div>

                    <div class="h-10 w-px bg-slate-200"></div>
                </div>
            @endif

            {{-- Collectors --}}
            <div class="w-full max-w-5xl mx-auto mb-10">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">
                    អ្នកប្រមូល ({{ $collectors->count() }})
                </h3>

                <div class="flex flex-wrap justify-center gap-5">
                    @foreach($collectors as $collector)
                        <div class="bg-purple-50 rounded-2xl border border-purple-100 w-44 p-4 text-center hover:shadow-md transition">
                            <div class="inline-flex rounded-full ring-4 ring-purple-100">
                                {!! avatar($collector, 'w-14 h-14') !!}
                            </div>

                            <h4 class="mt-2 font-black text-slate-700 text-[11px] truncate">{{ $collector->name }}</h4>
                            <span class="text-[8px] font-black text-purple-600 uppercase">អ្នកប្រមូល</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Members --}}
            <div class="w-full max-w-6xl mx-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest m-0">
                        សមាជិកទាំងអស់ ({{ $members->count() }})
                    </h3>
                    <span class="text-[10px] text-slate-400 font-bold">scroll for more</span>
                </div>

                <div class="max-h-[360px] overflow-y-auto pr-2">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                        @foreach($members as $member)
                            <div class="flex flex-col items-center bg-slate-50 rounded-2xl p-3 border border-slate-100 hover:bg-white hover:shadow-sm transition">
                                <div class="inline-flex rounded-full ring-4 ring-slate-100">
                                    {!! avatar($member, 'w-12 h-12') !!}
                                </div>
                                <span class="mt-2 text-[10px] font-black text-slate-700 truncate w-full">
                                    {{ $member->name }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- 3) Report Form (keep your current form) --}}
    <div class="max-w-5xl mx-auto mb-10">
        <div class="bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/2 p-10 flex flex-col justify-center bg-white">
                    @if(!$myReport)
                        <div class="mb-6">
                            <h4 class="text-lg font-bold text-slate-800">បញ្ជាក់ព័ត៌មានអវត្តមាន</h4>
                            <p class="text-xs text-slate-400 mt-1 italic">សូមបំពេញមូលហេតុ និងជ្រើសរើសប្រភេទអវត្តមានខាងក្រោម៖</p>
                        </div>

                        <form action="{{ route('member.skip_meal') }}" method="POST" id="reportForm">
                            @csrf

                            <div class="mb-6">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">
                                    មូលហេតុនៃការអវត្តមាន
                                </label>
                                <textarea name="reason" rows="2" required
                                    placeholder="ឧទាហរណ៍៖ និមន្តទៅរៀន, មានធុរៈនៅស្រុកកំណើត, ឬឆាន់ខាងក្រៅ..."
                                    class="w-full px-4 py-3 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none resize-none"></textarea>
                            </div>

                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">ជ្រើសរើសប្រភេទ</label>

                                <button type="submit" name="status" value="skip"
                                    class="group w-full flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-blue-500 hover:bg-blue-50/30 transition-all shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-xl bg-slate-50 group-hover:bg-blue-500 group-hover:text-white flex items-center justify-center transition-all">
                                            <i class="fas fa-walking text-xs"></i>
                                        </div>
                                        <div class="text-left">
                                            <span class="block font-bold text-slate-700 text-sm">និមន្តទៅខាងក្រៅ</span>
                                            <span class="text-[9px] text-slate-400">មិនបានឆាន់នៅកុដិពេញមួយពេល</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-paper-plane text-slate-300 group-hover:text-blue-500 text-[10px] transition-all mr-2"></i>
                                </button>

                                <button type="submit" name="status" value="late"
                                    class="group w-full flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-orange-500 hover:bg-orange-50/30 transition-all shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-xl bg-slate-50 group-hover:bg-orange-500 group-hover:text-white flex items-center justify-center transition-all">
                                            <i class="fas fa-clock text-xs"></i>
                                        </div>
                                        <div class="text-left">
                                            <span class="block font-bold text-slate-700 text-sm">និមន្តមក (យឺត)</span>
                                            <span class="text-[9px] text-slate-400">ឆាន់នៅកុដិធម្មតា តែអាចមកយឺតបន្តិច</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-paper-plane text-slate-300 group-hover:text-orange-500 text-[10px] transition-all mr-2"></i>
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-check text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-black text-slate-800">របាយការណ៍ត្រូវបានផ្ញើ!</h4>

                            <div class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 inline-block w-full">
                                <p class="text-[10px] text-slate-400 uppercase font-black mb-1">មូលហេតុដែលបានបញ្ជាក់</p>
                                <p class="text-sm text-slate-700 font-bold italic">"{{ $myReport->reason }}"</p>
                            </div>

                            <p class="text-[11px] text-slate-400 mt-6 mb-8 leading-relaxed">
                                របាយការណ៍នេះត្រូវបានបញ្ជូនទៅកាន់ក្រុមការងារ Telegram រួចរាល់។
                            </p>

                            <form action="{{ route('member.cancel_skip', $myReport->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-8 py-3 bg-slate-800 hover:bg-red-500 text-white text-[10px] font-bold rounded-full transition-all uppercase tracking-widest shadow-lg active:scale-95">
                                    <i class="fas fa-undo-alt"></i> បោះបង់ និងរាយការណ៍ឡើងវិញ
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- you can add a right panel later --}}
            </div>
        </div>
    </div>

    {{-- 4) Attendance (FAST: no DB query in loop) --}}
    @php
        $actualReports = $todayReports ?? 0;
        $onlineCount = ($totalPeople ?? 0) - $actualReports;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Online --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 bg-green-50/50 border-b border-green-100 flex justify-between items-center">
                <h3 class="font-black text-green-700 uppercase tracking-tighter flex items-center gap-2">
                    <i class="fas fa-users"></i> កំពុងនៅកុដិ ({{ $onlineCount }})
                </h3>
            </div>

            <div class="p-4 max-h-[500px] overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($allMembers as $member)
                    @if(!in_array($member->id, $offlineUserIds ?? []))
                        <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-50 bg-slate-50/50 hover:bg-white hover:shadow-md transition-all">
                            <div class="relative">
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white">
                                    {!! avatar($member, 'w-10 h-10') !!}
                                </div>
                                <span class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs font-black text-slate-700 truncate">{{ $member->name }}</p>
                                <p class="text-[8px] text-gray-400 uppercase font-bold">{{ $member->role }}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Offline --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 bg-red-50/50 border-b border-red-100 flex justify-between items-center">
                <h3 class="font-black text-red-700 uppercase tracking-tighter flex items-center gap-2">
                    <i class="fas fa-walking"></i> កំពុងនៅក្រៅ ({{ $actualReports }})
                </h3>
            </div>

            <div class="p-4 max-h-[500px] overflow-y-auto space-y-3">
                @forelse($allMembers as $member)
                    @php $report = ($todayOfflineReports[$member->id] ?? null); @endphp

                    @if($report)
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-red-50 bg-red-50/30">
                            <div class="flex items-center gap-3">
                                <div class="relative grayscale opacity-70">
                                    <div class="w-10 h-10 rounded-full overflow-hidden">
                                        {!! avatar($member, 'w-10 h-10') !!}
                                    </div>
                                    <span class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-gray-400 border-2 border-white rounded-full"></span>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-700">{{ $member->name }}</p>
                                    <p class="text-[9px] text-red-500 font-bold italic">{{ $report->reason }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] bg-white px-2 py-1 rounded-lg shadow-sm text-slate-400 font-bold">
                                {{ \Carbon\Carbon::parse($report->created_at)->format('H:i') }}
                            </span>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-20 text-slate-300 italic text-sm">មិនទាន់មានសមាជិកចេញក្រៅទេ</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
