# Koperasi Syariah - Aplikasi Manajemen Koperasi Syariah

## 📋 Deskripsi
Aplikasi berbasis web untuk mengelola kegiatan operasional koperasi syariah dengan sistem multi-role (Admin, Pengurus, Anggota).

## 🔧 Requirements
- PHP 7.4+
- MySQL 5.7+ atau MariaDB 10.3+
- Composer
- Node.js & NPM (untuk frontend assets)

## 📦 Fitur Utama

### Role Management
- **Admin**: Manajemen pengurus, data koperasi, dan user system
- **Pengurus**: Manajemen anggota, transaksi, pembiayaan (berdasarkan posisi)
- **Anggota**: Riwayat simpanan, riwayat pembiayaan, pengajuan pembiayaan

### Manajemen Simpanan (4 Jenis)
- **Simpanan Modal**: Sekali bayar, tidak bisa ditarik, menentukan hak suara
- **Simpanan Pokok**: Minimal wajib, bisa ditarik saat keluar
- **Simpanan Wajib**: Iuran bulanan wajib
- **Simpanan Sukarela**: Sesuai kemampuan, bisa setor/ambil kapan saja

### Manajemen Pembiayaan
- Jenis pembiayaan syariah (murabahah, mudharabah, musyarakah, qardh)
- Workflow approval (Anggota → Verifikasi → Persetujuan Ketua → Pencairan)
- Perhitungan angsuran otomatis

### Posisi Pengurus
- **Ketua**: Persetujuan final pengajuan pembiayaan
- **Bendahara**: Pencairan dana dan input pembayaran
- **Sekretaris/Pengurus Lainnya**: Verifikasi dokumen dan monitoring

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd koperasi-syariah/koperasi-syariah-app
```

### 2. Install Dependencies
```bash
composer install --ignore-platform-reqs
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
mysql -u root -p
CREATE DATABASE koperasi_syariah;
```

Edit `.env` file:
```env
DB_DATABASE=koperasi_syariah
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run Migrations
```bash
php artisan migrate
php artisan db:seed
```

### 6. Compile Frontend Assets
```bash
npm run dev
```

### 7. Start Application
```bash
php artisan serve --port=8003
```

Aplikasi akan berjalan di `http://localhost:8003`

## 📁 Struktur Direktori
```
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── ...
├── database/
│   ├── migrations/
│   └── seeds/
├── resources/
│   ├── views/
│   └── ...
├── public/
└── storage/
```

## 🔐 Default Login
Setelah instalasi, Anda dapat login dengan:
- **Admin**: admin@example.com / password
- **Pengurus**: manager@example.com / password

## 📞 Support
Untuk bantuan dan pertanyaan, silakan hubungi tim development.

## 📄 License
MIT License