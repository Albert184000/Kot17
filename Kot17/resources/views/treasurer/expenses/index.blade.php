@extends('layouts.admin')
@section('title', 'បញ្ជីចំណាយលម្អិត')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-bold text-slate-800 italic underline decoration-red-500">ប្រវត្តិការចំណាយ</h3>
    <a href="{{ route('treasurer.expenses.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-xl text-sm font-bold shadow-lg transition">
        <i class="fas fa-plus mr-2"></i> បន្ថែមចំណាយថ្មី
    </a>
</div>

{{-- បង្ហាញសារជោគជ័យ --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-bold animate-pulse">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-widest border-b border-gray-100">
                    <th class="p-4">កាលបរិច្ឆេទ</th>
                    <th class="p-4">បរិយាយ (ចំណាយលើអ្វី)</th>
                    <th class="p-4">ប្រភេទ</th>
                    <th class="p-4 text-right">តម្លៃ ($)</th>
                    <th class="p-4 text-right">តម្លៃ (៛)</th>
                    <th class="p-4 text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($expenses as $expense)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4 text-xs text-slate-600 font-medium">
                        {{ date('d-M-Y', strtotime($expense->expense_date)) }}
                    </td>
                    <td class="p-4">
                        <p class="text-sm font-bold text-slate-800">{{ $expense->description }}</p>
                        @if($expense->note)
                            <p class="text-[10px] text-gray-400 italic">{{ $expense->note }}</p>
                        @endif
                    </td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase 
                            {{ $expense->category == 'food' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                            {{ $expense->category == 'food' ? 'ម្ហូបព្រឹក' : ($categories[$expense->category] ?? $expense->category) }}
                        </span>
                    </td>
                    
                    {{-- USD Column: Only shows if currency is USD --}}
                    <td class="p-4 text-right">
                        @if($expense->currency == 'USD')
                            <span class="text-sm font-black text-red-600">${{ number_format($expense->amount, 2) }}</span>
                        @else
                            <span class="text-[10px] text-gray-300">-</span>
                        @endif
                    </td>

                    {{-- KHR Column: Only shows if currency is KHR --}}
                    <td class="p-4 text-right">
                        @if($expense->currency == 'KHR')
                            <span class="text-sm font-black text-red-600">{{ number_format($expense->amount, 0) }} ៛</span>
                        @else
                            <span class="text-[10px] text-gray-300">-</span>
                        @endif
                    </td>

                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('treasurer.expenses.edit', $expense->id) }}" class="text-slate-400 hover:text-blue-600 p-2 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('treasurer.expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('តើបងប្រាកដថាចង់លុបមែនទេ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-600 p-2 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-10 text-center text-gray-400 italic">
                        <i class="fas fa-folder-open mb-2 text-2xl block"></i>
                        មិនទាន់មានទិន្នន័យចំណាយនៅឡើយទេ។
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination Links --}}
<div class="mt-4">
    {{ $expenses->links() }}
</div>

{{-- Summary Box: Accurately calculated by Currency --}}
<div class="mt-6 p-6 bg-slate-900 rounded-3xl text-white flex flex-col md:flex-row justify-between items-center shadow-xl gap-4">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-orange-500/20 rounded-2xl grid place-items-center">
            <i class="fas fa-dollar-sign text-orange-500 text-xl"></i>
        </div>
        <div>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-none">សរុបការចំណាយ (USD)</p>
            <h2 class="text-3xl font-black text-orange-500 mt-1">
                ${{ number_format($expenses->where('currency', 'USD')->sum('amount'), 2) }}
            </h2>
        </div>
    </div>

    <div class="hidden md:block h-10 w-[1px] bg-slate-800"></div>

    <div class="flex items-center gap-4 text-right">
        <div>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-none">សរុបការចំណាយ (KHR)</p>
            <h2 class="text-3xl font-black text-white mt-1">
                {{ number_format($expenses->where('currency', 'KHR')->sum('amount'), 0) }} ៛
            </h2>
        </div>
        <div class="w-12 h-12 bg-white/10 rounded-2xl grid place-items-center">
            <span class="text-white font-bold text-xl">៛</span>
        </div>
    </div>
</div>
@endsection