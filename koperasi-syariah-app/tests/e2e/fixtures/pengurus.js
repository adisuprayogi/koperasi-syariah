// Test data for pengurus-specific operations

export const PENGURUS_DATA = {
    // Data untuk tambah anggota baru
    tambahAnggota: {
        valid: {
            nomorAnggota: '2521.99999',
            nama: 'Test Anggota Baru',
            email: 'anggotabaru@test.com',
            noHp: '08123456789',
            alamat: 'Jl. Test Anggota Baru No. 123',
            tempatLahir: 'Test City',
            tanggalLahir: '1990-01-01',
            jenisKelamin: 'L',
            status: 'Aktif'
        },
        invalid: {
            nomorAnggota: '',
            nama: '',
            email: 'invalid-email',
            noHp: '',
            alamat: '',
            tempatLahir: '',
            tanggalLahir: '',
            jenisKelamin: '',
            status: ''
        }
    },

    // Data untuk transaksi simpanan manual
    transaksiSimpanan: {
        valid: {
            jenisSimpanan: 'Simpanan Sukarela',
            jumlah: '500000',
            tanggal: '2024-12-12',
            keterangan: 'Setoran simpanan sukarela bulanan'
        },
        invalid: {
            jenisSimpanan: '',
            jumlah: '0',
            tanggal: '',
            keterangan: ''
        }
    },

    // Expected dashboard elements
    dashboardElements: {
        infoCards: [
            'Total Anggota',
            'Total Simpanan',
            'Total Pinjaman',
            'Pinjaman Belum Cair'
        ],
        menuItems: [
            'Dashboard',
            'Anggota',
            'Simpanan',
            'Pembiayaan',
            'Angsuran',
            'Laporan'
        ]
    },

    // Expected laporan types
    laporanTypes: [
        'Laporan Simpanan',
        'Laporan Pembiayaan',
        'Laporan Angsuran',
        'Laporan Laba Rugi',
        'Laporan Neraca'
    ],

    // Status pengajuan
    statusPengajuan: [
        'Diajukan',
        'Diproses',
        'Disetujui',
        'Ditolak',
        'Dicairkan'
    ],

    // Expected columns for anggota table
    anggotaTableColumns: [
        'No.',
        'Nomor Anggota',
        'Nama',
        'Email',
        'No. HP',
        'Status',
        'Aksi'
    ],

    // Expected columns for pengajuan table
    pengajuanTableColumns: [
        'No.',
        'Nomor Anggota',
        'Nama',
        'Jumlah',
        'Jangka Waktu',
        'Status',
        'Tanggal',
        'Aksi'
    ]
};

// Helper function untuk generate random anggota data
export function generateRandomAnggotaData() {
    const timestamp = Date.now();
    return {
        nomorAnggota: `2521.${timestamp}`,
        nama: `Test Anggota ${timestamp}`,
        email: `test${timestamp}@example.com`,
        noHp: `08123456${timestamp % 10000}`,
        alamat: `Jl. Test Address No. ${timestamp}`,
        tempatLahir: 'Test City',
        tanggalLahir: '1990-01-01',
        jenisKelamin: 'L',
        status: 'Aktif'
    };
}

// Helper function untuk generate random transaksi data
export function generateRandomTransaksiData() {
    const randomJumlah = Math.floor(Math.random() * 1000000) + 100000; // 100k - 1jt
    return {
        jenisSimpanan: 'Simpanan Sukarela',
        jumlah: randomJumlah.toString(),
        tanggal: new Date().toISOString().split('T')[0],
        keterangan: `Test transaksi ${Date.now()}`
    };
}