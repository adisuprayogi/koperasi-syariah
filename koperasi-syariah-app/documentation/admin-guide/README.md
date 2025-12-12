# Panduan Administrator Aplikasi Koperasi Syariah

## Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Dashboard Admin](#dashboard-admin)
3. [Manajemen Pengguna](#manajemen-pengguna)
4. [Manajemen Anggota](#manajemen-anggota)
5. [Manajemen Simpanan](#manajemen-simpanan)
6. [Manajemen Pembiayaan](#manajemen-pembiayaan)
7. [Laporan dan Analisis](#laporan-dan-analisis)
8. [Konfigurasi Sistem](#konfigurasi-sistem)
9. [Monitoring dan Maintenance](#monitoring-dan-maintenance)
10. [Security Guidelines](#security-guidelines)

## Pendahuluan

Panduan ini ditujukan untuk administrator dan pengurus koperasi yang bertanggung jawab atas operasional harian sistem koperasi syariah.

### Role dan Hak Akses
- **Super Admin**: Akses penuh ke semua fitur
- **Admin**: Kelola anggota, simpanan, pembiayaan
- **Pengurus**: View laporan, approve transaksi tertentu
- **Operator**: Input transaksi dasar

### Responsibilities
- Verifikasi data anggota baru
- Approval pengajuan pembiayaan
- Monitoring kesehatan keuangan koperasi
- Generate laporan periodik
- Maintenance data sistem

## Dashboard Admin

### Overview Dashboard
Dashboard admin adalah pusat kontrol yang menampilkan:

#### Key Metrics
- **Total Anggota**: Jumlah anggota aktif
- **Total Simpanan**: Nilai seluruh simpanan
- **Total Pembiayaan**: Nilai portofolio pembiayaan
- **Tunggakan**: Total tunggakan angsuran
- **Transaksi Hari Ini**: Jumlah transaksi hari ini

#### Quick Actions
- Tambah anggota baru
- Input transaksi simpanan
- Proses pengajuan pembiayaan
- Generate laporan cepat

#### Recent Activities
- Registrasi anggota terbaru
- Transaksi simpanan terakhir
- Pengajuan pembiayaan pending
- Login aktivitas user

#### Charts & Analytics
- Grafik pertumbuhan simpanan
- Chart distribusi pembiayaan
- Trend pencairan per bulan
- Heatmap aktivitas transaksi

## Manajemen Pengguna

### Melihat Semua Pengguna
1. Menu **Users** → **Semua Pengguna**
2. Filter berdasarkan:
   - Role (Admin/Operator/Pengurus)
   - Status (Aktif/Non-aktif)
   - Tanggal registrasi
3. Search berdasarkan nama atau email

### Menambah User Baru
1. Menu **Users** → **Tambah User**
2. Isi form:
   - **Name**: Nama lengkap user
   - **Email**: Email unik untuk login
   - **Role**: Pilih role sesuai tanggung jawab
   - **Password**: Auto-generate atau custom
   - **Status**: Aktif/Non-aktif
3. Klik "Create User"

### Mengedit User
1. Pilih user dari daftar
2. Klik "Edit"
3. Update informasi:
   - Nama dan email
   - Role dan permissions
   - Status akun
   - Password reset
4. Klik "Update User"

### Mengelola Permissions
Role-based access control (RBAC):

#### Super Admin
- ✅ Semua fitur
- ✅ Konfigurasi sistem
- ✅ Manage users & roles
- ✅ Backup & restore

#### Admin
- ✅ CRUD anggota
- ✅ Input transaksi
- ✅ Generate laporan
- ✅ Approval pembiayaan
- ❌ Manage users
- ❌ Konfigurasi sistem

#### Pengurus
- ✅ View laporan
- ✅ Approve pembiayaan besar
- ✅ Monitoring transaksi
- ❌ Input data
- ❌ Delete data

#### Operator
- ✅ Input simpanan
- ✅ Input angsuran
- ✅ Print bukti transaksi
- ❌ Approval
- ❌ Laporan analitik

## Manajemen Anggota

### Registrasi Anggota Baru

#### Manual Registration
1. Menu **Anggota** → **Tambah Anggota**
2. Isi data lengkap:
   - **Data Pribadi**: Nama, tempat/tanggal lahir, jenis kelamin
   - **Kontak**: Email, telepon, alamat lengkap
   - **Identitas**: No. KTP, KK, NPWP
   - **Pekerjaan**: Pekerjaan, penghasilan, nama perusahaan
   - **Bank**: Rekening bank untuk pencairan
   - **Emergency Contact**: Kontak darurat
3. Upload dokumen:
   - Scan KTP
   - Foto diri
   - Dokumen pendukung lainnya
4. Klik "Simpan Anggota"

#### Verifikasi Registrasi Online
1. Menu **Anggota** → **Registrasi Pending**
2. Review data yang diajukan
3. Cek kelengkapan dokumen
4. Set status:
   - **Approve**: Anggota aktif
   - **Reject**: Tolak dengan alasan
   - **Request Info**: Butuh info tambahan

### Edit Data Anggota
1. Cari anggota di daftar
2. Klik "Detail" → "Edit"
3. Update data yang diperlukan
4. Upload dokumen baru jika perlu
5. Simpan perubahan

### Status Keanggotaan
- **Calon**: Baru registrasi, menunggu verifikasi
- **Aktif**: Sudah diverifikasi, bisa transaksi
- **Tidak Aktif**: Tidak bisa transaksi tapi data tetap
- **Keluar**: Resign dari koperasi
- **Blacklist**: Pelanggaran berat

### Bulk Operations
- **Import**: Import data dari Excel/CSV
- **Export**: Export data anggota
- **Bulk Update**: Update status multiple anggota
- **Bulk Delete**: Hapus multiple anggota (hati-hati)

## Manajemen Simpanan

### Input Transaksi Simpanan

#### Setoran Simpanan
1. Menu **Simpanan** → **Input Transaksi**
2. Pilih anggota (auto-search)
3. Pilih jenis simpanan:
   - Simpanan Pokok
   - Simpanan Wajib
   - Simpanan Sukarela
4. Masukkan jumlah setoran
5. Pilih metode pembayaran:
   - Cash
   - Transfer bank
   - Auto-debet gaji
6. Upload bukti transfer (jika transfer)
7. Klik "Proses Transaksi"

#### Penarikan Simpanan
1. Menu **Simpanan** → **Penarikan**
2. Pilih anggota
3. Pilih jenis simpanan (sukarela saja)
4. Masukkan jumlah penarikan
5. Verifikasi saldo cukup
6. Masukkan alasan penarikan
7. Upload surat penarikan (jika diperlukan)
8. Approve penarikan
9. Proses pembayaran

### Generate Simpanan Wajib Otomatis
1. Menu **Simpanan** → **Generate Wajib**
2. Pilih periode (bulan/tahun)
3. Sistem akan generate untuk semua anggota aktif
4. Review daftar yang akan dibuatkan
5. Klik "Generate Transactions"
6. Print atau notifikasi ke anggota

### Monitoring Simpanan
- **Dashboard**: Real-time overview
- **Detail Per Anggota**: Lihat riwayat lengkap
- **Rekap Per Jenis**: Total per jenis simpanan
- **Analisis Growth**: Trend pertumbuhan simpanan
- **Outstanding**: tunggakan simpanan wajib

### Saldo Awal Periodik
Untuk tahun baru atau sistem baru:
1. Menu **Simpanan** → **Saldo Awal**
2. Pilih anggota
3. Input saldo awal per jenis simpanan
4. Tanggal transaksi awal
5. Keterangan saldo awal
6. Save dan konfirmasi

## Manajemen Pembiayaan

### Proses Approval Pembiayaan

#### Review Pengajuan
1. Menu **Pembiayaan** → **Pengajuan Pending**
2. Klik detail untuk review:
   - **Data Pengaju**: Verifikasi identitas dan keanggotaan
   - **Analisis Kelayakan**: 5C (Character, Capacity, Capital, Collateral, Condition)
   - **Dokumen**: Cek kelengkapan dan validitas
3. Input review notes
4. Set decision:
   - **Approve**: Setuju dengan syarat
   - **Reject**: Tolak dengan alasan
   - **Request Revision**: Butuh data tambahan

#### Analysis Checklist
- [ ] Status keanggotaan aktif
- [ ] Riwayat simpanan bagus
- [ ] Tidak ada tunggakan
- [ ] Penghasilan memadai
- [ ] Agunan mencukupi
- [ ] Dokumen lengkap & valid
- [ ] Tujuan jelas & produktif

### Pencairan Dana
Setelah approval:
1. Menu **Pembiayaan** → **Approved** → **Cairkan Dana**
2. Verifikasi data pencairan:
   - Rekening tujuan
   - Jumlah sesuai approval
   - Biaya admin (jika ada)
3. Upload bukti transfer
4. Konfirmasi pencairan
5. Generate schedule angsuran otomatis
6. Notifikasi ke anggota

### Monitoring Portofolio

#### Dashboard Portofolio
- **Total Outstanding**: Jumlah pokok outstanding
- **Portfolio at Risk**: Portofolio berisiko
- **NPL Ratio**: Non-performing loan ratio
- **Coverage Ratio**: Rasio cadangan
- **Yield**: Return rate portofolio

#### Aging Analysis
- **Current**: Lancar (0-30 hari)
- **Watch List**: Perhatian (31-60 hari)
- **Substandard**: Sub-standard (61-90 hari)
- **Doubtful**: Diragukan (91-180 hari)
- **Loss**: Macet (>180 hari)

#### Collecting Tunggakan
1. Menu **Pembiayaan** → **Tunggakan**
2. Filter berdasarkan keterlambatan
3. Generate surat penagihan:
   - Reminder (1-30 hari)
   - Warning letter (31-60 hari)
   - Final notice (61-90 hari)
4. Schedule follow-up
5. Document collection efforts

### Restructuring & Rescheduling
Untuk anggota mengalami kesulitan:
1. Menu **Pembiayaan** → **Restructuring**
2. Pilih pembiayaan yang akan direstruktur
3. Propose new terms:
   - Perpanjangan tenor
   - Penurunan angsuran sementara
   - Grace period
4. Analisis dampak ke portofolio
5. Approve/reject proposal
6. Update schedule angsuran

## Laporan dan Analisis

### Laporan Keuangan

#### Laporan Harian
1. Menu **Laporan** → **Harian**
2. Pilih tanggal
3. Generate laporan:
   - Cash flow harian
   - Transaksi simpanan
   - Pencairan pembiayaan
   - Pembayaran angsuran
4. Export ke Excel atau PDF

#### Laporan Bulanan
1. Menu **Laporan** → **Bulanan**
2. Pilih bulan dan tahun
3. Available reports:
   - **Laporan Laba Rugi**: Pendapatan vs beban
   - **Laporan Neraca**: Aset, liabilitas, ekuitas
   - **Laporan Perubahan Ekuitas**: Perubahan modal
   - **Cash Flow**: Arus kas operasi/investasi/finansial
4. Comparative analysis vs bulan sebelumnya

#### Laporan Tahunan
1. Menu **Laporan** → **Tahunan**
2. Pilih tahun fiskal
3. Comprehensive reports:
   - Financial statements lengkap
   - Kinerja portofolio
   - Growth analysis
   - Risk assessment
   - Compliance reports

### Analisis Bisnis

#### Member Analysis
- **Acquisition**: Cara anggota bergabung
- **Retention**: Rate keanggotaan aktif
- **Segmentation**: Demografi anggota
- **Lifetime Value**: Nilai anggota sepanjang masa
- **Churn Analysis**: Alasan keluar

#### Product Analysis
- **Simpanan Mix**: Komposisi jenis simpanan
- **Pembiayaan Mix**: Distribusi jenis pembiayaan
- **Profitability**: Margin per produk
- **Utilization**: Pemakaian fasilitas
- **Cross-sell**: Penjualan produk gabungan

#### Risk Analysis
- **Credit Risk**: Risiko kredit
- **Liquidity Risk**: Risiko likuiditas
- **Operational Risk**: Risiko operasional
- **Market Risk**: Risiko pasar
- **Compliance Risk**: Risiko regulasi

### Custom Reports
1. Menu **Laporan** → **Custom Report**
2. Design query dengan visual interface
3. Pilih data sources
4. Set filters dan parameters
5. Define visualization
6. Schedule automated generation
7. Export ke multiple formats

## Konfigurasi Sistem

### General Settings
1. Menu **Settings** → **General**
2. Configure:
   - **Info Koperasi**: Nama, alamat, kontak
   - **Working Hours**: Jam operasional
   - **Currency**: Simbol dan format mata uang
   - **Date Format**: Format tanggal sistem
   - **Time Zone**: Zona waktu

### Product Configuration

#### Jenis Simpanan
1. Menu **Settings** → **Simpanan**
2. Add/Edit jenis simpanan:
   - Nama produk
   - Minimal setoran
   - Saldo minimal
   - Rate bagi hasil (jika ada)
   - Rules penarikan

#### Jenis Pembiayaan
1. Menu **Settings** → **Pembiayaan**
2. Configure produk:
   - Nama produk
   - Plafond min/max
   - Tenor min/max
   - Rate margin
   - Grace period
   - Collateral requirements

### Fee & Interest Settings
1. Menu **Settings** → **Fee Structure**
2. Configure:
   - Admin fee setoran
   - Admin fee penarikan
   - Late payment fee
   - Early repayment fee
   - Annual membership fee

### Notification Settings
1. Menu **Settings** → **Notifications**
2. Configure channels:
   - **Email**: SMTP settings
   - **SMS**: Gateway API
   - **WhatsApp**: Business API
   - **Push**: Mobile app notifications
3. Configure triggers:
   - Registrasi approval
   - Payment due reminder
   - Payment confirmation
   - Low balance alert

## Monitoring dan Maintenance

### System Health Monitoring
1. Menu **Admin** → **System Health**
2. Monitor:
   - **Server Status**: CPU, Memory, Disk usage
   - **Database**: Connection pool, query performance
   - **Application**: Response time, error rates
   - **Backup Status**: Last successful backup
   - **Security**: Failed login attempts

### Database Maintenance

#### Regular Tasks
- **Backup**: Daily automated backups
- **Optimization**: Weekly table optimization
- **Archive**: Monthly archive old data
- **Cleanup**: Quarterly cleanup logs

#### Manual Tasks
```sql
-- Optimize tables
OPTIMIZE TABLE anggota;
OPTIMIZE TABLE transaksi_simpanan;
OPTIMIZE TABLE pengajuan_pembiayaan;

-- Archive old transactions
INSERT INTO transaksi_simpanan_archive
SELECT * FROM transaksi_simpanan
WHERE tanggal_transaksi < DATE_SUB(NOW(), INTERVAL 2 YEAR);
```

### Log Management
1. Menu **Admin** → **Logs**
2. Monitor:
   - **Application Logs**: Laravel logs
   - **Access Logs**: Web server logs
   - **Error Logs**: System errors
   - **Audit Logs**: User activities
3. Download logs for analysis
4. Set log retention policy

### Performance Optimization

#### Database Optimization
- Add indexes on frequently queried columns
- Partition large tables by date
- Optimize complex queries
- Enable query cache

#### Application Optimization
- Enable OPcache
- Configure Redis cache
- Implement CDN for assets
- Minimize asset sizes

## Security Guidelines

### Access Control
- **Principle of Least Privilege**: Grant minimal necessary access
- **Regular Review**: Quarterly review user permissions
- **Password Policy**: Enforce strong passwords
- **Session Management**: Automatic logout for inactivity
- **Two-Factor Auth**: Enable for admin accounts

### Data Protection
- **Encryption**: Encrypt sensitive data at rest
- **Backup Security**: Encrypt backup files
- **Audit Trail**: Log all data modifications
- **PII Protection**: Mask sensitive personal data
- **GDPR Compliance**: Right to be forgotten

### Security Best Practices

#### Password Security
```
Minimum requirements:
- 12+ characters
- Mix of uppercase, lowercase, numbers, symbols
- Not based on dictionary words
- Changed every 90 days
- No password reuse
```

#### Session Security
- Timeout after 30 minutes inactivity
- Secure cookies (HTTPOnly, Secure)
- CSRF protection enabled
- Rate limiting on login attempts

#### Network Security
- HTTPS mandatory (SSL/TLS)
- Firewall configuration
- VPN access for admin
- IP whitelisting if needed

### Incident Response
1. **Detection**: Monitor security alerts
2. **Assessment**: Evaluate impact and scope
3. **Containment**: Isolate affected systems
4. **Eradication**: Remove threat
5. **Recovery**: Restore normal operations
6. **Lessons Learned**: Document and improve

## Emergency Procedures

### System Down
1. Check server status
2. Review error logs
3. Restart services if needed
4. Restore from backup if necessary
5. Communicate with users

### Data Loss
1. Stop all operations
2. Assess extent of data loss
3. Restore from most recent backup
4. Verify data integrity
5. Communicate incident

### Security Breach
1. Immediately disable affected accounts
2. Assess breach scope
3. Change all passwords
4. Review audit logs
5. Report to management
6. Implement preventive measures

---

**Version**: 1.0.0
**Last Updated**: December 2024
**Next Review**: March 2025