@extends('layouts.admin')
@section('title', 'ចុះបញ្ជីប្រមូលប្រចាំថ្ងៃ')

@section('content')

{{-- ✅ Alerts --}}
@if(session('success'))
    <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 font-semibold">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 font-semibold">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
        <ul class="list-disc pl-5 space-y-1 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    {{-- Header --}}
    <div class="p-5 border-b bg-gray-50/50 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">
                <i class="fas fa-basket-shopping mr-2 text-orange-500"></i>ចុះបញ្ជីកត់ឈ្មោះអ្នកប្រគេនបច្ច័យចង្ហាន់សម្រាប់កុដិ
            </h3>
            <p class="text-xs text-gray-400 mt-1">បញ្ចូលព័ត៌មាន និងចុច “រក្សាទុក” ដើម្បីកត់ត្រា</p>
        </div>

        <a href="{{ route('collector.donations.index') }}"
           class="text-sm font-bold text-blue-600 hover:text-blue-800">
            មើលបញ្ជីទាំងអស់ →
        </a>
    </div>

    {{-- Form --}}
    <div class="p-6">
        {{-- ✅ IMPORTANT: use new route --}}
        <form action="{{ route('collector.donations.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="flex flex-col">
                    <label class="text-xs mb-1 text-gray-500 ml-1">ឈ្មោះអ្នកប្រគេន</label>
                    <input type="text" name="donor_name" value="{{ old('donor_name') }}" placeholder="ឈ្មោះ..."
                        class="border rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500 outline-none bg-white"
                        required>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs mb-1 text-gray-500 ml-1">រូបិយប័ណ្ណ</label>
                    <select name="currency"
                        class="border rounded-xl px-4 py-2 text-sm bg-gray-50 font-bold outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="USD" {{ old('currency','USD')=='USD' ? 'selected' : '' }}>ដុល្លារ ($)</option>
                        <option value="KHR" {{ old('currency')=='KHR' ? 'selected' : '' }}>រៀល (៛)</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs mb-1 text-gray-500 ml-1">ចំនួនទឹកប្រាក់</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" placeholder="0.00"
                        class="border rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500 outline-none bg-white"
                        required>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs mb-1 text-gray-500 ml-1">ការពិពណ៌នា</label>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="មកពីណា..."
                        class="border rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500 outline-none bg-white"
                        required>
                </div>
            </div>

            <div class="mt-5 flex flex-col md:flex-row gap-3 md:items-center md:justify-end">
                <a href="{{ route('collector.dashboard') }}"
                   class="px-5 py-2 rounded-xl font-bold text-sm border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                    ត្រឡប់ក្រោយ
                </a>

                <button type="submit"
                    class="px-6 py-2 rounded-xl font-bold text-sm bg-orange-600 text-white hover:bg-orange-700 transition shadow-lg shadow-orange-200">
                    <i class="fas fa-save mr-2"></i>រក្សាទុក
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
