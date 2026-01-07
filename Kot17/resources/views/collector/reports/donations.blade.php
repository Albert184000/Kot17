@extends('layouts.admin')
@section('title', 'របាយការណ៍ប្រមូល')

@section('content')
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <h3 class="font-bold text-slate-800">
            <i class="fas fa-chart-bar mr-2 text-orange-500"></i>របាយការណ៍ប្រមូល ({{ $from }} → {{ $to }})
        </h3>

        <form method="GET" class="flex flex-wrap gap-2">
            <input type="date" name="from" value="{{ $from }}" class="border rounded-xl px-3 py-2 text-sm">
            <input type="date" name="to" value="{{ $to }}" class="border rounded-xl px-3 py-2 text-sm">
            <button class="bg-slate-900 text-white rounded-xl px-4 py-2 text-sm font-bold">Update</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
        <div class="p-5 rounded-2xl bg-blue-50 border border-blue-100">
            <p class="text-xs text-blue-700 font-bold">សរុប USD</p>
            <p class="text-2xl font-extrabold text-blue-900">${{ number_format($totalUSD,2) }}</p>
        </div>

        <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-100">
            <p class="text-xs text-emerald-700 font-bold">សរុប KHR</p>
            <p class="text-2xl font-extrabold text-emerald-900">{{ number_format($totalKHR) }} ៛</p>
        </div>

        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100">
            <p class="text-xs text-slate-700 font-bold">បម្លែងជា USD ({{ number_format($exchangeRate) }}៛/$)</p>
            <p class="text-2xl font-extrabold text-slate-900">${{ number_format($totalInUSD,2) }}</p>
        </div>

        <div class="p-5 rounded-2xl bg-orange-50 border border-orange-100">
            <p class="text-xs text-orange-700 font-bold">ចំនួនកំណត់ត្រា</p>
            <p class="text-2xl font-extrabold text-orange-900">{{ number_format($countAll) }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b bg-gray-50/50 font-bold text-slate-800">Top Donors (by times)</div>
        <div class="p-4">
            <ul class="space-y-2 text-sm">
                @forelse($topDonors as $t)
                    <li class="flex justify-between">
                        <span class="font-semibold text-slate-800">{{ $t->donor_name }}</span>
                        <span class="text-gray-500">{{ $t->times }} ដង</span>
                    </li>
                @empty
                    <li class="text-gray-400 italic">មិនមានទិន្នន័យ</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b bg-gray-50/50 font-bold text-slate-800">Latest 10 records</div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <tbody class="divide-y divide-gray-50">
                    @forelse($latest as $d)
                        <tr>
                            <td class="p-3 font-bold text-slate-800">{{ $d->donor_name }}</td>
                            <td class="p-3 text-right font-bold {{ $d->currency=='USD' ? 'text-blue-600' : 'text-emerald-600' }}">
                                {{ $d->currency=='USD' ? '$'.number_format($d->amount,2) : number_format($d->amount).' ៛' }}
                            </td>
                            <td class="p-3 text-right text-[11px] text-gray-400">
                                {{ optional($d->donated_at)->format('d/m/Y H:i') ?? $d->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr><td class="p-6 text-center text-gray-400 italic">មិនមានទិន្នន័យ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
