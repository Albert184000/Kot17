@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-black text-slate-800">របាយការណ៍ហិរញ្ញវត្ថុ</h1>
            <p class="text-sm text-gray-500">ចន្លោះថ្ងៃទី: {{ $startDate }} ដល់ {{ $endDate }}</p>
        </div>
        
        <form action="{{ URL::current() }}" method="GET" id="reportForm" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">ជ្រើសរើសខែ/ឆ្នាំ</label>
                <input type="month" name="filter_date" value="{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" 
                       class="text-sm border-gray-200 rounded-lg focus:ring-blue-500" onchange="this.form.submit()">
            </div>
            <div class="h-8 w-px bg-gray-200 mx-2 hidden md:block"></div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">ចាប់ពីថ្ងៃ</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="text-sm border-gray-200 rounded-lg focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">ដល់ថ្ងៃ</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="text-sm border-gray-200 rounded-lg focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition">ឆែក</button>
            <button type="button" onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition">បោះពុម្ព</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border-b-4 border-green-500 shadow-sm">
            <p class="text-gray-400 text-xs font-bold uppercase">ចំណូលសរុប (In)</p>
            <h3 class="text-3xl font-black text-green-600 mt-1">
                +${{ number_format($totalIn ?? 0, 2) }}
            </h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border-b-4 border-red-500 shadow-sm">
            <p class="text-gray-400 text-xs font-bold uppercase">ចំណាយសរុប (Out)</p>
            <h3 class="text-3xl font-black text-red-600 mt-1">
                -${{ number_format($totalOut ?? 0, 2) }}
            </h3>
        </div>
        <div class="bg-white p-6 rounded-2xl border-b-4 border-blue-600 shadow-sm">
            <p class="text-gray-400 text-xs font-bold uppercase">សមតុល្យ (Balance)</p>
            <h3 class="text-3xl font-black text-slate-800 mt-1">
                ${{ number_format($profit ?? 0, 2) }}
            </h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">កាលបរិច្ឆេទ</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">បរិយាយ / ឈ្មោះ</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">ប្រភេទ</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">ទឹកប្រាក់</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reportData as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ date('d-M-Y', strtotime($item->date)) }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $item->name }}</td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] font-bold {{ $item->type == 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-2 py-1 rounded">
                            {{ $item->type == 'income' ? 'ចំណូល' : 'ចំណាយ' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold {{ $item->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $item->type == 'income' ? '+' : '-' }}${{ number_format($item->amount, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">មិនមានទិន្នន័យក្នុងចន្លោះថ្ងៃនេះទេ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        form, button, nav, aside, header { display: none !important; }
        body { background: white; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 100%; padding: 0; margin: 0; }
        .rounded-2xl { border-radius: 0; border: none; }
        .shadow-sm { box-shadow: none; }
    }
</style>
@endsection