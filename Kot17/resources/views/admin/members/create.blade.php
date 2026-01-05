@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <nav class="flex mb-5 text-sm text-gray-500" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-orange-600">ផ្ទាំងគ្រប់គ្រង</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.members.index') }}" class="hover:text-orange-600">បញ្ជីសមាជិក</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">បន្ថែមសមាជិកថ្មី</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 bg-orange-600 text-white">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-user-plus mr-3"></i> ចុះឈ្មោះសមាជិកថ្មី
            </h2>
        </div>

        <form action="{{ route('admin.members.store') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-gray-700 font-bold mb-2">ឈ្មោះសមាជិក <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="name" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition outline-none" placeholder="បញ្ចូលឈ្មោះពេញ" required>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">លេខទូរស័ព្ទ</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fas fa-phone-alt"></i>
                    </span>
                    <input type="text" name="phone" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition outline-none" placeholder="ឧទាហរណ៍៖ 012 345 678">
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">អាសយដ្ឋានបច្ចុប្បន្ន</label>
                <textarea name="address" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition outline-none" placeholder="ផ្ទះលេខ, ផ្លូវ, ភូមិ/សង្កាត់..."></textarea>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.members.index') }}" class="px-6 py-2.5 text-gray-500 font-medium hover:text-gray-700 transition">
                    បោះបង់
                </a>
                <button type="submit" class="px-8 py-2.5 bg-orange-600 text-white font-bold rounded-xl shadow-lg hover:bg-orange-700 hover:-translate-y-0.5 transition transform">
                    <i class="fas fa-save mr-2"></i> រក្សាទុកទិន្នន័យ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection