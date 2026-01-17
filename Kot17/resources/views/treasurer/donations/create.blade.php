@extends('layouts.admin')
@section('title', 'បន្ថែមការបរិច្ចាគថ្មី')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 border border-gray-100 overflow-hidden">
        <div class="bg-orange-600 p-6 text-white flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg leading-none mb-1">បញ្ចូលទិន្នន័យអ្នកប្រគេនបច្ច័យ</h3>
                <p class="text-orange-200 text-xs uppercase tracking-wider font-semibold">New Donation Entry</p>
            </div>
            <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
        </div>

        <form action="{{ route('treasurer.donations.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">ឈ្មោះសប្បុរសជន / អ្នកប្រគេន</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-orange-600 transition-colors">
                            <i class="fas fa-user-edit"></i>
                        </span>
                        <input type="text" name="donor_name" placeholder="វាយឈ្មោះនៅទីនេះ..." 
                               class="w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-orange-100 focus:border-orange-500 focus:bg-white outline-none transition-all font-medium" required>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">ចំនួនបច្ច័យ</label>
                    <div class="flex shadow-sm rounded-2xl overflow-hidden focus-within:ring-4 focus-within:ring-orange-100 transition-all">
                        <select name="currency" class="bg-gray-100 border border-gray-200 border-r-0 px-5 font-black text-slate-700 focus:ring-0 outline-none cursor-pointer hover:bg-gray-200 transition-colors">
                            <option value="USD">$</option>
                            <option value="KHR">៛</option>
                        </select>
                        <input type="number" name="amount" step="any" 
                               class="w-full px-4 py-4 bg-gray-50 border border-gray-200 border-l-0 font-black text-xl text-orange-600 focus:bg-white outline-none" 
                               placeholder="0.00" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">ថ្ងៃប្រគេន</label>
                    <div class="relative">
                        <input type="date" name="donated_at" value="{{ date('Y-m-d') }}" 
                               class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-orange-100 focus:border-orange-500 focus:bg-white outline-none transition-all font-bold text-slate-700">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">កំណត់ចំណាំ (ផ្សេងៗ)</label>
                <textarea name="note" rows="3" class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-orange-100 focus:border-orange-500 focus:bg-white outline-none transition-all placeholder:text-slate-300" placeholder="ព័ត៌មានបន្ថែមអំពីការបរិច្ចាគ..."></textarea>
            </div>

            <div class="flex flex-col md:flex-row gap-4 pt-4">
                <a href="{{ route('treasurer.donations.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-slate-600 font-bold py-4 rounded-2xl transition-colors">
                    <i class="fas fa-times mr-2"></i> បោះបង់
                </a>
                <button type="submit" class="flex-[2] bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-2xl shadow-xl shadow-slate-200 transition-all transform hover:-translate-y-1 active:scale-95">
                    <i class="fas fa-save mr-2 text-orange-500"></i> រក្សាទុកទិន្នន័យ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection