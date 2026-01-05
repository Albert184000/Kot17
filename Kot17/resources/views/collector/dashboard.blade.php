@extends('layouts.admin')
@section('title', 'ផ្ទាំងគ្រប់គ្រងអ្នកប្រមូល')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 rounded-2xl text-white shadow-md">
        <p class="text-blue-100 text-sm font-medium">ទឹកប្រាក់ប្រមូលបានថ្ងៃនេះ</p>
        <h3 class="text-3xl font-bold mt-2">$150.00</h3>
        <p class="text-xs mt-2 text-blue-100 italic">* ចំនួនសរុបដែលមិនទាន់វាយចូលប្រព័ន្ធ</p>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <p class="text-gray-500 text-sm font-medium">គោលដៅថ្ងៃនេះ</p>
        <div class="flex items-center mt-2">
            <h3 class="text-3xl font-bold text-slate-800">75%</h3>
            <div class="ml-4 flex-1 h-2 bg-gray-100 rounded-full">
                <div class="bg-green-500 h-2 rounded-full" style="width: 75%"></div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6 text-center">
    <h3 class="font-bold text-slate-800 mb-4">សកម្មភាពរហ័ស</h3>
    <div class="grid grid-cols-2 gap-4">
        <a href="{{ route('collector.collections.daily') }}" class="p-4 bg-orange-50 rounded-xl border border-orange-100 hover:bg-orange-100 transition group">
            <i class="fas fa-hand-holding-usd text-2xl text-orange-600 mb-2"></i>
            <p class="text-sm font-bold text-orange-700">ចុះបញ្ជីប្រមូល</p>
        </a>
        <a href="{{ route('collector.collections.history') }}" class="p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition">
            <i class="fas fa-history text-2xl text-slate-600 mb-2"></i>
            <p class="text-sm font-bold text-slate-700">ប្រវត្តិប្រមូល</p>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 border-b border-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-slate-800 italic text-sm"><i class="fas fa-list-ul mr-2 text-orange-500"></i>បញ្ជីប្រមូលចុងក្រោយ</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <tbody class="divide-y divide-gray-50">
                <tr class="hover:bg-gray-50/50">
                    <td class="p-4">
                        <p class="font-bold text-slate-800 text-sm">លោក សុខ ជា</p>
                        <p class="text-[10px] text-gray-400">កាលពី ៣០ នាទីមុន</p>
                    </td>
                    <td class="p-4 text-right">
                        <span class="text-orange-600 font-bold">$10.00</span>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50/50">
                    <td class="p-4">
                        <p class="font-bold text-slate-800 text-sm">អ្នកស្រី ម៉ារី</p>
                        <p class="text-[10px] text-gray-400">កាលពី ១ ម៉ោងមុន</p>
                    </td>
                    <td class="p-4 text-right">
                        <span class="text-orange-600 font-bold">$5.00</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection