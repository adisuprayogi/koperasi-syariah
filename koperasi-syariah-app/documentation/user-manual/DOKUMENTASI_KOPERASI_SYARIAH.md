# DOKUMENTASI LENGKAP APLIKASI KOPERASI SYARIAH

**URL Aplikasi:** http://127.0.0.1:8010
**Tanggal Dokumentasi:** 12 Desember 2025

---

## 📋 INFORMASI LOGIN PER ROLE

### 1. ROLE ANGGOTA
- **Username:** 2521.00001
- **Password:** 22222222
- **URL Login:** http://127.0.0.1:8010/login

### 2. ROLE KETUA PENGURUS
- **Username:** yogi@gmail.com
- **Password:** 22222222
- **URL Login:** http://127.0.0.1:8010/login

### 3. ROLE BENDAHARA
- **Username:** fitri@gmail.com
- **Password:** 33333333
- **URL Login:** http://127.0.0.1:8010/login

### 4. ROLE ADMINISTRATOR
- **Username:** admin@admin.com
- **Password:** password
- **URL Login:** http://127.0.0.1:8010/login

---

## 👤 ROLE ANGGOTA

### Dashboard Anggota
**URL:** http://127.0.0.1:8010/dashboard/anggota

#### Menu yang Tersedia untuk Anggota:

1. **Dashboard**
   - Menampilkan informasi profil anggota
   - Total simpanan pokok
   - Total simpanan wajib
   - Total simpanan sukarela
   - Total pinjaman yang sedang aktif
   - Grafik riwayat transaksi

2. **Profil Saya**
   - Lihat dan edit data pribadi
   - Upload foto profil
   - Update informasi kontak
   - Ganti password

3. **Simpanan**
   - **Simpanan Pokok**: Lihat riwayat dan status simpanan pokok
   - **Simpanan Wajib**: Lihat riwayat pembayaran simpanan wajib bulanan
   - **Simpanan Sukarela**: Input dan lihat riwayat simpanan sukarela

4. **Pinjaman**
   - Ajukan pinjaman baru
   - Lihat status pengajuan pinjaman
   - Lihat jadwal angsuran
   - Riwayat pembayaran angsuran
   - Detail sisa pinjaman

5. **Angsuran**
   - Bayar angsuran pinjaman
   - Lihat riwayat pembayaran angsuran
   - Cetak bukti pembayaran
   - Lihat jumlah tunggakan (jika ada)

6. **SHU (Sisa Hasil Usaha)**
   - Lihat perhitungan SHU tahunan
   - Riwayat pembagian SHU
   - Download laporan SHU

7. **Laporan**
   - Cetak buku simpanan
   - Cetak riwayat pinjaman
   - Download laporan transaksi pribadi

8. **Notifikasi**
   - Notifikasi pembayaran angsuran
   - Informasi penting dari pengurus
   - Pengumuman koperasi

---

## 👔 ROLE PENGURUS (KETUA & BENDAHARA)

### Dashboard Pengurus
**URL:** http://127.0.0.1:8010/dashboard/pengurus

#### Menu yang Tersedia untuk Pengurus:

**Semua menu Anggota + Menu tambahan berikut:**

1. **Dashboard Pengurus**
   - Statistik keseluruhan koperasi
   - Grafik perkembangan anggota
   - Total simpanan semua anggota
   - Total pinjaman aktif
   - Rasio pinjaman terhadap simpanan
   - Indikator kesehatan koperasi

2. **Data Anggota**
   - **Daftar Anggota**: Lihat semua data anggota
   - **Registrasi Anggota Baru**: Tambah anggota baru
   - **Verifikasi Anggota**: Setujui/tolak pendaftaran
   - **Update Data Anggota**: Edit data anggota
   - **Status Keanggotaan**: Aktif/non-aktifkan anggota

3. **Manajemen Simpanan**
   - **Verifikasi Simpanan**: Setujui simpanan anggota
   - **Laporan Simpanan**: Lihat semua simpanan
   - **Setting Jumlah Wajib**: Atur jumlah simpanan wajib
   - **Export Data Simpanan**: Download Excel/PDF

4. **Manajemen Pinjaman**
   - **Verifikasi Pinjaman**: Setujui/tolak pengajuan pinjaman
   - **Pencairan Pinjaman**: Proses pencairan dana
   - **Daftar Tunggakan**: Lihat anggota menunggak
   - **Reminder Tunggakan**: Kirim notifikasi
   - **Setting Bunga**: Atur persentase bunga pinjaman

5. **Keuangan**
   - **Jurnal Umum**: Input transaksi keuangan
   - **Buku Besar**: Lihat semua akun
   - **Neraca Saldo**: Lihat saldo per akun
   - **Laporan Laba Rugi**: Lihat keuntungan/kerugian
   - **Arus Kas**: Lihat cash flow koperasi

6. **SHU Management**
   - **Perhitungan SHU**: Hitung SHU tahunan
   - **Pembagian SHU**: Bagi SHU ke anggota
   - **Laporan SHU**: Cetak laporan SHU
   - **Setting Ratio SHU**: Atur persentase pembagian

7. **Laporan Manajemen**
   - **Laporan Bulanan**: Generate laporan bulanan
   - **Laporan Tahunan**: Generate laporan tahunan
   - **Laporan Auditor**: Prepare untuk audit
   - **Export Laporan**: Download dalam berbagai format

8. **Pengaturan Pengurus**
   - **Management User**: Atur user pengurus
   - **Hak Akses**: Set permission per role
   - **Backup Data**: Backup database
   - **System Logs**: Lihat aktivitas sistem

---

## 🛡️ ROLE ADMINISTRATOR

### Dashboard Administrator
**URL:** http://127.0.0.1:8010/dashboard/admin

#### Menu yang Tersedia untuk Administrator:

**Semua menu Anggota & Pengurus + Menu tambahan berikut:**

1. **Dashboard Administrator**
   - Monitoring sistem real-time
   - Statistik pengguna aktif
   - Performance monitoring
   - Security alerts
   - System health check

2. **System Configuration**
   - **General Settings**: Pengaturan umum aplikasi
   - **Email Configuration**: Setup email server
   - **Backup Settings**: Atur jadwal backup
   - **Security Settings**: Konfigurasi keamanan
   - **API Configuration**: Setup API jika ada

3. **User Management**
   - **Manage All Users**: Kelola semua user
   - **Role & Permission**: Atur role dan hak akses
   - **User Activity**: Monitor aktivitas user
   - **Login History**: Lihat riwayat login
   - **Blocked Users**: Kelola user yang diblokir

4. **Database Management**
   - **Database Status**: Monitor database
   - **Query Manager**: Jalankan query manual
   - **Data Migration**: Migrasi data
   - **Optimization**: Optimize database
   - **Restore Data**: Restore dari backup

5. **Security & Audit**
   - **Security Logs**: Lihat log keamanan
   - **Audit Trail**: Track perubahan data
   - **Failed Login**: Monitor login gagal
   - **IP Whitelist**: Atur IP yang diizinkan
   - **Two Factor Auth**: Setup 2FA

6. **Module Management**
   - **Installed Modules**: Lihat modul aktif
   - **Install New Module**: Tambah modul baru
   - **Update System**: Update aplikasi
   - **License Management**: Kelola lisensi

7. **Report & Analytics**
   - **System Reports**: Generate laporan sistem
   - **Usage Analytics**: Analisis penggunaan
   - **Performance Report**: Laporan performa
   - **Error Reports**: Laporan error sistem
   - **Custom Reports**: Buat report custom

8. **Developer Tools**
   - **API Documentation**: Dokumentasi API
   - **Debug Mode**: Enable/disable debug
   - **Clear Cache**: Hapus cache sistem
   - **Test Connection**: Test koneksi database
   - **System Info**: Informasi teknis sistem

---

## 🔄 ALUR KERJA APLIKASI

### Alur Pendaftaran Anggota:
1. Admin/Pengurus input data anggota baru
2. Sistem generate nomor anggota otomatis
3. Anggota login dengan nomor anggota + password default
4. Anggota mengganti password dan melengkapi profil

### Alur Pengajuan Pinjaman:
1. Anggota ajukan pinjaman via dashboard
2. Pengurus review dokumen persyaratan
3. Ketua approve/reject pengajuan
4. Bendahara proses pencairan jika approve
5. Anggota dapat melihat jadwal angsuran

### Alur Pembayaran Angsuran:
1. Anggota bayar angsuran via dashboard
2. Sistem update status pembayaran
3. Otomatis update sisa pinjaman
4. Generate bukti pembayaran
5. Notifikasi ke pengurus jika ada tunggakan

---

## 📱 FITUR UTAMA PER ROLE

### Anggota:
✅ Simpanan (Pokok, Wajib, Sukarela)
✅ Pengajuan Pinjaman
✅ Pembayaran Angsuran
✅ Lihat SHU
✅ Cetak Laporan Pribadi
✅ Notifikasi Pembayaran

### Pengurus:
✅ Semua fitur Anggota
✅ Verifikasi Transaksi
✅ Manajemen Data Anggota
✅ Laporan Keuangan
✅ Perhitungan SHU
✅ Monitoring Tunggakan

### Administrator:
✅ Semua fitur Anggota & Pengurus
✅ Konfigurasi Sistem
✅ User Management
✅ Database Management
✅ Security & Audit
✅ System Monitoring

---

## 🔒 KEAMANAN SISTEM

- Password minimal 8 karakter
- Session timeout setelah 30 menit idle
- Log semua aktivitas penting
- Backup otomatis harian
- enkripsi data sensitif
- Role-based access control

---

## 📞 KONTAK SUPPORT

Untuk bantuan teknis atau pertanyaan seputar aplikasi:
- **Email Support:** support@koperasi-syariah.com
- **Hotline:** 08xx-xxxx-xxxx
- **WhatsApp:** 08xx-xxxx-xxxx

---

*© 2025 - Koperasi Syariah - Dokumentasi Sistem*