<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Kot17</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; }

        /* Better scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.03); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.25); border-radius: 999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(148,163,184,0.40); }

        /* Active left indicator */
        .nav-active {
            position: relative;
        }
        .nav-active::before{
            content:"";
            position:absolute;
            left: 0;
            top: 10px;
            bottom: 10px;
            width: 4px;
            border-radius: 999px;
            background: rgba(255,255,255,0.95);
        }
    </style>
</head>

<body class="bg-gray-100">
<div class="flex h-screen overflow-hidden">

    <!-- Mobile Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
<aside id="sidebar"
           class="w-72 bg-slate-900 text-white flex-shrink-0 relative hidden md:flex flex-col z-50
                  md:translate-x-0 translate-x-[-100%] md:static fixed inset-y-0 left-0 transition-transform duration-200">

        <div class="px-5 py-5 bg-orange-600 shadow-lg flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl grid place-items-center overflow-hidden">
                <img src="{{ asset('assets/images/logo_kot17.png') }}" alt="Kot17 Logo" class="w-8 h-8 object-contain">
            </div>
            <div class="leading-tight">
                <div class="text-lg font-extrabold">កុដិ ១៧</div>
                <div class="text-[11px] opacity-90">Admin Panel</div>
            </div>
        </div>

        <nav class="mt-5 px-3 space-y-2 flex-1 overflow-y-auto custom-scrollbar">

            {{-- ១. ផ្នែករដ្ឋបាល (Admin Only) --}}
            @if(auth()->user()->role == 'admin')
                <div class="px-4 py-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">រដ្ឋបាល</div>
                @php
                    $adminMenus = [
                        ['route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt', 'label' => 'ផ្ទាំងគ្រប់គ្រង'],
                        ['route' => 'admin.users.index', 'icon' => 'fas fa-users', 'label' => 'គ្រប់គ្រងអ្នកប្រើប្រាស់', 'pattern' => 'admin.users.*'],
                        ['route' => 'admin.reports.index', 'icon' => 'fas fa-chart-line', 'label' => 'របាយការណ៍', 'pattern' => 'admin.reports.*'],
                    ];
                @endphp

                @foreach($adminMenus as $menu)
                    @php $isActive = request()->routeIs($menu['pattern'] ?? $menu['route']); @endphp
                    <a href="{{ route($menu['route']) }}"
                       class="flex items-center py-3 px-4 rounded-xl transition-all
                              {{ $isActive ? 'bg-orange-600 text-white shadow-lg nav-active' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="{{ $menu['icon'] }} w-6"></i>
                        <span class="ml-3 font-semibold">{{ $menu['label'] }}</span>
                    </a>
                @endforeach
            @endif

            {{-- ២. ផ្នែកហិរញ្ញវត្ថុ (Admin & Treasurer) --}}
            @if(in_array(auth()->user()->role, ['admin', 'treasurer']))
                <div class="px-4 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest border-t border-slate-800 mt-4">ហិរញ្ញវត្ថុ</div>
                @php
                    $treasurerMenus = [
                        ['route' => 'treasurer.dashboard', 'icon' => 'fas fa-wallet', 'label' => 'សង្ខេបថវិកា'],
                        ['route' => 'treasurer.donations.index', 'icon' => 'fas fa-hand-holding-heart', 'label' => 'បច្ច័យចំណូល', 'pattern' => 'treasurer.donations.*'],
                        ['route' => 'treasurer.expenses.index', 'icon' => 'fas fa-file-invoice-dollar', 'label' => 'ការចំណាយ', 'pattern' => 'treasurer.expenses.*'],
                        ['route' => 'treasurer.reports.index', 'icon' => 'fas fa-chart-bar', 'label' => 'របាយការណ៍លុយកាក់', 'pattern' => 'treasurer.reports.*'],
                    ];
                @endphp

                @foreach($treasurerMenus as $menu)
                    @php $isActive = request()->routeIs($menu['pattern'] ?? $menu['route']); @endphp
                    <a href="{{ route($menu['route']) }}"
                       class="flex items-center py-3 px-4 rounded-xl transition-all
                              {{ $isActive ? 'bg-orange-600 text-white shadow-lg nav-active' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="{{ $menu['icon'] }} w-6"></i>
                        <span class="ml-3 font-semibold">{{ $menu['label'] }}</span>
                    </a>
                @endforeach
            @endif

            {{-- ៣. ផ្នែកទឹកភ្លើង (Moved here - Under Finance) --}}
            @if(in_array(auth()->user()->role, ['admin', 'utilities_treasurer']))
                <div class="px-4 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest border-t border-slate-800 mt-4">សេវាសាធារណៈ</div>
                @php
                    $utilitiesMenus = [
                        ['route' => 'admin.utilities.index', 'icon' => 'fas fa-bolt text-yellow-400', 'label' => 'ចំណាយទឹក និងភ្លើង', 'pattern' => 'admin.utilities.*'],
                    ];
                @endphp

                @foreach($utilitiesMenus as $menu)
                    @php $isActive = request()->routeIs($menu['pattern'] ?? $menu['route']); @endphp
                    <a href="{{ route($menu['route']) }}"
                       class="flex items-center py-3 px-4 rounded-xl transition-all
                              {{ $isActive ? 'bg-orange-600 text-white shadow-lg nav-active' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="{{ $menu['icon'] }} w-6"></i>
                        <span class="ml-3 font-semibold">{{ $menu['label'] }}</span>
                    </a>
                @endforeach
            @endif

            {{-- ៤. ផ្នែកអ្នកប្រមូលលុយ (Admin & Collector) --}}
            @if(in_array(auth()->user()->role, ['admin', 'collector']))
                <div class="px-4 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest border-t border-slate-800 mt-4">អ្នកប្រមូលលុយ</div>
                @php
                    $collectorMenus = [
                        ['route' => 'collector.dashboard', 'icon' => 'fas fa-bowl-rice', 'label' => 'ផ្ទាំងអ្នកប្រមូល'],
                        ['route' => 'collector.collections.daily', 'icon' => 'fas fa-plus-circle', 'label' => 'ចុះបញ្ជីប្រមូលលុយ', 'pattern' => 'collector.collections.*'],
                        ['route' => 'collector.reports.lunch', 'icon' => 'fas fa-chart-bar', 'label' => 'របាយការណ៍បច្ច័យចង្ហាន់', 'pattern' => 'collector.reports.lunch'],
                    ];
                @endphp

                @foreach($collectorMenus as $menu)
                    @php $isActive = request()->routeIs($menu['pattern'] ?? $menu['route']); @endphp
                    <a href="{{ route($menu['route']) }}"
                       class="flex items-center py-3 px-4 rounded-xl transition-all
                              {{ $isActive ? 'bg-orange-600 text-white shadow-lg nav-active' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="{{ $menu['icon'] }} w-6"></i>
                        <span class="ml-3 font-semibold">{{ $menu['label'] }}</span>
                    </a>
                @endforeach
            @endif

        </nav>
        {{-- ៥. ផ្នែកខាងក្រោម (Logout Section) --}}
        <div class="p-3 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center gap-3 px-4 py-3 mb-2">
                <div class="w-9 h-9 rounded-full bg-orange-600/20 flex items-center justify-center text-orange-500 font-black border border-orange-600/30">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="leading-tight overflow-hidden text-ellipsis whitespace-nowrap">
                    <div class="text-sm font-bold text-white">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-slate-500 capitalize">{{ auth()->user()->role }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center py-3 px-4 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all font-semibold group">
                    <i class="fas fa-sign-out-alt w-6 group-hover:translate-x-1 transition-transform"></i>
                    <span class="ml-3">ចាកចេញពីប្រព័ន្ធ</span>
                </button>
            </form>
        </div>
        </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 px-4 md:px-8 py-4 flex justify-between items-center">

            <!-- Left: Mobile menu + Title -->
            <div class="flex items-center gap-3">
                <button class="md:hidden w-10 h-10 rounded-xl border border-gray-200 bg-white hover:bg-gray-50"
                        onclick="openSidebar()" aria-label="Open menu">
                    <i class="fas fa-bars text-slate-700"></i>
                </button>

                <div>
                    <h2 class="text-lg md:text-xl font-extrabold text-slate-800 uppercase leading-tight">
                        @yield('title', 'ផ្ទាំងគ្រប់គ្រង')
                    </h2>
                    <p class="text-[11px] text-slate-500 mt-0.5 hidden sm:block">
                        ប្រព័ន្ធគ្រប់គ្រងកុដិ ១៧ • {{ now()->format('d-m-Y') }}
                    </p>
                </div>
            </div>

            <!-- Right: User -->
            <div class="flex items-center space-x-3 md:space-x-4">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-extrabold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-orange-600 mt-1 font-extrabold uppercase tracking-widest">{{ auth()->user()->role }}</p>
                </div>

                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ea580c&color=fff&bold=true"
                    class="w-10 h-10 rounded-full border-2 border-orange-100 shadow-sm"
                    alt="Avatar"
                >
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50/50">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    function openSidebar() {
        overlay.classList.remove('hidden');
        sidebar.classList.remove('hidden');
        sidebar.classList.remove('translate-x-[-100%]');
        sidebar.classList.add('translate-x-0');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        overlay.classList.add('hidden');
        sidebar.classList.add('translate-x-[-100%]');
        sidebar.classList.remove('translate-x-0');
        document.body.style.overflow = '';
        setTimeout(() => {
            // keep hidden on mobile after animation
            if (window.innerWidth < 768) sidebar.classList.add('hidden');
        }, 200);
    }

    // Ensure correct state on resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            overlay.classList.add('hidden');
            sidebar.classList.remove('hidden');
            sidebar.classList.remove('translate-x-[-100%]');
            sidebar.classList.add('translate-x-0');
            document.body.style.overflow = '';
        } else {
            // keep sidebar hidden by default on mobile
            if (!overlay.classList.contains('hidden')) return;
            sidebar.classList.add('hidden');
            sidebar.classList.add('translate-x-[-100%]');
            sidebar.classList.remove('translate-x-0');
        }
    });
</script>

</body>
</html>
