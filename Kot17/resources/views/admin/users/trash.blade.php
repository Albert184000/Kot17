@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-black text-slate-800">ធុងសម្រាម (Deleted Users)</h2>
        <a href="{{ route('admin.users.index') }}" class="text-slate-500 hover:text-slate-800 font-bold transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> ត្រឡប់ទៅបញ្ជីសកម្ម
        </a>
    </div>

    {{-- ================= Desktop Table Mode ================= --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 uppercase text-[11px] font-black tracking-widest border-b border-gray-50 bg-gray-50/30">
                        <th class="py-4 px-6">សមាជិកដែលបានលុប</th>
                        <th class="py-4 px-4">ថ្ងៃខែបានលុប</th>
                        <th class="py-4 px-6 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition group">
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" 
                                         class="w-10 h-10 rounded-full object-cover grayscale opacity-70">
                                    <div class="ml-4">
                                        <div class="font-black text-slate-600">{{ $user->name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm text-slate-500">{{ $user->deleted_at->format('d/M/Y H:i') }}</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- ប៊ូតុងយកមកវិញ --}}
                                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                                        @csrf
                                        <button class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 px-3 py-1.5 rounded-lg text-xs font-black transition border border-emerald-100">
                                            យកមកវិញ
                                        </button>
                                    </form>
                                    
                                    {{-- ប៊ូតុងលុបចោលជាស្ថាពរ --}}
                                    <form action="{{ route('admin.users.force-delete', $user->id) }}" method="POST" onsubmit="return confirm('ប្រយ័ត្ន! ការលុបនេះនឹងបាត់បង់ទិន្នន័យរហូត តើអ្នកពិតជាចង់លុប?')">
                                        @csrf @method('DELETE')
                                        <button class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-black transition border border-red-100">
                                            លុបដាច់
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-20 text-center text-slate-300 italic">ធុងសម្រាមទទេស្អាត...</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= Mobile Mode ================= --}}
    <div class="md:hidden space-y-3">
        @foreach($users as $user)
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" class="w-10 h-10 rounded-full grayscale">
                        <div class="ml-3">
                            <h4 class="font-black text-slate-700">{{ $user->name }}</h4>
                            <p class="text-[10px] text-slate-400">លុបនៅ៖ {{ $user->deleted_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-4">
                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                        @csrf
                        <button class="w-full py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[11px] font-black border border-emerald-100">យកមកវិញ</button>
                    </form>
                    <form action="{{ route('admin.users.force-delete', $user->id) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបដាច់?')">
                        @csrf @method('DELETE')
                        <button class="w-full py-2 bg-red-50 text-red-600 rounded-xl text-[11px] font-black border border-red-100">លុបដាច់</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection