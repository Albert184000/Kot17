@extends('layouts.admin')
@section('title', 'របាយការណ៍ហិរញ្ញវត្ថុ')

@section('content')
<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
    <form action="{{ route('treasurer.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">ជ្រើសរើសខែ/ឆ្នាំ</label>
            <input type="month" name="filter_date" value="{{ $year }}-{{ $month }}" 
                   onchange="this.form.submit()"
                   class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none">
        </div>
        <div class="text-right flex-1">
            <button type="button" onclick="window.print()" class="bg-slate-900 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-slate-800 transition shadow-lg">
                <i class="fas fa-print mr-2"></i> បោះពុម្ព Report
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- ចំណូល --}}
    <div class="bg-white p-6 rounded-3xl border-l-4 border-l-green-500 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ចំណូលសរុប</p>
        <div class="mt-2">
            {{-- លុយរៀលនៅពីលើ --}}
            <h3 class="text-lg font-bold text-blue-600">{{ number_format($totalDonationsKHR) }} ៛</h3>
            <h3 class="text-2xl font-black text-green-600">${{ number_format($totalDonationsUSD, 2) }}</h3>
        </div>
    </div>

    {{-- ចំណាយ --}}
    <div class="bg-white p-6 rounded-3xl border-l-4 border-l-red-500 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ចំណាយសរុប</p>
        <div class="mt-2">
            <h3 class="text-lg font-bold text-blue-600">{{ number_format($totalExpensesKHR) }} ៛</h3>
            <h3 class="text-2xl font-black text-red-600">${{ number_format($totalExpensesUSD, 2) }}</h3>
        </div>
    </div>

    {{-- ចំណាយម្ហូប --}}
    <div class="bg-white p-6 rounded-3xl border-l-4 border-l-orange-500 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ចំណាយម្ហូបព្រឹក</p>
        <div class="mt-2">
            <h3 class="text-lg font-bold text-blue-600">{{ number_format($foodExpensesKHR) }} ៛</h3>
            <h3 class="text-2xl font-black text-orange-500">${{ number_format($foodExpensesUSD, 2) }}</h3>
        </div>
    </div>

    {{-- សមតុល្យសល់សុទ្ធ --}}
    <div class="bg-slate-900 p-6 rounded-3xl shadow-xl">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">សមតុល្យសល់សុទ្ធ</p>
        <div class="mt-2">
            <h3 class="text-lg font-bold text-orange-400">
                {{ $balanceKHR < 0 ? '-' : '' }}{{ number_format(abs($balanceKHR)) }} ៛
            </h3>
            <h3 class="text-2xl font-black text-white">
                {{ $balanceUSD < 0 ? '-' : '' }}${{ number_format(abs($balanceUSD), 2) }}
            </h3>
        </div>
    </div>
</div>

{{-- ផ្នែកសេចក្តីសង្ខេប --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8 px-8 py-10">
    <h3 class="font-bold text-slate-800 text-center text-xl italic underline decoration-orange-500 underline-offset-8 mb-10">
        សេចក្តីសង្ខេបលំហូរថវិកា ប្រចាំខែ {{ $month }}/{{ $year }}
    </h3>
    <div class="space-y-4 max-w-2xl mx-auto">
        <div class="flex justify-between items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
            <span class="text-slate-600 font-medium text-sm">១. សរុបបច្ច័យទទួលបាន</span>
            <div class="text-right">
                <div class="font-black text-green-600">+ ${{ number_format($totalDonationsUSD, 2) }}</div>
                <div class="font-bold text-blue-600 text-sm">+ {{ number_format($totalDonationsKHR) }} ៛</div>
            </div>
        </div>

        <div class="flex justify-between items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
            <span class="text-slate-600 font-medium text-sm">២. សរុបការចំណាយលើម្ហូបអាហារ</span>
            <div class="text-right">
                <div class="font-black text-orange-600">- ${{ number_format($foodExpensesUSD, 2) }}</div>
                <div class="font-bold text-orange-600 text-sm">- {{ number_format($foodExpensesKHR) }} ៛</div>
            </div>
        </div>

        <div class="flex justify-between items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
            <span class="text-slate-600 font-medium text-sm">៣. ចំណាយដទៃទៀត</span>
            <div class="text-right">
                <div class="font-black text-red-600">- ${{ number_format($totalExpensesUSD - $foodExpensesUSD, 2) }}</div>
                <div class="font-bold text-red-600 text-sm">- {{ number_format($totalExpensesKHR - $foodExpensesKHR) }} ៛</div>
            </div>
        </div>

        <div class="flex justify-between items-center p-6 bg-slate-900 rounded-3xl shadow-lg mt-8">
            <span class="text-white font-bold">បច្ច័យនៅសល់ក្នុងឃ្លាំង</span>
            <div class="text-right">
                <div class="font-black text-2xl text-white">{{ $balanceUSD < 0 ? '-' : '' }}${{ number_format(abs($balanceUSD), 2) }}</div>
                <div class="font-black text-lg text-orange-400">{{ $balanceKHR < 0 ? '-' : '' }}{{ number_format(abs($balanceKHR)) }} ៛</div>
            </div>
        </div>
    </div>
</div>
@endsection