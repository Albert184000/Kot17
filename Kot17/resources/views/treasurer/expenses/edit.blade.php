@extends('layouts.admin')
@section('title', 'កែប្រែការចំណាយ')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('treasurer.expenses.index') }}" class="text-sm font-bold text-slate-500 hover:text-red-600 transition">
            <i class="fas fa-arrow-left mr-1"></i> ត្រឡប់ទៅបញ្ជីវិញ
        </a>
    </div>

    <form action="{{ route('treasurer.expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="bg-slate-800 p-6 text-white flex justify-between items-center">
            <h3 class="font-bold"><i class="fas fa-edit mr-2"></i> កែប្រែទិន្នន័យចំណាយ</h3>
            <span class="text-xs bg-slate-700 px-3 py-1 rounded-full uppercase tracking-widest font-bold">កូដ៖ #{{ $expense->id }}</span>
        </div>

        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">ប្រភេទចំណាយ</label>
                    <select name="category" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-red-500 outline-none transition-all" required>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ $expense->category == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">កាលបរិច្ឆេទ</label>
                    <input type="date" name="expense_date" value="{{ $expense->expense_date->format('Y-m-d') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-red-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">បរិយាយលម្អិត</label>
                <input type="text" name="description" value="{{ $expense->description }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-red-500 outline-none" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">ចំនួនទឹកប្រាក់</label>
                    <div class="flex">
                        <select name="currency" class="bg-gray-100 border border-gray-200 border-r-0 rounded-l-2xl px-3 font-bold text-slate-600 focus:ring-0 outline-none">
                            <option value="USD" {{ $expense->currency == 'USD' ? 'selected' : '' }}>$</option>
                            <option value="KHR" {{ $expense->currency == 'KHR' ? 'selected' : '' }}>៛</option>
                        </select>
                        <input type="number" name="amount" step="any" value="{{ $expense->amount }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-r-2xl font-bold text-red-600 focus:ring-2 focus:ring-red-500 outline-none" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">រូបភាពបង្កាន់ដៃ (បច្ចុប្បន្ន)</label>
                    <div class="flex items-center gap-3">
                        @if($expense->receipt_image)
                            <div class="relative group w-12 h-12">
                                <img src="{{ asset('storage/'.$expense->receipt_image) }}" class="w-full h-full object-cover rounded-lg border border-gray-200">
                            </div>
                        @endif
                        <input type="file" name="receipt" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-red-600 hover:file:bg-red-100 cursor-pointer">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">កំណត់ចំណាំបន្ថែម (Note)</label>
                <textarea name="note" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-red-500 outline-none">{{ $expense->note }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-2xl transition-all duration-300 shadow-lg">
                    <i class="fas fa-save mr-2"></i> រក្សាទុកការផ្លាស់ប្តូរ
                </button>
                <a href="{{ route('treasurer.expenses.index') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 text-center font-bold py-4 rounded-2xl transition-all duration-300">
                    បោះបង់
                </a>
            </div>
        </div>
    </form>
</div>
@endsection