@extends('layouts.admin')
@section('title', 'ស្ថានភាពហិរញ្ញវត្ថុកុដិ')

@section('content')
<div class="main-content font-['Khmer_OS_Battambang'] bg-slate-50 min-h-screen p-4 md:p-8">
    
    {{-- ផ្នែកខាងលើ៖ សរុបថវិកា និងស្ថិតិសង្ខេប --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Card: លុយសាច់ក្នុងកុដិ (USD & KHR) --}}
            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">លុយសាច់ក្នុងកុដិ</p>
                <div class="space-y-1">
                    <h3 class="text-3xl font-black text-blue-600 font-mono">${{ number_format($cashInHandUSD, 2) }}</h3>
                    <h3 class="text-xl font-black text-orange-500 font-mono">{{ number_format($cashInHandKHR) }} ៛</h3>
                </div>
                <p class="text-[10px] text-slate-400 mt-4 italic border-t pt-2">បច្ចុប្បន្នភាព៖ {{ date('d-M-Y') }}</p>
            </div>

            {{-- Card: ចំណូលប្រចាំខែ (USD) --}}
            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-110 transition-transform"></div>
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">ចំណូលក្នុងខែ {{ date('M') }}</p>
                <h3 class="text-3xl font-black text-green-600 font-mono">${{ number_format($monthlyIncomeUSD, 2) }}</h3>
                <p class="text-[10px] text-slate-400 mt-2 italic border-t pt-2">គិតតែជារូបិយប័ណ្ណដុល្លារ</p>
            </div>

            {{-- Card: ថវិកាកុដិសរុប (Grand Total) --}}
            <div class="md:col-span-2 bg-slate-900 p-8 rounded-[3rem] shadow-2xl relative overflow-hidden">
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-3">ថវិកាកុដិសរុបក្នុងដៃ (Net Balance)</p>
                        <div class="flex flex-col md:flex-row md:items-end gap-2 md:gap-10">
                            <h3 class="text-5xl font-black text-white font-mono">${{ number_format($cashInHandUSD, 2) }}</h3>
                            <h3 class="text-3xl font-black text-orange-400 font-mono">{{ number_format($cashInHandKHR) }} ៛</h3>
                        </div>
                        <div class="mt-6 flex gap-4">
                            <span class="text-xs bg-white/10 px-3 py-1 rounded-full text-blue-300 font-bold">● សមាជិកសរុប: {{ $totalMembers }} នាក់</span>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <i class="fas fa-wallet text-7xl text-orange-500 opacity-20"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: សង្ខេបបំណុល --}}
        <div class="bg-white p-8 rounded-[3rem] shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center">
            <h4 class="text-sm font-black text-slate-800 uppercase mb-6 italic underline decoration-red-500">ស្ថានភាពអ្នកជំពាក់</h4>
            
            <div class="mb-6">
                <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">បំណុលដែលមិនទាន់សងសរុប</p>
                <h3 class="text-2xl font-black text-red-600 font-mono">${{ number_format($totalDebtUSD, 2) }}</h3>
                <h3 class="text-lg font-black text-red-500 font-mono">{{ number_format($totalDebtKHR) }} ៛</h3>
            </div>

            <div class="w-full space-y-3 pt-4 border-t border-slate-50">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-slate-500 font-bold">ចំនួនអ្នកជំពាក់</span>
                    <span class="font-black text-slate-800">{{ $debtors->count() }} នាក់</span>
                </div>
                <a href="#" class="block w-full py-3 bg-red-50 text-red-600 rounded-2xl text-[11px] font-black hover:bg-red-600 hover:text-white transition-all">
                    គ្រប់គ្រងបញ្ជីបំណុល
                </a>
            </div>
        </div>
    </div>

    {{-- តារាងអ្នកជំពាក់លុយកុដិ --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div>
                <h3 class="font-black text-slate-800 text-xl tracking-tight uppercase">
                    <i class="fas fa-user-clock mr-2 text-red-500"></i> បញ្ជីឈ្មោះអ្នកជំពាក់លុយកុដិ
                </h3>
                <p class="text-slate-400 text-xs mt-1">រាប់បញ្ចូលទាំងការជំពាក់ថ្លៃស្នាក់នៅ និងថ្លៃផ្សេងៗ</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">
                        <th class="p-6 text-center w-16 italic">ល.រ</th>
                        <th class="p-6">ឈ្មោះអ្នកជំពាក់</th>
                        <th class="p-6">មូលហេតុ</th>
                        <th class="p-6">ថ្ងៃខែ</th>
                        <th class="p-6 text-right">ទឹកប្រាក់</th>
                        <th class="p-6 text-center">ស្ថានភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($debtors as $index => $debtor)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="p-6 text-center font-mono text-xs text-slate-400">{{ $index + 1 }}</td>
                        <td class="p-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center font-black text-xs mr-4">
                                    {{ mb_substr($debtor->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-sm tracking-tight">{{ $debtor->name }}</p>
                                    <p class="text-[10px] text-slate-400 italic">{{ $debtor->phone ?? 'គ្មានលេខទូរស័ព្ទ' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 text-xs font-bold text-slate-500 italic">{{ $debtor->reason ?? 'មិនបានបញ្ជាក់' }}</td>
                        <td class="p-6 text-xs font-black text-slate-600 font-mono">{{ $debtor->created_at->format('d-M-Y') }}</td>
                        <td class="p-6 text-right font-mono font-black">
                            @if($debtor->currency == 'USD')
                                <span class="text-red-600">${{ number_format($debtor->debt_amount, 2) }}</span>
                            @else
                                <span class="text-orange-600">{{ number_format($debtor->debt_amount) }} <span class="font-['Khmer_OS_Battambang'] text-xs">៛</span></span>
                            @endif
                        </td>
                        <td class="p-6 text-center">
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[9px] font-black uppercase">
                                មិនទាន់សង
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-check-circle text-5xl text-green-100 mb-4"></i>
                                <p class="text-slate-400 italic text-sm font-bold">គ្មានអ្នកជំពាក់លុយកុដិទេនាពេលនេះ!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .font-mono { font-family: 'JetBrains Mono', 'Fira Code', monospace !important; }
</style>
@endsection