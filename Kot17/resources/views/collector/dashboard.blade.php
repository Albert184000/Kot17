@extends('layouts.admin')
@section('title', 'ផ្ទាំងគ្រប់គ្រងអ្នកប្រមូល')

@section('content')
@php
    $exchangeRate = 4100;
    $todayTotalUSD = (float) ($todayTotalUSD ?? 0);
    $todayTotalKHR = (float) ($todayTotalKHR ?? 0);
    $totalInUSD = $todayTotalUSD + ($todayTotalKHR / $exchangeRate);
    $goalUSD = 10; 
    $totalPercentage = $goalUSD > 0 ? min(round(($totalInUSD / $goalUSD) * 100), 100) : 0;
@endphp

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- ✅ ផ្នែក Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="p-6 rounded-[2rem] text-white shadow-lg {{ $todayTotalUSD > 0 ? 'bg-gradient-to-br from-blue-600 to-blue-700 shadow-blue-100' : 'bg-gray-400 opacity-50' }}">
        <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest">សរុបក្នុងប្រព័ន្ធ ($)</p>
        <h3 class="text-3xl font-black mt-2">${{ number_format($todayTotalUSD, 2) }}</h3>
    </div>

    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 p-6 rounded-[2rem] text-white shadow-lg shadow-emerald-100">
        <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest">សរុបក្នុងប្រព័ន្ធ (៛)</p>
        <h3 class="text-3xl font-black mt-2">{{ number_format($todayTotalKHR) }} ៛</h3>
    </div>

    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm text-slate-800">
        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Progress ថ្ងៃនេះ ({{ $totalPercentage }}%)</p>
        <div class="mt-4 h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="bg-orange-500 h-full transition-all duration-700" style="width: {{ $totalPercentage }}%"></div>
        </div>
        <h4 class="text-xl font-black mt-4">${{ number_format($totalInUSD, 2) }}</h4>
    </div>
</div>

<form action="{{ route('collector.summary') }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់ផ្ញើរបាយការណ៍រួមនេះទៅ Telegram មែនទេ?')">
    @csrf

    {{-- ១. ផ្នែកកំណត់តម្លៃបង់ក្នុងម្នាក់ --}}
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 mb-6">
        <div class="w-full">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">កំណត់ការបង់ប្រាក់ក្នុងម្នាក់:</label>
            <div class="flex flex-wrap gap-4">
                <input type="number" step="any" name="per_person_amount" placeholder="ឧ៖ 1 ឬ 5000" required
                    class="w-32 px-4 py-2 border border-gray-100 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50 font-bold text-slate-700">
                
                <select name="per_person_currency" class="px-4 py-2 border border-gray-100 rounded-xl text-sm outline-none bg-gray-50/50 font-bold text-slate-700">
                    <option value="USD">USD ($)</option>
                    <option value="KHR">KHR (៛)</option>
                </select>

                <input type="text" name="final_description" placeholder="សម្គាល់ (ឧ៖ ចង្ហាន់ថ្ងៃត្រង់)"
                    class="grow px-4 py-2 border border-gray-100 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50/50 font-bold text-slate-700">
            </div>
        </div>
    </div>

    {{-- ២. ផ្នែកបញ្ជីវត្តមានសមាជិកពិត --}}
    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
            <h3 class="font-black text-slate-800 text-xs uppercase tracking-[0.2em]">
                <i class="fas fa-users mr-2 text-blue-500"></i>បញ្ជីវត្តមានបង់ប្រាក់ថ្ងៃនេះ
            </h3>
            
            <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-2xl font-black shadow-lg shadow-blue-100 transition-all flex items-center justify-center group uppercase tracking-widest text-[11px]">
                <i class="fab fa-telegram-plane mr-3 text-lg group-hover:rotate-12 transition-transform"></i> 
                ផ្ញើរបាយការណ៍រួមទៅ TELEGRAM 📊
            </button>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($realMembers as $member)
            <div class="flex flex-col items-center p-6 bg-gray-50/50 rounded-[2rem] border border-gray-100 shadow-sm" x-data="{ status: 'absent' }">
                <span class="font-black text-slate-700 text-sm mb-4">{{ $member->name }}</span>
                <input type="hidden" name="attendance[{{ $member->name }}]" :value="status">
                
                <div class="flex flex-col gap-2 w-full">
                    <button type="button" @click="status = 'present'"
                        class="w-full py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border"
                        :style="status === 'present' ? 'background-color: #10b981 !important; color: white !important; border-color: transparent !important;' : 'background-color: white; color: #9ca3af; border-color: #f3f4f6;'">
                        <i class="fas fa-check-circle mr-1"></i> បង់រួច
                    </button>

                    <button type="button" @click="status = 'absent'"
                        class="w-full py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border"
                        :style="status === 'absent' ? 'background-color: #f43f5e !important; color: white !important; border-color: transparent !important;' : 'background-color: white; color: #9ca3af; border-color: #f3f4f6;'">
                        <i class="fas fa-times-circle mr-1"></i> មិនទាន់បង់
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</form>

{{-- ✅ ផ្នែកប្រវត្តិ --}}
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-50 bg-gray-50/30">
        <h3 class="font-black text-slate-800 text-xs uppercase tracking-[0.2em]">
            <i class="fas fa-history mr-2 text-orange-500"></i>ប្រវត្តិប្រមូលក្នុងប្រព័ន្ធ
        </h3>
    </div>
    <div class="overflow-x-auto p-4">
        <table class="w-full text-left border-separate border-spacing-y-2">
            <thead>
                <tr class="text-gray-400 uppercase text-[10px] font-black tracking-widest">
                    <th class="px-6 py-2">ម្ចាស់ទាន</th>
                    <th class="px-6 py-2">សម្គាល់</th>
                    <th class="px-6 py-2 text-right">ទឹកប្រាក់</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentCollections as $item)
                    <tr class="hover:bg-gray-50/80 transition-all shadow-sm">
                        <td class="px-6 py-4 font-bold bg-white border-y border-l rounded-l-2xl border-gray-50 text-slate-800">{{ $item->donor_name }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs italic bg-white border-y border-gray-50">{{ $item->description }}</td>
                        <td class="px-6 py-4 text-right bg-white border-y border-r rounded-r-2xl border-gray-50">
                            <span class="{{ $item->currency == 'USD' ? 'text-blue-600' : 'text-emerald-600' }} font-black text-lg">
                                {{ $item->currency == 'USD' ? '$'.number_format($item->amount, 2) : number_format($item->amount).' ៛' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="p-10 text-center text-gray-400 italic">មិនទាន់មានទិន្នន័យ</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection