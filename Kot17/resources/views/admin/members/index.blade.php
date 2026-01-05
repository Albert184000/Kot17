@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
            <h3 class="text-xl font-bold text-gray-800" style="font-family: 'Kantumruy Pro';">បញ្ជីសមាជិកសរុប</h3>
            <p class="text-sm text-gray-500">គ្រប់គ្រង និងមើលព័ត៌មានសមាជិកទាំងអស់</p>
        </div>
        <a href="{{ route('admin.members.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-lg font-medium transition flex items-center shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i> បន្ថែមសមាជិក
        </a>
    </div>

    <div class="overflow-x-auto p-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-400 uppercase text-xs font-bold border-b border-gray-100">
                    <th class="py-4 px-4 text-center">លេខកូដ</th>
                    <th class="py-4 px-4">ឈ្មោះសមាជិក</th>
                    <th class="py-4 px-4">លេខទូរស័ព្ទ</th>
                    <th class="py-4 px-4">អាសយដ្ឋាន</th>
                    <th class="py-4 px-4">អ្នកចុះឈ្មោះ</th> <th class="py-4 px-4 text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($members as $member)
                <tr class="hover:bg-orange-50/30 transition">
                    <td class="py-4 px-4 text-center">
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-mono">#MB-{{ sprintf('%03d', $member->id) }}</span>
                    </td>
                    <td class="py-4 px-4">
                        <div class="font-bold text-gray-800">{{ $member->name }}</div>
                    </td>
                    <td class="py-4 px-4 text-gray-600">{{ $member->phone ?? '---' }}</td>
                    <td class="py-4 px-4 text-gray-500 text-sm italic">{{ Str::limit($member->address, 30) }}</td>
                    
                    <td class="py-4 px-4">
                        <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-md text-xs font-medium">
                            <i class="fas fa-user-shield mr-1"></i> {{ $member->creator->name ?? 'មិនស្គាល់' }}
                        </span>
                    </td>

                    <td class="py-4 px-4 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.members.edit', $member->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="កែប្រែ">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.members.destroy', $member->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" onclick="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?')" title="លុប">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center"> <div class="flex flex-col items-center">
                            <i class="fas fa-users-slash fa-3x text-gray-200 mb-3"></i>
                            <p class="text-gray-400 font-medium">មិនទាន់មានទិន្នន័យសមាជិកនៅឡើយទេ</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection