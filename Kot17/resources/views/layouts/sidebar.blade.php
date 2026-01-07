<nav class="mt-6 px-4">
    <p class="text-[10px] font-bold text-gray-400 uppercase mb-4 tracking-widest">មឺនុយមេ</p>

    <div class="space-y-2">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('*.dashboard') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
            <i class="fas fa-home w-6"></i>
            <span class="text-sm font-bold">ផ្ទាំងគ្រប់គ្រង</span>
        </a>

        {{-- Admin Menus --}}
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.members.index') }}"
               class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.members.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-users w-6"></i>
                <span class="text-sm font-bold">សមាជិក</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-bowl-rice w-6"></i>
                <span class="text-sm font-bold">អ្នកប្រើប្រាស់</span>
            </a>
        @endif

        {{-- Treasurer / Admin Finance --}}
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

        {{-- Collector Menus --}}
        @if(in_array(auth()->user()->role, ['admin', 'collector']))
            <p class="text-[10px] font-bold text-gray-400 uppercase mt-6 mb-2 tracking-widest">អ្នកប្រមូល</p>

            <a href="{{ route('collector.dashboard') }}"
               class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('collector.dashboard') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-basket-shopping w-6"></i>
                <span class="text-sm font-bold">ទំព័រអ្នកប្រមូល</span>
            </a>

            <a href="{{ route('collector.donations.index') }}"
               class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('collector.donations.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-list w-6"></i>
                <span class="text-sm font-bold">បញ្ជីទិន្នន័យប្រមូល</span>
            </a>

            <a href="{{ route('collector.reports.donations') }}"
               class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('collector.reports.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-chart-bar w-6"></i>
                <span class="text-sm font-bold">របាយការណ៍ប្រមូល</span>
            </a>
        @endif
{{-- ✅ Collector ONLY: Report --}}
@if(auth()->check() && auth()->user()->role === 'collector')
    <a href="{{ route('collector.reports.donations') }}"
       class="group flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition
              {{ request()->routeIs('collector.reports.*') ? 'bg-orange-600 text-white shadow-lg' : 'text-slate-200 hover:bg-white/10' }}">
        <span class="w-9 h-9 rounded-xl flex items-center justify-center
                     {{ request()->routeIs('collector.reports.*') ? 'bg-white/15' : 'bg-white/10 group-hover:bg-white/15' }}">
            <i class="fas fa-chart-bar"></i>
        </span>
        <span>មើលរបាយការណ៍</span>
    </a>
@endif


<nav class="mt-6 px-4 space-y-6">
    {{-- Main Menu --}}
    <div>
        <p class="text-[10px] font-bold text-gray-400 uppercase mb-3 tracking-widest">មឺនុយមេ</p>
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('*.dashboard') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-home w-6"></i>
                <span class="text-sm font-bold">ផ្ទាំងគ្រប់គ្រង</span>
            </a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.members.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.members.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                    <i class="fas fa-users w-6"></i>
                    <span class="text-sm font-bold">សមាជិកកុដិ</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Finance (Admin & Treasurers) --}}
    @if(in_array(auth()->user()->role, ['admin', 'treasurer', 'utilities_treasurer']))
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase mb-3 tracking-widest">ហិរញ្ញវត្ថុ</p>
            <div class="space-y-1">
                @if(auth()->user()->role !== 'utilities_treasurer')
                    <a href="{{ route('treasurer.donations.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('treasurer.donations.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                        <i class="fas fa-hand-holding-heart w-6"></i>
                        <span class="text-sm font-bold">ចំណូល / បរិច្ចាគ</span>
                    </a>
                @endif
                
                {{-- Utilities Menu (Admin & Utilities Treasurer) --}}
                <a href="{{ route('admin.utilities.index') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.utilities.*') ? 'bg-emerald-500 text-white shadow-lg' : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-600' }}">
                    <i class="fas fa-bolt w-6"></i>
                    <span class="text-sm font-bold">ចំណាយទឹក និងភ្លើង</span>
                </a>
            </div>
        </div>
    @endif

    {{-- Collector --}}
    @if(in_array(auth()->user()->role, ['admin', 'collector']))
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase mb-3 tracking-widest">អ្នកប្រមូល</p>
            <a href="{{ route('collector.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('collector.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-basket-shopping w-6"></i>
                <span class="text-sm font-bold">ការងារប្រមូល</span>
            </a>
        </div>
    @endif
</nav>

{{-- ផ្នែកគ្រប់គ្រងទឹកភ្លើង --}}
@if(auth()->user()->role === 'admin' || auth()->user()->role === 'utilities_treasurer')
    <div class="px-4 mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
        ការងារទឹកភ្លើង
    </div>
    <a href="{{ route('admin.utilities.index') }}" 
       class="flex items-center px-4 py-3 mb-2 text-slate-600 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all {{ request()->routeIs('admin.utilities.*') ? 'bg-emerald-50 text-emerald-600' : '' }}">
        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-white shadow-sm mr-3">
            <i class="fas fa-bolt text-xs"></i>
        </div>
        <span class="text-sm font-bold">ចំណាយទឹក និងភ្លើង</span>
    </a>
@endif
        {{-- Shared Reports --}}
        @if(in_array(auth()->user()->role, ['admin', 'treasurer']))
            <p class="text-[10px] font-bold text-gray-400 uppercase mt-6 mb-2 tracking-widest">របាយការណ៍</p>
            @php
                $reportRoute = auth()->user()->role === 'admin'
                    ? 'admin.reports.index'
                    : 'treasurer.reports.index';
            @endphp

            <a href="{{ route($reportRoute) }}"
               class="flex items-center px-4 py-3 rounded-xl transition {{ request()->routeIs('*.reports.*') ? 'bg-orange-500 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100' }}">
                <i class="fas fa-chart-pie w-6"></i>
                <span class="text-sm font-bold">របាយការណ៍រួម</span>
            </a>
        @endif
    </div>
</nav>
