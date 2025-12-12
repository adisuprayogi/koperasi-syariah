<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $koperasi->nama_koperasi ?? 'Koperasi Syariah Bersama' }} - Sistem Informasi Koperasi Syariah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-islamic {
            background: linear-gradient(135deg, #15803d 0%, #16a34a 50%, #22c55e 100%);
        }
        .pattern-islamic {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50 pattern-islamic">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-xl font-bold text-gray-800">{{ $koperasi->nama_koperasi ?? 'Koperasi Syariah Bersama' }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/home') }}" class="text-gray-600 hover:text-islamic-green-600 transition duration-300">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-islamic-green-600 text-white px-6 py-2 rounded-lg hover:bg-islamic-green-700 transition duration-300 font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                        </a>
                        <a href="{{ route('manual.landing') }}" class="text-gray-600 hover:text-islamic-green-600 transition duration-300">
                            <i class="fas fa-book mr-2"></i>Manual
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-islamic text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="mb-8">
                    <div class="w-32 h-32 bg-white rounded-full mx-auto flex items-center justify-center shadow-2xl">
                        <i class="fas fa-hand-holding-usd text-islamic-green-600 text-5xl"></i>
                    </div>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6">
                    Selamat Datang di<br>
                    <span class="text-islamic-green-100">{{ $koperasi->nama_koperasi ?? 'Koperasi Syariah Bersama' }}</span>
                </h1>
                <p class="text-xl md:text-2xl text-islamic-green-100 mb-8 max-w-3xl mx-auto leading-relaxed">
                    Sistem Informasi Koperasi Syariah Terpadu untuk Mengelola Simpanan, Pembiayaan, dan Laporan dengan Prinsip Syariah yang Fair dan Transparan
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                    <a href="{{ route('login') }}" class="bg-white text-islamic-green-700 px-8 py-4 rounded-lg hover:bg-islamic-green-50 transition duration-300 font-semibold text-lg shadow-lg">
                        <i class="fas fa-user-circle mr-2"></i>Portal Anggota
                    </a>
                    @endguest
                    <a href="#features" class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-islamic-green-700 transition duration-300 font-semibold text-lg">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-center max-w-4xl mx-auto">
                <div class="bg-islamic-green-50 rounded-lg p-6 border border-islamic-green-200">
                    <i class="fas fa-users text-islamic-green-600 text-3xl mb-4"></i>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalAnggotaAktif ?? 0 }}</div>
                    <div class="text-gray-600 font-medium">Anggota Aktif</div>
                </div>
                <div class="bg-islamic-green-50 rounded-lg p-6 border border-islamic-green-200">
                    <i class="fas fa-chart-line text-islamic-green-600 text-3xl mb-4"></i>
                    <div class="text-3xl font-bold text-gray-800">98%</div>
                    <div class="text-gray-600 font-medium">Tingkat Kepuasan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Fitur Unggulan Kami</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Sistem lengkap untuk mengelola koperasi syariah dengan teknologi modern dan prinsip islami
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border-t-4 border-islamic-green-500">
                    <div class="w-16 h-16 bg-islamic-green-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-piggy-bank text-islamic-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Manajemen Simpanan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola simpanan pokok, wajib, dan sukarela dengan mudah. Pantau saldo dan riwayat transaksi secara real-time.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border-t-4 border-islamic-green-500">
                    <div class="w-16 h-16 bg-islamic-green-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-coins text-islamic-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Pembiayaan Syariah</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Ajukan pembiayaan dengan sistem syariah yang fair tanpa riba. Proses cepat dan transparan.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border-t-4 border-islamic-green-500">
                    <div class="w-16 h-16 bg-islamic-green-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-calculator text-islamic-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Angsuran Flexibel</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem angsuran yang mudah dan terencana. Pembayaran dapat dilakukan secara online atau offline.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border-t-4 border-islamic-green-500">
                    <div class="w-16 h-16 bg-islamic-green-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-chart-bar text-islamic-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Laporan Komprehensif</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Generate laporan keuangan, laba rugi, dan neraca otomatis. Export ke Excel untuk analisis lebih lanjut.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border-t-4 border-islamic-green-500">
                    <div class="w-16 h-16 bg-islamic-green-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-id-card text-islamic-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Kartu Anggota Digital</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kartu anggota digital yang dapat dicetak. Identitas resmi sebagai anggota koperasi syariah.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition duration-300 border-t-4 border-islamic-green-500">
                    <div class="w-16 h-16 bg-islamic-green-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-islamic-green-600 text-2xl"></i>
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
    <section class="py-20 gradient-islamic text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">Layanan Kami</h2>
                <p class="text-xl text-islamic-green-100 max-w-3xl mx-auto">
                    Solusi lengkap untuk kebutuhan finansial anggota koperasi syariah
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full mx-auto flex items-center justify-center mb-6">
                        <i class="fas fa-users text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Untuk Anggota</h3>
                    <ul class="text-islamic-green-100 space-y-2">
                        <li><i class="fas fa-check mr-2"></i>Ajukan pembiayaan mudah</li>
                        <li><i class="fas fa-check mr-2"></i>Pantau simpanan anytime</li>
                        <li><i class="fas fa-check mr-2"></i>Bayar angsuran online</li>
                        <li><i class="fas fa-check mr-2"></i>Lihat riwayat transaksi</li>
                    </ul>
                </div>

                <div class="text-center">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full mx-auto flex items-center justify-center mb-6">
                        <i class="fas fa-user-tie text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Untuk Pengurus</h3>
                    <ul class="text-islamic-green-100 space-y-2">
                        <li><i class="fas fa-check mr-2"></i>Manajemen anggota lengkap</li>
                        <li><i class="fas fa-check mr-2"></i>Verifikasi pengajuan cepat</li>
                        <li><i class="fas fa-check mr-2"></i>Generate laporan otomatis</li>
                        <li><i class="fas fa-check mr-2"></i>Monitoring real-time</li>
                    </ul>
                </div>

                <div class="text-center">
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full mx-auto flex items-center justify-center mb-6">
                        <i class="fas fa-user-shield text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold mb-4">Untuk Admin</h3>
                    <ul class="text-islamic-green-100 space-y-2">
                        <li><i class="fas fa-check mr-2"></i>Kelola sistem lengkap</li>
                        <li><i class="fas fa-check mr-2"></i>Master data management</li>
                        <li><i class="fas fa-check mr-2"></i>User access control</li>
                        <li><i class="fas fa-check mr-2"></i>System configuration</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Mengapa Memilih Koperasi Syariah Kami?</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-islamic-green-600 text-xl mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-800">Prinsip Syariah</h4>
                                <p class="text-gray-600">Semua transaksi mengikuti prinsip islam tanpa riba dan unsur bathil.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-islamic-green-600 text-xl mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-800">Transparent & Fair</h4>
                                <p class="text-gray-600">Sistem yang transparan dengan pembagian keuntungan yang adil.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-islamic-green-600 text-xl mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-800">Mudah Diakses</h4>
                                <p class="text-gray-600">Platform digital yang dapat diakses kapanpun dan dimanapun.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-islamic-green-600 text-xl mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-lg text-gray-800">Aman Terpercaya</h4>
                                <p class="text-gray-600">Keamanan data terjamin dan sistem yang telah teruji.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-xl p-8">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 bg-islamic-green-100 rounded-full mx-auto flex items-center justify-center mb-4">
                            <i class="fas fa-mosque text-islamic-green-600 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Informasi Koperasi</h3>
                    </div>
                    <div class="space-y-3 text-gray-600">
                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium">Nama Koperasi</span>
                            <span class="text-gray-800 text-right">{{ $koperasi->nama_koperasi }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium">No. Registrasi</span>
                            <span class="text-gray-800 text-right">{{ $koperasi->no_koperasi }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium">Alamat</span>
                            <span class="text-gray-800 text-right">{{ $koperasi->alamat }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
                            <span class="font-medium">Email</span>
                            <span class="text-gray-800 text-right">{{ $koperasi->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b">
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
    <section class="py-20 bg-islamic-green-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-4">Bergabung Bersama Kami</h2>
            <p class="text-xl text-islamic-green-100 mb-8 max-w-2xl mx-auto">
                Mari bersama membangun ekonomi umat yang kuat dan berdaya dengan prinsip syariah
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @guest
                <a href="{{ route('login') }}" class="bg-white text-islamic-green-700 px-8 py-4 rounded-lg hover:bg-islamic-green-50 transition duration-300 font-semibold text-lg shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk Sekarang
                </a>
                @endguest
                <a href="tel:+628123456789" class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-islamic-green-700 transition duration-300 font-semibold text-lg">
                    <i class="fas fa-phone mr-2"></i>Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-mosque text-islamic-green-500 text-xl mr-3"></i>
                        <span class="text-lg font-bold">{{ $koperasi->nama_koperasi ?? 'Koperasi Syariah Bersama' }}</span>
                    </div>
                    <p class="text-gray-400">
                        Sistem informasi koperasi syariah terpadu untuk kemajuan ekonomi umat.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-islamic-green-500">Simpanan</a></li>
                        <li><a href="#" class="hover:text-islamic-green-500">Pembiayaan</a></li>
                        <li><a href="#" class="hover:text-islamic-green-500">Angsuran</a></li>
                        <li><a href="#" class="hover:text-islamic-green-500">Laporan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Informasi</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-islamic-green-500">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-islamic-green-500">Visi & Misi</a></li>
                        <li><a href="#" class="hover:text-islamic-green-500">Pengurus</a></li>
                        <li><a href="{{ route('manual.landing') }}" class="hover:text-islamic-green-500">Manual</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-map-marker-alt mr-2"></i>{{ $koperasi->alamat ?? 'Jl. Cengkeh No. 789, Jakarta Pusat' }}</li>
                        <li><i class="fas fa-phone mr-2"></i>+62 812-3456-789</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@koperasi-syariah.com</li>
                        <li><i class="fas fa-clock mr-2"></i>Senin - Jumat: 08:00 - 17:00</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ $koperasi->nama_koperasi ?? 'Koperasi Syariah Bersama' }}. All rights reserved.</p>
                <p class="mt-2">Powered by Laravel & Tailwind CSS • Built with <i class="fas fa-heart text-red-500"></i> for Islamic Cooperative</p>
            </div>
        </div>
    </footer>
</body>
</html>
