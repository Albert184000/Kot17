@extends('layouts.admin')

@section('title', 'របាយការណ៍បច្ច័យចង្ហាន់')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border-l-4 border-blue-500 p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">សរុបលុយរៀល</p>
            <h3 class="text-2xl font-black text-blue-600 mt-1">{{ number_format($totalKHR) }} ៛</h3>
        </div>
        <div class="w-12 h-12 bg-blue-50 rounded-xl grid place-items-center">
            <i class="fas fa-money-bill-wave text-blue-500 text-xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border-l-4 border-green-500 p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">សរុបលុយដុល្លារ</p>
            <h3 class="text-2xl font-black text-green-600 mt-1">${{ number_format($totalUSD, 2) }}</h3>
        </div>
        <div class="w-12 h-12 bg-green-50 rounded-xl grid place-items-center">
            <i class="fas fa-dollar-sign text-green-500 text-xl"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-800">ប្រវត្តិប្រមូលបច្ច័យចង្ហាន់</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-4">កាលបរិច្ឆេទ</th>
                    <th class="px-6 py-4">សប្បុរសជន</th>
                    <th class="px-6 py-4">ចំនួនទឹកប្រាក់</th>
                    <th class="px-6 py-4">សម្គាល់</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($donations as $donation)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-slate-600 text-sm">
                        {{ $donation->donated_at ? $donation->donated_at->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-900">{{ $donation->donor_name }}</td>
                    <td class="px-6 py-4 font-bold">
                        @if($donation->currency == 'KHR')
                            <span class="text-blue-600">{{ number_format($donation->amount) }} ៛</span>
                        @else
                            <span class="text-green-600">${{ number_format($donation->amount, 2) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-sm">{{ $donation->description }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">មិនទាន់មានទិន្នន័យនៅឡើយទេ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($donations->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $donations->links() }}
    </div>
    @endif
</div>
@endsection