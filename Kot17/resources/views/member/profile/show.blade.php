@extends('layouts.admin')
@section('title', 'ប្រវត្តិរូប')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">ប្រវត្តិរូប</h3>
                <p class="text-sm text-slate-500 mt-1">ព័ត៌មានគណនី និងព័ត៌មានផ្ទាល់ខ្លួន</p>
            </div>

            <a href="{{ route('member.profile.edit') }}"
               class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-sm">
                <i class="fas fa-pen mr-2"></i> កែប្រែ
            </a>
        </div>

        <div class="p-6">
            <div class="flex items-center gap-4">
                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ea580c&color=fff&bold=true"
                    class="w-16 h-16 rounded-full border-2 border-orange-100 shadow-sm"
                    alt="Avatar"
                >
                <div>
                    <div class="text-lg font-extrabold text-slate-800">{{ $user->name }}</div>
                    <div class="text-sm text-slate-500">{{ $user->email }}</div>

                    <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-lg text-xs font-extrabold uppercase
                        {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                        {{ $user->role == 'treasurer' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $user->role == 'collector' ? 'bg-orange-100 text-orange-700' : '' }}
                        {{ $user->role == 'member' ? 'bg-gray-100 text-gray-700' : '' }}">
                        {{ $user->role }}
                    </div>
                </div>
            </div>

            <div class="mt-6 grid md:grid-cols-2 gap-5">
                <div class="p-4 rounded-xl border border-gray-100 bg-white">
                    <div class="text-xs font-bold uppercase text-slate-400">លេខទូរស័ព្ទ</div>
                    <div class="mt-1 font-bold text-slate-800">{{ $user->phone ?? '---' }}</div>
                </div>

                <div class="p-4 rounded-xl border border-gray-100 bg-white">
                    <div class="text-xs font-bold uppercase text-slate-400">ស្ថានភាព</div>
                    <div class="mt-1 font-bold">
                        @if($user->is_active)
                            <span class="text-green-600 bg-green-50 px-2 py-1 rounded text-xs font-extrabold">សកម្ម</span>
                        @else
                            <span class="text-red-500 bg-red-50 px-2 py-1 rounded text-xs font-extrabold">អសកម្ម</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6 text-sm text-slate-500">
                <div>បង្កើត: <span class="font-bold text-slate-700">{{ optional($user->created_at)->format('d-m-Y H:i') }}</span></div>
                <div>កែប្រែចុងក្រោយ: <span class="font-bold text-slate-700">{{ optional($user->updated_at)->format('d-m-Y H:i') }}</span></div>
            </div>
        </div>
    </div>

</div>
@endsection
