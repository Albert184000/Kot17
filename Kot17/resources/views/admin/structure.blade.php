<div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 mb-8 text-center">
    <h2 class="text-xl font-black text-slate-800 mb-10 uppercase tracking-widest">
        រចនាសម្ពន្ធគ្រប់គ្រងកុដិ
    </h2>

    <div class="flex flex-col items-center">

        {{-- ADMIN --}}
        @if($admin)
        <div class="mb-8">
            {!! avatar($admin, 'w-24 h-24') !!}
            <div class="mt-3 bg-white px-6 py-3 rounded-xl shadow w-56">
                <h4 class="font-bold text-slate-800 text-sm truncate">{{ $admin->name }}</h4>
                <span class="text-[10px] font-black text-orange-500 uppercase">ប្រធានកុដិ</span>
            </div>
            <div class="h-8 w-px bg-slate-200 mx-auto"></div>
        </div>
        @endif

        {{-- TREASURER --}}
        @if($treasurer)
        <div class="mb-8">
            {!! avatar($treasurer, 'w-20 h-20') !!}
            <div class="mt-2 bg-white px-5 py-2 rounded-xl shadow w-48">
                <h4 class="font-bold text-slate-700 text-xs truncate">{{ $treasurer->name }}</h4>
                <span class="text-[9px] font-bold text-blue-500 uppercase">ហេរញ្ញិក</span>
            </div>
            <div class="h-8 w-px bg-slate-200 mx-auto"></div>
        </div>
        @endif

        {{-- COLLECTORS --}}
        <div class="flex flex-wrap justify-center gap-6 mb-10">
            @foreach($collectors as $collector)
            <div class="flex flex-col items-center bg-purple-50 px-4 py-4 rounded-2xl border border-purple-100 w-40">
                {!! avatar($collector, 'w-14 h-14') !!}
                <h4 class="mt-2 font-bold text-slate-700 text-[11px] truncate">{{ $collector->name }}</h4>
                <span class="text-[8px] font-bold text-purple-500 uppercase">អ្នកប្រមូល</span>
            </div>
            @endforeach
        </div>

        {{-- MEMBERS --}}
        <div class="w-full">
            <h3 class="text-xs font-black text-slate-400 uppercase mb-4 tracking-widest">
                សមាជិកទាំងអស់
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                @foreach($members as $member)
                <div class="flex flex-col items-center bg-slate-50 rounded-xl p-3 border">
                    {!! avatar($member, 'w-12 h-12') !!}
                    <span class="mt-2 text-[10px] font-bold text-slate-700 truncate">
                        {{ $member->name }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
