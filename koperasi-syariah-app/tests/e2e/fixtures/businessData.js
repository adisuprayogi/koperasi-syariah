// Business test data for functional testing scenarios
// This data is used for testing core business functionality

export const BUSINESS_DATA = {
    // Test data for creating new anggota
    newAnggota: {
        valid: {
            nama: "Test Anggota Baru",
            nomor_anggota: "2512.99999",
            email: "anggota.baru@test.com",
            telepon: "08123456789",
            alamat: "Jl. Test Address No. 123",
            tanggal_lahir: "1990-01-01",
            tempat_lahir: "Jakarta",
            pekerjaan: "Karyawan Swasta",
            kota: "Jakarta",
            provinsi: "DKI Jakarta",
            kode_pos: "12345"
        },
        duplicate: {
            nama: "Test Anggota Duplikat",
            nomor_anggota: "2512.00001", // This should cause validation error
            email: "anggota@test.com", // This email already exists
            telepon: "08123456789",
            alamat: "Jl. Test Address No. 456"
        },
        invalid: {
            nama: "", // Empty name should fail validation
            nomor_anggota: "INVALID",
            email: "invalid-email", // Invalid email format
            telepon: "123", // Invalid phone format
            alamat: "A" // Too short
        }
    },

    // Test data for simpanan operations
    simpanan: {
        pokok: {
            jenis: "Simpanan Pokok",
            jumlah: 500000,
            keterangan: "Simpanan pokok anggota baru"
        },
        wajib: {
            jenis: "Simpanan Wajib",
            jumlah: 100000,
            keterangan: "Simpanan wajib bulanan"
        },
        sukarela: {
            jenis: "Simpanan Sukarela",
            jumlah: 250000,
            keterangan: "Simpanan sukarela tambahan"
        },
        penarikan: {
            jumlah: 100000,
            alasan: "Kebutuhan mendesak",
            keterangan: "Penarikan sebagian simpanan"
        }
    },

    // Test data for pinjaman applications
    pinjaman: {
        aplikasi: {
            kecil: {
                jumlah: 5000000,
                tenor: 6,
                tujuan: "Modal usaha kecil",
                jaminan: "BPKB Motor",
                keterangan: "Pinjaman untuk tambahan modal usaha"
            },
            menengah: {
                jumlah: 15000000,
                tenor: 12,
                tujuan: "Modal usaha menengah",
                jaminan: "BPKB Mobil",
                keterangan: "Pinjaman untuk ekspansi usaha"
            },
            besar: {
                jumlah: 50000000,
                tenor: 24,
                tujuan: "Modal usaha besar",
                jaminan: "Sertifikat Tanah",
                keterangan: "Pinjaman untuk pembelian peralatan"
            }
        },
        persetujuan: {
            approve: {
                status: "disetujui",
                catatan: "Pinjaman disetujui setelah verifikasi dokumen lengkap",
                bunga: 5, // 5% per tahun
                jumlah_disetujui: 5000000
            },
            reject: {
                status: "ditolak",
                catatan: "Pinjaman ditolak karena dokumen tidak lengkap",
                alasan: "Dokumen jaminan tidak memenuhi syarat"
            },
            revision: {
                status: "revisi",
                catatan: "Perlu revisi jumlah pinjaman dan jaminan tambahan",
                saran: "Kurangi jumlah pinjaman atau tambahkan jaminan"
            }
        }
    },

    // Test data for angsuran payments
    angsuran: {
        pembayaran: {
            partial: {
                jumlah: 500000,
                metode: "Tunai",
                keterangan: "Angsuran bulan ke-1"
            },
            full: {
                jumlah: 1500000,
                metode: "Transfer Bank",
                keterangan: "Pelunasan angsuran bulan ke-3"
            },
            late: {
                jumlah: 1600000,
                metode: "Transfer Bank",
                keterangan: "Angsuran terlambat dengan denda",
                denda: 100000
            }
        },
        jadwal: {
            bulanan: {
                jumlah_angsuran: 1000000,
                bunga: 50000,
                total: 1050000,
                jatuh_tempo: "2025-01-15"
            }
        }
    },

    // Test data for laporan generation
    laporan: {
        dateRange: {
            thisMonth: {
                start: "2025-01-01",
                end: "2025-01-31"
            },
            thisYear: {
                start: "2025-01-01",
                end: "2025-12-31"
            },
            custom: {
                start: "2024-06-01",
                end: "2024-12-31"
            }
        },
        filters: {
            simpanan: {
                jenis: ["Semua", "Simpanan Pokok", "Simpanan Wajib", "Simpanan Sukarela"],
                status: ["Semua", "Aktif", "Tidak Aktif"]
            },
            pinjaman: {
                status: ["Semua", "Menunggu Persetujuan", "Disetujui", "Ditolak", "Lunas"],
                jenis: ["Semua", "Pinjaman Kecil", "Pinjaman Menengah", "Pinjaman Besar"]
            },
            anggota: {
                status: ["Semua", "Aktif", "Tidak Aktif", "Baru"]
            }
        }
    },

    // Expected values for assertions
    expectations: {
        successMessages: {
            createAnggota: "Data anggota berhasil ditambahkan",
            updateAnggota: "Data anggota berhasil diperbarui",
            deleteAnggota: "Data anggota berhasil dihapus",
            tambahSimpanan: "Simpanan berhasil ditambahkan",
            tarikSimpanan: "Penarikan simpanan berhasil diproses",
            ajukanPinjaman: "Pengajuan pinjaman berhasil diajukan",
            prosesPersetujuan: "Status pinjaman berhasil diperbarui",
            bayarAngsuran: "Pembayaran angsuran berhasil diproses"
        },
        errorMessages: {
            duplicateAnggota: "Nomor anggota atau email sudah terdaftar",
            invalidData: "Data yang dimasukkan tidak valid",
            insufficientSaldo: "Saldo tidak mencukupi untuk penarikan",
            pendingApplication: "Masih ada pengajuan pinjaman yang pending",
            latePayment: "Pembayaran terlambat, denda akan diterapkan"
        },
        calculations: {
            bungaPerBulan: 0.05 / 12, // 5% per tahun
            dendaKeterlambatan: 0.02, // 2% dari jumlah angsuran
            minimumSimpanan: 100000,
            maksimumPinjaman: 100000000
        }
    },

    // Helper functions for test data generation
    generators: {
        generateNomorAnggota: () => {
            const timestamp = Date.now().toString().slice(-6);
            return `2512.${timestamp}`;
        },

        generateEmail: (nama) => {
            const cleanName = nama.toLowerCase().replace(/\s+/g, '.');
            const timestamp = Date.now().toString().slice(-4);
            return `${cleanName}.${timestamp}@test.com`;
        },

        generateTelepon: () => {
            const randomDigits = Math.random().toString().slice(-8);
            return `08${randomDigits}`;
        },

        calculateAngsuran: (jumlahPinjaman, tenor, bungaTahunan = 0.05) => {
            const bungaPerBulan = bungaTahunan / 12;
            const totalBunga = jumlahPinjaman * bungaPerBulan * tenor;
            const totalPinjaman = jumlahPinjaman + totalBunga;
            return {
                jumlahPerBulan: Math.round(totalPinjaman / tenor),
                totalBunga: Math.round(totalBunga),
                totalPinjaman: Math.round(totalPinjaman)
            };
        }
    }
};