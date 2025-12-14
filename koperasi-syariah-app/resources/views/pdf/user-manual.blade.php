<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 20px 30px 20px 30px;
            size: A4;
            orientation: portrait;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 14px;
            margin-top: 5px;
            opacity: 0.9;
        }

        .footer {
            background: #f8f9fa;
            padding: 15px 30px;
            text-align: center;
            border-top: 2px solid #059669;
            margin-top: 30px;
            border-radius: 0 0 10px 10px;
        }

        .content {
            padding: 0 20px;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            color: #059669;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #059669;
            padding-bottom: 5px;
        }

        .section-content {
            margin-bottom: 15px;
            text-align: justify;
        }

        .screenshot-container {
            margin: 20px 0;
            text-align: center;
            page-break-inside: avoid;
        }

        .screenshot {
            max-width: 100%;
            height: auto;
            border: 2px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 10px 0;
        }

        .screenshot-title {
            font-weight: bold;
            color: #059669;
            margin: 10px 0 5px 0;
            font-size: 14px;
        }

        .screenshot-description {
            font-style: italic;
            color: #666;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .steps {
            background: #f8f9fa;
            border-left: 4px solid #059669;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }

        .steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }

        .steps li {
            margin-bottom: 8px;
        }

        .info-box {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .warning-box {
            background: #fff3e0;
            border: 1px solid #ff9800;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .success-box {
            background: #e8f5e8;
            border: 1px solid #4caf50;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .contact-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .page-break {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }

        .toc {
            background: #f8f9fa;
            border: 2px solid #059669;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .toc h3 {
            color: #059669;
            margin-top: 0;
            text-align: center;
        }

        .toc ul {
            list-style-type: none;
            padding-left: 0;
        }

        .toc li {
            margin-bottom: 8px;
            padding-left: 20px;
        }

        .toc li:before {
            content: "▶";
            color: #059669;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">Versi {{ $version }} - {{ $date }}</div>
    </div>

    <div class="content">
        <!-- Table of Contents -->
        <div class="section">
            <div class="section-title">Daftar Isi</div>
            <div class="toc">
                <ul>
                    @foreach($sections as $index => $section)
                        <li>{{ $index + 1 }}. {{ $section['title'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Introduction Section -->
        <div class="section">
            <div class="section-title">1. Pendahuluan</div>
            <div class="section-content">
                Aplikasi Koperasi Syariah adalah sistem informasi modern yang dirancang untuk memudahkan anggota dalam mengelola simpanan, pembiayaan, dan transaksi keuangan lainnya dengan prinsip syariah.
            </div>

            <div class="info-box">
                <strong>Apa itu Koperasi Syariah?</strong><br>
                Koperasi Syariah adalah koperasi yang menjalankan kegiatannya berdasarkan prinsip-prinsip syariah Islam, dimana aktivitas usaha tidak mengandung unsur riba, maisir, dan gharar.
            </div>
        </div>

        <!-- Login Section with Screenshot -->
        <div class="section">
            <div class="section-title">2. Login dan Registrasi</div>
            <div class="section-content">
                Untuk menggunakan aplikasi, Anda perlu login dengan email dan password yang terdaftar. Jika belum memiliki akun, Anda dapat melakukan registrasi terlebih dahulu.
            </div>

            @if(isset($screenshots['login']) && !empty($screenshots['login']['base64']))
            <div class="screenshot-container">
                <div class="screenshot-title">{{ $screenshots['login']['title'] }}</div>
                <img src="{{ $screenshots['login']['base64'] }}" class="screenshot" alt="{{ $screenshots['login']['title'] }}">
                <div class="screenshot-description">{{ $screenshots['login']['description'] }}</div>
            </div>
            @endif

            <div class="steps">
                <strong>Langkah-langkah Login:</strong>
                <ol>
                    <li>Buka aplikasi di browser Anda</li>
                    <li>Masukkan email yang terdaftar</li>
                    <li>Masukkan password Anda</li>
                    <li>Klik tombol "Masuk"</li>
                    <li>Anda akan diarahkan ke dashboard sesuai role Anda</li>
                </ol>
            </div>

            <div class="warning-box">
                <strong>⚠️ Penting:</strong> Jangan bagikan informasi login Anda kepada orang lain. Logout setelah selesai menggunakan aplikasi.
            </div>
        </div>

        <!-- Dashboard Section with Screenshot -->
        <div class="section">
            <div class="section-title">3. Dashboard Anggota</div>
            <div class="section-content">
                Dashboard adalah halaman utama yang menampilkan ringkasan informasi keuangan Anda, termasuk total simpanan, status pembiayaan, dan transaksi terakhir.
            </div>

            @if(isset($screenshots['dashboard']) && !empty($screenshots['dashboard']['base64']))
            <div class="screenshot-container">
                <div class="screenshot-title">{{ $screenshots['dashboard']['title'] }}</div>
                <img src="{{ $screenshots['dashboard']['base64'] }}" class="screenshot" alt="{{ $screenshots['dashboard']['title'] }}">
                <div class="screenshot-description">{{ $screenshots['dashboard']['description'] }}</div>
            </div>
            @endif

            <div class="info-box">
                <strong>Fitur Dashboard:</strong>
                <ul>
                    <li>Total simpanan semua jenis</li>
                    <li>Status pembiayaan aktif</li>
                    <li>Transaksi terakhir</li>
                    <li>Quick action buttons</li>
                    <li>Notifikasi penting</li>
                </ul>
            </div>
        </div>

        <!-- Profile Section with Screenshot -->
        <div class="section">
            <div class="section-title">4. Manajemen Profil</div>
            <div class="section-content">
                Pada menu profil, Anda dapat mengubah data pribadi seperti nomor telepon, alamat, dan foto profil. Pastikan data selalu update untuk kemudahan komunikasi.
            </div>

            @if(isset($screenshots['profile']) && !empty($screenshots['profile']['base64']))
            <div class="screenshot-container">
                <div class="screenshot-title">{{ $screenshots['profile']['title'] }}</div>
                <img src="{{ $screenshots['profile']['base64'] }}" class="screenshot" alt="{{ $screenshots['profile']['title'] }}">
                <div class="screenshot-description">{{ $screenshots['profile']['description'] }}</div>
            </div>
            @endif

            <div class="steps">
                <strong>Mengubah Profil:</strong>
                <ol>
                    <li>Klik menu "Profil"</li>
                    <li>Ubah informasi yang diperlukan</li>
                    <li>Upload foto profil baru (opsional)</li>
                    <li>Klik "Simpan Perubahan"</li>
                </ol>
            </div>
        </div>

        <div class="page-break"></div>

        <!-- Simpanan Section with Screenshot -->
        <div class="section">
            <div class="section-title">5. Data Simpanan</div>
            <div class="section-content">
                Lihat detail semua jenis simpanan Anda (pokok, wajib, sukarela) dengan tampilan kartu yang mudah dibaca. Monitor pertumbuhan simpanan Anda secara real-time.
            </div>

            @if(isset($screenshots['simpanan']) && !empty($screenshots['simpanan']['base64']))
            <div class="screenshot-container">
                <div class="screenshot-title">{{ $screenshots['simpanan']['title'] }}</div>
                <img src="{{ $screenshots['simpanan']['base64'] }}" class="screenshot" alt="{{ $screenshots['simpanan']['title'] }}">
                <div class="screenshot-description">{{ $screenshots['simpanan']['description'] }}</div>
            </div>
            @endif

            <div class="success-box">
                <strong>💡 Tips:</strong> Klik pada kartu simpanan untuk melihat detail transaksi dan riwayat setoran.
            </div>
        </div>

        <!-- Transaksi Section with Screenshot -->
        <div class="section">
            <div class="section-title">6. Riwayat Transaksi</div>
            <div class="section-content">
                Akses riwayat lengkap semua transaksi simpanan dan pembiayaan. Setiap transaksi dilengkapi dengan kode unik dan bukti digital yang bisa diunduh.
            </div>

            @if(isset($screenshots['transaksi']) && !empty($screenshots['transaksi']['base64']))
            <div class="screenshot-container">
                <div class="screenshot-title">{{ $screenshots['transaksi']['title'] }}</div>
                <img src="{{ $screenshots['transaksi']['base64'] }}" class="screenshot" alt="{{ $screenshots['transaksi']['title'] }}">
                <div class="screenshot-description">{{ $screenshots['transaksi']['description'] }}</div>
            </div>
            @endif
        </div>

        <!-- Pembiayaan Section with Screenshot -->
        <div class="section">
            <div class="section-title">7. Manajemen Pembiayaan</div>
            <div class="section-content">
                Monitor status pembiayaan Anda, lihat jadwal angsuran, dan tracking pembayaran. Sistem akan mengingatkan Anda untuk pembayaran yang akan jatuh tempo.
            </div>

            @if(isset($screenshots['pembiayaan']) && !empty($screenshots['pembiayaan']['base64']))
            <div class="screenshot-container">
                <div class="screenshot-title">{{ $screenshots['pembiayaan']['title'] }}</div>
                <img src="{{ $screenshots['pembiayaan']['base64'] }}" class="screenshot" alt="{{ $screenshots['pembiayaan']['title'] }}">
                <div class="screenshot-description">{{ $screenshots['pembiayaan']['description'] }}</div>
            </div>
            @endif
        </div>

        <!-- Pengajuan Section with Screenshot -->
        <div class="section">
            <div class="section-title">8. Pengajuan Pembiayaan</div>
            <div class="section-content">
                Ajukan pembiayaan baru dengan mudah melalui form online. Upload dokumen yang diperlukan dan tracking status pengajuan secara real-time.
            </div>

            @if(isset($screenshots['pengajuan']) && !empty($screenshots['pengajuan']['base64']))
            <div class="screenshot-container">
                <div class="screenshot-title">{{ $screenshots['pengajuan']['title'] }}</div>
                <img src="{{ $screenshots['pengajuan']['base64'] }}" class="screenshot" alt="{{ $screenshots['pengajuan']['title'] }}">
                <div class="screenshot-description">{{ $screenshots['pengajuan']['description'] }}</div>
            </div>
            @endif

            <div class="steps">
                <strong>Proses Pengajuan:</strong>
                <ol>
                    <li>Pilih jenis pembiayaan</li>
                    <li>Isi form pengajuan lengkap</li>
                    <li>Upload dokumen pendukung</li>
                    <li>Submit pengajuan</li>
                    <li>Tracking status pengajuan</li>
                </ol>
            </div>
        </div>

        <div class="page-break"></div>

        <!-- Laporan Section with Screenshot -->
        <div class="section">
            <div class="section-title">9. Laporan Keuangan</div>
            <div class="section-content">
                Generate laporan keuangan pribadi kapan saja. Export data ke Excel untuk analisis lebih lanjut atau cetak bukti transaksi sebagai dokumen.
            </div>

            @if(isset($screenshots['laporan']) && !empty($screenshots['laporan']['base64']))
            <div class="screenshot-container">
                <div class="screenshot-title">{{ $screenshots['laporan']['title'] }}</div>
                <img src="{{ $screenshots['laporan']['base64'] }}" class="screenshot" alt="{{ $screenshots['laporan']['title'] }}">
                <div class="screenshot-description">{{ $screenshots['laporan']['description'] }}</div>
            </div>
            @endif
        </div>

        <!-- Mobile Section -->
        <div class="section">
            <div class="section-title">10. Aplikasi Mobile</div>
            <div class="section-content">
                Aplikasi sepenuhnya responsif dan dapat diakses dari perangkat mobile. Nikmati kemudahan mengakses data koperasi di mana saja dan kapan saja.
            </div>

            <div class="info-box">
                <strong>Keunggulan Mobile:</strong>
                <ul>
                    <li>Responsive design</li>
                    <li>Fast loading</li>
                    <li>Touch-friendly interface</li>
                    <li>Full functionality</li>
                </ul>
            </div>
        </div>

        <!-- Bantuan Section -->
        <div class="section">
            <div class="section-title">11. Bantuan dan Dukungan</div>
            <div class="section-content">
                Jika mengalami masalah, jangan ragu menghubungi tim support kami. Tersedia berbagai kanal bantuan untuk memastikan pengalaman Anda terbaik.
            </div>

            <div class="contact-info">
                <strong>Hubungi Kami:</strong><br>
                📞 WhatsApp: 0812-3456-7890<br>
                ✉️ Email: support@koperasi-syariah.com<br>
                🌐 Website: www.koperasi-syariah.com<br>
                📍 Alamat: Jl. Syariah No. 123, Jakarta
            </div>

            <div class="warning-box">
                <strong>⚠️ Jam Operasional:</strong><br>
                Senin - Jumat: 08:00 - 17:00<br>
                Sabtu: 08:00 - 12:00<br>
                Minggu & Hari Libur: Tutup
            </div>
        </div>

        <!-- Penutup -->
        <div class="section">
            <div class="section-title">Penutup</div>
            <div class="section-content">
                Terima kasih telah menggunakan Aplikasi Koperasi Syariah. Kami terus berkomitmen untuk memberikan layanan terbaik dan kemudahan dalam mengelola keuangan Anda sesuai prinsip syariah.
            </div>

            <div class="success-box">
                <strong>Visi Kami:</strong><br>
                Menjadi koperasi syariah terdepan yang memberdayakan ekonomi anggota dengan teknologi modern dan prinsip islami.
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>© {{ date('Y') }} Koperasi Syariah</strong></p>
        <p>Dokumen ini bersifat rahasia dan hanya untuk anggota terdaftar</p>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>