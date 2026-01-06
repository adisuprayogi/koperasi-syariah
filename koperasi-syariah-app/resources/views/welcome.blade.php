<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $koperasi->nama_koperasi ?? 'Koperasi Syariah Bersama' }} - Sistem Informasi Koperasi Syariah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        'islamic-green': {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }

        .gradient-islamic {
            background: linear-gradient(135deg, #14532d 0%, #15803d 50%, #16a34a 100%);
        }

        .gradient-hero {
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 50%, #2dd4bf 100%);
        }

        .pattern-islamic {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card-hover:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            animation: pulse-slow 2s infinite;
        }

        .whatsapp-float a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: #25d366;
            border-radius: 50%;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            transition: all 0.3s ease;
        }

        .whatsapp-float a:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(37, 211, 102, 0.6);
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #16a34a;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Mobile menu animation */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        /* Loading screen */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #14532d 0%, #16a34a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        #loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Testimonial card */
        .testimonial-card {
            position: relative;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 80px;
            color: #16a34a;
            opacity: 0.2;
            font-family: Georgia, serif;
            line-height: 1;
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
            40% { transform: translateX(-50%) translateY(-20px); }
            60% { transform: translateX(-50%) translateY(-10px); }
        }

        /* Stats counter */
        .stat-number {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-50 pattern-islamic overflow-x-hidden">
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="text-center">
            <div class="loader mx-auto mb-4"></div>
            <p class="text-white text-lg font-medium">Memuat...</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-10 h-10 gradient-islamic rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-mosque text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-800">{{ $koperasi->nama_koperasi ?? 'Koperasi Syariah' }}</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#features" class="nav-link text-gray-600 hover:text-islamic-green-600 transition duration-300 font-medium">Fitur</a>
                    <a href="#services" class="nav-link text-gray-600 hover:text-islamic-green-600 transition duration-300 font-medium">Layanan</a>
                    <a href="#testimonials" class="nav-link text-gray-600 hover:text-islamic-green-600 transition duration-300 font-medium">Testimoni</a>
                    <a href="#about" class="nav-link text-gray-600 hover:text-islamic-green-600 transition duration-300 font-medium">Tentang</a>
                    @auth
                        <a href="{{ url('/home') }}" class="bg-islamic-green-600 text-white px-6 py-2 rounded-lg hover:bg-islamic-green-700 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('manual.landing') }}" class="text-gray-600 hover:text-islamic-green-600 transition duration-300 font-medium">
                            <i class="fas fa-book mr-1"></i>Manual
                        </a>
                        <a href="{{ route('login') }}" class="bg-islamic-green-600 text-white px-6 py-2 rounded-lg hover:bg-islamic-green-700 transition duration-300 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-600 hover:text-islamic-green-600 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu fixed top-0 right-0 h-full w-64 bg-white shadow-2xl md:hidden z-50">
            <div class="p-4">
                <button id="close-menu" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times text-2xl"></i>
                </button>
                <div class="mt-12 space-y-4">
                    <a href="#features" class="block py-3 px-4 text-gray-600 hover:bg-islamic-green-50 hover:text-islamic-green-600 rounded-lg transition duration-300">
                        <i class="fas fa-star mr-3"></i>Fitur
                    </a>
                    <a href="#services" class="block py-3 px-4 text-gray-600 hover:bg-islamic-green-50 hover:text-islamic-green-600 rounded-lg transition duration-300">
                        <i class="fas fa-concierge-bell mr-3"></i>Layanan
                    </a>
                    <a href="#testimonials" class="block py-3 px-4 text-gray-600 hover:bg-islamic-green-50 hover:text-islamic-green-600 rounded-lg transition duration-300">
                        <i class="fas fa-comments mr-3"></i>Testimoni
                    </a>
                    <a href="#about" class="block py-3 px-4 text-gray-600 hover:bg-islamic-green-50 hover:text-islamic-green-600 rounded-lg transition duration-300">
                        <i class="fas fa-info-circle mr-3"></i>Tentang
                    </a>
                    @auth
                        <a href="{{ url('/home') }}" class="block py-3 px-4 bg-islamic-green-600 text-white rounded-lg text-center font-medium">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('manual.landing') }}" class="block py-3 px-4 text-gray-600 hover:bg-islamic-green-50 hover:text-islamic-green-600 rounded-lg transition duration-300">
                            <i class="fas fa-book mr-3"></i>Manual
                        </a>
                        <a href="{{ route('login') }}" class="block py-3 px-4 bg-islamic-green-600 text-white rounded-lg text-center font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative gradient-islamic text-white py-20 md:py-32 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white opacity-5 rounded-full blur-3xl animate-blob"></div>
            <div class="absolute top-40 right-10 w-72 h-72 bg-teal-300 opacity-10 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-20 left-1/2 w-72 h-72 bg-green-300 opacity-10 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <div class="mb-8" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="w-32 h-32 md:w-40 md:h-40 bg-white rounded-full mx-auto flex items-center justify-center shadow-2xl animate-float">
                        <i class="fas fa-hand-holding-usd text-islamic-green-600 text-5xl md:text-6xl"></i>
                    </div>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    Selamat Datang di<br>
                    <span class="text-islamic-green-100">{{ $koperasi->nama_koperasi ?? 'Koperasi Syariah Bersama' }}</span>
                </h1>

                <p class="text-lg md:text-xl lg:text-2xl text-islamic-green-100 mb-8 max-w-3xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                    Sistem Informasi Koperasi Syariah Terpadu untuk Mengelola Simpanan, Pembiayaan, dan Laporan dengan Prinsip Syariah yang Fair dan Transparan
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
                    @guest
                    <a href="{{ route('login') }}" class="bg-white text-islamic-green-700 px-8 py-4 rounded-lg hover:bg-islamic-green-50 transition duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 inline-flex items-center justify-center">
                        <i class="fas fa-user-circle mr-2"></i>Portal Anggota
                    </a>
                    @endguest
                    <a href="#features" class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-islamic-green-700 transition duration-300 font-semibold text-lg inline-flex items-center justify-center">
                        <i class="fas fa-info-circle mr-2"></i>Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            <a href="#stats" class="text-white opacity-70 hover:opacity-100 transition duration-300">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-16 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-duration="800">
                    <div class="w-16 h-16 bg-islamic-green-100 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-users text-islamic-green-600 text-2xl"></i>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold stat-number" data-target="{{ $totalAnggotaAktif ?? 150 }}">0</div>
                    <div class="text-gray-600 font-medium mt-2">Anggota Aktif</div>
                </div>

                <div class="text-center" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="w-16 h-16 bg-teal-100 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-hand-holding-usd text-teal-600 text-2xl"></i>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold stat-number" data-target="5">0</div>
                    <div class="text-gray-600 font-medium mt-2">Miliar Dana</div>
                </div>

                <div class="text-center" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold stat-number" data-target="98">0</div>
                    <div class="text-gray-600 font-medium mt-2">% Kepuasan</div>
                </div>

                <div class="text-center" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div class="w-16 h-16 bg-purple-100 rounded-full mx-auto flex items-center justify-center mb-4">
                        <i class="fas fa-award text-purple-600 text-2xl"></i>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold stat-number" data-target="10">0</div>
                    <div class="text-gray-600 font-medium mt-2">Tahun Pengalaman</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4" data-aos="fade-up">Fitur Unggulan Kami</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                    Sistem lengkap untuk mengelola koperasi syariah dengan teknologi modern dan prinsip islami
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border-t-4 border-islamic-green-500" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-gradient-to-br from-islamic-green-400 to-islamic-green-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-piggy-bank text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Manajemen Simpanan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola simpanan pokok, wajib, dan sukarela dengan mudah. Pantau saldo dan riwayat transaksi secara real-time.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border-t-4 border-teal-500" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-teal-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-coins text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Pembiayaan Syariah</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Ajukan pembiayaan dengan sistem syariah yang fair tanpa riba. Proses cepat dan transparan.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border-t-4 border-blue-500" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-calculator text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Angsuran Flexibel</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem angsuran yang mudah dan terencana. Pembayaran dapat dilakukan secara online atau offline.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border-t-4 border-purple-500" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Laporan Komprehensif</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Generate laporan keuangan, laba rugi, dan neraca otomatis. Export ke Excel untuk analisis lebih lanjut.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border-t-4 border-orange-500" data-aos="fade-up" data-aos-delay="500">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-id-card text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Kartu Anggota Digital</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kartu anggota digital yang dapat dicetak. Identitas resmi sebagai anggota koperasi syariah.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white rounded-xl shadow-lg p-8 card-hover border-t-4 border-pink-500" data-aos="fade-up" data-aos-delay="600">
                    <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-pink-600 rounded-xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Akses Mobile Friendly</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Akses sistem dari mana saja melalui smartphone. Desain responsif yang user-friendly.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 gradient-islamic text-white relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-teal-300 opacity-10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4" data-aos="fade-up">Layanan Kami</h2>
                <p class="text-lg md:text-xl text-islamic-green-100 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                    Solusi lengkap untuk kebutuhan finansial anggota koperasi syariah
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-effect rounded-2xl p-8 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full mx-auto flex items-center justify-center mb-6">
                        <i class="fas fa-users text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Untuk Anggota</h3>
                    <ul class="text-islamic-green-100 space-y-3 text-left">
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>Ajukan pembiayaan mudah</li>
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>Pantau simpanan anytime</li>
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>Bayar angsuran online</li>
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>Lihat riwayat transaksi</li>
                    </ul>
                </div>

                <div class="glass-effect rounded-2xl p-8 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full mx-auto flex items-center justify-center mb-6">
                        <i class="fas fa-user-tie text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Untuk Pengurus</h3>
                    <ul class="text-islamic-green-100 space-y-3 text-left">
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>Manajemen anggota lengkap</li>
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>Verifikasi pengajuan cepat</li>
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>Generate laporan otomatis</li>
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>Monitoring real-time</li>
                    </ul>
                </div>

                <div class="glass-effect rounded-2xl p-8 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full mx-auto flex items-center justify-center mb-6">
                        <i class="fas fa-user-shield text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Untuk Admin</h3>
                    <ul class="text-islamic-green-100 space-y-3 text-left">
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>Kelola sistem lengkap</li>
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>Master data management</li>
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>User access control</li>
                        <li><i class="fas fa-check-circle mr-2 text-islamic-green-300"></i>System configuration</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4" data-aos="fade-up">Apa Kata Anggota Kami</h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                    Testimoni nyata dari anggota yang telah merasakan kemudahan sistem koperasi syariah kami
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="testimonial-card bg-white rounded-xl shadow-lg p-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-islamic-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-islamic-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Ahmad Fauzi</h4>
                            <p class="text-sm text-gray-500">Pedagang</p>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        "Sangat mudah dan praktis! Pengajuan pembiayaan saya cepat diproses dan angsurannya terjangkau. Sistemnya transparan dan sesuai syariah."
                    </p>
                    <div class="flex text-islamic-green-500">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="testimonial-card bg-white rounded-xl shadow-lg p-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-teal-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Siti Aminah</h4>
                            <p class="text-sm text-gray-500">Ibu Rumah Tangga</p>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        "Alhamdulillah sejak bergabung dengan koperasi syariah ini, saya bisa mengatur keuangan keluarga dengan lebih baik. Aplikasinya juga mudah digunakan."
                    </p>
                    <div class="flex text-islamic-green-500">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="testimonial-card bg-white rounded-xl shadow-lg p-8" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-6">
                        <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Budi Santoso</h4>
                            <p class="text-sm text-gray-500">Wiraswasta</p>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        "Sangat terbantu dengan sistem angsuran yang fleksibel. Pengurusnya ramah dan responsif. Recommended untuk yang butuh pembiayaan syariah."
                    </p>
                    <div class="flex text-islamic-green-500">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Mengapa Memilih Koperasi Syariah Kami?</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-islamic-green-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-check text-islamic-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-800">Prinsip Syariah</h4>
                                <p class="text-gray-600">Semua transaksi mengikuti prinsip islam tanpa riba dan unsur bathil.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-check text-teal-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-800">Transparent & Fair</h4>
                                <p class="text-gray-600">Sistem yang transparan dengan pembagian keuntungan yang adil.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-check text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-800">Mudah Diakses</h4>
                                <p class="text-gray-600">Platform digital yang dapat diakses kapanpun dan dimanapun.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-check text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-800">Aman Terpercaya</h4>
                                <p class="text-gray-600">Keamanan data terjamin dan sistem yang telah teruji.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-islamic-green-50 to-teal-50 rounded-xl p-8 shadow-lg" data-aos="fade-left">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-islamic-green-100 rounded-full mx-auto flex items-center justify-center mb-4">
                            <i class="fas fa-mosque text-islamic-green-600 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Informasi Koperasi</h3>
                    </div>
                    <div class="space-y-3 text-gray-600">
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="font-medium">Nama Koperasi</span>
                            <span class="text-gray-800 text-right">{{ $koperasi->nama_koperasi }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="font-medium">No. Registrasi</span>
                            <span class="text-gray-800 text-right">{{ $koperasi->no_koperasi }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="font-medium">Alamat</span>
                            <span class="text-gray-800 text-right">{{ $koperasi->alamat }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="font-medium">Email</span>
                            <span class="text-gray-800 text-right">{{ $koperasi->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200">
                            <span class="font-medium">Telepon</span>
                            <span class="text-gray-800 text-right">{{ $koperasi->telepon ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="font-medium">Status</span>
                            <span class="bg-islamic-green-100 text-islamic-green-800 px-3 py-1 rounded-full text-sm font-medium">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-islamic text-white relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-teal-300 opacity-10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-4" data-aos="fade-up">Bergabung Bersama Kami</h2>
            <p class="text-lg md:text-xl text-islamic-green-100 mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Mari bersama membangun ekonomi umat yang kuat dan berdaya dengan prinsip syariah
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
                @guest
                <a href="{{ route('login') }}" class="bg-white text-islamic-green-700 px-8 py-4 rounded-lg hover:bg-islamic-green-50 transition duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 inline-flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk Sekarang
                </a>
                @endguest
                <a href="tel:+628123456789" class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-islamic-green-700 transition duration-300 font-semibold text-lg inline-flex items-center justify-center">
                    <i class="fas fa-phone mr-2"></i>Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 gradient-islamic rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-mosque text-white"></i>
                        </div>
                        <span class="text-lg font-bold">{{ $koperasi->nama_koperasi ?? 'Koperasi Syariah' }}</span>
                    </div>
                    <p class="text-gray-400">
                        Sistem informasi koperasi syariah terpadu untuk kemajuan ekonomi umat.
                    </p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-islamic-green-600 transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-islamic-green-600 transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-islamic-green-600 transition duration-300">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-islamic-green-600 transition duration-300">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-lg">Layanan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-islamic-green-500 transition duration-300"><i class="fas fa-chevron-right mr-2 text-xs"></i>Simpanan</a></li>
                        <li><a href="#" class="hover:text-islamic-green-500 transition duration-300"><i class="fas fa-chevron-right mr-2 text-xs"></i>Pembiayaan</a></li>
                        <li><a href="#" class="hover:text-islamic-green-500 transition duration-300"><i class="fas fa-chevron-right mr-2 text-xs"></i>Angsuran</a></li>
                        <li><a href="#" class="hover:text-islamic-green-500 transition duration-300"><i class="fas fa-chevron-right mr-2 text-xs"></i>Laporan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-lg">Informasi</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-islamic-green-500 transition duration-300"><i class="fas fa-chevron-right mr-2 text-xs"></i>Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-islamic-green-500 transition duration-300"><i class="fas fa-chevron-right mr-2 text-xs"></i>Visi & Misi</a></li>
                        <li><a href="#" class="hover:text-islamic-green-500 transition duration-300"><i class="fas fa-chevron-right mr-2 text-xs"></i>Pengurus</a></li>
                        <li><a href="{{ route('manual.landing') }}" class="hover:text-islamic-green-500 transition duration-300"><i class="fas fa-chevron-right mr-2 text-xs"></i>Manual</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-lg">Kontak</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mr-3 mt-1 text-islamic-green-500"></i>
                            <span>{{ $koperasi->alamat ?? 'Jl. Cengkeh No. 789, Jakarta Pusat' }}</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-islamic-green-500"></i>
                            <span>+62 812-3456-789</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-islamic-green-500"></i>
                            <span>info@koperasi-syariah.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock mr-3 text-islamic-green-500"></i>
                            <span>Senin - Jumat: 08:00 - 17:00</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ $koperasi->nama_koperasi ?? 'Koperasi Syariah Bersama' }}. All rights reserved.</p>
                <p class="mt-2">Powered by Laravel & Tailwind CSS • Built with <i class="fas fa-heart text-red-500"></i> for Islamic Cooperative</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <div class="whatsapp-float">
        <a href="https://wa.me/628123456789" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-whatsapp text-white text-3xl"></i>
        </a>
    </div>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-32 right-8 bg-islamic-green-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-islamic-green-700 transition duration-300 opacity-0 invisible z-40">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Loading screen
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loading-screen').classList.add('hidden');
            }, 500);
        });

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeMenuButton = document.getElementById('close-menu');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.add('active');
        });

        closeMenuButton.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
        });

        // Close menu when clicking on links
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
            });
        });

        // Back to top button
        const backToTop = document.getElementById('back-to-top');

        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.remove('opacity-0', 'invisible');
                backToTop.classList.add('opacity-100', 'visible');
            } else {
                backToTop.classList.add('opacity-0', 'invisible');
                backToTop.classList.remove('opacity-100', 'visible');
            }
        });

        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Stats counter animation
        const statsSection = document.getElementById('stats');
        const statNumbers = document.querySelectorAll('.stat-number');
        let animated = false;

        function animateStats() {
            if (animated) return;

            const sectionTop = statsSection.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (sectionTop < windowHeight * 0.75) {
                animated = true;

                statNumbers.forEach(stat => {
                    const target = parseInt(stat.getAttribute('data-target'));
                    const duration = 2000;
                    const step = target / (duration / 16);
                    let current = 0;

                    const updateCounter = () => {
                        current += step;
                        if (current < target) {
                            stat.textContent = Math.ceil(current);
                            requestAnimationFrame(updateCounter);
                        } else {
                            stat.textContent = target;
                        }
                    };

                    updateCounter();
                });
            }
        }

        window.addEventListener('scroll', animateStats);
        animateStats(); // Check on load
    </script>
</body>
</html>
