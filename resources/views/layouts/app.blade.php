<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FieldBook') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50"
      x-data="{
        sidebarOpen: false,
        collapsed: localStorage.getItem('fieldbook_sidebar_collapsed') === 'true'
      }"
      x-init="$watch('collapsed', value => localStorage.setItem('fieldbook_sidebar_collapsed', value))">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', collapsed ? 'md:w-20' : 'md:w-64']"
               class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform transition-all duration-200 ease-in-out md:translate-x-0 md:static md:inset-auto flex flex-col">

            <div class="h-16 flex items-center gap-2 px-6 border-b border-gray-100 shrink-0" :class="collapsed && 'md:px-0 md:justify-center'">
                <x-application-logo class="w-8 h-8 fill-current text-green-600 shrink-0" />
                <span class="text-xl font-bold text-gray-800 whitespace-nowrap" x-show="!collapsed" x-transition.opacity>Field<span class="text-green-600">Book</span></span>
                <button @click="collapsed = !collapsed" class="hidden md:flex items-center justify-center w-7 h-7 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 ml-auto shrink-0">
                    <svg :class="collapsed && 'rotate-180'" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto overflow-x-hidden px-3 py-4 space-y-1">

                <a href="{{ route('dashboard') }}" :title="collapsed ? 'Dashboard' : ''" :class="collapsed && 'md:justify-center'" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span x-show="!collapsed" x-transition.opacity class="whitespace-nowrap">Dashboard</span>
                </a>

                <a href="{{ route('farm') }}" :title="collapsed ? 'My Farm' : ''" :class="collapsed && 'md:justify-center'" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('farm') || request()->routeIs('lands.*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                    <span x-show="!collapsed" x-transition.opacity class="whitespace-nowrap">My Farm</span>
                </a>

                <a href="{{ route('labour-expenses.index') }}" :title="collapsed ? 'Labour Expenses' : ''" :class="collapsed && 'md:justify-center'" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('labour-expenses.*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <span x-show="!collapsed" x-transition.opacity class="whitespace-nowrap">Labour Expenses</span>
                </a>

                <a href="{{ route('fertilizer-applications.index') }}" :title="collapsed ? 'Fertilizer' : ''" :class="collapsed && 'md:justify-center'" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('fertilizer-applications.*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6v4l4 8a2 2 0 01-1.8 3H6.8A2 2 0 015 15l4-8V3z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8" /></svg>
                    <span x-show="!collapsed" x-transition.opacity class="whitespace-nowrap">Fertilizer</span>
                </a>

                <a href="{{ route('other-expenses.index') }}" :title="collapsed ? 'Other Expenses' : ''" :class="collapsed && 'md:justify-center'" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('other-expenses.*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3v-6m-3 6v-3m9 3H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v11a2 2 0 01-2 2z" /></svg>
                    <span x-show="!collapsed" x-transition.opacity class="whitespace-nowrap">Other Expenses</span>
                </a>

                <a href="{{ route('crop-stocks.index') }}" :title="collapsed ? 'Crop Stocks' : ''" :class="collapsed && 'md:justify-center'" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('crop-stocks.*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span x-show="!collapsed" x-transition.opacity class="whitespace-nowrap">Crop Stocks</span>
                </a>

                <a href="{{ route('crop-sales.index') }}" :title="collapsed ? 'Crop Sales' : ''" :class="collapsed && 'md:justify-center'" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('crop-sales.*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 2v8m0 0v2m0-2c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span x-show="!collapsed" x-transition.opacity class="whitespace-nowrap">Crop Sales</span>
                </a>

                <a href="{{ route('reports') }}" :title="collapsed ? 'Reports' : ''" :class="collapsed && 'md:justify-center'" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('reports') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    <span x-show="!collapsed" x-transition.opacity class="whitespace-nowrap">Reports</span>
                </a>

                <a href="{{ route('settings') }}" :title="collapsed ? 'Settings' : ''" :class="collapsed && 'md:justify-center'" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('settings') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span x-show="!collapsed" x-transition.opacity class="whitespace-nowrap">Settings</span>
                </a>
            </nav>

            <div class="border-t border-gray-100 p-4 shrink-0">
                <div class="flex items-center gap-3 mb-3 px-2" :class="collapsed && 'md:justify-center md:px-0'">
                    <div class="w-9 h-9 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-semibold text-sm shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0" x-show="!collapsed" x-transition.opacity>
                        <p class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" :title="collapsed ? 'Profile' : ''" :class="collapsed && 'md:text-center'" class="block px-2 py-1.5 text-sm text-gray-600 hover:text-green-700 rounded-md">
                    <span x-show="!collapsed" x-transition.opacity>Profile</span>
                    <span x-show="collapsed" class="hidden md:inline">
                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" :title="collapsed ? 'Log Out' : ''" :class="collapsed && 'md:text-center'" class="w-full text-left px-2 py-1.5 text-sm text-gray-600 hover:text-red-600 rounded-md">
                        <span x-show="!collapsed" x-transition.opacity>Log Out</span>
                        <span x-show="collapsed" class="hidden md:inline">
                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 md:hidden"></div>

        <!-- Main column -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>

                @isset($header)
                    <div class="text-lg font-semibold text-gray-800">{{ $header }}</div>
                @else
                    <div></div>
                @endisset

                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500 hidden sm:block">{{ now()->format('l, d M Y') }}</span>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>