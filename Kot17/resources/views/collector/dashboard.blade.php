@extends('layouts.admin')
@section('title', 'ផ្ទាំងគ្រប់គ្រងអ្នកប្រមូល')

@section('content')
@php
    $exchangeRate = 4100;
    $todayTotalUSD = (float) ($todayTotalUSD ?? 0);
    $todayTotalKHR = (float) ($todayTotalKHR ?? 0);
    $totalInUSD = $todayTotalUSD + ($todayTotalKHR / $exchangeRate);
    $goalUSD = 10; 
    $totalPercentage = $goalUSD > 0 ? min(round(($totalInUSD / $goalUSD) * 100), 100) : 0;
@endphp

{{-- Load Alpine.js --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="{ 
    globalStatus: 'absent', 
    imagePreviews: [],
    handleFiles(event) {
        this.imagePreviews = [];
        const files = Array.from(event.target.files).slice(0, 4); // Limit to 4 images
        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => { this.imagePreviews.push(e.target.result) };
            reader.readAsDataURL(file);
        });
    }
}">
    <form action="{{ route('collector.summary') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('តើអ្នកពិតជាចង់ផ្ញើរបាយការណ៍រួមនេះទៅ Telegram មែនទេ?')">
        @csrf

        {{-- ១. ផ្នែកបញ្ចូលតម្លៃ និង រូបភាពច្រើនសន្លឹក --}}
        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-50 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- បញ្ចូលទឹកប្រាក់ --}}
                <div class="lg:col-span-3">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-3">ចំនួនទឹកប្រាក់បង់ក្នុងម្នាក់</label>
                    <div class="relative">
                        <input type="number" step="any" name="per_person_amount" value="5000" required
                            class="w-full px-6 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl text-lg font-black text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-300 italic">AMT</span>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-3">រូបិយប័ណ្ណ</label>
                    <select name="per_person_currency" class="w-full px-5 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl font-bold text-slate-700 outline-none cursor-pointer appearance-none">
                        <option value="KHR" selected>KHR (៛)</option>
                        <option value="USD">USD ($)</option>
                    </select>
                </div>

                {{-- បន្ថែមផ្នែក Upload រូបភាពច្រើន (Multiple Images) --}}
                <div class="lg:col-span-4">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-3">រូបភាពយោង (អតិបរមា ៤ សន្លឹក)</label>
                    <div class="relative group">
                        <input type="file" name="report_images[]" id="report_images" accept="image/*" multiple class="hidden" 
                               @change="handleFiles($event)">
                        
                        <label for="report_images" class="flex flex-wrap gap-2 items-center justify-center w-full min-h-[60px] px-6 py-3 bg-gray-50/50 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all">
                            
                            <template x-if="imagePreviews.length === 0">
                                <div class="text-center">
                                    <i class="fas fa-camera text-gray-400"></i>
                                    <span class="text-[10px] font-bold text-gray-400 ml-2">ភ្ជាប់រូបភាព (១-៤ សន្លឹក)</span>
                                </div>
                            </template>

                            <div class="flex flex-wrap gap-2">
                                <template x-for="(img, index) in imagePreviews" :key="index">
                                    <div class="relative">
                                        <img :src="img" class="h-12 w-12 object-cover rounded-lg shadow-sm border-2 border-white">
                                        <div class="absolute -top-1 -right-1 bg-blue-500 text-white text-[8px] w-4 h-4 rounded-full flex items-center justify-center" x-text="index + 1"></div>
                                    </div>
                                </template>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-3">សម្គាល់កម្មវិធី</label>
                    <input type="text" name="final_description" placeholder="ឧ. ចង្ហាន់ថ្ងៃត្រង់"
                        class="w-full px-6 py-4 bg-gray-50/50 border border-gray-100 rounded-2xl font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
            </div>
        </div>

        {{-- ២. ផ្នែកបញ្ជីវត្តមាន --}}
        <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50 mb-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-800 text-sm uppercase tracking-widest">បញ្ជីវត្តមានបង់ប្រាក់</h3>
                    </div>
                </div>

                <div class="flex items-center gap-4 w-full md:w-auto">
                    <div class="bg-gray-100 p-1.5 rounded-2xl flex gap-1">
                        <button type="button" @click="globalStatus = 'present'" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase transition-all" :class="globalStatus === 'present' ? 'bg-white shadow-sm text-slate-700' : 'text-gray-400'">បង់គ្រប់គ្នា</button>
                        <button type="button" @click="globalStatus = 'absent'" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase transition-all" :class="globalStatus === 'absent' ? 'bg-[#ff4d6d] text-white' : 'text-gray-400'">សម្អាត</button>
                    </div>
                    <button type="submit" class="flex-1 md:flex-none bg-[#3b82f6] hover:bg-blue-600 text-white px-8 py-4 rounded-2xl font-black shadow-lg flex items-center justify-center gap-3 transition-all active:scale-95 uppercase tracking-widest text-[11px]">
                        <i class="fab fa-telegram-plane text-lg"></i> ផ្ញើរបាយការណ៍ 📊
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($realMembers as $member)
                    @if($member->role !== 'member')
                    <div class="relative flex flex-col items-center p-8 rounded-[3.5rem] bg-gray-50/50 border border-gray-100" 
                         x-data="{ status: 'absent' }" 
                         x-init="status = globalStatus; $watch('globalStatus', val => status = val)">
                        
                        <div class="w-20 h-20 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 border-[6px] border-white text-2xl font-black text-slate-200 uppercase">
                            {{ substr($member->name, 0, 1) }}
                        </div>

                        <span class="font-black text-slate-700 text-lg mb-1">{{ $member->name }}</span>
                        
                        <input type="hidden" name="attendance[{{ $member->name }}]" :value="status">
                        
                        <div class="flex flex-col gap-2 w-full mt-4">
                            <button type="button" @click="status = 'present'"
                                class="w-full py-3 rounded-xl text-[10px] font-black uppercase transition-all border shadow-sm"
                                :class="status === 'present' ? 'bg-white border-emerald-500 text-emerald-500' : 'bg-white border-gray-100 text-gray-300'">
                                <i class="fas fa-check-circle mr-1"></i> បង់រួច
                            </button>

                            <button type="button" @click="status = 'absent'"
                                class="w-full py-3 rounded-xl text-[10px] font-black uppercase transition-all border shadow-sm"
                                :class="status === 'absent' ? 'bg-[#ff4d6d] border-transparent text-white' : 'bg-white border-gray-100 text-gray-300'">
                                <i class="fas fa-times-circle mr-1"></i> មិនទាន់បង់
                            </button>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </form>
</div>
@endsection