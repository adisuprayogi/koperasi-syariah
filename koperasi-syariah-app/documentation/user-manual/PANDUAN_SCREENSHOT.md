# PANDUAN LENGKAP SCREENSHOT DOKUMENTASI KOPERASI SYARIAH

## 📋 PREPARASI

### Pastikan Aplikasi Berjalan
1. Buka browser (Chrome/Firefox/Safari)
2. Akses: http://127.0.0.1:8010
3. Pastikan halaman login muncul

### Tools yang Dibutuhkan
- **Browser**: Chrome/Firefox/Safari
- **Screenshot Tool**:
  - Windows: Snipping Tool atau Win + Shift + S
  - Mac: Cmd + Shift + 4
  - Browser Extension: GoFullPage atau Awesome Screenshot

---

## 🔐 LOGIN CREDENTIALS

| Role | Username | Password |
|------|----------|----------|
| **Anggota** | 2521.00001 | 22222222 |
| **Ketua Pengurus** | yogi@gmail.com | 22222222 |
| **Bendahara** | fitri@gmail.com | 33333333 |
| **Administrator** | admin@admin.com | password |

---

## 📸 LANGKAH-LANGKAH SCREENSHOT

### 1. ROLE ANGGOTA (2521.00001)

**Step 1: Login**
1. Buka http://127.0.0.1:8010/login
2. Masukkan username: `2521.00001`
3. Masukkan password: `22222222`
4. Klik Login

**Step 2: Screenshot Dashboard Anggota**
- **Nama File**: `01_dashboard_anggota.png`
- **Yang harus di-capture**:
  - Menu sidebar (kiri)
  - Dashboard utama dengan statistik
  - Info profil anggota

**Step 3: Screenshot Menu Simpanan**
- Klik menu "Simpanan"
- **Nama File**: `02_menu_simpanan_anggota.png`
- **Yang harus di-capture**:
  - Tab Simpanan Pokok
  - Tab Simpanan Wajib
  - Tab Simpanan Sukarela

**Step 4: Screenshot Menu Pinjaman**
- Klik menu "Pinjaman"
- **Nama File**: `03_menu_pinjaman_anggota.png`
- **Yang harus di-capture**:
  - Tombol "Ajukan Pinjaman"
  - Daftar pinjaman aktif
  - Status pinjaman

**Step 5: Screenshot Menu Angsuran**
- Klik menu "Angsuran"
- **Nama File**: `04_menu_angsuran_anggota.png`
- **Yang harus di-capture**:
  - Jadwal angsuran
  - Tombol bayar
  - Riwayat pembayaran

### 2. ROLE KETUA PENGURUS (yogi@gmail.com)

**Step 1: Login**
1. Logout dari akun anggota
2. Login dengan username: `yogi@gmail.com`
3. Password: `22222222`

**Step 2: Screenshot Dashboard Pengurus**
- **Nama File**: `05_dashboard_pengurus.png`
- **Yang harus di-capture**:
  - Statistik keseluruhan koperasi
  - Grafik perkembangan
  - Menu lengkap untuk pengurus

**Step 3: Screenshot Data Anggota**
- Klik menu "Data Anggota"
- **Nama File**: `06_data_anggota_pengurus.png`
- **Yang harus di-capture**:
  - Daftar semua anggota
  - Tombol "Tambah Anggota"
  - Search dan filter

**Step 4: Screenshot Verifikasi Pinjaman**
- Klik menu "Pinjaman" → "Verifikasi"
- **Nama File**: `07_verifikasi_pinjaman.png`
- **Yang harus di-capture**:
  - Daftar pengajuan pinjaman
  - Tombol approve/reject
  - Detail pengajuan

**Step 5: Screenshot Laporan Keuangan**
- Klik menu "Laporan" → "Keuangan"
- **Nama File**: `08_laporan_keuangan.png`
- **Yang harus di-capture**:
  - Laporan laba rugi
  - Neraca
  - Filter periode

### 3. ROLE BENDAHARA (fitri@gmail.com)

**Step 1: Login**
1. Logout dari akun ketua
2. Login dengan username: `fitri@gmail.com`
3. Password: `33333333`

**Step 2: Screenshot Dashboard Bendahara**
- **Nama File**: `09_dashboard_bendahara.png`
- **Yang harus di-capture**:
  - Saldo kas
  - Total pencairan hari ini
  - Menu keuangan

**Step 3: Screenshot Pencairan Pinjaman**
- Klik menu "Pinjaman" → "Pencairan"
- **Nama File**: `10_pencairan_pinjaman.png`
- **Yang harus di-capture**:
  - Daftar pinjaman yang disetujui
  - Tombol "Cairkan"
  - Bukti pencairan

**Step 4: Screenshot Daftar Tunggakan**
- Klik menu "Tunggakan"
- **Nama File**: `11_daftar_tunggakan.png`
- **Yang harus di-capture**:
  - Daftar anggota menunggak
  - Jumlah tunggakan
  - Tombol kirim reminder

### 4. ROLE ADMINISTRATOR (admin@admin.com)

**Step 1: Login**
1. Logout dari akun bendahara
2. Login dengan username: `admin@admin.com`
3. Password: `password`

**Step 2: Screenshot Dashboard Admin**
- **Nama File**: `12_dashboard_admin.png`
- **Yang harus di-capture**:
  - System monitoring
  - User statistics
  - Full admin menu

**Step 3: Screenshot User Management**
- Klik menu "User Management"
- **Nama File**: `13_user_management.png`
- **Yang harus di-capture**:
  - Daftar semua user
  - Role assignment
  - Add new user

**Step 4: Screenshot System Settings**
- Klik menu "Settings"
- **Nama File**: `14_system_settings.png`
- **Yang harus di-capture**:
  - General settings
  - Email configuration
  - Security settings

---

## 📁 ORGANISASI FILE

Setelah selesai, susun file dalam folder berikut:

```
/documentation/user-manual/
├── screenshots/
│   ├── anggota/
│   │   ├── 01_dashboard_anggota.png
│   │   ├── 02_menu_simpanan_anggota.png
│   │   ├── 03_menu_pinjaman_anggota.png
│   │   └── 04_menu_angsuran_anggota.png
│   ├── pengurus/
│   │   ├── 05_dashboard_pengurus.png
│   │   ├── 06_data_anggota_pengurus.png
│   │   ├── 07_verifikasi_pinjaman.png
│   │   └── 08_laporan_keuangan.png
│   ├── bendahara/
│   │   ├── 09_dashboard_bendahara.png
│   │   ├── 10_pencairan_pinjaman.png
│   │   └── 11_daftar_tunggakan.png
│   └── admin/
│       ├── 12_dashboard_admin.png
│       ├── 13_user_management.png
│       └── 14_system_settings.png
├── DOKUMENTASI_KOPERASI_SYARIAH.md
├── PANDUAN_SCREENSHOT.md
└── index.html
```

---

## ✅ CHECKLIST SEBELUM UPLOAD

Pastikan semua screenshot memenuhi kriteria:

- [ ] Login page terlihat jelas
- [ ] Sidebar menu lengkap tercapture
- [ ] Data terlihat jelas (jangan blur)
- [ ] Tanggal dan waktu terlihat (opsional)
- [ ] Tidak ada informasi sensitif yang terlihat
- [ ] Resolusi minimal 1280x720 px
- [ ] Format file: PNG (recommended) atau JPG
- [ ] Nama file sesuai konvensi

---

## 📝 NOTES

1. **Privacy**: Jangan menampilkan data asli anggota tanpa permission
2. **Focus**: Pastikan focus pada fitur yang didokumentasikan
3. **Consistency**: Gunakan browser yang sama untuk semua screenshot
4. **Quality**: Pastikan screenshot tajam dan mudah dibaca
5. **Full Page**: Untuk panjang page, gunakan full page screenshot

---

## 🔧 TROUBLESHOOTING

### Tidak bisa login?
- Cek username dan password
- Clear browser cache
- Pastikan aplikasi berjalan (http://127.0.0.1:8010)

### Menu tidak muncul?
- Refresh halaman (F5)
- Cek role user
- Clear browser cache

### Screenshot blur?
- Gunakan zoom 100%
- Pastikan page sudah fully loaded
- Gunakan screenshot tool yang baik

### Halaman error 404?
- Pastikan URL benar
- Cek apakah aplikasi running
- Restart aplikasi jika perlu

---

## 📤 UPLOAD

Setelah semua screenshot siap:

1. Compress folder screenshots
2. Upload ke documentation folder
3. Update DOKUMENTASI_KOPERASI_SYARIAH.md dengan link screenshot
4. Test semua link berfungsi

---

*Last updated: 12 Desember 2025*