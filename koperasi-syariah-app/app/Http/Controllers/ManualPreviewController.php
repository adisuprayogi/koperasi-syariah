<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManualPreviewController extends Controller
{
    /**
     * Display manual preview page
     */
    public function index()
    {
        return view('manual-preview.index');
    }

    /**
     * Get manual data by role
     */
    public function getManualData($role)
    {
        $manuals = [
            'anggota' => [
                'title' => 'Panduan Penggunaan Anggota',
                'subtitle' => 'Koperasi Syariah Bersama - Aplikasi Digital Keuangan Syariah',
                'color' => '#059669',
                'icon' => 'fas fa-users',
                'description' => 'Panduan lengkap untuk anggota Koperasi Syariah Bersama dalam menggunakan aplikasi digital keuangan syariah',
                'appInfo' => [
                    'name' => 'Koperasi Syariah Bersama',
                    'version' => '1.0.0',
                    'url' => 'http://127.0.0.1:8010',
                    'logo' => 'http://127.0.0.1:8010/storage/koperasi/logo/1765257692_logo_ksa_ad-zikra.png'
                ],
                'sections' => [
                    [
                        'id' => 'login',
                        'title' => '1. Login Anggota',
                        'description' => 'Cara masuk ke aplikasi menggunakan nomor anggota',
                        'icon' => 'fas fa-sign-in-alt',
                        'screenshots' => ['/screenshots/login-page.png'],
                        'steps' => [
                            'Buka browser dan akses: http://127.0.0.1:8010',
                            'Klik tombol "Log in" di pojok kanan atas',
                            'Masukkan nomor anggota yang terdaftar',
                            'Masukkan password yang telah dibuat',
                            'Klik tombol "Masuk ke Akun"',
                            'System akan redirect ke dashboard anggota'
                        ],
                        'tips' => [
                            'Pastikan nomor anggota valid dan terdaftar',
                            'Gunakan password yang kuat dan mudah diingat',
                            'Jaga kerahasiaan password Anda',
                            'Hubungi admin jika lupa password',
                            'Logout setelah selesai menggunakan aplikasi'
                        ],
                        'features' => [
                            'Secure login authentication',
                            'Password encryption',
                            'Session management',
                            'Auto-logout untuk keamanan',
                            'Role-based access control'
                        ]
                    ],
                    [
                        'id' => 'dashboard',
                        'title' => '2. Dashboard Anggota',
                        'description' => 'Halaman utama dengan ringkasan informasi keuangan dan quick actions',
                        'icon' => 'fas fa-tachometer-alt',
                        'screenshots' => ['/screenshots/dashboard.png'],
                        'steps' => [
                            'View informasi profil anggota di bagian atas',
                            'Monitor total simpanan dari semua jenis simpanan',
                            'Track status pembiayaan yang sedang aktif',
                            'Access quick actions untuk pengajuan cepat',
                            'View jadwal angsuran yang akan datang',
                            'Monitor notifikasi dan aktivitas terbaru',
                            'Navigate ke menu lain melalui sidebar kiri'
                        ],
                        'tips' => [
                            'Dashboard memberikan overview cepat keuangan',
                            'Perhatikan notifikasi penting',
                            'Gunakan quick actions untuk kemudahan',
                            'Monitor jadwal angsuran secara rutin',
                            'Check dashboard untuk update terkini'
                        ],
                        'features' => [
                            'Real-time balance updates',
                            'Interactive charts dan visualisasi',
                            'Quick action buttons',
                            'Notifikasi system',
                            'Responsive dashboard layout'
                        ]
                    ],
                    [
                        'id' => 'profile',
                        'title' => '3. Profil Saya',
                        'description' => 'Kelola data pribadi, informasi kontak, dan pengaturan akun anggota',
                        'icon' => 'fas fa-user',
                        'screenshots' => ['/screenshots/profile.png'],
                        'steps' => [
                            'Klik menu "Profil Saya" di sidebar kiri',
                            'View data pribadi yang terdaftar (nama, email, telepon)',
                            'Edit informasi pribadi jika ada perubahan',
                            'Update alamat lengkap dan data kontak',
                            'Upload foto profil baru melalui file upload',
                            'Klik tombol "Simpan Perubahan" untuk update data',
                            'Download kartu anggota jika tersedia'
                        ],
                        'tips' => [
                            'Pastikan data selalu update untuk komunikasi',
                            'Foto profil yang jelas membantu identifikasi',
                            'Gunakan email aktif untuk verifikasi',
                            'Simpan perubahan sebelum meninggalkan halaman',
                            'Data penting harus valid dan sesuai'
                        ],
                        'features' => [
                            'Real-time data update',
                            'File upload untuk foto profil',
                            'Form validation system',
                            'Download kartu anggota PDF',
                            'Contact information management'
                        ]
                    ],
                    [
                        'id' => 'simpanan-saya',
                        'title' => '4. Simpanan Saya',
                        'description' => 'Monitor semua jenis simpanan dengan detail saldo dan history transaksi',
                        'icon' => 'fas fa-piggy-bank',
                        'screenshots' => ['/screenshots/simpanan.png'],
                        'steps' => [
                            'Klik menu "Simpanan Saya" dari sidebar',
                            'View card-based layout untuk setiap jenis simpanan',
                            'Monitor saldo: Simpanan Pokok, Wajib, Sukarela',
                            'Klik kartu simpanan untuk lihat history detail',
                            'Track pertumbuhan simpanan dengan visualisasi',
                            'Download bukti transaksi sebagai PDF',
                            'View informasi bagi hasil syariah'
                        ],
                        'tips' => [
                            'Monitor reguler saldo simpanan',
                            'Download bukti transaksi untuk arsip',
                            'Perhatikan informasi bagi hasil',
                            'Track pertumbuhan dari waktu ke waktu',
                            'Hubungi pengurus untuk informasi'
                        ],
                        'features' => [
                            'Real-time balance tracking',
                            'Interactive transaction history',
                            'PDF receipt generation',
                            'Visual growth charts',
                            'Syariah-compliant profit sharing'
                        ]
                    ],
                    [
                        'id' => 'ajukan-pembiayaan',
                        'title' => '5. Ajukan Pembiayaan',
                        'description' => 'Form pengajuan pembiayaan syariah dengan document upload',
                        'icon' => 'fas fa-plus',
                        'screenshots' => ['/screenshots/pengajuan-pembiayaan.png'],
                        'steps' => [
                            'Klik menu "Ajukan Pembiayaan" dari sidebar',
                            'Pilih jenis pembiayaan yang diinginkan',
                            'Input jumlah pembiayaan yang diajukan',
                            'Pilih tenor pembiayaan (jangka waktu)',
                            'Upload dokumen persyaratan (KTP, slip gaji, dll)',
                            'Isi form informasi tambahan yang diperlukan',
                            'Review pengajuan sebelum submit',
                            'Klik "Ajukan Pembiayaan" untuk submit'
                        ],
                        'tips' => [
                            'Persiapkan dokumen sebelum memulai',
                            'Pastikan dokumen clear dan readable',
                            'Isi data dengan lengkap dan benar',
                            'Pilih tenor yang sesuai kemampuan',
                            'Review sebelum submit untuk hindari error'
                        ],
                        'features' => [
                            'Multi-type financing options',
                            'Document upload system',
                            'Real-time validation',
                            'Application status tracking',
                            'Syariah-compliant products'
                        ]
                    ],
                    [
                        'id' => 'pengajuan-saya',
                        'title' => '6. Pengajuan Saya',
                        'description' => 'Monitor status pengajuan pembiayaan dan tracking proses approval',
                        'icon' => 'fas fa-file-invoice',
                        'screenshots' => ['/screenshots/pengajuan-pembiayaan.png'],
                        'steps' => [
                            'Klik menu "Pengajuan Saya" dari sidebar',
                            'View list semua pengajuan yang telah dibuat',
                            'Monitor status: Pending, Approved, Rejected',
                            'View detail pengajuan dengan icon view',
                            'Track proses approval dari pengurus',
                            'Download dokumen pengajuan jika diperlukan',
                            'Edit pengajuan yang masih pending'
                        ],
                        'tips' => [
                            'Monitor status pengajuan secara rutin',
                            'Pastikan dokumen lengkap untuk approval',
                            'Hubungi pengurus untuk update status',
                            'Simpan nomor pengajuan untuk tracking',
                            'Review feedback jika pengajuan ditolak'
                        ],
                        'features' => [
                            'Real-time status tracking',
                            'Detailed application history',
                            'Document management',
                            'Status notifications',
                            'Application filtering dan search'
                        ]
                    ],
                    [
                        'id' => 'pembiayaan-saya',
                        'title' => '7. Pembiayaan Saya',
                        'description' => 'Monitor pembiayaan aktif, jadwal angsuran, dan detail pembayaran',
                        'icon' => 'fas fa-hand-holding-usd',
                        'screenshots' => ['/screenshots/pembiayaan.png'],
                        'steps' => [
                            'Klik menu "Pembiayaan Saya" dari sidebar',
                            'View semua pembiayaan yang sedang aktif',
                            'Monitor detail: jumlah, tenor, margin syariah',
                            'View jadwal angsuran mendatang',
                            'Track status pembayaran angsuran',
                            'Download jadwal angsuran sebagai PDF',
                            'View riwayat pembayaran angsuran'
                        ],
                        'tips' => [
                            'Monitor jadwal angsuran secara rutin',
                            'Bayar angsuran tepat waktu untuk avoid penalty',
                            'Download jadwal untuk planning keuangan',
                            'Track sisa saldo pembiayaan',
                            'Hubungi pengurus untuk konsultasi'
                        ],
                        'features' => [
                            'Real-time payment tracking',
                            'Installment schedule management',
                            'PDF schedule generation',
                            'Payment history',
                            'Outstanding balance calculator'
                        ]
                    ]
                ]
            ],
            'pengurus' => [
                'title' => 'Panduan Pengurus Koperasi',
                'subtitle' => 'Koperasi Syariah Bersama - Manajemen Operasional',
                'color' => '#2563EB',
                'icon' => 'fas fa-user-tie',
                'description' => 'Panduan lengkap untuk Pengurus Koperasi Syariah Bersama dalam mengelola operasional harian',
                'appInfo' => [
                    'name' => 'Koperasi Syariah Bersama',
                    'version' => '1.0.0',
                    'url' => 'http://127.0.0.1:8010',
                    'logo' => 'http://127.0.0.1:8010/storage/koperasi/logo/1765257692_logo_ksa_ad-zikra.png'
                ],
                'sections' => [
                    [
                        'id' => 'dashboard',
                        'title' => '1. Dashboard Pengurus',
                        'description' => 'Overview operasional koperasi dengan statistik real-time',
                        'icon' => 'fas fa-tachometer-alt',
                        'screenshots' => ['/screenshots/dashboard.png'],
                        'steps' => [
                            'Login menggunakan email pengurus yang terdaftar',
                            'View statistik overview: total anggota, simpanan, pembiayaan',
                            'Monitor recent activities dan transaksi terbaru',
                            'Access quick links ke modul penting',
                            'Review pending tasks dan notifikasi',
                            'Track performance metrics koperasi',
                            'Navigate ke menu pengurus lainnya'
                        ],
                        'tips' => [
                            'Monitor dashboard reguler untuk update terkini',
                            'Perhatikan notifikasi penting',
                            'Access quick links untuk efficiency',
                            'Track KPIs koperasi performance',
                            'Review analytics untuk decision making'
                        ],
                        'features' => [
                            'Real-time statistics',
                            'Interactive charts dan graphs',
                            'Activity monitoring',
                            'Performance metrics display',
                            'Quick navigation system'
                        ]
                    ],
                    [
                        'id' => 'manajemen-anggota',
                        'title' => '2. Manajemen Anggota',
                        'description' => 'Kelola data keanggotaan, registrasi baru, dan update informasi anggota',
                        'icon' => 'fas fa-users',
                        'screenshots' => ['/screenshots/dashboard.png'],
                        'steps' => [
                            'Klik menu "Manajemen Anggota" dari sidebar',
                            'View list semua anggota dengan search dan filter',
                            'Tambah anggota baru: klik tombol "Tambah Anggota"',
                            'Input data lengkap: personal, kontak, keuangan',
                            'Upload dokumen anggota (KTP, KK, foto profil)',
                            'Edit data anggota existing dengan icon edit',
                            'View detail anggota dengan icon view',
                            'Manage status aktif/non-aktif anggota'
                        ],
                        'tips' => [
                            'Verify data anggota sebelum menyimpan',
                            'Maintain updated contact information',
                            'Regular review anggota status',
                            'Backup data anggota secara berkala',
                            'Use search untuk quick access data'
                        ],
                        'features' => [
                            'Advanced search dan filter system',
                            'Bulk operations management',
                            'Document upload system',
                            'Status management workflow',
                            'Export data functionality'
                        ]
                    ],
                    [
                        'id' => 'transaksi-simpanan',
                        'title' => '3. Transaksi Simpanan',
                        'description' => 'Proses transaksi simpanan, cetak bukti, dan monitor saldo anggota',
                        'icon' => 'fas fa-piggy-bank',
                        'screenshots' => ['/screenshots/transaksi-simpanan.png'],
                        'steps' => [
                            'Klik menu "Transaksi Simpanan" dari sidebar',
                            'Pilih jenis transaksi: Setoran atau Penarikan',
                            'Cari data anggota berdasarkan nomor atau nama',
                            'Verify identitas anggota sebelum transaksi',
                            'Input jumlah transaksi yang valid',
                            'Pilih jenis simpanan (Pokok, Wajib, Sukarela)',
                            'Add catatan transaksi jika diperlukan',
                            'Proses transaksi dan cetak bukti PDF'
                        ],
                        'tips' => [
                            'Always verify anggota identity',
                            'Double-check jumlah sebelum proses',
                            'Cetak bukti untuk setiap transaksi',
                            'Monitor saldo anggota reguler',
                            'Reconcile transaksi harian'
                        ],
                        'features' => [
                            'Real-time balance update',
                            'PDF receipt generation',
                            'Transaction history tracking',
                            'Multi-type simpanan support',
                            'Complete audit trail'
                        ]
                    ],
                    [
                        'id' => 'verifikasi-pengajuan',
                        'title' => '4. Verifikasi Pengajuan',
                        'description' => 'Review, approve, dan process pengajuan pembiayaan dari anggota',
                        'icon' => 'fas fa-clipboard-check',
                        'screenshots' => ['/screenshots/pengajuan-pembiayaan.png'],
                        'steps' => [
                            'Klik menu "Verifikasi Pengajuan" dari sidebar',
                            'View list semua pengajuan menunggu review',
                            'Filter berdasarkan status: Pending, Approved, Rejected',
                            'Review detail pengajuan dengan icon view',
                            'Check kelengkapan dokumen persyaratan',
                            'Verify informasi keuangan dan kredit scoring',
                            'Approve atau reject pengajuan dengan alasan',
                            'Process pencairan dana untuk yang approved'
                        ],
                        'tips' => [
                            'Review pengajuan secara objektif',
                            'Check kelengkapan dokumen carefully',
                            'Follow syariah compliance guidelines',
                            'Document keputusan dengan jelas',
                            'Process pencairan dengan cepat'
                        ],
                        'features' => [
                            'Real-time application tracking',
                            'Document verification system',
                            'Approval workflow management',
                            'Credit scoring tools',
                            'Syariah compliance checking'
                        ]
                    ],
                    [
                        'id' => 'manajemen-pembiayaan',
                        'title' => '5. Manajemen Pembiayaan',
                        'description' => 'Monitor pembiayaan aktif, input angsuran, dan generate jadwal pembayaran',
                        'icon' => 'fas fa-hand-holding-usd',
                        'screenshots' => ['/screenshots/pembiayaan.png'],
                        'steps' => [
                            'Klik menu "Manajemen Pembiayaan" dari sidebar',
                            'View semua pembiayaan yang sedang aktif',
                            'Monitor status: Aktif, Lunas, Macet',
                            'Input pembayaran angsuran dari anggota',
                            'Generate jadwal angsuran otomatis',
                            'Calculate sisa saldo dan denda',
                            'Update status pembiayaan jika lunas',
                            'Cetak bukti pembayaran angsuran PDF'
                        ],
                        'tips' => [
                            'Input pembayaran segera setelah diterima',
                            'Verify jumlah pembayaran准确性',
                            'Generate jadwal otomatis untuk consistency',
                            'Monitor pembiayaan macet untuk follow-up',
                            'Maintain accurate payment records'
                        ],
                        'features' => [
                            'Real-time payment processing',
                            'Automatic schedule generation',
                            'Balance calculation system',
                            'Payment history tracking',
                            'PDF receipt generation'
                        ]
                    ],
                    [
                        'id' => 'laporan',
                        'title' => '6. Laporan',
                        'description' => 'Generate berbagai jenis laporan keuangan dan operasional koperasi',
                        'icon' => 'fas fa-chart-bar',
                        'screenshots' => ['/screenshots/laporan.png'],
                        'steps' => [
                            'Klik menu "Laporan" dari sidebar',
                            'Pilih jenis laporan: Harian, Mingguan, Bulanan',
                            'Set periode tanggal untuk laporan',
                            'Generate laporan Simpanan per Anggota',
                            'Create laporan Pembiayaan per Anggota',
                            'Generate Laporan Laba Rugi dan Neraca',
                            'Export laporan ke Excel format',
                            'Download atau print laporan yang dihasilkan'
                        ],
                        'tips' => [
                            'Generate laporan reguler untuk monitoring',
                            'Export Excel untuk analisis lebih lanjut',
                            'Simpan laporan untuk audit trails',
                            'Review trends untuk decision making',
                            'Backup laporan penting secara berkala'
                        ],
                        'features' => [
                            'Multiple report types',
                            'Custom period selection',
                            'Excel export functionality',
                            'Real-time data processing',
                            'Interactive dashboard views'
                        ]
                    ]
                ]
            ],
            'admin' => [
                'title' => 'Panduan Administrator System',
                'subtitle' => 'Koperasi Syariah Bersama - System Administration',
                'color' => '#7C3AED',
                'icon' => 'fas fa-shield-alt',
                'description' => 'Panduan lengkap administrator dalam mengelola sistem Koperasi Syariah Bersama',
                'appInfo' => [
                    'name' => 'Koperasi Syariah Bersama',
                    'version' => '1.0.0',
                    'url' => 'http://127.0.0.1:8010',
                    'logo' => 'http://127.0.0.1:8010/storage/koperasi/logo/1765257692_logo_ksa_ad-zikra.png',
                    'admin_email' => 'admin@koperasi-syariah.com',
                    'support' => '0812-3456-7890'
                ],
                'sections' => [
                    [
                        'id' => 'dashboard',
                        'title' => '1. Dashboard Administrator',
                        'description' => 'Halaman utama administrator dengan overview sistem dan akses cepat ke modul admin',
                        'icon' => 'fas fa-tachometer-alt',
                        'screenshots' => ['/screenshots/dashboard.png'],
                        'steps' => [
                            'Buka browser dan akses: http://127.0.0.1:8010/login',
                            'Login menggunakan email administrator yang terdaftar',
                            'Masukkan password administrator',
                            'Klik "Masuk ke Akun" untuk akses administrator',
                            'System akan redirect ke /admin/dashboard',
                            'View overview statistik sistem',
                            'Monitor aktivitas terkini',
                            'Access quick links ke modul admin lainnya'
                        ],
                        'tips' => [
                            'Dashboard memberikan gambaran umum sistem',
                            'Monitor user activity dan statistik',
                            'Access cepat ke modul yang sering digunakan',
                            'Perhatikan notifikasi sistem penting',
                            'Regular check untuk maintenance sistem'
                        ],
                        'features' => [
                            'Real-time statistics overview',
                            'Recent activity monitoring',
                            'Quick navigation to admin modules',
                            'System health indicators',
                            'Performance metrics display'
                        ]
                    ],
                    [
                        'id' => 'pengurus',
                        'title' => '2. Manajemen Pengurus',
                        'description' => 'Kelola data pengurus koperasi (Ketua, Bendahara, Sekretaris) dengan kontrol akses masing-masing',
                        'icon' => 'fas fa-users',
                        'screenshots' => ['/screenshots/dashboard.png'],
                        'steps' => [
                            'Klik menu "Pengurus" di sidebar admin',
                            'View list semua pengurus yang terdaftar',
                            'Tambah pengurus baru dengan tombol "Tambah"',
                            'Edit data pengurus existing dengan icon edit',
                            'Assign role: Ketua, Bendahara, atau Sekretaris',
                            'Soft delete pengurus dengan icon hapus',
                            'Restore pengurus yang dihapus jika diperlukan'
                        ],
                        'tips' => [
                            'Set password yang kuat untuk setiap pengurus',
                            'Assign role sesuai tanggung jawab masing-masing',
                            'Maintain updated contact information',
                            'Regular review pengurus access permissions',
                            'Backup data pengurus secara berkala'
                        ],
                        'features' => [
                            'CRUD operations untuk data pengurus',
                            'Role-based access assignment',
                            'Soft delete dengan restore capability',
                            'Search dan filter pengurus',
                            'Validation untuk data input'
                        ]
                    ],
                    [
                        'id' => 'koperasi',
                        'title' => '3. Data Koperasi',
                        'description' => 'Kelola informasi master data koperasi, pengaturan umum, dan konfigurasi sistem',
                        'icon' => 'fas fa-building',
                        'screenshots' => ['/screenshots/dashboard.png'],
                        'steps' => [
                            'Klik menu "Data Koperasi" di sidebar',
                            'View informasi umum koperasi saat ini',
                            'Edit data koperasi dengan tombol "Edit"',
                            'Update informasi: nama, alamat, telepon, email',
                            'Modify informasi legal: NPWP, SIUP, akta pendirian',
                            'Configure pengaturan operasional koperasi',
                            'Save perubahan dengan tombol "Simpan"'
                        ],
                        'tips' => [
                            'Pastikan data legal selalu update',
                            'Verify informasi kontak sebelum menyimpan',
                            'Document semua perubahan data koperasi',
                            'Regular review informasi keabsahan',
                            'Maintain consistency dengan dokumen fisik'
                        ],
                        'features' => [
                            'Master data management',
                            'Legal information tracking',
                            'Contact information management',
                            'Operational settings configuration',
                            'Data validation dan integrity checks'
                        ]
                    ],
                    [
                        'id' => 'jenis-simpanan',
                        'title' => '4. Jenis Simpanan',
                        'description' => 'Definisikan dan kelola berbagai jenis simpanan koperasi dengan aturan masing-masing',
                        'icon' => 'fas fa-piggy-bank',
                        'screenshots' => ['/screenshots/dashboard.png'],
                        'steps' => [
                            'Klik menu "Jenis Simpanan" di sidebar admin',
                            'View list semua jenis simpanan yang tersedia',
                            'Tambah jenis simpanan baru dengan "Tambah Jenis Simpanan"',
                            'Input nama simpanan (contoh: Pokok, Wajib, Sukarela)',
                            'Set minimal simpanan dan aturan persyaratan',
                            'Define bunga/bagi hasil sesuai syariah',
                            'Edit atau hapus jenis simpanan existing'
                        ],
                        'tips' => [
                            'Gunakan nama yang deskriptif untuk setiap jenis',
                            'Set minimal yang reasonable untuk anggota',
                            'Define clear rules untuk setiap jenis simpanan',
                            'Review rates sesuai market condition',
                            'Test new simpanan types sebelum implementasi'
                        ],
                        'features' => [
                            'Multiple simpanan types management',
                            'Custom rules dan minimal amounts',
                            'Syariah-compliant profit sharing',
                            'Flexible configuration options',
                            'Historical data tracking'
                        ]
                    ],
                    [
                        'id' => 'jenis-pembiayaan',
                        'title' => '5. Jenis Pembiayaan',
                        'description' => 'Konfigurasikan berbagai jenis pembiayaan syariah dengan margin dan tenor yang sesuai',
                        'icon' => 'fas fa-hand-holding-usd',
                        'screenshots' => ['/screenshots/dashboard.png'],
                        'steps' => [
                            'Klik menu "Jenis Pembiayaan" di sidebar',
                            'View portfolio jenis pembiayaan yang tersedia',
                            'Tambah jenis pembiayaan syariah baru',
                            'Define nama produk pembiayaan (contoh: Murabahah, Mudharabah)',
                            'Set persentase margin sesuai prinsip syariah',
                            'Configure tenor pembiayaan dalam bulan',
                            'Define persyaratan dan dokumen yang diperlukan',
                            'Edit atau hapus jenis pembiayaan yang tidak relevan'
                        ],
                        'tips' => [
                            'Pastikan margin compliance dengan syariah',
                            'Set tenor yang sesuai kemampuan anggota',
                            'Define clear eligibility criteria',
                            'Regular review produk competitiveness',
                            'Document semua syarat dan ketentuan'
                        ],
                        'features' => [
                            'Syariah-compliant financing products',
                            'Flexible margin dan tenor configuration',
                            'Eligibility criteria management',
                            'Document requirements tracking',
                            'Product performance analytics'
                        ]
                    ],
                    [
                        'id' => 'kartu-anggota',
                        'title' => '6. Kartu Anggota',
                        'description' => 'Generate dan kelola kartu anggota koperasi dengan desain kustom dan pengaturan PDF',
                        'icon' => 'fas fa-id-card',
                        'screenshots' => ['/screenshots/dashboard.png'],
                        'steps' => [
                            'Klik menu "Kartu Anggota" di sidebar admin',
                            'Access halaman settings kartu anggota',
                            'Upload logo koperasi untuk kartu',
                            'Upload signature pengurus untuk validasi',
                            'Configure layout dan desain kartu',
                            'Set card dimensions dan orientation',
                            'Preview kartu sebelum generate massal',
                            'Generate kartu anggota PDF untuk semua anggota',
                            'Download atau cetak kartu yang telah digenerate'
                        ],
                        'tips' => [
                            'Gunakan logo high resolution untuk hasil terbaik',
                            'Test preview sebelum generate massal',
                            'Verify signature authenticity',
                            'Maintain consistent branding',
                            'Regular backup generated cards'
                        ],
                        'features' => [
                            'Custom card design configuration',
                            'Logo dan signature upload',
                            'Batch PDF generation',
                            'Preview functionality',
                            'Download dan print capabilities'
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($manuals[$role] ?? $manuals['anggota']);
    }

    /**
     * Get all available manuals
     */
    public function getAllManuals()
    {
        return response()->json([
            'anggota' => $this->getManualByRole('anggota'),
            'pengurus' => $this->getManualByRole('pengurus'),
            'admin' => $this->getManualByRole('admin')
        ]);
    }
}