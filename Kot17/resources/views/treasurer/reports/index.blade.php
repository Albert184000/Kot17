@extends('layouts.admin')
@section('title', 'របាយការណ៍ហិរញ្ញវត្ថុលម្អិត')

@section('content')
<div class="main-content font-['Khmer_OS_Battambang'] bg-slate-50 min-h-screen p-2 md:p-6">
    
    {{-- Top Header & Filter --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 no-print">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">របាយការណ៍ហិរញ្ញវត្ថុ</h1>
            <p class="text-slate-500 text-sm">ត្រួតពិនិត្យរំហូរទឹកប្រាក់ប្រចាំថ្ងៃ និងសមតុល្យសរុប</p>
        </div>
        
        <form action="{{ route('treasurer.reports.index') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex items-end gap-4">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 ml-1">ជ្រើសរើសខែបោះពុម្ព</label>
                <input type="month" name="filter_date" value="{{ $filterDate }}" 
                       onchange="this.form.submit()"
                       class="block w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm font-bold">
            </div>
            <{{-- ក្នុង index.blade.php --}}
<div class="flex flex-wrap items-end gap-3">
    {{-- ប៊ូតុងថ្មីសម្រាប់តំណទៅកាន់ទំព័រ Print --}}
    <a href="{{ route('treasurer.reports.print', ['filter_date' => $filterDate]) }}" 
       target="_blank" 
       class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-800 transition-all shadow-md flex items-center">
        <i class="fas fa-file-invoice-dollar mr-2 text-orange-400"></i> បោះពុម្ពទម្រង់ផ្លូវការ
    </a>
</div>
        </form>
    </div>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Income --}}
        <div class="relative overflow-hidden bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <i class="fas fa-arrow-down text-6xl text-green-600"></i>
            </div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">ចំណូលសរុបខែនេះ</p>
            <h3 class="text-3xl font-black text-green-600 mb-1 font-mono">${{ number_format($totalDonationsUSD, 2) }}</h3>
            <p class="text-md font-bold text-blue-600 font-mono">{{ number_format($totalDonationsKHR) }} ៛</p>
        </div>

        {{-- Expense --}}
        <div class="relative overflow-hidden bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm group hover:shadow-md transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <i class="fas fa-arrow-up text-6xl text-red-600"></i>
            </div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">ចំណាយសរុបខែនេះ</p>
            <h3 class="text-3xl font-black text-red-600 mb-1 font-mono">${{ number_format($totalExpensesUSD, 2) }}</h3>
            <p class="text-md font-bold text-blue-600 font-mono">{{ number_format($totalExpensesKHR) }} ៛</p>
        </div>

        {{-- Net Balance --}}
        <div class="relative overflow-hidden bg-slate-900 p-6 rounded-[2rem] shadow-xl group hover:scale-[1.02] transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fas fa-wallet text-6xl text-white"></i>
            </div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">សមតុល្យសរុបក្នុងឃ្លាំង</p>
            <h3 class="text-3xl font-black text-white mb-1 font-mono">${{ number_format($runningUSD, 2) }}</h3>
            <p class="text-md font-bold text-orange-400 font-mono">{{ number_format($runningKHR) }} ៛</p>
        </div>
    </div>

    {{-- Main Ledger Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="font-black text-slate-800 text-lg uppercase tracking-tight">
                <i class="fas fa-list-ul mr-2 text-orange-500"></i> តារាងប្រតិបត្តិការប្រចាំថ្ងៃ (ខែ {{ $month }}/{{ $year }})
            </h3>
            <div class="flex gap-2">
                 <span class="text-[9px] font-black px-3 py-1 bg-green-100 text-green-700 rounded-lg uppercase">ចំណូល</span>
                 <span class="text-[9px] font-black px-3 py-1 bg-red-100 text-red-700 rounded-lg uppercase">ចំណាយ</span>
                 <span class="text-[9px] font-black px-3 py-1 bg-blue-100 text-blue-700 rounded-lg uppercase italic">សមតុល្យរត់</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white text-[10px] uppercase tracking-tighter">
                        <th rowspan="2" class="p-4 border-r border-slate-700 text-center w-12">ល.រ</th>
                        <th rowspan="2" class="p-4 border-r border-slate-700 text-center">កាលបរិច្ឆេទ</th>
                        <th colspan="2" class="p-2 border-b border-r border-slate-700 text-center bg-green-900/40">ប្រាក់ចំណូល (In)</th>
                        <th colspan="2" class="p-2 border-b border-r border-slate-700 text-center bg-red-900/40">ប្រាក់ចំណាយ (Out)</th>
                        <th colspan="2" class="p-2 border-b border-slate-700 text-center bg-blue-900/40 font-black italic">សមតុល្យសល់ (Balance)</th>
                    </tr>
                    <tr class="bg-slate-800 text-slate-300 text-[9px] uppercase">
                        <th class="p-2 border-r border-slate-700 text-center">រៀល</th>
                        <th class="p-2 border-r border-slate-700 text-center font-bold text-white uppercase italic">USD</th>
                        <th class="p-2 border-r border-slate-700 text-center">រៀល</th>
                        <th class="p-2 border-r border-slate-700 text-center font-bold text-white uppercase italic">USD</th>
                        <th class="p-2 border-r border-slate-700 text-center">រៀល</th>
                        <th class="p-2 text-center font-bold text-white uppercase italic">USD</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dailyReport as $row)
                    @php 
                        $isToday = ($row['date'] == date('Y-m-d'));
                        $hasActivity = ($row['in_usd'] > 0 || $row['out_usd'] > 0 || $row['in_khr'] > 0 || $row['out_khr'] > 0);
                    @endphp
                    <tr class="{{ $hasActivity ? 'bg-white' : 'bg-slate-50/50 opacity-60' }} {{ $isToday ? 'ring-2 ring-orange-400 ring-inset' : '' }} hover:bg-slate-50 transition-colors">
                        <td class="p-3 border-r border-slate-100 text-center font-mono text-[11px] text-slate-400">{{ $row['day'] }}</td>
                        <td class="p-3 border-r border-slate-100 text-center font-bold text-slate-600 whitespace-nowrap">
                            {{ date('d-M-Y', strtotime($row['date'])) }}
                        </td>
                        
                        {{-- Incomes --}}
                        <td class="p-3 border-r border-slate-100 text-right font-mono text-green-600">
                            {{ $row['in_khr'] > 0 ? number_format($row['in_khr']) : '-' }}
                        </td>
                        <td class="p-3 border-r border-slate-100 text-right font-mono font-black text-green-700 bg-green-50/30">
                            {{ $row['in_usd'] > 0 ? '$'.number_format($row['in_usd'], 2) : '-' }}
                        </td>
                        
                        {{-- Expenses --}}
                        <td class="p-3 border-r border-slate-100 text-right font-mono text-red-500">
                            {{ $row['out_khr'] > 0 ? number_format($row['out_khr']) : '-' }}
                        </td>
                        <td class="p-3 border-r border-slate-100 text-right font-mono font-black text-red-600 bg-red-50/30">
                            {{ $row['out_usd'] > 0 ? '$'.number_format($row['out_usd'], 2) : '-' }}
                        </td>
                        
                        {{-- Balance --}}
                        <td class="p-3 border-r border-slate-100 text-right font-mono font-bold text-blue-700 bg-blue-50/20">
                            {{ number_format($row['bal_khr']) }}
                        </td>
                        <td class="p-3 text-right font-mono font-black text-blue-900 bg-blue-50/50">
                            ${{ number_format($row['bal_usd'], 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-20 text-center">
                            <i class="fas fa-folder-open text-4xl text-slate-200 mb-3"></i>
                            <p class="text-slate-400 italic">មិនមានទិន្នន័យសម្រាប់ខែនេះទេ</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-900 text-white font-black border-t-4 border-orange-500">
                    <tr class="divide-x divide-slate-700">
                        <td colspan="2" class="p-5 text-center uppercase tracking-widest text-[10px] text-slate-400">សរុបប្រចាំខែរួម</td>
                        <td class="p-5 text-right font-mono text-orange-400">{{ number_format($totalDonationsKHR) }} ៛</td>
                        <td class="p-5 text-right font-mono text-orange-400 text-lg">${{ number_format($totalDonationsUSD, 2) }}</td>
                        <td class="p-5 text-right font-mono text-red-400">{{ number_format($totalExpensesKHR) }} ៛</td>
                        <td class="p-5 text-right font-mono text-red-400 text-lg">${{ number_format($totalExpensesUSD, 2) }}</td>
                        <td class="p-5 text-right font-mono text-green-400">{{ number_format($runningKHR) }} ៛</td>
                        <td class="p-5 text-right font-mono text-green-400 text-lg">${{ number_format($runningUSD, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Footer for Signatures (Only on Print) --}}
    <div class="hidden print:grid grid-cols-3 gap-8 mt-12 text-center text-xs">
        <div>
            <p class="font-bold mb-12 uppercase italic">រៀបចំដោយបេឡា</p>
            <div class="border-t border-slate-300 w-3/4 mx-auto pt-2">ហត្ថលេខា និងឈ្មោះ</div>
        </div>
        <div>
            <p class="font-bold mb-12 uppercase italic">បានពិនិត្យត្រឹមត្រូវ</p>
            <div class="border-t border-slate-300 w-3/4 mx-auto pt-2">គណនេយ្យករ</div>
        </div>
        <div>
            <p class="font-bold mb-12 uppercase italic">ប្រធានអង្គភាព</p>
            <div class="border-t border-slate-300 w-3/4 mx-auto pt-2">ហត្ថលេខា និងត្រា</div>
        </div>
    </div>
</div>

<style>
    @media print {
        @page { size: landscape; margin: 1cm; }
        .no-print { display: none !important; }
        body { background: white !important; font-size: 9px !important; }
        .main-content { padding: 0 !important; }
        .rounded-[2.5rem], .rounded-[2rem] { border-radius: 0 !important; }
        .shadow-sm, .shadow-xl { shadow: none !important; }
        table { border: 1px solid #1e293b !important; }
        th, td { border: 1px solid #e2e8f0 !important; padding: 4px !important; }
        .bg-slate-900 { background-color: #0f172a !important; color: white !important; -webkit-print-color-adjust: exact; }
        .bg-slate-800 { background-color: #1e293b !important; color: white !important; -webkit-print-color-adjust: exact; }
    }
    /* Smooth Scrollbar for Table */
    .overflow-x-auto::-webkit-scrollbar { height: 6px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #f1f1f1; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
@endsection