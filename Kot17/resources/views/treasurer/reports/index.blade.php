@extends('layouts.admin')
@section('title', 'របាយការណ៍ហិរញ្ញវត្ថុប្រចាំខែ')

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
            <button type="button" onclick="window.print()" class="bg-slate-900 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-slate-800 transition">
                <i class="fas fa-print mr-2"></i> បោះពុម្ព Report
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border-l-4 border-l-green-500 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase">ចំណូលសរុប</p>
        <h3 class="text-2xl font-black text-green-600 mt-1">${{ number_format($totalDonations ?? 0, 2) }}</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border-l-4 border-l-red-500 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase">ចំណាយសរុប</p>
        <h3 class="text-2xl font-black text-red-600 mt-1">${{ number_format($totalExpenses ?? 0, 2) }}</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border-l-4 border-l-orange-500 shadow-sm">
        <p class="text-[10px] font-bold text-gray-400 uppercase">ចំណាយម្ហូបព្រឹក</p>
        <h3 class="text-2xl font-black text-orange-500 mt-1">${{ number_format($foodExpenses ?? 0, 2) }}</h3>
    </div>

    <div class="bg-slate-900 p-6 rounded-3xl shadow-sm">
        <p class="text-[10px] font-bold text-slate-400 uppercase">សមតុល្យសល់សុទ្ធ</p>
        <h3 class="text-2xl font-black text-white mt-1">${{ number_format($balance ?? 0, 2) }}</h3>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 text-center">
        <h3 class="font-bold text-slate-800 italic underline decoration-orange-500 text-xl">សេចក្តីសង្ខេបលំហូរថវិកា ប្រចាំខែ {{ $month }}/{{ $year }}</h3>
    </div>
    <div class="p-8">
        <div class="space-y-4 max-w-2xl mx-auto">
            <div class="flex justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 shadow-sm">
                <span class="text-slate-600 font-medium">១. សរុបបច្ច័យទទួលបាន (សមាជិក + សប្បុរសជន)</span>
                <span class="font-bold text-green-600">+ ${{ number_format($totalDonations ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 shadow-sm">
                <span class="text-slate-600 font-medium">២. សរុបការចំណាយលើម្ហូបអាហារ/ម្ហូបព្រឹក</span>
                <span class="font-bold text-orange-600">- ${{ number_format($foodExpenses ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 shadow-sm">
                <span class="text-slate-600 font-medium">៣. ចំណាយដទៃទៀត (ទឹក-ភ្លើង/ជួសជុល...)</span>
                <span class="font-bold text-red-600">- ${{ number_format(($totalExpenses ?? 0) - ($foodExpenses ?? 0), 2) }}</span>
            </div>
            <hr class="border-dashed my-6 border-gray-300">
            <div class="flex justify-between p-6 bg-slate-900 rounded-3xl shadow-xl">
                <span class="text-white font-bold text-lg">បច្ច័យនៅសល់ក្នុងឃ្លាំង</span>
                <span class="font-black text-2xl text-orange-400">${{ number_format($balance ?? 0, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection