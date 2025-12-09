# Business Requirements - Aplikasi Koperasi Syariah

## 1. Overview
Sistem informasi koperasi syariah yang mengelola kegiatan operasional koperasi dengan 3 tingkat akses pengguna: Admin, Pengurus, dan Anggota.

## 2. Role dan Tanggung Jawab

### 2.1 Admin
- Mengatur data legalitas koperasi
- **Manajemen User/Anggota** (data keanggotaan koperasi dengan auto-create user account)
- **Manajemen Pengurus** (buat/edit akun Pengurus)

### 2.2 Pengurus
**Secara umum:**
- **Manajemen User/Anggota** (data keanggotaan koperasi dengan auto-create user account)
- **Reset Password Anggota** (hanya reset password, tidak buat akun)
- Manajemen jenis pembiayaan
- Manajemen simpanan
- Manajemen pembiayaan
- Input transaksi pembayaran simpanan
- Input transaksi penarikan simpanan

**Per posisi:**
- **Ketua**:
  - Persetujuan final pengajuan pembiayaan
  - Final approval (approve/reject)
- **Bendahara**:
  - Input transaksi pembayaran pembiayaan (pencairan)
  - Input pembayaran angsuran pembiayaan
- **Sekretaris/Pengurus Lainnya**:
  - Verifikasi dokumen dan data pengajuan
  - Monitoring dan reporting

### 2.3 Anggota
- Melihat riwayat simpanan
- Melihat riwayat pembiayaan
- Mengajukan pembiayaan baru

## 3. Functional Requirements

### 3.1 Modul Autentikasi dan Authorization
- Login sistem dengan username dan password
- Role-based access control
- Session management
- Reset password
- First-time login password change

### 3.2 Modul Manajemen Pengurus (Admin Only)
- Buat akun Pengurus baru
- Edit data Pengurus (nama, email, username)
- Reset password Pengurus
- Aktivasi/deaktivasi akun Pengurus
- Edit/delete akun Pengurus

### 3.3 Modul Manajemen User/Anggota Koperasi (Admin & Pengurus)

#### 3.3.1 Registrasi Anggota dengan Auto User Creation
- Form pendaftaran data anggota koperasi
- **Otomatis membuat akun pengguna dengan role Anggota:**
  - Username: generate dari NIK/No. Anggota
  - Default password: NIK/No. HP (first login harus ganti password)
  - Email notifikasi untuk credentials
- Update data keanggotaan (biodata, kontak, pekerjaan)
- Verifikasi status keanggotaan
- Upload dokumen keanggotaan (KTP, KK, NPWP)
- Kategorisasi jenis anggota (biasa, luar biasa, kehormatan)
- Riwayat perubahan data anggota
- Status keaktifan anggota

#### 3.3.2 Fitur Auto-Registration
- Generate username secara otomatis (format: ANG_[tanggal]_[nomor_urut])
- Generate default password (format: TglLahir[DDMMYY] + [3 digit random])
- Send welcome email dengan login credentials
- Force password change pada first login
- Create profile anggota otomatis

#### 3.3.3 Manajemen Password Anggota (Pengurus)
- Reset password anggota
- Aktivasi/deaktivasi akun anggota
- Unlock akun terkunci

### 3.4 Modul Data Koperasi (Admin)
- Data profil koperasi (nama, alamat, telepon, email)
- Data legalitas (akta notaris, no. koperasi, tanggal berdiri)
- Data pengurus (ketua, sekretaris, bendahara)
- Upload dokumen legalitas

### 3.5 Modul Jenis Simpanan (Pengurus)
- Master jenis simpanan (4 jenis):
  - **Simpanan Modal**:
    - Satu kali bayar saat pendaftaran
    - Tidak bisa diambil kembali
    - Menentukan hak suara dalam rapat anggota
  - **Simpanan Pokok**:
    - Minimal wajib sesuai AD/ART
    - Bisa diambil saat keluar dari koperasi
    - Sebagai jaminan keanggotaan
  - **Simpanan Wajib**:
    - Iuran bulanan wajib
    - Sesuai dengan peraturan AD/ART
    - Bukan untuk penarikan sewaktu-waktu
  - **Simpanan Sukarela**:
    - Simpanan sesuai kemampuan anggota
    - Bisa disetor/ambil kapan saja
    - Dapat hasil bagi (nisbah)
- Setup hasil bagi (nisbah) untuk masing-masing jenis
- Set minimal dan maksimal simpanan
- Aturan penarikan sesuai jenis simpanan
- Auto-calculation untuk simpanan wajib bulanan

### 3.6 Modul Jenis Pembiayaan (Pengurus)
- Master jenis pembiayaan (murabahah, mudharabah, musyarakah, qardh)
- Set margin/keuntungan
- Set tenor pembiayaan
- Persyaratan pembiayaan
- Perhitungan angsuran

### 3.7 Modul Transaksi Simpanan (Pengurus)
- Input setor simpanan untuk 4 jenis:
  - **Simpanan Modal**: Saat pendaftaran anggota
  - **Simpanan Pokok**: Saat pendaftaran atau tambahan
  - **Simpanan Wajib**: Auto-generate bulanan atau input manual
  - **Simpanan Sukarela**: Input sesuai setor anggota
- Input penarikan simpanan (sesuai aturan jenis):
  - **Modal**: Tidak bisa ditarik
  - **Pokok**: Bisa ditarik saat keluar koperasi
  - **Wajib**: Tidak bisa ditarik (kecuali keluar)
  - **Sukarela**: Bisa ditarik kapan saja
- Generate bukti transaksi
- Transaction validation sesuai aturan
- Balance calculation per jenis
- Laporan transaksi harian/mingguan/bulanan
- Auto-calculate simpanan wajib terhutang

### 3.8 Modul Pengajuan Pembiayaan (Anggota & Pengurus)
- **Anggota**:
  - Form pengajuan pembiayaan
  - Upload dokumen persyaratan
  - Lihat status pengajuan
  - Lihat riwayat pengajuan
- **Pengurus** (berdasarkan posisi):
  - **Ketua**:
    - Review hasil verifikasi
    - Persetujuan final (approve/reject)
    - Add notes/catatan persetujuan
  - **Bendahara**:
    - View approved applications
    - Input pencairan dana
    - Upload bukti transfer
  - **Sekretaris/Pengurus Lainnya**:
    - Verifikasi dokumen dan data pengajuan
    - Add catatan verifikasi
    - Submit to Ketua untuk persetujuan
- Workflow approval (Pengajuan → Verifikasi oleh Pengurus → Persetujuan Ketua → Pencairan oleh Bendahara)

### 3.9 Modul Manajemen Pembiayaan (Pengurus)
- **Bendahara**:
  - Input pencairan pembiayaan (setelah approval Ketua)
  - Input pembayaran angsuran dari anggota
  - Upload bukti pencairan/pembayaran
  - Perhitungan sisa pokok dan margin
- **Ketua**:
  - View semua transaksi pembiayaan
  - Monitoring pembayaran
  - Approval pelunasan dipercepat
- **Semua Pengurus**:
  - Generate jadwal angsuran
  - View laporan pembayaran
  - Monitoring tunggakan

### 3.10 Modul Laporan (Pengurus)
- Laporan simpanan per anggota:
  - Detail per jenis simpanan (Modal, Pokok, Wajib, Sukarela)
  - Total simpanan keseluruhan
  - History transaksi simpanan
- Laporan rekap simpanan:
  - Total per jenis simpanan
  - Bulanan/kuartalan/tahunan
  - Growth analysis
- Laporan pembiayaan per anggota
- Laporan tunggakan simpanan wajib
- Laporan laba rugi
- Laporan neraca sederhana
- Export ke Excel/PDF

### 3.11 Modul Dashboard
- Dashboard Admin: statistik user, aktivitas sistem, data pengurus
- Dashboard Pengurus: total simpanan, total pembiayaan, pending approval
- Dashboard Anggota:
  - Saldo simpanan per jenis (Modal, Pokok, Wajib, Sukarela)
  - Total simpanan
  - Sisa pembiayaan
  - Status pengajuan

### 3.12 Modul Notifikasi (Otomatis)
- Welcome email untuk anggota baru
- SMS/Email untuk login credentials
- Notifikasi saat pengajuan disetujui/ditolak
- Simpanan wajib monthly reminder
- Pengingat jatuh tempo pembayaran
- Overdue simpanan wajib notifications

## 4. Data Requirements

### 4.1 Master Data
- Data Koperasi
- Data Jenis Simpanan
- Data Jenis Pembiayaan
- Data Pengurus (akun sistem)
- Data User/Anggota Koperasi (terintegrasi dengan user account)

### 4.2 Transaksi Data
- Transaksi Simpanan
- Pengajuan Pembiayaan
- Transaksi Pembiayaan
- Log Aktivitas
- Log Registrasi

## 5. Business Process Flow

### 5.1 Alur Registrasi Anggota Baru
1. Admin/Pengurus input data anggota di modul Manajemen User
2. Sistem otomatis:
   - Buat akun pengguna dengan role Anggota
   - Generate username & password
   - Kirim credentials via email/SMS
   - Set status: "First Login Required"
3. Anggota login pertama kali:
   - Wajib ganti password
   - Update profile jika needed
   - Status berubah menjadi "Active"

### 5.2 Alur Manajemen Pengurus
1. Admin buat akun Pengurus baru
2. Sistem kirim email notifikasi ke Pengurus
3. Pengurus login dan aktivasi akun
4. Pengurus bisa mulai mengelola data anggota

## 6. Non-Functional Requirements

### 6.1 Security
- Enkripsi data sensitif
- Access control berdasarkan role
- Audit trail untuk setiap transaksi
- Backup data berkala
- Password policy (minimal 8 karakter, kombinasi)

### 6.2 Performance
- Response time < 3 detik untuk operasi CRUD
- Dapat menangani 100+ concurrent users
- Optimasi query untuk laporan besar

### 6.3 Usability
- Interface yang user-friendly
- Mobile responsive
- Bantuan/panduan penggunaan

### 6.4 Reliability
- Uptime 99%
- Data integrity
- Error handling yang baik

## 7. Integration Requirements
- Export laporan ke Excel/PDF
- Upload/download dokumen
- Email service untuk notifikasi
- SMS gateway (opsional)

## 8. Compliance
- Sesuai prinsip syariah dalam operasional
- Compliance dengan peraturan koperasi di Indonesia
- Perlindungan data pribadi (privacy policy)

## 9. Success Criteria
- Mempermudah pengelolaan transaksi koperasi
- Mengurangi kesalahan input data manual
- Meningkatkan transparansi keuangan
- Mempercepat proses approval pembiayaan
- Memberikan informasi real-time kepada anggota
- **Otomatisasi pembuatan akun anggota tanpa input manual ganda**
- **Simplifikasi manajemen akun tanpa redundansi**

## 10. Key Features Update

### Simplified User Management
- **Admin**: Hanya kelola Pengurus + Anggota (auto-create user)
- **Pengurus**: Kelola Anggota (auto-create user) + reset password
- **Anggota**: Di-create otomatis saat pendaftaran keanggotaan
- **Tidak ada manajemen pengguna terpisah** - semua terintegrasi

Dengan pendekatan ini, sistem lebih sederhana dan tidak ada redundansi antara manajemen pengguna dan manajemen anggota. Setiap pendaftaran anggota baru otomatis memiliki akses login tanpa proses manual yang berlebihan.