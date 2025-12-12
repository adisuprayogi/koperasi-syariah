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

        .anggota-highlight {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
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

        <!-- Anggota-Specific Sections -->
        @foreach($sections as $index => $section)
        <div class="section">
            <div class="section-title">{{ $index + 1 }}. {{ $section['title'] }}</div>
            <div class="section-content">
                {{ $section['content'] }}
            </div>

            <!-- Add role-specific content based on section ID -->
            @if($section['id'] === 'login')
            <div class="steps">
                <strong>📝 Alur Login Anggota:</strong>
                <ol>
                    <li>Buka browser dan akses URL aplikasi Koperasi Syariah</li>
                    <li>Pada halaman login, masukkan email anggota</li>
                    <li>Masukkan password yang telah Anda buat</li>
                    <li>Klik tombol "Masuk" untuk mengakses dashboard</li>
                    <li>System akan otomatis mengarahkan Anda ke dashboard anggota</li>
                </ol>
            </div>
            <div class="warning-box">
                <strong>⚠️ Keamanan Akun:</strong><br>
                • Gunakan password yang kuat (minimal 8 karakter)<br>
                • Jangan bagikan informasi login kepada orang lain<br>
                • Logout setelah selesai menggunakan aplikasi<br>
                • Ganti password secara berkala
            </div>
            @endif

            @if($section['id'] === 'dashboard' && isset($screenshots['dashboard']) && !empty($screenshots['dashboard']['base64']))
            <div class="screenshot-container">
                <div class="screenshot-title">{{ $screenshots['dashboard']['title'] }}</div>
                <img src="{{ $screenshots['dashboard']['base64'] }}" class="screenshot" alt="{{ $screenshots['dashboard']['title'] }}">
                <div class="screenshot-description">{{ $screenshots['dashboard']['description'] }}</div>
            </div>
            <div class="anggota-highlight">
                <strong>📊 Fitur Dashboard Anggota:</strong><br>
                • Total simpanan semua jenis (pokok, wajib, sukarela)<br>
                • Status pembiayaan aktif dan jadwal angsuran<br>
                • Quick access untuk pengajuan pembiayaan baru<br>
                • Notifikasi transaksi terakhir dan reminder pembayaran
            </div>
            <div class="steps">
                <strong>🎯 Cara Menggunakan Dashboard:</strong>
                <ol>
                    <li>Periksa total simpanan di ringkasan keuangan</li>
                    <li>Monitor status pembiayaan aktif</li>
                    <li>Klik "Ajukan Pembiayaan" untuk pengajuan baru</li>
                    <li>View transaksi terakhir di activity feed</li>
                </ol>
            </div>
            @endif

            @if($section['id'] === 'profil')
            <div class="steps">
                <strong>👤 Mengelola Profil Anggota:</strong>
                <ol>
                    <li>Klik menu "Profil" di sidebar navigasi</li>
                    <li>Edit informasi pribadi (nama, email, telepon)</li>
                    <li>Update alamat lengkap dan data kontak</li>
                    <li>Upload foto profil baru (opsional)</li>
                    <li>Klik "Simpan Perubahan" untuk update data</li>
                    <li>Verifikasi perubahan melalui email konfirmasi</li>
                </ol>
            </div>
            @endif

            @if($section['id'] === 'simpanan')
            <div class="steps">
                <strong>💰 Mengelola Simpanan:</strong>
                <ol>
                    <li>Akses menu "Simpanan" dari dashboard</li>
                    <li>Lihat detail saldo untuk setiap jenis simpanan</li>
                    <li>Klik kartu simpanan untuk melihat transaksi detail</li>
                    <li>Download bukti transaksi sebagai PDF</li>
                    <li>Monitor pertumbuhan simpanan secara real-time</li>
                </ol>
            </div>
            <div class="success-box">
                <strong>🎯 Jenis Simpanan:</strong><br>
                • <strong>Simpanan Pokok:</strong> Modal awal keanggotaan (Rp 100.000)<br>
                • <strong>Simpanan Wajib:</strong> Setoran bulanan wajib (Rp 50.000)<br>
                • <strong>Simpanan Sukarela:</strong> Setoran fleksibel kapan saja<br>
                • <strong>Bunga Syariah:</strong> Bagi hasil kompetitif perbulan
            </div>
            @endif

            @if($section['id'] === 'pembiayaan')
            <div class="steps">
                <strong>💸 Alur Pembiayaan Anggota:</strong>
                <ol>
                    <li>Klik "Ajukan Pembiayaan" dari dashboard</li>
                    <li>Pilih jenis pembiayaan (produktif/konsumtif)</li>
                    <li>Isi form aplikasi dengan data lengkap</li>
                    <li>Upload dokumen pendukung (KTP, slip gaji, dll)</li>
                    <li>Review aplikasi dan submit untuk persetujuan</li>
                    <li>Monitor status pengajuan di dashboard</li>
                    <li>Terima notifikasi ketika disetujui</li>
                </ol>
            </div>
            <div class="info-box">
                <strong>📋 Dokumen yang Diperlukan:</strong><br>
                • Fotokopi KTP yang masih berlaku<br>
                • Fotokopi KK (Kartu Keluarga)<br>
                • Slip gaji/Surat Keterangan Penghasilan<br>
                • Surat Keterangan Usaha (untuk pembiayaan produktif)<br>
                • Fotokopi Buku Tabungan 3 bulan terakhir
            </div>
            @endif

            @if($section['id'] === 'transaksi')
            <div class="steps">
                <strong>📜 Melihat Riwayat Transaksi:</strong>
                <ol>
                    <li>Akses menu "Riwayat Transaksi"</li>
                    <li>Filter transaksi berdasarkan tanggal dan jenis</li>
                    <li>Klik detail transaksi untuk informasi lengkap</li>
                    <li>Download bukti transaksi sebagai PDF</li>
                    <li>Export data ke Excel untuk analisis pribadi</li>
                </ol>
            </div>
            <div class="anggota-highlight">
                <strong>🔍 Jenis Transaksi yang Tercatat:</strong><br>
                • Setoran simpanan (tunai/transfer)<br>
                • Penarikan simpanan<br>
                • Pembayaran angsuran pembiayaan<br>
                • Pencairan dana pembiayaan<br>
                • Biaya administrasi dan denda
            </div>
            @endif
        </div>

        @if($loop->iteration % 3 == 0)
        <div class="page-break"></div>
        @endif
        @endforeach

        <!-- Contact Section -->
        <div class="section">
            <div class="section-title">Hubungi Kami</div>
            <div class="contact-info">
                <strong>Butuh Bantuan? Kami Siap Membantu!</strong><br><br>
                📞 <strong>WhatsApp:</strong> 0812-3456-7890<br>
                ✉️ <strong>Email:</strong> support@koperasi-syariah.com<br>
                🌐 <strong>Website:</strong> www.koperasi-syariah.com<br>
                📍 <strong>Alamat:</strong> Jl. Syariah No. 123, Jakarta<br><br>
                <strong>⏰ Jam Operasional:</strong><br>
                Senin - Jumat: 08:00 - 17:00<br>
                Sabtu: 08:00 - 12:00
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>© {{ date('Y') }} Koperasi Syariah</strong></p>
        <p>Dokumen ini khusus untuk anggota terdaftar</p>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>