@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 text-slate-800">
    
    {{-- Status Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-2xl border border-green-200 font-bold flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Welcome Header --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800">សួស្តី, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-gray-500 mt-1 italic">សូមស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រង និងការឆាន់ចង្ហាន់ប្រចាំថ្ងៃរបស់កុដិ ១៧។</p>
        </div>
        <a href="{{ route('member.profile.show') }}" class="bg-slate-800 text-white px-6 py-3 rounded-xl hover:bg-slate-700 transition font-bold text-sm shadow-lg active:scale-95">
            <i class="fas fa-user-circle mr-2"></i> មើលព័ត៌មានផ្ទាល់ខ្លួន
        </a>
    </div>

    {{-- ផ្នែករចនាសម្ពន្ធគ្រប់គ្រងកុដិ --}}
    <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <h2 class="text-center text-2xl font-black text-slate-800 mb-12 uppercase tracking-wide">រចនាសម្ពន្ធគ្រប់គ្រងកុដិ</h2>

        <div class="flex flex-col items-center">
            @if($admin)
            <div class="flex flex-col items-center">
                <div class="text-center border-2 border-orange-500 p-4 rounded-2xl bg-orange-50 shadow-lg w-56">
                    <div class="w-20 h-20 rounded-full mx-auto border-4 border-white shadow-sm overflow-hidden mb-2 bg-slate-200">
                        <img src="{{ $admin->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($admin->name) }}" class="w-full h-full object-cover aspect-square">
                    </div>
                    <h4 class="font-bold text-slate-800">{{ $admin->name }}</h4>
                    <span class="text-[10px] font-black text-orange-600 uppercase">1. ប្រធាន/Admin</span>
                </div>
                <div class="h-8 w-0.5 bg-gray-300"></div>
            </div>
            @endif

            @if($treasurer)
            <div class="flex flex-col items-center">
                <div class="h-0.5 w-16 bg-gray-300"></div>
                <div class="text-center border-2 border-blue-500 p-4 rounded-2xl bg-blue-50 shadow-md w-48">
                    <h4 class="font-bold text-slate-700 text-sm">{{ $treasurer->name }}</h4>
                    <span class="text-[9px] font-bold text-blue-600 uppercase">2. ហេរញ្ញិក/Treasurer</span>
                </div>
                <div class="h-8 w-0.5 bg-gray-300"></div>
            </div>
            @endif

            <div class="flex flex-wrap justify-center gap-6 relative pt-4">
                <div class="absolute top-0 h-0.5 w-1/2 bg-gray-200"></div> 
                @foreach($collectors as $collector)
                <div class="flex flex-col items-center">
                    <div class="h-6 w-0.5 bg-gray-200"></div>
                    <div class="text-center border border-purple-400 p-3 rounded-xl bg-purple-50 w-40 shadow-sm">
                        <h4 class="font-bold text-slate-700 text-xs">{{ $collector->name }}</h4>
                        <span class="text-[8px] font-bold text-purple-600 uppercase">3. អ្នកប្រមូល/Collector</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 w-full border-t border-dashed border-gray-200 pt-10">
                <p class="text-center text-gray-400 text-xs font-bold mb-6 uppercase tracking-widest italic">4. សមាជិកកុដិទាំងអស់ (Members)</p>
                <div class="flex flex-wrap justify-center gap-4 text-center">
                    @foreach($allMembers as $member)
                    <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl flex items-center gap-3 w-44 hover:bg-white hover:shadow-md transition group">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-200 overflow-hidden border-2 border-white shadow-sm">
                            <img src="{{ $member->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->name) }}" class="w-full h-full object-cover aspect-square">
                        </div>
                        <div class="overflow-hidden text-left">
                            <h5 class="text-[11px] font-bold text-slate-700 truncate group-hover:text-blue-600">{{ $member->name }}</h5>
                            <p class="text-[8px] text-slate-400 uppercase tracking-tighter">{{ $member->role }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Skip Meal Form Section --}}
    @php $userRole = strtolower(auth()->user()->role); @endphp
    @if($userRole === 'monk' || $userRole === 'member')
    <div class="bg-gradient-to-br from-slate-50 to-white p-6 rounded-3xl border border-slate-100 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row items-start gap-4">
            <div class="bg-slate-800 p-3 rounded-2xl text-white shadow-lg mx-auto md:mx-0">
                <i class="fas fa-utensils text-xl"></i>
            </div>
            <div class="flex-1 w-full text-center md:text-left">
                <h4 class="font-bold text-slate-900 text-lg">រាយការណ៍ស្ថានភាពឆាន់ភត្តថ្ងៃត្រង់</h4>
                <p class="text-sm text-slate-500 mt-1 mb-4 italic">* បញ្ជាក់មូលហេតុ និងជ្រើសរើសស្ថានភាពខាងក្រោម</p>
                
                <form action="{{ route('member.skip_meal') }}" method="POST">
                    @csrf
                    <div class="mb-4 text-left">
                        <label class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wider">មូលហេតុ/បញ្ជាក់បន្ថែម:</label>
                        <input type="text" name="reason" placeholder="ឧទាហរណ៍៖ និមន្តទៅរៀន, ជាប់ភារកិច្ច..." 
                               class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-slate-800 bg-white text-sm shadow-sm transition-all" required>
                    </div>
                    
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        <button type="submit" name="status" value="skip" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition transform hover:-translate-y-0.5">
                            <i class="fas fa-ban mr-2"></i> មិនបានឆាន់ទេ
                        </button>
                        <button type="submit" name="status" value="late" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition transform hover:-translate-y-0.5">
                            <i class="fas fa-clock mr-2"></i> ឆាន់ដែរ តែមកយឺត
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Statistics Summary --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-5 rounded-3xl shadow-sm text-white">
            <p class="text-orange-100 text-[10px] font-bold uppercase tracking-wider">ព្រះសង្ឃសរុប</p>
            <h3 class="text-2xl font-black mt-1">{{ $countMonks }} អង្គ</h3>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-5 rounded-3xl shadow-sm text-white">
            <p class="text-blue-100 text-[10px] font-bold uppercase tracking-wider">កូនសិស្សសរុប</p>
            <h3 class="text-2xl font-black mt-1">{{ $countStudents }} នាក់</h3>
        </div>
        <div class="bg-slate-800 p-5 rounded-3xl shadow-sm text-white border border-slate-700 col-span-2">
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">សរុបរួមសមាជិកកុដិ</p>
            <h3 class="text-2xl font-black mt-1 text-orange-400">{{ $totalPeople }} អង្គ/នាក់</h3>
        </div>
    </div>

    {{-- បញ្ជីវត្តមានថ្ងៃនេះ (At Kuti vs Away) --}}
    @php 
        $isOpen = now()->format('H') < 12;
        $actualReports = $todayReports ?? 0;
        $onlineCount = ($totalPeople ?? 0) - $actualReports;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        {{-- អ្នកនៅកុដិ --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-green-50 border-b border-green-100 flex justify-between items-center">
                <h3 class="font-bold text-green-700 flex items-center gap-2"><i class="fas fa-home"></i> នៅកុដិ ({{ $onlineCount }})</h3>
                <span class="text-[10px] bg-green-500 text-white px-2 py-0.5 rounded-full animate-pulse">Online</span>
            </div>
            <div class="p-4 max-h-[400px] overflow-y-auto grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($allMembers as $member)
                    @php $isAbsent = \App\Models\Donation::where('user_id', $member->id)->whereDate('created_at', today())->exists(); @endphp
                    @if(!$isAbsent)
                    <div class="flex items-center gap-3 p-2 rounded-xl border border-gray-50 bg-gray-50/50">
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm flex-shrink-0">
                            <img src="{{ $member->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->name) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-slate-700 truncate">{{ $member->name }}</p>
                            <p class="text-[9px] text-gray-400 uppercase">{{ $member->role }}</p>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- អ្នកក្រៅកុដិ --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-100 flex justify-between items-center">
                <h3 class="font-bold text-red-700 flex items-center gap-2"><i class="fas fa-walking"></i> ក្រៅកុដិ ({{ $actualReports }})</h3>
                <span class="text-[10px] bg-red-500 text-white px-2 py-0.5 rounded-full">Away</span>
            </div>
            <div class="p-4 max-h-[400px] overflow-y-auto space-y-3">
                @php $hasAbsent = false; @endphp
                @foreach($allMembers as $member)
                    @php $report = \App\Models\Donation::where('user_id', $member->id)->whereDate('created_at', today())->first(); @endphp
                    @if($report)
                    @php $hasAbsent = true; @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl border border-red-100 bg-red-50/30">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm grayscale flex-shrink-0">
                                <img src="{{ $member->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($member->name) }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">{{ $member->name }}</p>
                                <p class="text-[10px] text-red-500 font-medium italic">មូលហេតុ៖ {{ $report->reason }}</p>
                            </div>
                        </div>
                        <span class="text-[9px] font-bold text-gray-400">{{ $report->created_at->format('H:i') }}</span>
                    </div>
                    @endif
                @endforeach
                @if(!$hasAbsent)
                    <div class="text-center py-10 text-gray-300 italic text-sm">មិនទាន់មានសេចក្តីរាយការណ៍អវត្តមាន...</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection