@extends('layouts.admin')
@section('title', 'សង្ខេបថវិកា និងហិរញ្ញវត្ថុ')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
            <i class="fas fa-vault text-6xl text-slate-900"></i>
        </div>
        <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">សមតុល្យសាច់ប្រាក់ក្នុងដៃ</p>
        <h3 class="text-3xl font-black text-slate-900 mt-2">${{ number_format($balance ?? 0, 2) }}</h3>
        <div class="mt-4 flex items-center text-[10px] font-bold text-green-600 bg-green-50 w-fit px-2 py-1 rounded-lg">
            <i class="fas fa-arrow-up mr-1"></i> សុវត្ថិភាព
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex justify-between">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">ការបរិច្ចាគសរុប</p>
            <i class="fas fa-arrow-trend-up text-green-500"></i>
        </div>
        <h3 class="text-3xl font-black text-green-600 mt-2">${{ number_format($totalDonation ?? 0, 2) }}</h3>
        <p class="text-[10px] text-gray-400 mt-4 italic">គិតត្រឹមថ្ងៃនេះ: {{ date('d-M-Y') }}</p>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex justify-between">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">ការចំណាយសរុប</p>
            <i class="fas fa-arrow-trend-down text-red-500"></i>
        </div>
        <h3 class="text-3xl font-black text-red-500 mt-2">${{ number_format($totalExpense ?? 0, 2) }}</h3>
        <p class="text-[10px] text-gray-400 mt-4 italic">រាប់បញ្ចូលទាំងថ្លៃទឹក-ភ្លើង</p>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex justify-between">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">សមាជិកសរុប</p>
            <i class="fas fa-users text-purple-500"></i>
        </div>
        <h3 class="text-3xl font-black text-purple-600 mt-2">{{ number_format($totalMembers ?? 0) }}</h3>
        <p class="text-[10px] text-gray-400 mt-4 italic">សមាជិកចុះឈ្មោះផ្លូវការ</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-sm italic underline decoration-orange-500">ការបរិច្ចាគចុងក្រោយ</h3>
            <a href="{{ route('treasurer.donations.index') }}" class="text-[10px] font-bold text-orange-600 hover:underline text-uppercase">មើលទាំងអស់</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentDonations ?? [] as $donation)
            <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold mr-3">
                        {{ mb_substr($donation->member->name ?? ($donation->donor_name ?? 'ស'), 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $donation->member->name ?? ($donation->donor_name ?? 'អ្នកបរិច្ចាគទូទៅ') }}</p>
                        <p class="text-[10px] text-gray-400 italic">ប្រមូលដោយ៖ {{ $donation->recorder->name ?? 'Admin' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-black text-green-600">+${{ number_format($donation->amount, 2) }}</p>
                    <p class="text-[9px] text-gray-400">
                        {{ \Carbon\Carbon::parse($donation->donated_at ?? $donation->created_at)->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <div class="p-10 text-center text-gray-400 text-xs italic">មិនទាន់មានទិន្នន័យថ្មីនៅឡើយ</div>
            @endforelse
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xl font-bold mb-2 text-orange-500">សកម្មភាពរហ័ស</h3>
                <p class="text-slate-400 text-xs mb-6 font-medium">គ្រប់គ្រងលំហូរថវិកាបានភ្លាមៗ</p>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('treasurer.donations.create') }}" class="flex flex-col items-center p-4 bg-slate-800 rounded-2xl hover:bg-orange-600 transition group text-center">
                        <i class="fas fa-plus text-xl mb-2"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">បន្ថែមចំណូល</span>
                    </a>
                    <a href="{{ route('treasurer.expenses.create') }}" class="flex flex-col items-center p-4 bg-slate-800 rounded-2xl hover:bg-red-600 transition text-center">
                        <i class="fas fa-minus text-xl mb-2"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">បន្ថែមចំណាយ</span>
                    </a>
                </div>
            </div>
            <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-orange-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="font-bold text-slate-800 text-sm mb-4">សេចក្តីសង្ខេបខែនេះ ({{ date('M') }})</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">ចំណូលខែនេះ</span>
                    <span class="font-bold text-slate-800">${{ number_format($monthlyIncome ?? 0, 2) }}</span>
                </div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                    @php
                        $baseVal = ($totalDonation ?? 0) > 0 ? $totalDonation : 1;
                        $percent = (($monthlyIncome ?? 0) / $baseVal) * 100;
                        $percent = $percent > 100 ? 100 : $percent;
                    @endphp
                    <div class="bg-orange-500 h-full rounded-full transition-all duration-700" style="width: {{ $percent }}%"></div>
                </div>
                <p class="text-[9px] text-gray-400 italic">ស្មើនឹង {{ number_format($percent, 1) }}% នៃចំណូលសរុប</p>
            </div>
        </div>
    </div>
</div>
@endsection 