@extends('layouts.admin')
@section('title', 'កែប្រែប្រវត្តិរូប')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
        <h3 class="text-xl font-extrabold text-slate-800">កែប្រែប្រវត្តិរូប</h3>
        <a href="{{ route('member.profile.show') }}"
           class="text-slate-600 hover:text-orange-600 font-bold transition">
            <i class="fas fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <form action="{{ route('member.profile.update') }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-100 rounded-xl text-red-700">
                <ul class="list-disc ml-5 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-bold text-slate-700">ឈ្មោះ</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-4 focus:ring-orange-100">
            </div>

            <div>
                <label class="text-sm font-bold text-slate-700">អ៊ីមែល</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-4 focus:ring-orange-100">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-bold text-slate-700">លេខទូរស័ព្ទ</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-4 focus:ring-orange-100">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-sm">
                <i class="fas fa-save mr-2"></i> រក្សាទុក
            </button>

            <a href="{{ route('member.profile.show') }}"
               class="px-6 py-3 rounded-xl font-bold border border-gray-200 text-slate-700 hover:bg-gray-50 transition">
                បោះបង់
            </a>
        </div>
    </form>
</div>
@endsection
