<nav class="mt-6 px-4">
    <p class="text-[10px] font-bold text-gray-400 uppercase mb-4 tracking-widest">មឺនុយមេ</p>
    
    <div class="space-y-2">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('*.dashboard') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
            <i class="fas fa-home w-6"></i>
            <span class="text-sm font-bold">ផ្ទាំងគ្រប់គ្រង</span>
        </a>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.members.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.members.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-users w-6"></i>
                <span class="text-sm font-bold">សមាជិក</span>
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-bowl-rice"></i>
                <span class="text-sm font-bold">អ្នកប្រើប្រាស់</span>
            </a>
        @endif

        @if(in_array(auth()->user()->role, ['admin', 'treasurer']))
            <p class="text-[10px] font-bold text-gray-400 uppercase mt-6 mb-2 tracking-widest">ហិរញ្ញវត្ថុ</p>
            <a href="{{ route('treasurer.donations.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('treasurer.donations.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-hand-holding-heart w-6"></i>
                <span class="text-sm font-bold">ចំណូល / បរិច្ចាគ</span>
            </a>
            <a href="{{ route('treasurer.expenses.index') }}" 
               class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('treasurer.expenses.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-file-invoice-dollar w-6"></i>
                <span class="text-sm font-bold">ចំណាយ</span>
            </a>
        @endif

        <p class="text-[10px] font-bold text-gray-400 uppercase mt-6 mb-2 tracking-widest">របាយការណ៍</p>
        @php
            // កំណត់ Route Name តាម Role ដើម្បីឱ្យចុចទៅត្រូវទំព័រ
            $reportRoute = auth()->user()->role === 'admin' ? 'admin.reports.index' : 'treasurer.reports.index';
        @endphp
        <a href="{{ route($reportRoute) }}" 
           class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('*.reports.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
            <i class="fas fa-chart-pie w-6"></i>
            <span class="text-sm font-bold">របាយការណ៍រួម</span>
        </a>
    </div>
</nav>