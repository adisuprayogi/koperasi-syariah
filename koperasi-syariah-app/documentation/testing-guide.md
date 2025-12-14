# Testing Guide - Akses dan Eksplorasi Setiap Role

## 🚀 Quick Start

### URL Aplikasi
**http://127.0.0.1:8010**

---

## 1. ANGGOTA ROLE TESTING

### 🔑 Login Credentials
- **Username**: `2521.00001`
- **Password**: `22222222`
- **Access URL**: http://127.0.0.1:8010/login

### 📋 Testing Steps

#### Step 1: Login Process
1. Buka browser: http://127.0.0.1:8010
2. Klik "Log in" di pojok kanan atas
3. Masukkan credentials:
   - Email/Nomor Anggota: `2521.00001`
   - Password: `22222222`
4. Klik "Masuk ke Akun"

#### Step 2: Dashboard Navigation
**URL**: `/anggota/dashboard`

**Available Menus:**
- ✅ Dashboard (Total simpanan, pembiayaan aktif, quick actions)
- ✅ Profil (Edit data pribadi, foto, kontak)
- ✅ Simpanan (Lihat saldo, transaksi, download bukti)
- ✅ Pengajuan (Ajukan pembiayaan baru, upload dokumen)
- ✅ Pembiayaan (Status aktif, jadwal angsuran, detail)
- ✅ Ubah Password (Security settings)
- ✅ Logout (Keluar sistem)

#### Step 3: Test Scenarios
1. **View Total Simpanan**: Cek saldo semua jenis simpanan
2. **Create Pengajuan**: Ajukan pembiayaan baru
3. **View Pembiayaan**: Monitor status dan jadwal angsuran
4. **Edit Profil**: Update data pribadi
5. **Download Kartu**: Generate kartu anggota PDF

---

## 2. PENGURUS ROLE TESTING

### 👥 Ketua Pengurus Login
- **Username**: `yogi@gmail.com`
- **Password**: `22222222`
- **Access URL**: http://127.0.0.1:8010/login

### 💰 Bendahara Login
- **Username**: `fitri@gmail.com`
- **Password**: `33333333`
- **Access URL**: http://127.0.0.1:8010/login

### 📋 Testing Steps

#### Step 1: Login Process
1. Buka browser: http://127.0.0.1:8010/login
2. Masukkan email dan password sesuai role
3. Klik "Masuk ke Akun"

#### Step 2: Dashboard Navigation
**URL**: `/pengurus/dashboard`

**Available Menus:**
- ✅ Dashboard (Stats overview, recent activities)
- ✅ Data Anggota (CRUD anggota, search, filter)
- ✅ Simpanan (Input transaksi, riwayat, API saldo)
- ✅ Pengajuan (Approval workflow, verifikasi, pencairan)
- ✅ Pembiayaan (Manajemen aktif, input angsuran, generate jadwal)
- ✅ Laporan (Harian/Mingguan/Bulanan, Excel export)
- ✅ Ubah Password (Security settings)
- ✅ Logout

#### Step 3: Test Scenarios for Ketua Pengurus
1. **Manage Anggota**: Tambah anggota baru, edit data existing
2. **Approve Pengajuan**: Verifikasi dan approve pengajuan pembiayaan
3. **Generate Reports**: Create laporan bulanan
4. **Monitor Pembiayaan**: Review pembiayaan aktif

#### Step 4: Test Scenarios for Bendahara
1. **Input Simpanan**: Record transaksi simpanan anggota
2. **Process Pembayaran**: Input pembayaran angsuran
3. **Cetak Bukti**: Generate PDF bukti transaksi
4. **Export Laporan**: Download Excel reports
5. **API Saldo**: Test saldo inquiry API

---

## 3. ADMINISTRATOR ROLE TESTING

### 🛡️ Login Credentials
- **Username**: `admin@admin.com`
- **Password**: `password`
- **Access URL**: http://127.0.0.1:8010/login

### 📋 Testing Steps

#### Step 1: Login Process
1. Buka browser: http://127.0.0.1:8010/login
2. Masukkan credentials admin
3. Klik "Masuk ke Akun"

#### Step 2: Dashboard Navigation
**URL**: `/admin/dashboard`

**Available Menus:**
- ✅ Dashboard (System overview, analytics)
- ✅ Pengurus Management (CRUD pengurus, permissions)
- ✅ Koperasi Management (Master data, settings)
- ✅ Data Koperasi (Advanced database management)
- ✅ Jenis Simpanan (Master simpanan types)
- ✅ Jenis Pembiayaan (Master pembiayaan types)
- ✅ Kartu Anggota (Generate, settings, PDF)
- ✅ Ubah Password (Security settings)
- ✅ Logout

#### Step 3: Test Scenarios
1. **Master Data Management**:
   - Add/Edit jenis simpanan
   - Setup jenis pembiayaan dengan margin
   - Configure koperasi settings

2. **Pengurus Management**:
   - Tambah pengurus baru (Ketua, Bendahara, Sekretaris)
   - Edit data pengurus existing
   - Delete/restore pengurus

3. **Kartu Anggota System**:
   - Upload logo dan signature
   - Configure card settings
   - Generate kartu anggota PDF
   - Preview dan download

4. **System Configuration**:
   - Update data koperasi
   - Manage user permissions
   - Monitor system performance

---

## 4. FLOW TESTING SCENARIOS

### 🔄 Complete User Flow Test

#### Scenario 1: Anggota Registration to Pembiayaan
1. **Admin**: Tambah anggota baru di system
2. **Anggota**: Login dengan nomor anggota baru
3. **Anggota**: Setup profil dan ajukan pembiayaan
4. **Pengurus**: Review dan approve pengajuan
5. **Pengurus**: Cairkan dana pembiayaan
6. **Anggota**: View pembiayaan aktif di dashboard
7. **Pengurus**: Input pembayaran angsuran
8. **Admin**: Generate comprehensive reports

#### Scenario 2: Simpanan Transaction Flow
1. **Anggota**: Cek saldo simpanan
2. **Pengurus**: Input transaksi simpanan baru
3. **Pengurus**: Cetak bukti transaksi PDF
4. **Anggota**: View updated saldo
5. **Admin**: Generate laporan simpanan
6. **Pengurus**: Export Excel reports

#### Scenario 3: Multi-Role Collaboration
1. **Admin**: Setup master data (jenis simpanan/pembiayaan)
2. **Admin**: Tambah pengurus dengan role assignments
3. **Pengurus**: Register anggota baru
4. **Anggota**: Login dan explore features
5. **Pengurus**: Process daily transactions
6. **Admin**: Review system reports

---

## 5. TECHNICAL TESTING

### 🌐 Network Testing
- Test responsive design di mobile/tablet/desktop
- Verify all routes accessible
- Test API endpoints functionality
- Check PDF generation and downloads
- Validate Excel export functionality

### 🔒 Security Testing
- Test role-based access control
- Verify unauthorized access prevention
- Test session management
- Validate form submissions
- Check CSRF protection

### 📱 UI/UX Testing
- Verify responsive layout
- Test navigation flow
- Check form validation
- Verify loading states
- Test error handling

---

## 6. EXPECTED OUTCOMES

### ✅ Success Indicators
- All roles can login successfully
- Dashboard loads with correct data
- Navigation works properly
- Forms submit without errors
- PDF/Excel downloads work
- Responsive design functions
- Data persists correctly
- Reports generate accurately

### ⚠️ Common Issues & Solutions
- **Login fails**: Verify credentials in database
- **PDF not downloading**: Check DomPDF configuration
- **Excel export error**: Verify Laravel Excel setup
- **Permission denied**: Check role assignments
- **Layout broken**: Clear browser cache

---

## 7. TESTING CHECKLIST

### Role Access Checklist
- [ ] Anggota login dengan `2521.00001` / `22222222`
- [ ] Ketua Pengurus login dengan `yogi@gmail.com` / `22222222`
- [ ] Bendahara login dengan `fitri@gmail.com` / `33333333`
- [ ] Admin login dengan `admin@admin.com` / `password`

### Functionality Checklist
- [ ] Dashboard loads with correct data for each role
- [ ] All navigation menus work properly
- [ ] CRUD operations complete without errors
- [ ] PDF generation and download works
- [ ] Excel export functionality works
- [ ] Form validation works correctly
- [ ] Search and filter functions work
- [ ] Responsive design on mobile devices

### Data Flow Checklist
- [ ] Anggota registration complete
- [ ] Simpanan transactions recorded
- [ ] Pengajuan workflow functions
- [ ] Pembiayaan management works
- [ ] Report generation complete
- [ ] User permissions enforced

---

## 📞 Support Contact

Jika menemukan issues selama testing:
- **Developer Support**: Available during testing session
- **Documentation**: Refer to user manual
- **Error Logs**: Check Laravel logs for debugging

---

**Testing Environment**: http://127.0.0.1:8010
**Database**: Local development database
**Logs Location**: `/storage/logs/laravel.log`