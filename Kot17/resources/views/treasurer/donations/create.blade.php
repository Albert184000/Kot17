@extends('layouts.admin')
@section('title', 'បន្ថែមការបរិច្ចាគថ្មី')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-orange-600 p-6 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg"><i class="fas fa-hand-holding-heart mr-2"></i> បញ្ចូលទិន្នន័យអ្នកប្រគេនបច្ច័យ</h3>
            <span class="text-xs bg-orange-700 px-3 py-1 rounded-full font-bold uppercase">Donation</span>
        </div>

        <form action="{{ route('treasurer.donations.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">រើសពីបញ្ជីសមាជិក</label>
                    <select name="member_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                        <option value="">-- ជ្រើសរើសសមាជិក --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div> --}}

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">វាយឈ្មោះអ្នកប្រគេនផ្ទាល់</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <i class="fas fa-user-edit"></i>
                        </span>
                        <input type="text" name="donor_name" placeholder="ឈ្មោះសប្បុរសជន..." class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- 🟢 កែសម្រួលផ្នែកចំនួនទឹកប្រាក់ឱ្យមានរើសប្រភេទលុយ --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">ចំនួនបច្ច័យ</label>
                    <div class="flex">
                        <select name="currency" class="bg-gray-100 border border-gray-200 border-r-0 rounded-l-2xl px-4 font-bold text-slate-600 focus:ring-0 outline-none">
                            <option value="USD">$</option>
                            <option value="KHR">៛</option>
                        </select>
                        <input type="number" name="amount" step="any" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-r-2xl font-bold text-orange-600 focus:ring-2 focus:ring-orange-500 outline-none" placeholder="0.00" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">ថ្ងៃប្រគេន</label>
                    <input type="date" name="donated_at" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">កំណត់ចំណាំ (ឧទាហរណ៍៖ ប្រគេនក្នុងពិធីបុណ្យ...)</label>
                <textarea name="note" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none" placeholder="ព័ត៌មានបន្ថែម..."></textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <a href="{{ route('treasurer.donations.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-4 rounded-2xl transition">បោះបង់</a>
                <button type="submit" class="flex-[2] bg-slate-900 hover:bg-orange-600 text-white font-bold py-4 rounded-2xl shadow-lg transition-all transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i> រក្សាទុកទិន្នន័យ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection