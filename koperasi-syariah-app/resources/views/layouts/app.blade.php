<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Koperasi Syariah') - Aplikasi Koperasi Syariah</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @stack('styles')
</head>
<body class="bg-gray-50 pt-16">
    @auth
        <!-- Navigation -->
        <nav class="bg-white shadow-md fixed top-0 left-0 right-0 z-50">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo - Left Aligned -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                            <i class="fas fa-mosque mr-2"></i>Koperasi Syariah
                        </a>
                    </div>

                    <!-- User Menu - Right Aligned -->
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">
                            <i class="fas fa-user-circle mr-2"></i>
                            {{ Auth::user()->name }}
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
                            <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
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
        </nav>

        <!-- Sidebar & Content -->
        <div class="flex">
            <!-- Sidebar -->
            <aside class="w-[256px] min-h-screen bg-gray-800 fixed left-0 top-16 z-40 h-screen overflow-y-auto">
                <div class="p-4">
                    @if(Auth::user()->isAdmin())
                        <!-- Admin Menu -->
                        <div class="space-y-2">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin Menu</h3>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                            </a>
                            <a href="{{ route('admin.pengurus.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-users mr-3"></i>Manajemen Pengurus
                            </a>
                            <a href="{{ route('admin.koperasi.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-building mr-3"></i>Data Koperasi
                            </a>
                            <a href="{{ route('admin.jenis-simpanan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-piggy-bank mr-3"></i>Jenis Simpanan
                            </a>
                            <a href="{{ route('admin.jenis-pembiayaan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-hand-holding-usd mr-3"></i>Jenis Pembiayaan
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->isPengurus() || Auth::user()->isAdmin())
                        <!-- Pengurus Menu -->
                        <div class="mt-6 space-y-2">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Pengurus</h3>
                            <a href="{{ route('pengurus.dashboard') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                            </a>
                            <a href="{{ route('pengurus.anggota.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-users mr-3"></i>Manajemen Anggota
                            </a>
                            <a href="{{ route('pengurus.simpanan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-piggy-bank mr-3"></i>Transaksi Simpanan
                            </a>
                            <a href="{{ route('pengurus.pengajuan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-file-invoice mr-3"></i>Pengajuan Pembiayaan
                            </a>
                            <a href="{{ route('pengurus.pembiayaan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-hand-holding-usd mr-3"></i>Manajemen Pembiayaan
                            </a>
                            <a href="{{ route('pengurus.laporan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-chart-bar mr-3"></i>Laporan
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->isAnggota())
                        <!-- Anggota Menu -->
                        <div class="space-y-2">
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
                            <a href="{{ route('anggota.pengajuan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-file-invoice mr-3"></i>Pengajuan Pembiayaan
                            </a>
                            <a href="{{ route('anggota.pembiayaan.index') }}" class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-md">
                                <i class="fas fa-hand-holding-usd mr-3"></i>Pembiayaan Saya
                            </a>
                        </div>
                    @endif
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 ml-[256px]">
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
            </main>
        </div>
    @else
        @yield('content')
    @endauth

    @stack('scripts')
</body>
</html>
