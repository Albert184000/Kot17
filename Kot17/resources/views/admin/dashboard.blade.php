@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
    <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full text-blue-500">
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">សមាជិកសរុប</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalMembers) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full text-green-500">
                <i class="fas fa-hand-holding-usd fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">ថវិកាប្រមូលបាន</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($totalDonations, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-red-500">
        <div class="flex items-center">
            <div class="p-3 bg-red-100 rounded-full text-red-500">
                <i class="fas fa-file-invoice-dollar fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">ចំណាយសរុប</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($totalExpenses, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-full text-purple-500">
                <i class="fas fa-user-shield fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">អ្នកប្រើប្រាស់</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-orange-500">
        <div class="flex items-center">
            <div class="p-3 bg-orange-100 rounded-full text-orange-500">
                <i class="fas fa-utensils fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">ឆាន់នៅកុដិថ្ងៃនេះ</p>
                <p class="text-2xl font-bold text-gray-800">{{ $presentCount }} អង្គ</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">សកម្មភាពបច្ច័យចូលចុងក្រោយ</h3>
        <a href="{{ route('treasurer.donations.index') }}" class="text-orange-600 hover:underline text-sm font-medium">មើលទាំងអស់</a>
    </div>
    <div class="overflow-x-auto p-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-400 uppercase text-sm border-b">
                    <th class="pb-3 px-2">កាលបរិច្ឆេទ</th>
                    <th class="pb-3 px-2">ឈ្មោះសប្បុរសជន</th>
                    <th class="pb-3 px-2 text-right">ចំនួនទឹកប្រាក់</th>
                    <th class="pb-3 px-2 text-center">ស្ថានភាព</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 divide-y">
                @forelse($recentDonations as $donation)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-2">
                        {{ \Carbon\Carbon::parse($donation->donation_date ?? $donation->donated_at)->format('d-m-Y') }}
                    </td>
                    <td class="py-4 px-2 font-medium text-gray-800">
                        {{ $donation->member->name ?? ($donation->donor_name ?? 'អ្នកបរិច្ចាគទូទៅ') }}
                    </td>
                    <td class="py-4 px-2 text-right text-green-600 font-bold">
                        ${{ number_format($donation->amount, 2) }}
                    </td>
                    <td class="py-4 px-2 text-center">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">ជោគជ័យ</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-10 text-center text-gray-400 italic">មិនទាន់មានទិន្នន័យនៅឡើយ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection