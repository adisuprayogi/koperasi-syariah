<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $koperasi->nama_koperasi ?? 'Koperasi Syariah') - {{ $koperasi->nama_koperasi ?? 'Koperasi Syariah' }}</title>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @stack('styles')
</head>
<body class="bg-gray-50">
    @auth
        <div class="flex h-screen">
            <!-- Sidebar - Hidden on Mobile -->
            <aside class="hidden md:flex md:flex-shrink-0">
                <div class="flex flex-col w-64">
                    <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto bg-gradient-to-b from-primary-800 to-primary-900">
                        <div class="flex items-center flex-shrink-0 px-4">
                            <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-bold text-white">
                                @if($koperasi && $koperasi->logo)
                                    <img src="{{ asset('storage/' . $koperasi->logo) }}"
                                         alt="{{ $koperasi->nama_koperasi ?? 'Logo' }}"
                                         class="h-10 w-auto rounded">
                                @else
                                    <i class="fas fa-mosque mr-2 text-primary-300"></i>
                                    <span class="text-primary-100">{{ $koperasi->nama_koperasi ?? 'Koperasi Syariah' }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="mt-8 flex-1 flex flex-col">
                            <nav class="flex-1 px-2 pb-4 space-y-1">
                                @if(Auth::user()->isAdmin())
                                    <!-- Admin Menu -->
                                    <div class="mb-4">
                                        <h3 class="text-xs font-semibold text-primary-300 uppercase tracking-wider mb-2">Admin Menu</h3>
                                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Dashboard
                                        </a>
                                        <a href="{{ route('admin.pengurus.index') }}" class="{{ request()->routeIs('admin.pengurus*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-users mr-3 {{ request()->routeIs('admin.pengurus*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Pengurus
                                        </a>
                                        <a href="{{ route('admin.koperasi.index') }}" class="{{ request()->routeIs('admin.koperasi*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-building mr-3 {{ request()->routeIs('admin.koperasi*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Data Koperasi
                                        </a>
                                        <a href="{{ route('admin.jenis-simpanan.index') }}" class="{{ request()->routeIs('admin.jenis-simpanan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-piggy-bank mr-3 {{ request()->routeIs('admin.jenis-simpanan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Jenis Simpanan
                                        </a>
                                        <a href="{{ route('admin.jenis-pembiayaan.index') }}" class="{{ request()->routeIs('admin.jenis-pembiayaan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-hand-holding-usd mr-3 {{ request()->routeIs('admin.jenis-pembiayaan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Jenis Pembiayaan
                                        </a>
                                        <a href="{{ route('admin.kartu-anggota.settings') }}" class="{{ request()->routeIs('admin.kartu-anggota*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-id-card mr-3 {{ request()->routeIs('admin.kartu-anggota*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Kartu Anggota
                                        </a>
                                    </div>
                                @endif

                                @if(Auth::user()->isPengurus())
                                    <!-- Pengurus Menu -->
                                    <div class="mb-4">
                                        <h3 class="text-xs font-semibold text-primary-300 uppercase tracking-wider mb-2">Menu Pengurus</h3>
                                        <a href="{{ route('pengurus.dashboard') }}" class="{{ request()->routeIs('pengurus.dashboard') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('pengurus.dashboard') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Dashboard
                                        </a>
                                        <a href="{{ route('pengurus.anggota.index') }}" class="{{ request()->routeIs('pengurus.anggota*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-users mr-3 {{ request()->routeIs('pengurus.anggota*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Manajemen Anggota
                                        </a>
                                        <a href="{{ route('pengurus.anggota.import') }}" class="{{ request()->routeIs('pengurus.anggota.import*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-file-excel mr-3 {{ request()->routeIs('pengurus.anggota.import*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Import Data Anggota
                                        </a>
                                        <a href="{{ route('pengurus.simpanan.index') }}" class="{{ request()->routeIs('pengurus.simpanan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-piggy-bank mr-3 {{ request()->routeIs('pengurus.simpanan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Transaksi Simpanan
                                        </a>
                                        <a href="{{ route('pengurus.pengajuan.index') }}" class="{{ request()->routeIs('pengurus.pengajuan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-clipboard-check mr-3 {{ request()->routeIs('pengurus.pengajuan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Verifikasi Pengajuan
                                        </a>
                                        <a href="{{ route('pengurus.pembiayaan.index') }}" class="{{ request()->routeIs('pengurus.pembiayaan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-hand-holding-usd mr-3 {{ request()->routeIs('pengurus.pembiayaan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Manajemen Pembiayaan
                                        </a>
                                        <a href="{{ route('pengurus.laporan.index') }}" class="{{ request()->routeIs('pengurus.laporan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-chart-bar mr-3 {{ request()->routeIs('pengurus.laporan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                            Laporan
                                        </a>
                                    </div>
                                @endif

                                @if(Auth::user()->isAnggota())
                                    <!-- Anggota Menu -->
                                    <div class="mb-4">
                                        <h3 class="text-xs font-semibold text-primary-300 uppercase tracking-wider mb-2">Menu Anggota</h3>
                                        <a href="{{ route('anggota.dashboard') }}" class="{{ request()->routeIs('anggota.dashboard') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('anggota.dashboard') ? 'text-primary-300' : 'text-primary-400' }}"></i>Dashboard
                                        </a>
                                        <a href="{{ route('anggota.profile') }}" class="{{ request()->routeIs('anggota.profile') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-user mr-3 {{ request()->routeIs('anggota.profile') ? 'text-primary-300' : 'text-primary-400' }}"></i>Profil Saya
                                        </a>
                                        <a href="{{ route('anggota.simpanan.index') }}" class="{{ request()->routeIs('anggota.simpanan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-piggy-bank mr-3 {{ request()->routeIs('anggota.simpanan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>Simpanan Saya
                                        </a>
                                        <a href="{{ route('anggota.pengajuan.create') }}" class="{{ request()->routeIs('anggota.pengajuan.create') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-plus mr-3 {{ request()->routeIs('anggota.pengajuan.create') ? 'text-primary-300' : 'text-primary-400' }}"></i>Ajukan Pembiayaan
                                        </a>
                                        <a href="{{ route('anggota.pengajuan.index') }}" class="{{ request()->routeIs('anggota.pengajuan.index') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-file-invoice mr-3 {{ request()->routeIs('anggota.pengajuan.index') ? 'text-primary-300' : 'text-primary-400' }}"></i>Pengajuan Saya
                                        </a>
                                        <a href="{{ route('anggota.pembiayaan.index') }}" class="{{ request()->routeIs('anggota.pembiayaan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-hand-holding-usd mr-3 {{ request()->routeIs('anggota.pembiayaan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>Pembiayaan Saya
                                        </a>
                                    </div>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                <!-- Top Navigation -->
                <header class="bg-white shadow">
                    <div class="px-2 sm:px-4 lg:px-6">
                        <div class="flex items-center justify-between h-16 w-full">
                            <!-- Left Side -->
                            <div class="flex items-center space-x-4">
                                <!-- Mobile menu button -->
                                <button id="mobile-menu-button" class="md:hidden p-3 rounded-lg text-gray-600 hover:text-primary-600 hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors duration-200">
                                    <i class="fas fa-bars text-xl"></i>
                                </button>

                                <!-- Logo (Mobile) -->
                                <div class="md:hidden flex items-center">
                                    <a href="{{ route('dashboard') }}" class="flex items-center text-lg font-bold text-primary-600">
                                        @if($koperasi && $koperasi->logo)
                                            <img src="{{ asset('storage/' . $koperasi->logo) }}"
                                                 alt="{{ $koperasi->nama_koperasi ?? 'Logo' }}"
                                                 class="h-8 w-auto rounded-lg">
                                        @else
                                            <i class="fas fa-mosque mr-1 text-primary-600"></i>
                                            <span class="text-primary-800">{{ $koperasi->singkatan ?? 'KS' }}</span>
                                        @endif
                                    </a>
                                </div>
                            </div>

                            <!-- Right Side - User Menu -->
                            <div class="flex items-center space-x-2 sm:space-x-4 ml-auto">
                                <!-- Mobile User Icon Only -->
                                <span class="md:hidden flex items-center">
                                    <i class="fas fa-user-circle text-gray-600 text-xl"></i>
                                </span>

                                <!-- Desktop User Info -->
                                <span class="hidden sm:block text-gray-700 text-sm">
                                    <i class="fas fa-user-circle mr-2"></i>{{ Auth::user()->name }}
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full
                                        @if(Auth::user()->isAdmin())
                                            bg-red-100 text-red-800
                                        @elseif(Auth::user()->isPengurus())
                                            bg-blue-100 text-blue-800
                                        @else
                                            bg-green-100 text-green-800
                                        @endif">
                                        {{ Auth::user()->role_label }}
                                    </span>
                                </span>

                                <!-- User Dropdown Menu -->
                                <div class="relative group">
                                    <button class="text-gray-500 hover:text-gray-700 focus:outline-none p-1">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>

                                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                        @if(Auth::user()->isAnggota())
                                            <a href="{{ route('anggota.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class="fas fa-user mr-2"></i>Profil Saya
                                            </a>
                                        @endif

                                        <a href="{{ route('password.change') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-key mr-2"></i>Ubah Password
                                        </a>

                                        <hr class="my-1">

                                        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin logout?')">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 relative overflow-y-auto">
                    <div class="px-2 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
                        @if(session('success'))
                            <div class="mb-4">
                                <x-alert type="success" dismissible>
                                    {{ session('success') }}
                                </x-alert>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4">
                                <x-alert type="error" dismissible>
                                    {{ session('error') }}
                                </x-alert>
                            </div>
                        @endif

                        @if(session('info'))
                            <div class="mb-4">
                                <x-alert type="info" dismissible>
                                    {{ session('info') }}
                                </x-alert>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>

            <!-- Mobile Sidebar -->
            <aside id="mobile-sidebar" class="fixed top-0 left-0 h-screen w-64 bg-gradient-to-b from-primary-800 to-primary-900 transform -translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden" style="height: 100vh; overflow-y: auto;">
                <!-- Header -->
                <div class="flex items-center justify-between h-16 px-4 bg-primary-900 border-b border-primary-700">
                    <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-bold text-white">
                        @if($koperasi && $koperasi->logo)
                            <img src="{{ asset('storage/' . $koperasi->logo) }}"
                                 alt="{{ $koperasi->nama_koperasi ?? 'Logo' }}"
                                 class="h-8 w-auto rounded-lg">
                        @else
                            <i class="fas fa-mosque mr-2 text-primary-300"></i>
                            <span class="text-primary-100">{{ $koperasi->singkatan ?? 'KS' }}</span>
                        @endif
                    </a>
                    <button id="close-mobile-sidebar" class="p-2 rounded-md text-primary-300 hover:text-white hover:bg-primary-700 transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Navigation Menu Container -->
                <nav class="flex-1 overflow-y-auto py-4" style="min-height: calc(100vh - 4rem);">
                    <div class="px-4 space-y-6" style="min-height: 100%;">
                        @if(Auth::user()->isAdmin())
                            <!-- Admin Menu -->
                            <div class="space-y-2 mb-6">
                                <h3 class="text-xs font-semibold text-primary-300 uppercase tracking-wider" style="color: #9CA3AF;">Admin Menu</h3>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-700 text-white' : 'text-gray-200 hover:bg-primary-700 hover:text-white' }}" style="display: flex; align-items: center;">
                                    <i class="fas fa-tachometer-alt mr-3 text-sm" style="color: {{ request()->routeIs('admin.dashboard') ? '#F3F4F6' : '#9CA3AF' }};"></i>
                                    <span class="text-sm" style="font-weight: 500;">Dashboard</span>
                                </a>
                                <a href="{{ route('admin.pengurus.index') }}" class="{{ request()->routeIs('admin.pengurus*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-users mr-3 text-sm {{ request()->routeIs('admin.pengurus*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Pengurus</span>
                                </a>
                                <a href="{{ route('admin.koperasi.index') }}" class="{{ request()->routeIs('admin.koperasi*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-building mr-3 text-sm {{ request()->routeIs('admin.koperasi*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Data Koperasi</span>
                                </a>
                                <a href="{{ route('admin.jenis-simpanan.index') }}" class="{{ request()->routeIs('admin.jenis-simpanan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-piggy-bank mr-3 text-sm {{ request()->routeIs('admin.jenis-simpanan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Jenis Simpanan</span>
                                </a>
                                <a href="{{ route('admin.jenis-pembiayaan.index') }}" class="{{ request()->routeIs('admin.jenis-pembiayaan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-hand-holding-usd mr-3 text-sm {{ request()->routeIs('admin.jenis-pembiayaan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Jenis Pembiayaan</span>
                                </a>
                            </div>
                        @endif

                        @if(Auth::user()->isPengurus())
                            <!-- Pengurus Menu -->
                            <div class="space-y-2 mb-6">
                                <h3 class="text-xs font-semibold text-primary-300 uppercase tracking-wider">Menu Pengurus</h3>
                                <a href="{{ route('pengurus.dashboard') }}" class="{{ request()->routeIs('pengurus.dashboard') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-tachometer-alt mr-3 text-sm {{ request()->routeIs('pengurus.dashboard') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Dashboard</span>
                                </a>
                                <a href="{{ route('pengurus.anggota.index') }}" class="{{ request()->routeIs('pengurus.anggota*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-users mr-3 text-sm {{ request()->routeIs('pengurus.anggota*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Manajemen Anggota</span>
                                </a>
                                <a href="{{ route('pengurus.anggota.import') }}" class="{{ request()->routeIs('pengurus.anggota.import*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-file-excel mr-3 text-sm {{ request()->routeIs('pengurus.anggota.import*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Import Data Anggota</span>
                                </a>
                                <a href="{{ route('pengurus.simpanan.index') }}" class="{{ request()->routeIs('pengurus.simpanan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-piggy-bank mr-3 text-sm {{ request()->routeIs('pengurus.simpanan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Transaksi Simpanan</span>
                                </a>
                                <a href="{{ route('pengurus.pengajuan.index') }}" class="{{ request()->routeIs('pengurus.pengajuan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-clipboard-check mr-3 text-sm {{ request()->routeIs('pengurus.pengajuan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Verifikasi Pengajuan</span>
                                </a>
                                <a href="{{ route('pengurus.pembiayaan.index') }}" class="{{ request()->routeIs('pengurus.pembiayaan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-hand-holding-usd mr-3 text-sm {{ request()->routeIs('pengurus.pembiayaan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Manajemen Pembiayaan</span>
                                </a>
                                <a href="{{ route('pengurus.laporan.index') }}" class="{{ request()->routeIs('pengurus.laporan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-chart-bar mr-3 text-sm {{ request()->routeIs('pengurus.laporan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Laporan</span>
                                </a>
                            </div>
                        @endif

                        @if(Auth::user()->isAnggota())
                            <!-- Anggota Menu -->
                            <div class="space-y-2 mb-6">
                                <h3 class="text-xs font-semibold text-primary-300 uppercase tracking-wider">Menu Anggota</h3>
                                <a href="{{ route('anggota.dashboard') }}" class="{{ request()->routeIs('anggota.dashboard') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('anggota.dashboard') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Dashboard</span>
                                </a>
                                <a href="{{ route('anggota.profile') }}" class="{{ request()->routeIs('anggota.profile') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-user mr-3 {{ request()->routeIs('anggota.profile') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Profil Saya</span>
                                </a>
                                <a href="{{ route('anggota.simpanan.index') }}" class="{{ request()->routeIs('anggota.simpanan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-piggy-bank mr-3 {{ request()->routeIs('anggota.simpanan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Simpanan Saya</span>
                                </a>
                                <a href="{{ route('anggota.pengajuan.create') }}" class="{{ request()->routeIs('anggota.pengajuan.create') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-plus mr-3 {{ request()->routeIs('anggota.pengajuan.create') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Ajukan Pembiayaan</span>
                                </a>
                                <a href="{{ route('anggota.pengajuan.index') }}" class="{{ request()->routeIs('anggota.pengajuan.index') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-file-invoice mr-3 {{ request()->routeIs('anggota.pengajuan.index') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Pengajuan Saya</span>
                                </a>
                                <a href="{{ route('anggota.pembiayaan.index') }}" class="{{ request()->routeIs('anggota.pembiayaan*') ? 'bg-primary-700 text-white' : 'text-primary-200 hover:bg-primary-700 hover:text-white' }} flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-hand-holding-usd mr-3 {{ request()->routeIs('anggota.pembiayaan*') ? 'text-primary-300' : 'text-primary-400' }}"></i>
                                    <span class="text-sm">Pembiayaan Saya</span>
                                </a>
                            </div>
                        @endif

                        <!-- Manual Penggunaan -->
                        <div class="mt-6">
                            <a href="{{ route('manual.landing') }}" target="_blank" class="flex items-center px-3 py-2.5 text-sm font-medium text-primary-200 hover:bg-primary-700 hover:text-white rounded-lg transition-colors duration-200">
                                <i class="fas fa-book mr-3 text-primary-400"></i>
                                <span>Manual Penggunaan</span>
                                <i class="fas fa-external-link-alt ml-auto text-xs text-primary-400"></i>
                            </a>
                        </div>

                        <!-- Logout Section -->
                        <div class="mt-6 pt-6 border-t border-primary-700">
                            <form action="{{ route('logout') }}" method="POST" class="px-3">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-3 py-2.5 text-sm font-medium text-primary-200 hover:bg-primary-700 hover:text-white rounded-lg transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-3 text-primary-400"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>
            </aside>

            <!-- Mobile Sidebar Overlay -->
            <div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity duration-300 md:hidden"></div>
        </div>
    @else
        @yield('content')
    @endauth

    <!-- Loading Spinner Component -->
    @include('components.loading-spinner')

    <script>
        // Mobile sidebar functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const closeMobileSidebar = document.getElementById('close-mobile-sidebar');
        const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');

        function openMobileSidebar() {
            console.log('Opening mobile sidebar');
            if (mobileSidebar && mobileSidebarOverlay) {
                // Remove translate classes and show sidebar
                mobileSidebar.style.transform = 'translateX(0)';
                mobileSidebar.classList.remove('-translate-x-full');

                // Show overlay
                mobileSidebarOverlay.classList.remove('hidden');
                mobileSidebarOverlay.classList.add('opacity-100');

                // Prevent background scroll
                document.body.style.overflow = 'hidden';

                // Force reflow
                mobileSidebar.offsetHeight;

                console.log('Mobile sidebar opened successfully');
                console.log('Menu items found:', mobileSidebar.querySelectorAll('a').length);
            } else {
                console.error('Mobile sidebar elements not found:', {
                    sidebar: mobileSidebar,
                    overlay: mobileSidebarOverlay
                });
            }
        }

        function closeMobileSidebarFunc() {
            console.log('Closing mobile sidebar');
            if (mobileSidebar && mobileSidebarOverlay) {
                // Hide sidebar
                mobileSidebar.style.transform = 'translateX(-100%)';
                mobileSidebar.classList.add('-translate-x-full');
                mobileSidebar.classList.remove('translate-x-0');

                // Hide overlay
                mobileSidebarOverlay.classList.add('hidden');
                mobileSidebarOverlay.classList.remove('opacity-100');

                // Restore background scroll
                document.body.style.overflow = '';

                console.log('Mobile sidebar closed');
            }
        }

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Mobile menu button clicked');
                openMobileSidebar();
            });
        } else {
            console.error('Mobile menu button not found');
        }

        if (closeMobileSidebar) {
            closeMobileSidebar.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Close button clicked');
                closeMobileSidebarFunc();
            });
        } else {
            console.error('Close button not found');
        }

        // Close mobile sidebar when clicking overlay
        if (mobileSidebarOverlay) {
            mobileSidebarOverlay.addEventListener('click', closeMobileSidebarFunc);
        }

        // Close mobile sidebar when pressing Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileSidebar && !mobileSidebar.classList.contains('-translate-x-full')) {
                closeMobileSidebarFunc();
            }
        });

        // Debug: Log elements on page load
        console.log('Mobile sidebar elements:', {
            menuButton: mobileMenuButton,
            sidebar: mobileSidebar,
            closeButton: closeMobileSidebar,
            overlay: mobileSidebarOverlay
        });
    </script>

    @stack('scripts')
</body>
</html>