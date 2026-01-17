@extends('layouts.admin')
@section('title', 'ចុះបញ្ជីប្រមូលប្រចាំថ្ងៃ')

@section('content')
<div class="main-content font-['Khmer_OS_Battambang'] bg-slate-50 min-h-screen p-4 md:p-8">

    {{-- ✅ Alerts Section --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 shadow-sm flex items-center animate-fade-in">
            <div class="bg-emerald-500 p-2 rounded-lg mr-3 shadow-lg shadow-emerald-200">
                <i class="fas fa-check text-white text-xs"></i>
            </div>
            <span class="font-black text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- 폼 ១៖ បញ្ចូលចំណូល (INCOME) --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden transition-all hover:shadow-md">
            <div class="p-6 border-b border-slate-50 bg-gradient-to-r from-white to-emerald-50/30">
                <h3 class="font-black text-slate-800 text-lg flex items-center">
                    <span class="bg-emerald-100 p-2 rounded-xl mr-3 text-emerald-600">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                    កត់ឈ្មោះអ្នកប្រគេនបច្ច័យ (IN)
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('collector.donations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">ឈ្មោះអ្នកប្រគេន</label>
                            <input type="text" name="donor_name" placeholder="ឈ្មោះ..." class="w-full border-slate-200 rounded-2xl px-4 py-3 text-sm focus:border-emerald-500 outline-none bg-slate-50/50" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">រូបិយប័ណ្ណ</label>
                            <select name="currency" class="w-full border-slate-200 rounded-2xl px-4 py-3 text-sm font-bold bg-slate-50/50 outline-none">
                                <option value="KHR">រៀល (៛)</option>
                                <option value="USD">ដុល្លារ ($)</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">ចំនួនទឹកប្រាក់</label>
                            <input type="number" step="0.01" name="amount" placeholder="0" class="w-full border-slate-200 rounded-2xl px-4 py-3 text-sm font-black bg-slate-50/50 outline-none" required>
                        </div>
                        <div class="col-span-2 space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">ការពិពណ៌នា</label>
                            <input type="text" name="description" placeholder="បញ្ជាក់បន្ថែម..." class="w-full border-slate-200 rounded-2xl px-4 py-3 text-sm focus:border-emerald-500 outline-none bg-slate-50/50">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-4 rounded-2xl font-black text-sm bg-emerald-600 text-white hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i> រក្សាទុកចំណូល
                    </button>
                </form>
            </div>
        </div>

        {{-- 폼 ២៖ បញ្ចូលចំណាយ (EXPENSE) --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden transition-all hover:shadow-md">
            <div class="p-6 border-b border-slate-50 bg-gradient-to-r from-white to-red-50/30">
                <h3 class="font-black text-slate-800 text-lg flex items-center">
                    <span class="bg-red-100 p-2 rounded-xl mr-3 text-red-600">
                        <i class="fas fa-minus-circle"></i>
                    </span>
                    កត់ត្រាចំណាយផ្សេងៗ (OUT)
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('collector.expenses.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">ចំណាយលើអ្វី?</label>
                            <input type="text" name="title" placeholder="ឧទាហរណ៍៖ បង់ថ្លៃទឹក..." class="w-full border-slate-200 rounded-2xl px-4 py-3 text-sm focus:border-red-500 outline-none bg-slate-50/50" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">រូបិយប័ណ្ណ</label>
                            <select name="currency" class="w-full border-slate-200 rounded-2xl px-4 py-3 text-sm font-bold bg-slate-50/50 outline-none">
                                <option value="KHR">រៀល (៛)</option>
                                <option value="USD">ដុល្លារ ($)</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black uppercase text-slate-400 ml-1">ចំនួនទឹកប្រាក់</label>
                            <input type="number" step="0.01" name="amount" placeholder="0" class="w-full border-slate-200 rounded-2xl px-4 py-3 text-sm font-black bg-slate-50/50 outline-none" required>
                        </div>
                    </div>
                    <div class="h-[68px]"></div> {{-- Spacer to align with Income form height --}}
                    <button type="submit" class="w-full py-4 rounded-2xl font-black text-sm bg-red-600 text-white hover:bg-red-700 transition-all shadow-lg shadow-red-100 flex items-center justify-center">
                        <i class="fas fa-receipt mr-2"></i> រក្សាទុកចំណាយ
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- តារាងរួមបង្ហាញចំណូល-ចំណាយ --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-gradient-to-r from-white to-slate-50/50">
            <h3 class="font-black text-slate-800 text-lg flex items-center">
                <span class="bg-blue-100 p-2 rounded-xl mr-3 text-blue-600">
                    <i class="fas fa-file-invoice-dollar"></i>
                </span>
                របាយការណ៍ប្រតិបត្តិការប្រចាំថ្ងៃ
            </h3>
            <span class="text-[11px] font-black text-slate-400 italic">កាលបរិច្ឆេទ៖ {{ date('d-M-Y') }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-widest text-center">
                        <th rowspan="2" class="p-4 border-b border-r border-slate-100">ឈ្មោះ / ការពិពណ៌នា</th>
                        <th colspan="2" class="p-3 border-b border-r border-slate-100 bg-emerald-50 text-emerald-700">ចំណូល (IN)</th>
                        <th colspan="2" class="p-3 border-b border-slate-100 bg-red-50 text-red-700">ចំណាយ (OUT)</th>
                    </tr>
                    <tr class="bg-slate-50/50 text-[10px] font-black text-center border-b border-slate-100">
                        <th class="p-2 border-r border-slate-100 text-orange-600">រៀល (៛)</th>
                        <th class="p-2 border-r border-slate-100 text-blue-600">USD ($)</th>
                        <th class="p-2 border-r border-slate-100 text-orange-600">រៀល (៛)</th>
                        <th class="p-2 text-blue-600">USD ($)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php 
                        $tInKHR = 0; $tInUSD = 0; $tOutKHR = 0; $tOutUSD = 0; 
                    @endphp

                    {{-- ផ្នែកចំណូល --}}
                    @foreach($recentCollections as $item)
                        @php 
                            if($item->currency == 'KHR') $tInKHR += $item->amount; 
                            else $tInUSD += $item->amount;
                        @endphp
                        <tr class="hover:bg-emerald-50/30 transition-all">
                            <td class="p-4 border-r border-slate-50">
                                <p class="font-black text-slate-800 text-sm leading-tight">{{ $item->donor_name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold italic">{{ $item->description ?: 'បច្ច័យប្រគេន' }}</p>
                            </td>
                            <td class="p-4 text-center border-r border-slate-50 font-mono font-black text-emerald-600 text-sm">
                                {{ $item->currency == 'KHR' ? number_format($item->amount) : '-' }}
                            </td>
                            <td class="p-4 text-center border-r border-slate-50 font-mono font-black text-emerald-600 text-sm">
                                {{ $item->currency == 'USD' ? '$' . number_format($item->amount, 2) : '-' }}
                            </td>
                            <td colspan="2" class="bg-slate-50/30 text-center text-slate-200">-</td>
                        </tr>
                    @endforeach

                    {{-- ផ្នែកចំណាយ --}}
                    @foreach($recentExpenses as $exp)
                        @php 
                            if($exp->currency == 'KHR') $tOutKHR += $exp->amount; 
                            else $tOutUSD += $exp->amount;
                        @endphp
                        <tr class="hover:bg-red-50/30 transition-all">
                            <td class="p-4 border-r border-slate-50 italic">
                                <p class="font-black text-red-700 text-sm leading-tight">{{ $exp->title }}</p>
                                <p class="text-[9px] text-red-400 font-bold uppercase">Transaction OUT</p>
                            </td>
                            <td colspan="2" class="bg-slate-50/30 text-center text-slate-200">-</td>
                            <td class="p-4 text-center border-r border-slate-50 font-mono font-black text-red-600 text-sm">
                                {{ $exp->currency == 'KHR' ? number_format($exp->amount) : '-' }}
                            </td>
                            <td class="p-4 text-center font-mono font-black text-red-600 text-sm">
                                {{ $exp->currency == 'USD' ? '$' . number_format($exp->amount, 2) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- សរុបចុងក្រោយ --}}
                <tfoot class="bg-slate-900 text-white shadow-2xl">
                    <tr class="text-center">
                        <td class="p-5 text-right font-black text-[10px] uppercase border-r border-white/10">សរុប (Grand Total)</td>
                        <td class="p-5 font-mono font-black text-emerald-400 text-base border-r border-white/10">
                            {{ number_format($tInKHR) }} ៛
                        </td>
                        <td class="p-5 font-mono font-black text-emerald-400 text-base border-r border-white/10">
                            ${{ number_format($tInUSD, 2) }}
                        </td>
                        <td class="p-5 font-mono font-black text-red-400 text-base border-r border-white/10">
                            {{ number_format($tOutKHR) }} ៛
                        </td>
                        <td class="p-5 font-mono font-black text-red-400 text-base">
                            ${{ number_format($tOutUSD, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
    .font-mono { font-family: 'JetBrains Mono', 'Fira Code', monospace !important; }
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
</style>
@endsection