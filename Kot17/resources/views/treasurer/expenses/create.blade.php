@extends('layouts.admin')
@section('title', 'បញ្ចូលការចំណាយថ្មី')

@section('content')
<div class="max-w-3xl mx-auto">
    <form action="{{ route('treasurer.expenses.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        <div class="bg-red-600 p-6 text-white flex justify-between items-center">
            <h3 class="font-bold"><i class="fas fa-file-invoice-dollar mr-2"></i> បញ្ជីចំណាយថវិកា</h3>
            <span class="text-xs bg-red-700 px-3 py-1 rounded-full uppercase tracking-widest font-bold">កុដិ ១៧</span>
        </div>

        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">ប្រភេទចំណាយ</label>
                    <select name="category" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-red-500 outline-none transition-all" required>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">កាលបរិច្ឆេទ</label>
                    <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-red-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">បរិយាយលម្អិត (ទិញអ្វីខ្លះ?)</label>
                <input type="text" name="description" placeholder="ឧទាហរណ៍៖ ម្ហូបព្រឹកសម្រាប់លោកសង្ឃ..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-red-500 outline-none" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">ចំនួនទឹកប្រាក់</label>
                    <div class="flex">
                        <select name="currency" class="bg-gray-100 border border-gray-200 border-r-0 rounded-l-2xl px-3 font-bold text-slate-600 focus:ring-0 outline-none">
                            <option value="USD">$</option>
                            <option value="KHR">៛</option>
                        </select>
                        <input type="number" name="amount" step="any" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-r-2xl font-bold text-red-600 focus:ring-2 focus:ring-red-500 outline-none" placeholder="0.00" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">រូបភាពបង្កាន់ដៃ/វិក្កយបត្រ (បើមាន)</label>
                    <input type="file" name="receipt" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-600 hover:file:bg-red-100 cursor-pointer">
                </div>
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-red-600 text-white font-bold py-4 rounded-2xl transition-all duration-300 shadow-lg shadow-slate-100">
                <i class="fas fa-check-circle mr-2"></i> រក្សាទុកការចំណាយ
            </button>
        </div>
    </form>
</div>
@endsection