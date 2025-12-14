<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DocumentationController extends Controller
{
    /**
     * Generate User Manual PDF
     */
    public function generateUserManualPDF()
    {
        $data = [
            'title' => 'Panduan Pengguna Aplikasi Koperasi Syariah',
            'version' => '1.0.0',
            'date' => Carbon::now()->format('d F Y'),
            'screenshots' => $this->getScreenshotsList(),
            'sections' => $this->getManualSections()
        ];

        $pdf = PDF::loadView('pdf.user-manual', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'enable_php' => true,
                'fontDir' => public_path('fonts'),
                'chroot' => realpath(public_path()),
            ]);

        $filename = 'User_Manual_Koperasi_Syariah_' . date('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate User Manual PDF preview
     */
    public function previewUserManualPDF()
    {
        $data = [
            'title' => 'Panduan Pengguna Aplikasi Koperasi Syariah',
            'version' => '1.0.0',
            'date' => Carbon::now()->format('d F Y'),
            'screenshots' => $this->getScreenshotsList(),
            'sections' => $this->getManualSections()
        ];

        $pdf = PDF::loadView('pdf.user-manual', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'enable_php' => true,
                'chroot' => realpath(public_path()),
            ]);

        return $pdf->stream('User_Manual_Koperasi_Syariah_Preview.pdf');
    }

    /**
     * Get list of screenshots with descriptions
     */
    private function getScreenshotsList()
    {
        $screenshots = [
            'login' => [
                'title' => 'Halaman Login',
                'description' => 'Halaman login untuk masuk ke aplikasi Koperasi Syariah',
                'path' => public_path('login-page.png')
            ],
            'dashboard' => [
                'title' => 'Dashboard Anggota',
                'description' => 'Dashboard utama anggota dengan ringkasan informasi',
                'path' => public_path('dashboard.png')
            ],
            'profile' => [
                'title' => 'Profil Pengguna',
                'description' => 'Halaman profil untuk mengubah data pribadi',
                'path' => public_path('profile.png')
            ],
            'simpanan' => [
                'title' => 'Data Simpanan',
                'description' => 'Halaman data simpanan dengan tampilan kartu',
                'path' => public_path('simpanan.png')
            ],
            'transaksi' => [
                'title' => 'Transaksi Simpanan',
                'description' => 'Detail transaksi simpanan dan pembayaran',
                'path' => public_path('transaksi-simpanan.png')
            ],
            'pembiayaan' => [
                'title' => 'Data Pembiayaan',
                'description' => 'Status pembiayaan dan angsuran',
                'path' => public_path('pembiayaan.png')
            ],
            'laporan' => [
                'title' => 'Laporan Keuangan',
                'description' => 'Laporan keuangan pribadi anggota',
                'path' => public_path('laporan.png')
            ],
            'pengajuan' => [
                'title' => 'Pengajuan Pembiayaan',
                'description' => 'Form pengajuan pembiayaan baru',
                'path' => public_path('pengajuan-pembiayaan.png')
            ]
        ];

        // Convert images to base64 for PDF embedding
        foreach ($screenshots as $key => $screenshot) {
            if (file_exists($screenshot['path'])) {
                $imageData = file_get_contents($screenshot['path']);
                $screenshots[$key]['base64'] = 'data:image/png;base64,' . base64_encode($imageData);
            } else {
                $screenshots[$key]['base64'] = null;
            }
        }

        return $screenshots;
    }

    /**
     * Get manual sections structure
     */
    private function getManualSections()
    {
        return [
            [
                'id' => 'pendahuluan',
                'title' => 'Pendahuluan',
                'icon' => 'fas fa-book-open',
                'content' => 'Aplikasi Koperasi Syariah adalah sistem informasi modern yang dirancang untuk memudahkan anggota dalam mengelola simpanan, pembiayaan, dan transaksi keuangan lainnya dengan prinsip syariah.'
            ],
            [
                'id' => 'login',
                'title' => 'Login dan Registrasi',
                'icon' => 'fas fa-sign-in-alt',
                'content' => 'Untuk menggunakan aplikasi, Anda perlu login dengan email dan password yang terdaftar. Jika belum memiliki akun, Anda dapat melakukan registrasi terlebih dahulu.'
            ],
            [
                'id' => 'dashboard',
                'title' => 'Dashboard Anggota',
                'icon' => 'fas fa-tachometer-alt',
                'content' => 'Dashboard adalah halaman utama yang menampilkan ringkasan informasi keuangan Anda, termasuk total simpanan, status pembiayaan, dan transaksi terakhir.'
            ],
            [
                'id' => 'profil',
                'title' => 'Manajemen Profil',
                'icon' => 'fas fa-user',
                'content' => 'Pada menu profil, Anda dapat mengubah data pribadi seperti nomor telepon, alamat, dan foto profil. Pastikan data selalu update untuk kemudahan komunikasi.'
            ],
            [
                'id' => 'simpanan',
                'title' => 'Data Simpanan',
                'icon' => 'fas fa-piggy-bank',
                'content' => 'Lihat detail semua jenis simpanan Anda (pokok, wajib, sukarela) dengan tampilan kartu yang mudah dibaca. Monitor pertumbuhan simpanan Anda secara real-time.'
            ],
            [
                'id' => 'transaksi',
                'title' => 'Riwayat Transaksi',
                'icon' => 'fas fa-history',
                'content' => 'Akses riwayat lengkap semua transaksi simpanan dan pembiayaan. Setiap transaksi dilengkapi dengan kode unik dan bukti digital yang bisa diunduh.'
            ],
            [
                'id' => 'pembiayaan',
                'title' => 'Manajemen Pembiayaan',
                'icon' => 'fas fa-hand-holding-usd',
                'content' => 'Monitor status pembiayaan Anda, lihat jadwal angsuran, dan tracking pembayaran. Sistem akan mengingatkan Anda untuk pembayaran yang akan jatuh tempo.'
            ],
            [
                'id' => 'pengajuan',
                'title' => 'Pengajuan Pembiayaan',
                'icon' => 'fas fa-plus-circle',
                'content' => 'Ajukan pembiayaan baru dengan mudah melalui form online. Upload dokumen yang diperlukan dan tracking status pengajuan secara real-time.'
            ],
            [
                'id' => 'laporan',
                'title' => 'Laporan Keuangan',
                'icon' => 'fas fa-chart-line',
                'content' => 'Generate laporan keuangan pribadi kapan saja. Export data ke Excel untuk analisis lebih lanjut atau cetak bukti transaksi sebagai dokumen.'
            ],
            [
                'id' => 'mobile',
                'title' => 'Aplikasi Mobile',
                'icon' => 'fas fa-mobile-alt',
                'content' => 'Aplikasi sepenuhnya responsif dan dapat diakses dari perangkat mobile. Nikmati kemudahan mengakses data koperasi di mana saja dan kapan saja.'
            ],
            [
                'id' => 'bantuan',
                'title' => 'Bantuan dan Dukungan',
                'icon' => 'fas fa-headset',
                'content' => 'Jika mengalami masalah, jangan ragu menghubungi tim support kami. Tersedia berbagai kanal bantuan untuk memastikan pengalaman Anda terbaik.'
            ]
        ];
    }

    /**
     * Generate User Manual PDF for Anggota
     */
    public function generateUserManualAnggotaPDF()
    {
        $data = [
            'title' => 'Panduan Pengguna Anggota Koperasi Syariah',
            'version' => '1.0.0',
            'date' => Carbon::now()->format('d F Y'),
            'screenshots' => $this->getScreenshotsList(),
            'sections' => $this->getAnggotaManualSections()
        ];

        $pdf = PDF::loadView('pdf.user-manual-anggota', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'enable_php' => true,
                'fontDir' => public_path('fonts'),
                'chroot' => realpath(public_path()),
            ]);

        $filename = 'User_Manual_Anggota_Koperasi_Syariah_' . date('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate User Manual PDF for Admin
     */
    public function generateUserManualAdminPDF()
    {
        $data = [
            'title' => 'Panduan Pengguna Admin Koperasi Syariah',
            'version' => '1.0.0',
            'date' => Carbon::now()->format('d F Y'),
            'screenshots' => $this->getAdminScreenshotsList(),
            'sections' => $this->getAdminManualSections()
        ];

        $pdf = PDF::loadView('pdf.user-manual-admin', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'enable_php' => true,
                'fontDir' => public_path('fonts'),
                'chroot' => realpath(public_path()),
            ]);

        $filename = 'User_Manual_Admin_Koperasi_Syariah_' . date('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate User Manual PDF for Pengurus
     */
    public function generateUserManualPengurusPDF()
    {
        $data = [
            'title' => 'Panduan Pengguna Pengurus Koperasi Syariah',
            'version' => '1.0.0',
            'date' => Carbon::now()->format('d F Y'),
            'screenshots' => $this->getPengurusScreenshotsList(),
            'sections' => $this->getPengurusManualSections()
        ];

        $pdf = PDF::loadView('pdf.user-manual-pengurus', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'enable_php' => true,
                'fontDir' => public_path('fonts'),
                'chroot' => realpath(public_path()),
            ]);

        $filename = 'User_Manual_Pengurus_Koperasi_Syariah_' . date('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get manual sections for Anggota
     */
    private function getAnggotaManualSections()
    {
        return [
            [
                'id' => 'pendahuluan',
                'title' => 'Pendahuluan',
                'icon' => 'fas fa-book-open',
                'content' => 'Selamat datang di Koperasi Syariah! Panduan ini akan membantu Anda sebagai anggota dalam menggunakan aplikasi untuk mengelola simpanan, pembiayaan, dan transaksi keuangan dengan prinsip syariah.'
            ],
            [
                'id' => 'login',
                'title' => 'Login dan Keamanan',
                'icon' => 'fas fa-sign-in-alt',
                'content' => 'Pelajari cara login aman, mengubah password, dan menjaga keamanan akun Anda. Gunakan password yang kuat dan jangan bagikan informasi login kepada orang lain.'
            ],
            [
                'id' => 'dashboard',
                'title' => 'Dashboard Anggota',
                'icon' => 'fas fa-tachometer-alt',
                'content' => 'Dashboard personal Anda menampilkan total simpanan, status pembiayaan, transaksi terakhir, dan notifikasi penting. Monitor kesehatan finansial Anda dengan mudah.'
            ],
            [
                'id' => 'profil',
                'title' => 'Manajemen Profil',
                'icon' => 'fas fa-user',
                'content' => 'Update data pribadi, nomor telepon, alamat, dan foto profil. Pastikan data selalu akurat untuk kemudahan komunikasi dan verifikasi transaksi.'
            ],
            [
                'id' => 'simpanan',
                'title' => 'Simpanan dan Investasi',
                'icon' => 'fas fa-piggy-bank',
                'content' => 'Kelola simpanan pokok, wajib, dan sukarela Anda. Lihat pertumbuhan simpanan real-time dan cetak bukti transaksi sebagai dokumen pribadi.'
            ],
            [
                'id' => 'pembiayaan',
                'title' => 'Pembiayaan dan Angsuran',
                'icon' => 'fas fa-hand-holding-usd',
                'content' => 'Ajukan pembiayaan mudah dan murah. Monitor jadwal angsuran, tracking pembayaran, dan lihat riwayat pembiayaan lengkap dengan transparansi penuh.'
            ],
            [
                'id' => 'transaksi',
                'title' => 'Riwayat Transaksi',
                'icon' => 'fas fa-history',
                'content' => 'Akses lengkap semua transaksi keuangan Anda. Setiap transaksi memiliki kode unik dan bukti digital yang dapat diunduh untuk arsip pribadi.'
            ],
            [
                'id' => 'mobile',
                'title' => 'Akses Mobile',
                'icon' => 'fas fa-mobile-alt',
                'content' => 'Aplikasi fully responsive untuk akses di mana saja. Nikmati kemudahan mengelola keuangan koperasi langsung dari smartphone Anda.'
            ],
            [
                'id' => 'bantuan',
                'title' => 'Bantuan dan Support',
                'icon' => 'fas fa-headset',
                'content' => 'Tim support siap membantu Anda. Hubungi kami via WhatsApp, email, atau datang langsung ke kantor koperasi untuk bantuan personal.'
            ]
        ];
    }

    /**
     * Get manual sections for Admin
     */
    private function getAdminManualSections()
    {
        return [
            [
                'id' => 'pendahuluan',
                'title' => 'Pendahuluan Admin',
                'icon' => 'fas fa-shield-alt',
                'content' => 'Panduan khusus Administrator Sistem. Anda memiliki akses penuh untuk mengelola seluruh aspek sistem Koperasi Syariah dengan tanggung jawab keamanan dan integritas data.'
            ],
            [
                'id' => 'dashboard-admin',
                'title' => 'Dashboard Administrator',
                'icon' => 'fas fa-cogs',
                'content' => 'Monitor kesehatan sistem: statistik pengguna, transaksi hari ini, performa server, dan alert keamanan. Real-time monitoring untuk seluruh operasional koperasi.'
            ],
            [
                'id' => 'user-management',
                'title' => 'Manajemen Pengguna',
                'icon' => 'fas fa-users',
                'content' => 'Kelola semua akun: create, edit, suspend, dan delete users. Atur role-based access control dan monitor aktivitas mencurigakan untuk keamanan sistem.'
            ],
            [
                'id' => 'system-config',
                'title' => 'Konfigurasi Sistem',
                'icon' => 'fas fa-sliders-h',
                'content' => 'Setup bunga simpanan, biaya admin, limit transaksi, dan parameter sistem. Konfigurasi workflow approval dan integrasi dengan payment gateway.'
            ],
            [
                'id' => 'data-management',
                'title' => 'Manajemen Data',
                'icon' => 'fas fa-database',
                'content' => 'Backup database, restore data, dan migrasi data. Monitor storage usage dan implementasi data retention policy untuk compliance keuangan.'
            ],
            [
                'id' => 'security',
                'title' => 'Keamanan Sistem',
                'icon' => 'fas fa-lock',
                'content' => 'Manage user permissions, audit trails, dan security logs. Implementasi 2FA, IP whitelisting, dan monitor akses tidak sah untuk proteksi maksimal.'
            ],
            [
                'id' => 'reports',
                'title' => 'Laporan Sistem',
                'icon' => 'fas fa-chart-bar',
                'content' => 'Generate comprehensive reports: user activity, system performance, error logs, dan compliance reports. Export data untuk audit dan analisis.'
            ],
            [
                'id' => 'integration',
                'title' => 'Integrasi API',
                'icon' => 'fas fa-plug',
                'content' => 'Manage API keys, rate limiting, dan third-party integrations. Monitor API usage dan troubleshoot connection issues dengan external services.'
            ],
            [
                'id' => 'maintenance',
                'title' => 'Maintenance Sistem',
                'icon' => 'fas fa-tools',
                'content' => 'Schedule maintenance windows, system updates, dan performance tuning. Troubleshoot common issues dan implement disaster recovery procedures.'
            ]
        ];
    }

    /**
     * Get manual sections for Pengurus
     */
    private function getPengurusManualSections()
    {
        return [
            [
                'id' => 'pendahuluan',
                'title' => 'Pendahuluan Pengurus',
                'icon' => 'fas fa-user-tie',
                'content' => 'Panduan untuk Pengurus Koperasi. Anda bertanggung jawab mengelola operasional harian, menyetujui transaksi, dan memberikan layanan terbaik kepada anggota.'
            ],
            [
                'id' => 'dashboard-pengurus',
                'title' => 'Dashboard Pengurus',
                'icon' => 'fas fa-briefcase',
                'content' => 'Overview operasional: pending approvals, transaksi hari ini, jumlah anggota aktif, dan pending tasks. Quick access untuk menu frequently used.'
            ],
            [
                'id' => 'member-management',
                'title' => 'Manajemen Anggota',
                'icon' => 'fas fa-user-friends',
                'content' => 'Registrasi anggota baru, verifikasi dokumen, update data anggota, dan manage membership status. Generate member ID cards dan certificates.'
            ],
            [
                'id' => 'transaction-approval',
                'title' => 'Approval Transaksi',
                'icon' => 'fas fa-check-circle',
                'content' => 'Review dan approve pengajuan pembiayaan, penarikan simpanan besar, dan transaksi mencurigakan. Set workflow approval limits dan escalation rules.'
            ],
            [
                'id' => 'simpanan-operations',
                'title' => 'Operasional Simpanan',
                'icon' => 'fas fa-coins',
                'content' => 'Process setoran simpanan, hitung bunga, manage jurnal simpanan, dan rekonsiliasi bank. Generate laporan simpanan harian dan bulanan.'
            ],
            [
                'id' => 'pembiayaan-management',
                'title' => 'Manajemen Pembiayaan',
                'icon' => 'fas fa-hand-holding-usd',
                'content' => 'Review aplikasi pembiayaan, assess kelayakan, set persetujuan, dan monitor repayment. Manage collection activities dan bad debt handling.'
            ],
            [
                'id' => 'financial-reports',
                'title' => 'Laporan Keuangan',
                'icon' => 'fas fa-balance-scale',
                'content' => 'Generate laporan laba rugi, neraca, arus kas, dan rasio keuangan. Prepare reports untuk rapat pengurus dan regulatory compliance.'
            ],
            [
                'id' => 'compliance',
                'title' => 'Compliance dan Audit',
                'icon' => 'fas fa-clipboard-check',
                'content' => 'Ensure compliance dengan peraturan koperasi dan syariah. Prepare untuk internal dan external audits. Manage document retention.'
            ],
            [
                'id' => 'member-services',
                'title' => 'Layanan Anggota',
                'icon' => 'fas fa-hands-helping',
                'content' => 'Handle member complaints, process special requests, dan provide financial counseling. Organize member education events dan social programs.'
            ]
        ];
    }

    /**
     * Get admin-specific screenshots
     */
    private function getAdminScreenshotsList()
    {
        $screenshots = [
            'dashboard' => [
                'title' => 'Dashboard Administrator',
                'description' => 'Dashboard admin dengan monitoring sistem, statistik pengguna, dan security alerts',
                'path' => public_path('admin-dashboard.png')
            ],
            'user-management' => [
                'title' => 'User Management',
                'description' => 'Halaman management pengguna dengan CRUD operations dan role-based access control',
                'path' => public_path('admin-user-management.png')
            ],
            'system-config' => [
                'title' => 'System Configuration',
                'description' => 'Konfigurasi sistem, payment gateway, dan parameter operasional',
                'path' => public_path('admin-system-config.png')
            ],
            'security' => [
                'title' => 'Security Settings',
                'description' => 'Pengaturan keamanan, audit trails, dan monitoring aktivitas sistem',
                'path' => public_path('admin-security.png')
            ]
        ];

        // Convert images to base64 for PDF embedding
        foreach ($screenshots as $key => $screenshot) {
            if (file_exists($screenshot['path'])) {
                $imageData = file_get_contents($screenshot['path']);
                $screenshots[$key]['base64'] = 'data:image/png;base64,' . base64_encode($imageData);
            } else {
                $screenshots[$key]['base64'] = null;
            }
        }

        return $screenshots;
    }

    /**
     * Get pengurus-specific screenshots
     */
    private function getPengurusScreenshotsList()
    {
        $screenshots = [
            'dashboard' => [
                'title' => 'Dashboard Pengurus',
                'description' => 'Dashboard Pengurus dengan overview operasional dan pending approvals',
                'path' => public_path('pengurus-dashboard.png')
            ],
            'anggota-list' => [
                'title' => 'Daftar Anggota',
                'description' => 'Halaman daftar anggota dengan fitur search, filter, dan bulk operations',
                'path' => public_path('pengurus-anggota-list.png')
            ],
            'simpanan-transaksi' => [
                'title' => 'Transaksi Simpanan',
                'description' => 'Form proses transaksi simpanan dengan validasi otomatis',
                'path' => public_path('pengurus-simpanan-transaksi.png')
            ],
            'approval-pembiayaan' => [
                'title' => 'Approval Pembiayaan',
                'description' => 'Halaman approval pengajuan pembiayaan dengan assessment matrix',
                'path' => public_path('pengurus-approval-pembiayaan.png')
            ],
            'laporan-keuangan' => [
                'title' => 'Laporan Keuangan',
                'description' => 'Generate laporan keuangan lengkap dengan export capabilities',
                'path' => public_path('pengurus-laporan-keuangan.png')
            ]
        ];

        // Convert images to base64 for PDF embedding
        foreach ($screenshots as $key => $screenshot) {
            if (file_exists($screenshot['path'])) {
                $imageData = file_get_contents($screenshot['path']);
                $screenshots[$key]['base64'] = 'data:image/png;base64,' . base64_encode($imageData);
            } else {
                $screenshots[$key]['base64'] = null;
            }
        }

        return $screenshots;
    }

    /**
     * Upload screenshot
     */
    public function uploadScreenshot(Request $request)
    {
        $request->validate([
            'screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string'
        ]);

        $file = $request->file('screenshot');
        $name = $request->name;

        $path = $file->storeAs('screenshots', $name . '.png', 'public');

        return response()->json([
            'success' => true,
            'message' => 'Screenshot berhasil diupload',
            'path' => $path
        ]);
    }
}