// Test data for admin-specific operations

export const ADMIN_DATA = {
    // Data untuk setup pengurus baru
    tambahPengurus: {
        valid: {
            nama: 'Test Pengurus Baru',
            email: 'pengurusbaru@test.com',
            password: 'password123',
            confirmPassword: 'password123',
            jabatan: 'Sekretaris',
            status: 'Aktif'
        },
        invalid: {
            nama: '',
            email: 'invalid-email',
            password: '',
            confirmPassword: 'mismatch',
            jabatan: '',
            status: ''
        }
    },

    // Data untuk konfigurasi sistem
    konfigurasiSistem: {
        valid: {
            namaKoperasi: 'Koperasi Syariah Test Updated',
            alamat: 'Jl. Updated Address No. 123',
            noTelp: '021-12345678',
            email: 'info@koperasitest.com',
            website: 'www.koperasitest.com'
        }
    },

    // Expected dashboard elements
    dashboardElements: {
        infoCards: [
            'Total Pengurus',
            'Total Anggota',
            'Total Simpanan',
            'Total Pembiayaan'
        ],
        menuItems: [
            'Dashboard',
            'Pengurus',
            'Konfigurasi',
            'Data Master',
            'Laporan',
            'System Settings'
        ]
    },

    // Expected data master menu items
    dataMasterItems: [
        'Jenis Simpanan',
        'Jenis Pembiayaan',
        'Coa (Chart of Accounts)',
        'Setup Angsuran'
    ],

    // Expected laporan types
    laporanTypes: [
        'Laporan Simpanan',
        'Laporan Pembiayaan',
        'Laporan Angsuran',
        'Laporan Tunggakan',
        'Laporan Laba Rugi',
        'Laporan Neraca',
        'Laporan Periode'
    ],

    // Expected columns for pengurus table
    pengurusTableColumns: [
        'No.',
        'Nama',
        'Email',
        'Jabatan',
        'Status',
        'Aksi'
    ],

    // Expected form fields for jenis simpanan
    jenisSimpananFields: [
        'Nama Jenis',
        'Minimal Setoran',
        'Minimal Saldo',
        'Bunga/Tingkat'
    ],

    // Expected form fields for jenis pembiayaan
    jenisPembiayaanFields: [
        'Nama Jenis',
        'Plafon Maksimal',
        'Jangka Waktu Maksimal',
        'Margin/Bagi Hasil',
        'Administrasi',
        'Provisi'
    ]
};

// Helper function untuk generate random pengurus data
export function generateRandomPengurusData() {
    const timestamp = Date.now();
    return {
        nama: `Pengurus ${timestamp}`,
        email: `pengurus${timestamp}@test.com`,
        password: 'password123',
        confirmPassword: 'password123',
        jabatan: 'Test Jabatan',
        status: 'Aktif'
    };
}

// Helper function untuk generate random jenis simpanan data
export function generateRandomJenisSimpananData() {
    const timestamp = Date.now();
    return {
        namaJenis: `Simpanan Test ${timestamp}`,
        minimalSetoran: '100000',
        minimalSaldo: '50000',
        bunga: '5'
    };
}

// Helper function untuk generate random jenis pembiayaan data
export function generateRandomJenisPembiayaanData() {
    const timestamp = Date.now();
    return {
        namaJenis: `Pembiayaan Test ${timestamp}`,
        plafonMaksimal: '50000000',
        jangkaWaktuMaksimal: '36',
        margin: '15',
        administrasi: '50000',
        provisi: '2'
    };
}