@extends('layouts.admin')

@section('content')
<div class="p-6">
    {{-- ក្បាលទំព័រ និងប៊ូតុងបន្ថែម --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 italic">បញ្ជីបច្ច័យចូល</h2>
            <p class="text-slate-500 text-sm">គ្រប់គ្រង និងតាមដានរាល់បច្ច័យដែលបានបរិច្ចាគ</p>
        </div>
        <a href="{{ route('treasurer.donations.create') }}" class="bg-orange-600 text-white px-5 py-2.5 rounded-xl hover:bg-orange-700 shadow-lg shadow-orange-200 transition-all transform hover:-translate-y-1">
            <i class="fas fa-plus-circle mr-2"></i> បន្ថែមបច្ច័យ
        </a>
    </div>

    {{-- បង្ហាញសារជូនដំណឹងនៅពេល Store, Update, ឬ Delete ជោគជ័យ --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-xl flex items-center shadow-sm animate-fade-in-down">
            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white mr-3 shadow-sm">
                <i class="fas fa-check text-xs"></i>
            </div>
            <span class="text-green-800 font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- តារាងទិន្នន័យ --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">ថ្ងៃខែ</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">ឈ្មោះសប្បុរសជន</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">បច្ច័យ ($)</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">បច្ច័យ (៛)</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">សម្គាល់</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($donations as $donation)
                    <tr class="hover:bg-gray-50/50 transition duration-200">
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                            {{ \Carbon\Carbon::parse($donation->donated_at)->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700">
                                {{ $donation->member->name ?? ($donation->donor_name ?? 'អ្នកបរិច្ចាគទូទៅ') }}
                            </div>
                            @if($donation->member)
                                <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-bold inline-flex items-center">
                                    <i class="fas fa-user-check mr-1 text-[8px]"></i> សមាជិក
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            @if($donation->currency == 'USD')
                                <span class="text-green-600 font-black text-lg">${{ number_format($donation->amount, 2) }}</span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            @if($donation->currency == 'KHR')
                                <span class="text-blue-600 font-black text-lg">{{ number_format($donation->amount, 0) }} ៛</span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-slate-400 text-sm italic">
                            {{ Str::limit($donation->note ?? '---', 20) }}
                        </td>

                        {{-- 🔵 សកម្មភាព: Edit & Delete --}}
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center items-center gap-3">
                                <a href="{{ route('treasurer.donations.edit', $donation->id) }}" 
                                   class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-md"
                                   title="កែប្រែ">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                
                                <form action="{{ route('treasurer.donations.destroy', $donation->id) }}" method="POST" 
                                      class="inline-block"
                                      onsubmit="return confirm('តើអ្នកប្រាកដថាចង់លុបទិន្នន័យបច្ច័យនេះ? វាមិនអាចត្រឡប់ក្រោយវិញបានទេ!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-md"
                                            title="លុប">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-4">
                                    <i class="fas fa-folder-open text-3xl"></i>
                                </div>
                                <p class="text-gray-400 font-medium italic">មិនទាន់មានទិន្នន័យបច្ច័យក្នុងប្រព័ន្ធនៅឡើយទេ</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- បំណែងចែកទំព័រ (Pagination) --}}
        @if($donations->hasPages())
            <div class="p-4 border-t bg-gray-50/30">
                {{ $donations->links() }}
            </div>
        @endif
    </div>

    {{-- កាតសរុបបច្ច័យ (Summary) --}}
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-slate-900 p-6 rounded-[2rem] shadow-xl border border-slate-800 flex justify-between items-center group hover:border-green-500/50 transition-all cursor-default">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">សរុបបច្ច័យដុល្លារ (Total USD)</p>
                <span class="text-3xl font-black text-green-400 drop-shadow-[0_0_10px_rgba(74,222,128,0.3)]">
                    ${{ number_format($donations->where('currency', 'USD')->sum('amount'), 2) }}
                </span>
            </div>
            <div class="w-14 h-14 bg-slate-800 rounded-2xl flex items-center justify-center text-green-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>

        <div class="bg-slate-900 p-6 rounded-[2rem] shadow-xl border border-slate-800 flex justify-between items-center group hover:border-blue-500/50 transition-all cursor-default">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">សរុបបច្ច័យរៀល (Total KHR)</p>
                <span class="text-3xl font-black text-white drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]">
                    {{ number_format($donations->where('currency', 'KHR')->sum('amount'), 0) }} ៛
                </span>
            </div>
            <div class="w-14 h-14 bg-slate-800 rounded-2xl flex items-center justify-center text-blue-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-coins"></i>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fade-in-down 0.5s ease-out;
    }
</style>
@endsection