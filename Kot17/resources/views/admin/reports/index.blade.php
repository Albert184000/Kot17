@extends('layouts.admin')

@section('content')
@php
    $exchangeRate = 4000; 
    $displayTotalIn  = $totalIn ?? 0;
    $displayTotalOut = $totalOut ?? 0;
    $displayNet     = $profit ?? 0;

    $totalInRiel  = $displayTotalIn * $exchangeRate;
    $totalOutRiel = $displayTotalOut * $exchangeRate;
    $netRiel      = $displayNet * $exchangeRate;

    $isProfit   = $displayNet >= 0;
    $statusColor= $isProfit ? '#10b981' : '#ef4444';
    $statusBg   = $isProfit ? '#ecfdf5' : '#fef2f2';
    $statusText = $isProfit ? 'ចំណូលលើសចំណាយ (Surplus)' : 'ចំណាយលើសចំណូល (Deficit)';

    // Running balances
    $runningBalanceMobile = 0;
    $runningBalanceDesktop = 0;
@endphp

<div class="main-content font-['Khmer_OS_Battambang',_sans-serif] bg-[#f8fafc] p-3 md:p-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 no-print">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">{{ $label }}</h2>
            <p class="text-slate-500 text-xs md:text-sm mt-1">
                <i class="fas fa-calendar-alt mr-1"></i> {{ $startDate }} ដល់ {{ $endDate }} 
                <span class="hidden sm:inline mx-2 text-slate-300">|</span> 
                <span class="block sm:inline text-blue-600 font-bold">1$ ≈ {{ number_format($exchangeRate) }}៛</span>
            </p>
        </div>
        <button onclick="window.print()" class="w-full sm:w-auto bg-white px-5 py-2.5 rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 transition flex items-center justify-center gap-2 font-bold text-slate-700">
            <i class="fas fa-print text-slate-400"></i> បោះពុម្ព Report
        </button>
    </div>

    {{-- Filter --}}
    <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-slate-100 mb-8 no-print">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div class="space-y-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase">ប្រភេទរបាយការណ៍</label>
                <select name="report_type" onchange="this.form.submit()" class="w-full p-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm font-medium">
                    <option value="month" {{ $type == 'month' ? 'selected' : '' }}>ប្រចាំខែ (Monthly)</option>
                    <option value="year" {{ $type == 'year' ? 'selected' : '' }}>ប្រចាំឆ្នាំ (Yearly)</option>
                    <option value="custom" {{ $type == 'custom' ? 'selected' : '' }}>កំណត់ដោយខ្លួនឯង</option>
                </select>
            </div>

            @if($type == 'month')
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">ជ្រើសរើសខែ</label>
                    <input type="month" name="filter_date" value="{{ $year }}-{{ str_pad($month,2,'0',STR_PAD_LEFT) }}" onchange="this.form.submit()" class="w-full p-2 rounded-lg border border-slate-200 text-sm">
                </div>
            @elseif($type == 'year')
                <div class="space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">ជ្រើសរើសឆ្នាំ</label>
                    <select name="filter_year" onchange="this.form.submit()" class="w-full p-2.5 rounded-lg border border-slate-200 text-sm">
                        @for($i = date('Y'); $i >= date('Y')-5; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>ឆ្នាំ {{ $i }}</option>
                        @endfor
                    </select>
                </div>
            @else
                <div class="grid grid-cols-2 gap-2">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">ចាប់ពី</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full p-2 rounded-lg border border-slate-200 text-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">ដល់</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full p-2 rounded-lg border border-slate-200 text-sm">
                    </div>
                </div>
            @endif

            <button type="submit" class="bg-slate-800 text-white p-2.5 rounded-lg font-bold hover:bg-slate-700 transition w-full shadow-sm">
                <i class="fas fa-sync-alt mr-1 text-xs opacity-70"></i> ទាញទិន្នន័យ
            </button>
        </form>
    </div>

    {{-- Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
        {{-- Total Income --}}
        <div class="metric-card border-l-4 border-green-500">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center shrink-0 shadow-sm"><i class="fas fa-hand-holding-usd"></i></div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ចំណូលសរុប</span>
            </div>
            <div class="text-2xl font-black text-slate-800">${{ number_format($displayTotalIn, 2) }}</div>
            <div class="text-green-600 font-bold text-sm">៛ {{ number_format($totalInRiel) }}</div>
            <div class="mt-3 pt-3 border-t border-slate-50 text-[11px] {{ ($deltaIn ?? 0) >= 0 ? 'text-green-500' : 'text-red-500' }} font-bold">
                <i class="fas fa-{{ ($deltaIn ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i> {{ number_format(abs($deltaIn ?? 0),1) }}% <span class="text-slate-400 font-normal">ធៀបគ្រាមុន</span>
            </div>
        </div>

        {{-- Total Expense --}}
        <div class="metric-card border-l-4 border-red-500">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 shadow-sm"><i class="fas fa-file-invoice-dollar"></i></div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ចំណាយសរុប</span>
            </div>
            <div class="text-2xl font-black text-slate-800">${{ number_format($displayTotalOut, 2) }}</div>
            <div class="text-red-500 font-bold text-sm">៛ {{ number_format($totalOutRiel) }}</div>
            <div class="mt-3 pt-3 border-t border-slate-50 text-[11px] {{ ($deltaOut ?? 0) <= 0 ? 'text-green-500' : 'text-red-500' }} font-bold">
                <i class="fas fa-{{ ($deltaOut ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i> {{ number_format(abs($deltaOut ?? 0),1) }}% <span class="text-slate-400 font-normal">ធៀបគ្រាមុន</span>
            </div>
        </div>

        {{-- Net Balance --}}
        <div class="metric-card sm:col-span-2 lg:col-span-1 shadow-md relative overflow-hidden" style="background: {{ $statusBg }}; border: 1px solid {{ $statusColor }}33;">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm shrink-0" style="color: {{ $statusColor }}"><i class="fas fa-wallet"></i></div>
                <span class="text-[10px] font-bold uppercase tracking-wider" style="color: {{ $statusColor }}">សមតុល្យសុទ្ធ</span>
            </div>
            <div class="text-2xl font-black" style="color: {{ $statusColor }}">${{ number_format($displayNet,2) }}</div>
            <div class="font-bold opacity-70 text-sm" style="color: {{ $statusColor }}">៛ {{ number_format($netRiel) }}</div>
            <div class="mt-3 pt-3 border-t border-black/5 text-[10px] font-bold uppercase flex items-center gap-2" style="color: {{ $statusColor }}">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background-color: {{ $statusColor }}"></span>
                {{ $statusText }}
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-xs font-black text-slate-400 mb-6 uppercase tracking-widest">វិភាគចរន្តសាច់ប្រាក់ (Cashflow)</h3>
            <div class="h-64 sm:h-80"><canvas id="mainComparisonChart"></canvas></div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-xs font-black text-slate-400 mb-6 uppercase tracking-widest text-center">ការបែងចែកចំណាយ</h3>
            <div class="h-48 sm:h-56"><canvas id="expenseDonutChart"></canvas></div>
            <div class="mt-8 space-y-2 max-h-40 overflow-y-auto pr-2 custom-scroll">
                @foreach($donutLabels as $index => $lbl)
                <div class="flex justify-between items-center text-[11px] border-b border-slate-50 pb-2">
                    <span class="text-slate-600 flex items-center">
                        <span class="w-2 h-2 rounded-full mr-2" style="background-color: {{ ['#10b981','#f59e0b','#ef4444','#3b82f6','#8b5cf6'][$index]??'#cbd5e1' }}"></span>
                        {{ $lbl }}
                    </span>
                    <span class="font-black text-slate-800">${{ number_format($donutValues[$index],2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-10">
        <div class="bg-slate-800 p-4 text-white flex justify-between items-center">
            <span class="font-bold uppercase text-xs tracking-widest flex items-center gap-2">
                <i class="fas fa-list-ul text-blue-400"></i> ប្រតិបត្តិការលម្អិត
            </span>
            <span class="text-[10px] bg-white/10 px-3 py-1 rounded-full text-slate-300">Total: {{ count($reportData) }} items</span>
        </div>

        {{-- MOBILE VIEW --}}
        <div class="block md:hidden divide-y divide-slate-100 bg-white">
            @php $runningBalMobile = 0; @endphp
            @forelse($reportData as $index => $item)
                @php $runningBalMobile += $item['type']==='income'?$item['amount']:-$item['amount']; @endphp
                <div class="p-4 hover:bg-slate-50 transition-colors">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-[10px] font-mono font-bold text-slate-300">#{{ str_pad($index+1,2,'0',STR_PAD_LEFT) }}</span>
                        <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($item['date'])->format('d M, Y') }}</span>
                    </div>
                    <p class="text-sm font-bold text-slate-800 mb-3">{{ $item['name'] }}</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                            <p class="text-[9px] uppercase font-black text-slate-400 mb-1">ប្រតិបត្តិការ</p>
                            <p class="text-sm font-black {{ $item['type']==='income'?'text-green-600':'text-red-600' }}">
                                {{ $item['type']==='income'?'+':'-' }} ${{ number_format($item['amount'],2) }}
                            </p>
                        </div>
                        <div class="bg-blue-50/50 p-2 rounded-xl border border-blue-100">
                            <p class="text-[9px] uppercase font-black text-blue-400 mb-1">សមតុល្យ</p>
                            <p class="text-sm font-black text-blue-800">${{ number_format($runningBalMobile,2) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <i class="fas fa-folder-open text-3xl text-slate-200 mb-3 block"></i>
                    <p class="text-slate-400 italic text-sm">មិនមានទិន្នន័យសម្រាប់បង្ហាញ</p>
                </div>
            @endforelse
        </div>

        {{-- DESKTOP VIEW --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600">
                        <th rowspan="2" class="p-4 border-r border-slate-200 text-center w-12 text-[10px] uppercase font-black">ល.រ</th>
                        <th rowspan="2" class="p-4 border-r border-slate-200 text-left text-[10px] uppercase font-black">បរិយាយ / ថ្ងៃខែ</th>
                        <th colspan="2" class="p-2 border-r border-slate-200 text-center bg-green-50/50 text-green-700 font-bold uppercase text-[10px]">ចំណូល (In)</th>
                        <th colspan="2" class="p-2 border-r border-slate-200 text-center bg-red-50/50 text-red-700 font-bold uppercase text-[10px]">ចំណាយ (Out)</th>
                        <th colspan="2" class="p-2 text-center bg-blue-50/50 text-blue-700 font-bold uppercase text-[10px]">សមតុល្យ (Balance)</th>
                    </tr>
                    <tr class="bg-slate-50 border-b-2 border-slate-800 text-[9px] text-slate-400 uppercase font-black">
                        <th class="p-2 border-r border-slate-200 text-right">រៀល</th>
                        <th class="p-2 border-r border-slate-200 text-right">ដុល្លារ</th>
                        <th class="p-2 border-r border-slate-200 text-right">រៀល</th>
                        <th class="p-2 border-r border-slate-200 text-right">ដុល្លារ</th>
                        <th class="p-2 border-r border-slate-200 text-right">រៀល</th>
                        <th class="p-2 text-right">ដុល្លារ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $runningBalDesktop = 0; @endphp
                    @foreach($reportData as $index => $item)
                        @php $runningBalDesktop += $item['type']==='income'? $item['amount']:-$item['amount']; @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3 text-center border-r border-slate-100 text-slate-400 font-mono text-xs">{{ $index+1 }}</td>
                            <td class="p-3 border-r border-slate-100">
                                <div class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($item['date'])->format('d-m-Y') }}</div>
                                <div
                                <div class="text-[11px] text-slate-400 leading-tight">{{ $item['name'] }}</div>
                            </td>
                            <td class="p-3 text-right bg-green-50/10 border-r border-slate-100 text-slate-500">
                                {{ $item['type'] === 'income' ? number_format($item['amount'] * $exchangeRate).' ៛' : '-' }}
                            </td>
                            <td class="p-3 text-right bg-green-50/20 border-r border-slate-100 font-black {{ $item['type'] === 'income' ? 'text-green-600' : 'text-slate-300' }}">
                                {{ $item['type'] === 'income' ? '$'.number_format($item['amount'], 2) : '-' }}
                            </td>
                            <td class="p-3 text-right bg-red-50/10 border-r border-slate-100 text-slate-500">
                                {{ $item['type'] === 'expense' ? number_format($item['amount'] * $exchangeRate).' ៛' : '-' }}
                            </td>
                            <td class="p-3 text-right bg-red-50/20 border-r border-slate-100 font-black {{ $item['type'] === 'expense' ? 'text-red-600' : 'text-slate-300' }}">
                                {{ $item['type'] === 'expense' ? '$'.number_format($item['amount'], 2) : '-' }}
                            </td>
                            <td class="p-3 text-right bg-blue-50/5 border-r border-slate-100 text-blue-600/70 font-bold">
                                {{ number_format($runningBalDesktop * $exchangeRate) }} ៛
                            </td>
                            <td class="p-3 text-right bg-blue-50/20 font-black text-blue-800">
                                ${{ number_format($runningBalDesktop, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-900 text-white font-bold border-t-2 border-white/10">
                    <tr>
                        <td colspan="2" class="p-4 text-center uppercase text-[10px] tracking-[0.2em] text-slate-400">សរុបរួម (Grand Total)</td>
                        <td class="p-4 text-right border-r border-white/5">{{ number_format($totalInRiel) }} ៛</td>
                        <td class="p-4 text-right border-r border-white/5 text-green-400">${{ number_format($displayTotalIn, 2) }}</td>
                        <td class="p-4 text-right border-r border-white/5">{{ number_format($totalOutRiel) }} ៛</td>
                        <td class="p-4 text-right border-r border-white/5 text-red-400">${{ number_format($displayTotalOut, 2) }}</td>
                        <td class="p-4 text-right border-r border-white/5 text-blue-300">{{ number_format($netRiel) }} ៛</td>
                        <td class="p-4 text-right font-black text-yellow-400 text-lg">${{ number_format($displayNet, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = 'Khmer OS Battambang';
    Chart.defaults.color = '#64748b';

    // Main Comparison Chart
    new Chart(document.getElementById('mainComparisonChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($audienceLabels ?? []) !!},
            datasets: [
                { label: 'ចំណូល', data: {!! json_encode($audienceBars ?? []) !!}, backgroundColor: '#10b981', borderRadius: 6, barThickness: 12 },
                { label: 'ចំណាយ', data: {!! json_encode($audienceLine ?? []) !!}, backgroundColor: '#ef4444', borderRadius: 6, barThickness: 12 }
            ]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { 
                legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 6, font: { size: 10, weight: 'bold' } } } 
            },
            scales: { 
                y: { grid: { display: false }, ticks: { callback: v => '$' + v, font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });

    // Expense Donut Chart
    new Chart(document.getElementById('expenseDonutChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($donutLabels ?? []) !!},
            datasets: [{
                data: {!! json_encode($donutValues ?? []) !!},
                backgroundColor: ['#10b981','#f59e0b','#ef4444','#3b82f6','#8b5cf6'],
                borderWidth: 4,
                borderColor: '#ffffff',
                hoverOffset: 10
            }]
        },
        options: { 
            cutout: '75%', 
            maintainAspectRatio: false, 
            plugins: { legend: { display: false } } 
        }
    });
</script>
@endsection
