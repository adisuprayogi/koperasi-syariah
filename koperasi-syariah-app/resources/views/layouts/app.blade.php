<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $koperasi->nama_koperasi ?? 'Koperasi Syariah') - {{ $koperasi->nama_koperasi ?? 'Koperasi Syariah' }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

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
                    <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto bg-gray-800">
                        <div class="flex items-center flex-shrink-0 px-4">
                            <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-bold text-indigo-600">
                                @if($koperasi && $koperasi->logo)
                                    <img src="{{ asset('storage/' . $koperasi->logo) }}"
                                         alt="{{ $koperasi->nama_koperasi ?? 'Logo' }}"
                                         class="h-8 w-auto rounded">
                                @else
                                    <i class="fas fa-mosque mr-2"></i>
                                    {{ $koperasi->nama_koperasi ?? 'Koperasi Syariah' }}
                                @endif
                            </a>
                        </div>
                        <div class="mt-8 flex-1 flex flex-col">
                            <nav class="flex-1 px-2 pb-4 space-y-1">
                                @if(Auth::user()->isAdmin())
                                    <!-- Admin Menu -->
                                    <div class="mb-4">
                                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Admin Menu</h3>
                                        <a href="{{ route('admin.dashboard') }}" class="bg-gray-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-tachometer-alt mr-3 text-sm"></i>
                                            Dashboard
                                        </a>
                                        <a href="{{ route('admin.pengurus.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-users mr-3 text-sm"></i>
                                            Pengurus
                                        </a>
                                        <a href="{{ route('admin.koperasi.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-building mr-3 text-sm"></i>
                                            Data Koperasi
                                        </a>
                                        <a href="{{ route('admin.jenis-simpanan.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-piggy-bank mr-3 text-sm"></i>
                                            Jenis Simpanan
                                        </a>
                                        <a href="{{ route('admin.jenis-pembiayaan.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-hand-holding-usd mr-3 text-sm"></i>
                                            Jenis Pembiayaan
                                        </a>
                                    </div>
                                @endif

                                @if(Auth::user()->isPengurus() || Auth::user()->isAdmin())
                                    <!-- Pengurus Menu -->
                                    <div class="mb-4">
                                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Pengurus</h3>
                                        <a href="{{ route('pengurus.dashboard') }}" class="{{ request()->routeIs('pengurus.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-tachometer-alt mr-3 text-sm"></i>
                                            Dashboard
                                        </a>
                                        <a href="{{ route('pengurus.anggota.index') }}" class="{{ request()->routeIs('pengurus.anggota*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-users mr-3 text-sm"></i>
                                            Manajemen Anggota
                                        </a>
                                        <a href="{{ route('pengurus.simpanan.index') }}" class="{{ request()->routeIs('pengurus.simpanan*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-piggy-bank mr-3 text-sm"></i>
                                            Transaksi Simpanan
                                        </a>
                                        <a href="{{ route('pengurus.pengajuan.index') }}" class="{{ request()->routeIs('pengurus.pengajuan*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-clipboard-check mr-3 text-sm"></i>
                                            Verifikasi Pengajuan
                                        </a>
                                        <a href="{{ route('pengurus.pembiayaan.index') }}" class="{{ request()->routeIs('pengurus.pembiayaan*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-hand-holding-usd mr-3 text-sm"></i>
                                            Manajemen Pembiayaan
                                        </a>
                                        <a href="{{ route('pengurus.laporan.index') }}" class="{{ request()->routeIs('pengurus.laporan*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-chart-bar mr-3 text-sm"></i>
                                            Laporan
                                        </a>
                                    </div>
                                @endif

                                @if(Auth::user()->isAnggota())
                                    <!-- Anggota Menu -->
                                    <div class="mb-4">
                                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Anggota</h3>
                                        <a href="{{ route('anggota.dashboard') }}" class="{{ request()->routeIs('anggota.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                                        </a>
                                        <a href="{{ route('anggota.profile') }}" class="{{ request()->routeIs('anggota.profile') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-user mr-3"></i>Profil Saya
                                        </a>
                                        <a href="{{ route('anggota.simpanan.index') }}" class="{{ request()->routeIs('anggota.simpanan*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-piggy-bank mr-3"></i>Simpanan Saya
                                        </a>
                                        <a href="{{ route('anggota.pengajuan.create') }}" class="{{ request()->routeIs('anggota.pengajuan.create') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-plus mr-3"></i>Ajukan Pembiayaan
                                        </a>
                                        <a href="{{ route('anggota.pengajuan.index') }}" class="{{ request()->routeIs('anggota.pengajuan.index') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-file-invoice mr-3"></i>Pengajuan Saya
                                        </a>
                                        <a href="{{ route('anggota.pembiayaan.index') }}" class="{{ request()->routeIs('anggota.pembiayaan*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                            <i class="fas fa-hand-holding-usd mr-3"></i>Pembiayaan Saya
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
                        <div class="flex justify-between items-center h-16">
                            <!-- Mobile menu button -->
                            <button id="mobile-menu-button" class="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                                <i class="fas fa-bars text-xl"></i>
                            </button>

                            <!-- Logo (Mobile) -->
                            <div class="md:hidden flex items-center">
                                <a href="{{ route('dashboard') }}" class="flex items-center text-lg font-bold text-indigo-600">
                                    @if($koperasi && $koperasi->logo)
                                        <img src="{{ asset('storage/' . $koperasi->logo) }}"
                                             alt="{{ $koperasi->nama_koperasi ?? 'Logo' }}"
                                             class="h-6 w-auto rounded">
                                    @else
                                        <i class="fas fa-mosque mr-1"></i>
                                        {{ $koperasi->singkatan ?? 'KS' }}
                                    @endif
                                </a>
                            </div>

                            <!-- User Menu -->
                            <div class="flex items-center space-x-4">
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
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session('info'))
                            <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
                                {{ session('info') }}
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>

            <!-- Mobile Sidebar -->
            <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 transform -translate-x-full transition-transform duration-300 md:hidden">
                <div class="flex items-center justify-between h-16 px-4 bg-gray-900">
                    <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-bold text-indigo-600">
                        @if($koperasi && $koperasi->logo)
                            <img src="{{ asset('storage/' . $koperasi->logo) }}"
                                 alt="{{ $koperasi->nama_koperasi ?? 'Logo' }}"
                                 class="h-6 w-auto rounded">
                        @else
                            <i class="fas fa-mosque mr-2"></i>
                            {{ $koperasi->singkatan ?? 'KS' }}
                        @endif
                    </a>
                    <button id="close-mobile-sidebar" class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="flex-1 h-0 pt-2 pb-4 overflow-y-auto">
                    <div class="px-4">
                        @if(Auth::user()->isAdmin())
                            <!-- Admin Menu -->
                            <div class="space-y-2 mb-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin Menu</h3>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-tachometer-alt mr-3 text-sm"></i>
                                    <span class="text-sm">Dashboard</span>
                                </a>
                                <a href="{{ route('admin.pengurus.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-users mr-3 text-sm"></i>
                                    <span class="text-sm">Pengurus</span>
                                </a>
                                <a href="{{ route('admin.koperasi.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-building mr-3 text-sm"></i>
                                    <span class="text-sm">Data Koperasi</span>
                                </a>
                                <a href="{{ route('admin.jenis-simpanan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-piggy-bank mr-3 text-sm"></i>
                                    <span class="text-sm">Jenis Simpanan</span>
                                </a>
                                <a href="{{ route('admin.jenis-pembiayaan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-hand-holding-usd mr-3 text-sm"></i>
                                    <span class="text-sm">Jenis Pembiayaan</span>
                                </a>
                            </div>
                        @endif

                        @if(Auth::user()->isPengurus() || Auth::user()->isAdmin())
                            <!-- Pengurus Menu -->
                            <div class="space-y-2 mb-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Pengurus</h3>
                                <a href="{{ route('pengurus.dashboard') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-tachometer-alt mr-3 text-sm"></i>
                                    <span class="text-sm">Dashboard</span>
                                </a>
                                <a href="{{ route('pengurus.anggota.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-users mr-3 text-sm"></i>
                                    <span class="text-sm">Manajemen Anggota</span>
                                </a>
                                <a href="{{ route('pengurus.simpanan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-piggy-bank mr-3 text-sm"></i>
                                    <span class="text-sm">Transaksi Simpanan</span>
                                </a>
                                <a href="{{ route('pengurus.pengajuan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-clipboard-check mr-3 text-sm"></i>
                                    <span class="text-sm">Verifikasi Pengajuan</span>
                                </a>
                                <a href="{{ route('pengurus.pembiayaan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-hand-holding-usd mr-3 text-sm"></i>
                                    <span class="text-sm">Manajemen Pembiayaan</span>
                                </a>
                                <a href="{{ route('pengurus.laporan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-chart-bar mr-3 text-sm"></i>
                                    <span class="text-sm">Laporan</span>
                                </a>
                            </div>
                        @endif

                        @if(Auth::user()->isAnggota())
                            <!-- Anggota Menu -->
                            <div class="space-y-2 mb-6">
                                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Anggota</h3>
                                <a href="{{ route('anggota.dashboard') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                                </a>
                                <a href="{{ route('anggota.profile') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-user mr-3"></i>Profil Saya
                                </a>
                                <a href="{{ route('anggota.simpanan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-piggy-bank mr-3"></i>Simpanan Saya
                                </a>
                                <a href="{{ route('anggota.pengajuan.create') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-plus mr-3"></i>Ajukan Pembiayaan
                                </a>
                                <a href="{{ route('anggota.pengajuan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-file-invoice mr-3"></i>Pengajuan Saya
                                </a>
                                <a href="{{ route('anggota.pembiayaan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                    <i class="fas fa-hand-holding-usd mr-3"></i>Pembiayaan Saya
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    @else
        @yield('content')
    @endauth

    <script>
        // Mobile sidebar functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const closeMobileSidebar = document.getElementById('close-mobile-sidebar');

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => {
                mobileSidebar.classList.remove('-translate-x-full');
            });
        }

        if (closeMobileSidebar) {
            closeMobileSidebar.addEventListener('click', () => {
                mobileSidebar.classList.add('-translate-x-full');
            });
        }

        // Close mobile sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (mobileSidebar && !mobileSidebar.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                mobileSidebar.classList.add('-translate-x-full');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>