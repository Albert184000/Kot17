@extends('layouts.admin')
@section('title', 'បញ្ជីទិន្នន័យប្រមូលទាំងអស់')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="p-5 border-b bg-gray-50/50 flex items-center justify-between">
        <h3 class="font-bold text-slate-800">
            <i class="fas fa-list mr-2 text-orange-500"></i>បញ្ជីទិន្នន័យប្រមូលទាំងអស់
        </h3>
        <a href="{{ route('collector.reports.donations') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800">
            មើលរបាយការណ៍ →
        </a>
    </div>

    <div class="p-5">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <input type="date" name="from" value="{{ request('from') }}" class="border rounded-xl px-3 py-2 text-sm">
            <input type="date" name="to" value="{{ request('to') }}" class="border rounded-xl px-3 py-2 text-sm">

            <select name="currency" class="border rounded-xl px-3 py-2 text-sm bg-white">
                <option value="">-- ទាំងអស់ --</option>
                <option value="USD" @selected(request('currency')=='USD')>USD ($)</option>
                <option value="KHR" @selected(request('currency')=='KHR')>KHR (៛)</option>
            </select>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកឈ្មោះ/ពិពណ៌នា..."
                class="border rounded-xl px-3 py-2 text-sm">

            <button class="bg-slate-900 text-white rounded-xl py-2 font-bold text-sm">Filter</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-400 uppercase text-[10px] tracking-wider">
                    <th class="p-4">#</th>
                    <th class="p-4">អ្នកប្រគេន</th>
                    <th class="p-4">ពិពណ៌នា</th>
                    <th class="p-4 text-right">ទឹកប្រាក់</th>
                    <th class="p-4 text-center">ថ្ងៃ/ម៉ោង</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($donations as $d)
                    <tr class="hover:bg-gray-50/60">
                        <td class="p-4 text-gray-400 font-medium">#{{ $d->id }}</td>
                        <td class="p-4 font-bold text-slate-800">{{ $d->donor_name }}</td>
                        <td class="p-4 text-gray-500 text-xs">{{ $d->description }}</td>
                        <td class="p-4 text-right font-bold {{ $d->currency=='USD' ? 'text-blue-600' : 'text-emerald-600' }}">
                            {{ $d->currency=='USD' ? '$'.number_format($d->amount,2) : number_format($d->amount).' ៛' }}
                        </td>
                        <td class="p-4 text-center text-[11px] text-gray-400">
                            {{ optional($d->donated_at)->format('d/m/Y H:i') ?? $d->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-10 text-center text-gray-400 italic">មិនទាន់មានទិន្នន័យ</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4">
        {{ $donations->links() }}
    </div>
</div>
@endsection
