@extends('layouts.admin')
@section('title', 'ចុះបញ្ជីប្រមូលប្រាក់ប្រចាំថ្ងៃ')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-orange-600 p-6 text-white">
            <h3 class="text-lg font-bold flex items-center">
                <i class="fas fa-hand-holding-usd mr-3 text-2xl"></i> បញ្ចូលទិន្នន័យប្រមូលប្រាក់
            </h3>
            <p class="text-orange-100 text-xs mt-1 italic">សូមពិនិត្យឈ្មោះសមាជិក និងចំនួនទឹកប្រាក់ឱ្យបានត្រឹមត្រូវ</p>
        </div>

        <form action="{{ route('collector.collections.collect') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">ស្វែងរកសមាជិក (ឈ្មោះ/លេខទូរស័ព្ទ)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <select name="member_id" class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all appearance-none" required>
                        <option value="">-- រើសសមាជិក --</option>
                        {{-- កន្លែងនេះនឹង Loop ឈ្មោះសមាជិកពី Database --}}
                        <option value="1">សុខ ជា (012 345 678)</option>
                        <option value="2">ចាន់ ថន (098 765 432)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ទឹកប្រាក់ប្រមូលបាន</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-500 font-bold">$</span>
                        <input type="number" name="amount" step="0.01" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none" placeholder="0.00" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ថ្ងៃខែឆ្នាំ</label>
                    <input type="date" name="collected_at" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">កំណត់ចំណាំ (បើមាន)</label>
                <textarea name="note" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none" placeholder="បញ្ជាក់ផ្សេងៗ..."></textarea>
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-orange-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-slate-200 transition-all duration-300 transform hover:-translate-y-1">
                <i class="fas fa-save mr-2"></i> រក្សាទុកទិន្នន័យ
            </button>
        </form>
    </div>

    <div class="mt-6 flex justify-between items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center text-sm font-medium text-slate-500">
            <i class="fas fa-info-circle mr-2 text-blue-500"></i> ទិន្នន័យនឹងត្រូវបញ្ជូនទៅកាន់បេឡាករដើម្បីផ្ទៀងផ្ទាត់
        </div>
    </div>
</div>
@endsection