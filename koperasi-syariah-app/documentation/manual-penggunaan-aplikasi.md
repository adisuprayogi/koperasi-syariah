# Manual Penggunaan Aplikasi Koperasi Syariah Bersama
**Versi 1.0.0 - Desember 2024**

---

## 📋 Daftar Isi

1. [Login & Keamanan Akun](#1-login--keamanan-akun)
2. [Dashboard Anggota](#2-dashboard-anggota)
3. [Dashboard Pengurus](#3-dashboard-pengurus)
4. [Dashboard Administrator](#4-dashboard-administrator)
5. [Glosarium](#5-glosarium)

---

## 1. Login & Keamanan Akun

### 🔐 Aplikasi URL
- **URL Aplikasi**: http://127.0.0.1:8010
- **Landing Page**: http://127.0.0.1:8010

### 📝 Login Page Features
Halaman login menampilkan:
- **Logo Koperasi**: Gambar logo di bagian header dengan background gradient hijau
- **Form Login**:
  - Field Email atau Nomor Anggota (dengan icon user)
  - Field Password (dengan icon lock)
  - Tombol "Masuk ke Akun" dengan hover effect
  - Loading spinner saat proses login
- **Panduan Login**:
  - Admin (badge merah) - Login menggunakan email
  - Pengurus (badge biru) - Login menggunakan email
  - Anggota (badge hijau) - Login menggunakan nomor anggota

### 🎨 Design Interface
- **Theme**: Hijau gradient dengan Tailwind CSS
- **Responsive**: Mobile-friendly dengan proper form layout
- **Security**: CSRF protection, autocomplete attributes
- **User Experience**: Auto-focus pada login field, loading states

---

## 2. Dashboard Anggota

### 👤 Kredensial Login
- **Username**: `2521.00001`
- **Password**: `22222222`
- **Login Method**: Nomor Anggota

### 🏠 Dashboard Features
Setelah berhasil login, anggota diarahkan ke `/anggota/dashboard` dengan fitur:

#### Navigation Menu (Sidebar):
- **Dashboard** - Halaman utama dengan ringkasan keuangan
- **Profil** - Data pribadi dan pengaturan akun
- **Simpanan** - Riwayat dan detail simpanan
- **Pengajuan** - Ajukan pembiayaan baru
- **Pembiayaan** - Status dan jadwal angsuran
- **Ubah Password** - Keamanan akun
- **Logout** - Keluar dari sistem

#### Quick Access Cards:
1. **Total Simpanan** - Menampilkan saldo semua jenis simpanan
2. **Pembiayaan Aktif** - Jumlah dan status pembiayaan berjalan
3. **Ajukan Pembiayaan Baru** - Quick link ke form pengajuan
4. **Jadwal Angsuran** - Reminder pembayaran angsuran

#### Informasi Profil:
- **Nama Anggota**: Nama lengkap terdaftar
- **Nomor Anggota**: ID unik keanggotaan
- **Status**: Aktif/Non-aktif
- **Foto Profil** (jika ada)

#### Recent Activity:
- Transaksi simpanan terakhir
- Status pengajuan pembiayaan
- Notifikasi pembayaran angsuran

---

## 3. Dashboard Pengurus

### 👥 Kredensial Login

#### Ketua Pengurus:
- **Username**: `yogi@gmail.com`
- **Password**: `22222222`
- **Login Method**: Email

#### Bendahara:
- **Username**: `fitri@gmail.com`
- **Password**: `33333333`
- **Login Method**: Email

### 🏢 Dashboard Features
Setelah login, pengurus diarahkan ke `/pengurus/dashboard` dengan fitur:

#### Navigation Menu (Sidebar):
- **Dashboard** - Ringkasan operasional koperasi
- **Data Anggota** - Manajemen data keanggotaan
  - List semua anggota
  - Tambah anggota baru
  - Edit data anggota
  - Hapus/restore anggota
- **Simpanan** - Manajemen transaksi simpanan
  - Input transaksi simpanan
  - Riwayat transaksi
  - Cetak bukti transaksi
  - API saldo inquiry
- **Pengajuan** - Persetujuan pembiayaan
  - List pengajuan masuk
  - Detail pengajuan
  - Verifikasi & persetujuan
  - Pencairan dana
- **Pembiayaan** - Manajemen pembiayaan aktif
  - List pembiayaan berjalan
  - Input pembayaran angsuran
  - Generate jadwal angsuran
  - Cetak bukti pembayaran
- **Laporan** - Berbagai jenis laporan
  - Harian, Mingguan, Bulanan
  - Simpanan per anggota
  - Pembiayaan per anggota
  - Laba Rugi dan Neraca
  - Export Excel untuk semua laporan

#### Quick Stats Dashboard:
1. **Total Anggota** - Jumlah anggota aktif
2. **Total Simpanan** - Rekapitulasi semua simpanan
3. **Pembiayaan Aktif** - Total pembiayaan berjalan
4. **Pending Applications** - Jumlah pengajuan menunggu approval

#### Recent Activities:
- Anggota baru terdaftar
- Transaksi simpanan terakhir
- Pengajuan pembiayaan baru
- Pembayaran angsuran masuk

---

## 4. Dashboard Administrator

### 🛡️ Kredensial Login
- **Username**: `admin@admin.com`
- **Password**: `password`
- **Login Method**: Email

### ⚙️ Dashboard Features
Setelah login, admin diarahkan ke `/admin/dashboard` dengan fitur paling lengkap:

#### Navigation Menu (Sidebar):
- **Dashboard** - Overview sistem
- **Manajemen Pengurus** - CRUD data pengurus
  - List semua pengurus
  - Tambah pengurus baru
  - Edit data pengurus
  - Soft delete & restore
- **Data Koperasi** - Master data koperasi
  - Informasi umum koperasi
  - Edit data koperasi
- **Data Koperasi Management** - Database management
- **Jenis Simpanan** - Master jenis simpanan
  - CRUD jenis simpanan
  - Set aturan bunga/syarat
- **Jenis Pembiayaan** - Master jenis pembiayaan
  - CRUD jenis pembiayaan
  - Set margin dan tenor
- **Kartu Anggota** - Generate kartu anggota
  - Settings kartu
  - Upload logo/signature
  - Preview & download PDF
- **Sistem** - Konfigurasi sistem

#### System Administration:
1. **User Management** - Kelola semua user roles
2. **Master Data** - Kelola data referensi
3. **System Settings** - Konfigurasi aplikasi
4. **Security** - Keamanan dan permissions
5. **Backup & Restore** - Data management
6. **Audit Log** - Tracking aktivitas

#### Reports & Analytics:
- Semua fitur laporan Pengurus
- System performance reports
- User activity reports
- Financial analytics dashboard

---

## 5. Glosarium

### 📌 Istilah Penting

- **Anggota**: Nasabah koperasi yang memiliki simpanan dan dapat mengajukan pembiayaan
- **Pengurus**: Pengelola operasional koperasi (Ketua, Bendahara, Sekretaris)
- **Administrator**: System administrator dengan akses penuh
- **Simpanan Pokok**: Modal wajib awal keanggotaan
- **Simpanan Wajib**: Setoran bulanan wajib anggota
- **Simpanan Sukarela**: Setoran fleksibel sesuai kemampuan
- **Pembiayaan**: Pinjaman dengan sistem syariah
- **Margin**: Keuntungan yang disepakati dalam pembiayaan syariah
- **Angsuran**: Cicilan pembayaran pembiayaan

### 🔗 Routes Reference

#### Public Routes:
- `GET /` - Landing Page
- `GET /login` - Login Form
- `POST /login` - Authentication
- `POST /logout` - Logout

#### Admin Routes (`/admin/*`):
- Dashboard, Pengurus Management, Data Koperasi
- Jenis Simpanan, Jenis Pembiayaan, Kartu Anggota

#### Pengurus Routes (`/pengurus/*`):
- Dashboard, Anggota Management, Simpanan
- Pengajuan, Pembiayaan, Laporan, Export

#### Anggota Routes (`/anggota/*`):
- Dashboard, Profil, Simpanan, Pengajuan, Pembiayaan

#### Documentation Routes (`/documentation/*`):
- User Manual PDF generation
- Preview functionality

### 📱 Responsif Design
Aplikasi menggunakan Tailwind CSS dengan:
- Mobile-first approach
- Responsive sidebar navigation
- Touch-friendly buttons dan forms
- Optimized untuk tablet dan desktop

### 🔒 Security Features
- CSRF protection pada semua forms
- Password hashing dengan bcrypt
- Role-based access control
- Session management
- Input validation dan sanitization

---

## 📞 Technical Support

**Contact Information:**
- 📞 WhatsApp: 0812-3456-7890
- ✉️ Email: support@koperasi-syariah.com
- 🌐 Website: www.koperasi-syariah.com
- 📍 Alamat: Jl. Syariah No. 123, Jakarta

**Jam Operasional:**
- Senin - Jumat: 08:00 - 17:00
- Sabtu: 08:00 - 12:00

---

*Dokumen ini adalah manual resmi penggunaan aplikasi Koperasi Syariah Bersama. Generated pada tanggal {{ date('Y-m-d H:i:s') }}*