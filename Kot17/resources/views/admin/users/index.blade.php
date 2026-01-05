@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងអ្នកប្រើប្រាស់')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="text-xl font-bold text-slate-800">អ្នកប្រើប្រាស់ប្រព័ន្ធ</h3>
        <a href="{{ route('admin.users.create') }}"
           class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> បន្ថែមអ្នកប្រើប្រាស់
        </a>
    </div>


    
    <div class="overflow-x-auto p-4">
        <table class="w-full text-left">
            <thead>
                <tr class="text-slate-400 uppercase text-xs font-bold border-b border-gray-100">
                    <th class="py-4 px-4">ឈ្មោះ</th>
                    <th class="py-4 px-4">តួនាទី</th>
                    <th class="py-4 px-4">លេខទូរស័ព្ទ</th>
                    <th class="py-4 px-4 text-center">ស្ថានភាព</th>
                    <th class="py-4 px-4 text-center">សកម្មភាព</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="py-4 px-4">
                        <div class="flex items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                 class="w-8 h-8 rounded-full mr-3" alt="avatar">
                            <div>
                                <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="py-4 px-4">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase
                            {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-600' : '' }}
                            {{ $user->role == 'treasurer' ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $user->role == 'collector' ? 'bg-orange-100 text-orange-600' : '' }}
                            {{ $user->role == 'member' ? 'bg-gray-100 text-gray-600' : '' }}">
                            {{ $user->role }}
                        </span>
                    </td>

                    <td class="py-4 px-4 text-slate-600">{{ $user->phone ?? '---' }}</td>

                    <td class="py-4 px-4 text-center">
                        @if($user->is_active)
                            <span class="text-green-600 bg-green-50 px-2 py-1 rounded text-xs font-bold">សកម្ម</span>
                        @else
                            <span class="text-red-500 bg-red-50 px-2 py-1 rounded text-xs font-bold">អសកម្ម</span>
                        @endif
                    </td>

                    <td class="py-4 px-4 text-center">
                        <div class="flex justify-center space-x-2">

                            {{-- Edit --}}
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="text-slate-700 hover:bg-slate-100 p-2 rounded-lg transition"
                               title="កែប្រែ">
                                <i class="fas fa-pen"></i>
                            </a>

                            {{-- Reset password --}}
                            {{-- <form action="{{ route('admin.users.resetPassword', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition"
                                        title="Reset Password"
                                        onclick="return confirm('Reset password អ្នកប្រើប្រាស់នេះ?')">
                                    <i class="fas fa-key"></i>
                                </button>
                            </form> --}}

                            {{-- Delete --}}
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition"
                                        title="លុប"
                                        onclick="return confirm('លុបអ្នកប្រើប្រាស់នេះ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination (optional) --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
