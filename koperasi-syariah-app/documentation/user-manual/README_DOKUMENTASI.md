# 📚 DOKUMENTASI KOPERASI SYARIAH

## 📖 Ringkasan

Dokumentasi lengkap untuk aplikasi **Koperasi Syariah** yang berjalan di **http://127.0.0.1:8010**. Dokumentasi ini mencakup:

- Login credentials untuk setiap role
- Menu yang tersedia per role
- Fungsi setiap menu
- Screenshot dashboard untuk setiap role
- Panduan lengkap penggunaan aplikasi

---

## 📁 Struktur File

```
/documentation/user-manual/
├── README_DOKUMENTASI.md         # File ini - ringkasan dokumentasi
├── DOKUMENTASI_KOPERASI_SYARIAH.md # Dokumentasi lengkap aplikasi
├── PANDUAN_SCREENSHOT.md         # Panduan cara mengambil screenshot
├── index.html                    # Halaman utama dokumentasi (web)
├── capture_screenshots.php       # Script otomatis capture screenshot
├── screenshots/                  # Folder untuk menyimpan screenshot
│   ├── anggota/                  # Screenshot role Anggota
│   ├── pengurus/                 # Screenshot role Pengurus
│   ├── bendahara/                # Screenshot role Bendahara
│   └── admin/                    # Screenshot role Administrator
└── menu_documentation_*.md       # File dokumentasi per role (auto-generated)
```

---

## 🚀 Quick Start

### 1. Buka Dokumentasi Web
```bash
# Buka file index.html di browser
open index.html
# atau
double-click pada file index.html
```

### 2. Akses Aplikasi
- **URL**: http://127.0.0.1:8010
- **Login Page**: http://127.0.0.1:8010/login

### 3. Login dengan Role Berbeda

| Role | Username | Password | Akses |
|------|----------|----------|-------|
| **Anggota** | 2521.00001 | 22222222 | Dashboard Anggota |
| **Ketua** | yogi@gmail.com | 22222222 | Dashboard Pengurus |
| **Bendahara** | fitri@gmail.com | 33333333 | Dashboard Pengurus |
| **Admin** | admin@admin.com | password | Dashboard Admin |

---

## 📸 Cara Mengambil Screenshot

### Method 1: Manual (Recommended)
1. Follow panduan di: **PANDUAN_SCREENSHOT.md**
2. Login sesuai role
3. Navigasi ke setiap menu
4. Take screenshot
5. Save di folder yang sesuai

### Method 2: Otomatis (Jika aplikasi accessible via curl)
```bash
# Jalankan script PHP
php capture_screenshots.php
```

---

## 📋 Fitur Per Role

### 👤 Role Anggota
- ✅ Dashboard dengan statistik pribadi
- ✅ Simpanan (Pokok, Wajib, Sukarela)
- ✅ Pengajuan Pinjaman
- ✅ Pembayaran Angsuran
- ✅ Lihat SHU
- ✅ Cetak laporan pribadi

### 👔 Role Pengurus (Ketua & Bendahara)
- ✅ Semua fitur Anggota
- ✅ Manajemen Data Anggota
- ✅ Verifikasi Pinjaman
- ✅ Laporan Keuangan
- ✅ Perhitungan SHU
- ✅ Monitoring Tunggakan

### 🛡️ Role Administrator
- ✅ Semua fitur Anggota & Pengurus
- ✅ User Management
- ✅ System Configuration
- ✅ Database Management
- ✅ Security & Audit
- ✅ Module Management

---

## 🔧 Troubleshooting

### Aplikasi Tidak Bisa Diakses
1. Pastikan aplikasi berjalan: `http://127.0.0.1:8010`
2. Cek error di browser (F12 → Console)
3. Restart aplikasi jika perlu

### Login Gagal
1. Double-check username dan password
2. Clear browser cache
3. Coba incognito window

### Screenshot Tidak Muncul
1. Pastikan file screenshot ada di folder yang benar
2. Refresh halaman index.html
3. Check console untuk error

---

## 📞 Bantuan

Untuk bantuan lebih lanjut:
- **Documentation**: Lihat `DOKUMENTASI_KOPERASI_SYARIAH.md`
- **Screenshot Guide**: Lihat `PANDUAN_SCREENSHOT.md`
- **Web Interface**: Buka `index.html`

---

## 📝 Notes Penting

1. **Security**: Jangan share credentials ke orang yang tidak berwenang
2. **Privacy**: Blur/hapus data pribadi saat mengambil screenshot
3. **Backup**: Backup dokumentasi secara berkala
4. **Update**: Update dokumentasi jika ada perubahan fitur

---

## 🔄 Update Documentation

Jika ada perubahan pada aplikasi:

1. Update fitur di `DOKUMENTASI_KOPERASI_SYARIAH.md`
2. Retake screenshot yang relevan
3. Update credentials jika ada perubahan
4. Test semua link berfungsi

---

*Last Updated: 12 Desember 2025*
*Version: 1.0*
*Author: Documentation Team*