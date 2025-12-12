// Test data for anggota-specific operations

export const ANGGOTA_DATA = {
    // Data untuk pengajuan pembiayaan
    pengajuanPembiayaan: {
        valid: {
            jenisPembiayaan: 'Pembiayaan Modal Kerja',
            jumlah: '5000000',
            jangkaWaktu: '12',
            tujuan: 'Modal usaha tambahan untuk pengembangan bisnis',
            sumberPengembalian: 'Penghasilan dari usaha dagang',
           jaminan: 'Sertifikat tanah dan bangunan'
        },
        invalid: {
            jenisPembiayaan: '',
            jumlah: '0',
            jangkaWaktu: '0',
            tujuan: '',
            sumberPengembalian: '',
            jaminan: ''
        }
    },

    // Data untuk update profil anggota
    updateProfil: {
        valid: {
            nama: 'Test User Updated',
            email: 'test.updated@example.com',
            noHp: '08123456789',
            alamat: 'Jl. Test Update No. 123, Kelurahan Test, Kecamatan Test, Kota Test',
            tempatLahir: 'Test City',
            tanggalLahir: '1990-01-01'
        },
        invalid: {
            nama: '',
            email: 'invalid-email',
            noHp: '',
            alamat: '',
            tempatLahir: '',
            tanggalLahir: ''
        }
    },

    // Expected data untuk dashboard elements
    dashboardElements: {
        infoCards: [
            'Total Simpanan',
            'Saldo Pinjaman',
            'Jatuh Tempo',
            'Status Pengajuan'
        ],
        menuItems: [
            'Dashboard',
            'Profil',
            'Simpanan',
            'Pembiayaan',
            'Angsuran',
            'Laporan'
        ]
    },

    // Expected data untuk simpanan
    simpanan: {
        types: [
            'Simpanan Pokok',
            'Simpanan Wajib',
            'Simpanan Sukarela'
        ]
    },

    // Expected data untuk angsuran
    angsuran: {
        columns: [
            'No.',
            'Tanggal',
            'Jumlah',
            'Status'
        ]
    }
};

// Helper function untuk generate random test data
export function generateRandomPengajuanData() {
    const randomJumlah = Math.floor(Math.random() * 10000000) + 1000000; // 1jt - 10jt
    const randomJangkaWaktu = Math.floor(Math.random() * 24) + 6; // 6 - 30 bulan

    return {
        jenisPembiayaan: 'Pembiayaan Modal Kerja',
        jumlah: randomJumlah.toString(),
        jangkaWaktu: randomJangkaWaktu.toString(),
        tujuan: `Test pengajuan ${Date.now()}`,
        sumberPengembalian: 'Penghasilan dari usaha',
        jaminan: 'Test jaminan'
    };
}