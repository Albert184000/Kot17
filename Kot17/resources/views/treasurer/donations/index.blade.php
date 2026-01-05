@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">បញ្ជីបច្ច័យចូល</h2>
        <a href="{{ route('treasurer.donations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-1"></i> បន្ថែមបច្ច័យ
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-600">ថ្ងៃខែ</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-600">ឈ្មោះសប្បុរសជន</th>
                        {{-- 🟢 បំបែក Column លុយជាពីរដូចការចំណាយ --}}
                        <th class="px-6 py-3 text-sm font-semibold text-gray-600 text-right">បច្ច័យ ($)</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-600 text-right">បច្ច័យ (៛)</th>
                        <th class="px-6 py-3 text-sm font-semibold text-gray-600">សម្គាល់</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($donations as $donation)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm">
                            {{ \Carbon\Carbon::parse($donation->donation_date ?? $donation->donated_at)->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4 font-medium">
                            {{ $donation->member->name ?? ($donation->donor_name ?? 'អ្នកបរិច្ចាគទូទៅ') }}
                            @if($donation->member)
                                <span class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-bold">សមាជិក</span>
                            @endif
                        </td>

                        {{-- 🟢 បង្ហាញតាមប្រភេទលុយ USD --}}
                        <td class="px-6 py-4 text-right text-green-600 font-bold">
                            @if($donation->currency == 'USD')
                                ${{ number_format($donation->amount, 2) }}
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>

                        {{-- 🟢 បង្ហាញតាមប្រភេទលុយ KHR --}}
                        <td class="px-6 py-4 text-right text-green-600 font-bold">
                            @if($donation->currency == 'KHR')
                                {{ number_format($donation->amount, 0) }} ៛
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-gray-500 text-sm italic">
                            {{ $donation->notes ?? $donation->note ?? '---' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400">មិនទាន់មានទិន្នន័យ</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $donations->links() }}
        </div>  
    </div>

    {{-- 🟢 បន្ថែមប្រអប់សរុបបច្ច័យនៅខាងក្រោម (Optional) --}}
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-slate-900 p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <span class="text-white font-bold">សរុបបច្ច័យ (USD)</span>
            <span class="text-xl font-black text-green-500">${{ number_format($donations->where('currency', 'USD')->sum('amount'), 2) }}</span>
        </div>
        <div class="bg-slate-900 p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <span class="text-white font-bold">សរុបបច្ច័យ (KHR)</span>
            <span class="text-xl font-black text-white">{{ number_format($donations->where('currency', 'KHR')->sum('amount'), 0) }} ៛</span>
        </div>
    </div>
</div>
@endsection