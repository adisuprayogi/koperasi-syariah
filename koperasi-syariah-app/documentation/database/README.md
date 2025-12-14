# Database Schema Documentation

## Overview

Aplikasi Koperasi Syariah menggunakan database MySQL/MariaDB dengan Laravel 11. Database ini dirancang untuk mendukung operasional koperasi syariah secara komprehensif.

## Database Information

- **Database Name**: `koperasi_syariah`
- **Collation**: `utf8mb4_unicode_ci`
- **Engine**: `InnoDB`
- **Version**: MySQL 8.0+ / MariaDB 10.3+

## ERD (Entity Relationship Diagram)

```
┌─────────────────┐         ┌──────────────────────┐         ┌─────────────────┐
│     users       │         │       anggota         │         │     alamat      │
├─────────────────┤         ├──────────────────────┤         ├─────────────────┤
│ id (PK)         │◄────────┤ id (PK)               │◄────────┤ id (PK)         │
│ name            │         │ user_id (FK)          │         │ anggota_id (FK) │
│ email           │         │ no_anggota            │         │ jenis           │
│ email_verified  │         │ nama_lengkap          │         │ alamat          │
│ password        │         │ tempat_lahir          │         │ rt              │
│ remember_token  │         │ tanggal_lahir         │         │ rw              │
│ created_at      │         │ jenis_kelamin         │         │ kelurahan       │
│ updated_at      │         │ no_ktp                │         │ kecamatan       │
└─────────────────┘         │ no_hp                 │         │ kabupaten       │
                            │ email                 │         │ provinsi        │
                            │ status_keanggotaan    │         │ kode_pos        │
                            │ tanggal_join          │         └─────────────────┘
                            │ created_at            │
                            │ updated_at            │
                            └──────────────────────┘
                                      │
                                      │
                                      │
                    ┌─────────────────┼─────────────────┐
                    │                 │                 │
                    ▼                 ▼                 ▼
        ┌────────────────────┐ ┌─────────────────┐ ┌─────────────────┐
        │ transaksi_simpanan │ │ pengajuan       │ │  simpanan_pokok │
        ├────────────────────┤ ├─────────────────┤ ├─────────────────┤
        │ id (PK)           │ │ id (PK)         │ │ id (PK)         │
        │ anggota_id (FK)   │ │ anggota_id (FK) │ │ anggota_id (FK) │
        │ jenis_simpanan_id │ │ jenis_id (FK)   │ │ jumlah          │
        │ kode_transaksi    │ │ kode_pembiayaan │ │ tanggal_setor   │
        │ tanggal_transaksi │ │ jumlah_pengajuan│ │ created_at      │
        │ jumlah            │ │ jumlah_margin   │ │ updated_at      │
        │ jenis_transaksi   │ │ total_pinjaman  │ └─────────────────┘
        │ keterangan        │ │ jangka_waktu    │
        │ saldo_sebelumnya  │ │ tujuan          │
        │ saldo_setelahnya  │ │ status          │
        │ created_at        │ │ tanggal_pengajuan│
        │ updated_at        │ │ created_at      │
        └────────────────────┘ │ updated_at      │
                                 └─────────────────┘
                                          │
                                          ▼
                                ┌──────────────────────┐
                                │     angsuran         │
                                ├──────────────────────┤
                                │ id (PK)              │
                                │ pengajuan_id (FK)    │
                                │ nomor_angsuran      │
                                │ tanggal_jatuh_tempo │
                                │ tanggal_bayar       │
                                │ jumlah_angsuran     │
                                │ pokok               │
                                │ margin              │
                                │ status_bayar        │
                                │ created_at          │
                                │ updated_at          │
                                └──────────────────────┘
```

## Table Definitions

### 1. users

Master table untuk sistem authentication dan authorization.

**Purpose**: User management untuk admin, operator, dan login sistem
**Engine**: InnoDB
**Collation**: utf8mb4_unicode_ci

| Column | Type | Length | Null | Key | Default | Description |
|--------|------|--------|------|-----|---------|-------------|
| id | BIGINT | 20 | NO | PRI | | Auto increment primary key |
| name | VARCHAR | 255 | NO | | | Nama lengkap user |
| email | VARCHAR | 255 | NO | UNI | | Email login (unique) |
| email_verified_at | TIMESTAMP | | YES | | NULL | Email verification timestamp |
| password | VARCHAR | 255 | NO | | | Hashed password |
| remember_token | VARCHAR | 100 | YES | | NULL | Remember me token |
| created_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Last update time |

**Indexes**:
- PRIMARY KEY (id)
- UNIQUE KEY (email)

---

### 2. anggota

Master data untuk seluruh anggota koperasi.

**Purpose**: Data lengkap anggota koperasi
**Engine**: InnoDB
**Collation**: utf8mb4_unicode_ci

| Column | Type | Length | Null | Key | Default | Description |
|--------|------|--------|------|-----|---------|-------------|
| id | BIGINT | 20 | NO | PRI | | Auto increment primary key |
| user_id | BIGINT | 20 | YES | UNI | NULL | Foreign key ke users table |
| no_anggota | VARCHAR | 50 | NO | UNI | | Nomor anggota unik |
| nama_lengkap | VARCHAR | 255 | NO | | | Nama lengkap sesuai KTP |
| tempat_lahir | VARCHAR | 100 | YES | | NULL | Tempat lahir |
| tanggal_lahir | DATE | | YES | | NULL | Tanggal lahir (YYYY-MM-DD) |
| jenis_kelamin | ENUM | | YES | | NULL | L: Laki-laki, P: Perempuan |
| no_ktp | VARCHAR | 20 | YES | UNI | NULL | Nomor KTP (unique) |
| no_hp | VARCHAR | 20 | YES | | NULL | Nomor handphone aktif |
| email | VARCHAR | 255 | YES | | NULL | Email anggota |
| status_keanggotaan | ENUM | | NO | | 'calon' | calon, aktif, tidak_aktif, keluar, blacklist |
| tanggal_join | DATE | | YES | | NULL | Tanggal bergabung koperasi |
| created_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Last update time |

**Indexes**:
- PRIMARY KEY (id)
- UNIQUE KEY (no_anggota)
- UNIQUE KEY (no_ktp)
- UNIQUE KEY (user_id)
- INDEX (status_keanggotaan)

**Foreign Keys**:
- `user_id` references `users(id)` ON DELETE SET NULL

---

### 3. alamat

Table untuk menyimpan multiple alamat anggota (domisili, kantor, dll).

**Purpose**: Data alamat lengkap anggota
**Engine**: InnoDB
**Collation**: utf8mb4_unicode_ci

| Column | Type | Length | Null | Key | Default | Description |
|--------|------|--------|------|-----|---------|-------------|
| id | BIGINT | 20 | NO | PRI | | Auto increment primary key |
| anggota_id | BIGINT | 20 | NO | MUL | | Foreign key ke anggota table |
| jenis | ENUM | | NO | | 'domisili' | domisili, kantor, lainnya |
| alamat | TEXT | | YES | | NULL | Alamat lengkap |
| rt | VARCHAR | 5 | YES | | NULL | Nomor RT |
| rw | VARCHAR | 5 | YES | | NULL | Nomor RW |
| kelurahan | VARCHAR | 100 | YES | | NULL | Kelurahan |
| kecamatan | VARCHAR | 100 | YES | | NULL | Kecamatan |
| kabupaten | VARCHAR | 100 | YES | | NULL | Kabupaten/Kota |
| provinsi | VARCHAR | 100 | YES | | NULL | Provinsi |
| kode_pos | VARCHAR | 10 | YES | | NULL | Kode pos |
| created_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Last update time |

**Indexes**:
- PRIMARY KEY (id)
- INDEX (anggota_id)

**Foreign Keys**:
- `anggota_id` references `anggota(id)` ON DELETE CASCADE

---

### 4. jenis_simpanan

Master data untuk jenis-jenis simpanan yang tersedia.

**Purpose**: Konfigurasi produk simpanan
**Engine**: InnoDB
**Collation**: utf8mb4_unicode_ci

| Column | Type | Length | Null | Key | Default | Description |
|--------|------|--------|------|-----|---------|-------------|
| id | BIGINT | 20 | NO | PRI | | Auto increment primary key |
| nama_simpanan | VARCHAR | 100 | NO | | | Nama jenis simpanan |
| deskripsi | TEXT | | YES | | NULL | Deskripsi produk |
| minimal_setoran | DECIMAL | 15,2 | NO | | 0.00 | Minimal setoran awal |
| minimal_saldo | DECIMAL | 15,2 | NO | | 0.00 | Saldo minimal dipertahankan |
| is_wajib | BOOLEAN | | NO | | FALSE | Apakah simpanan wajib |
| rate_bagihasil | DECIMAL | 5,2 | YES | | NULL | Rate bagi hasil tahunan (%) |
| status | ENUM | | NO | | 'aktif' | aktif, tidak_aktif |
| created_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Last update time |

**Indexes**:
- PRIMARY KEY (id)
- INDEX (is_wajib)
- INDEX (status)

---

### 5. transaksi_simpanan

Table untuk semua transaksi simpanan (setoran dan penarikan).

**Purpose**: Record semua transaksi simpanan anggota
**Engine**: InnoDB
**Collation**: utf8mb4_unicode_ci

| Column | Type | Length | Null | Key | Default | Description |
|--------|------|--------|------|-----|---------|-------------|
| id | BIGINT | 20 | NO | PRI | | Auto increment primary key |
| anggota_id | BIGINT | 20 | NO | MUL | | Foreign key ke anggota |
| jenis_simpanan_id | BIGINT | 20 | NO | MUL | | Foreign key ke jenis_simpanan |
| kode_transaksi | VARCHAR | 50 | NO | UNI | | Kode transaksi unik |
| tanggal_transaksi | DATE | | NO | | | Tanggal transaksi |
| jumlah | DECIMAL | 15,2 | NO | | 0.00 | Jumlah transaksi |
| jenis_transaksi | ENUM | | NO | | 'setor' | setor: setoran, tarik: penarikan |
| keterangan | TEXT | | YES | | NULL | Keterangan transaksi |
| saldo_sebelumnya | DECIMAL | 15,2 | NO | | 0.00 | Saldo sebelum transaksi |
| saldo_setelahnya | DECIMAL | 15,2 | NO | | 0.00 | Saldo setelah transaksi |
| created_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Last update time |

**Indexes**:
- PRIMARY KEY (id)
- UNIQUE KEY (kode_transaksi)
- INDEX (anggota_id)
- INDEX (jenis_simpanan_id)
- INDEX (tanggal_transaksi)
- INDEX (jenis_transaksi)

**Foreign Keys**:
- `anggota_id` references `anggota(id)` ON DELETE CASCADE
- `jenis_simpanan_id` references `jenis_simpanan(id)` ON DELETE RESTRICT

---

### 6. jenis_pembiayaan

Master data untuk jenis-jenis pembiayaan syariah.

**Purpose**: Konfigurasi produk pembiayaan
**Engine**: InnoDB
**Collation**: utf8mb4_unicode_ci

| Column | Type | Length | Null | Key | Default | Description |
|--------|------|--------|------|-----|---------|-------------|
| id | BIGINT | 20 | NO | PRI | | Auto increment primary key |
| nama_pembiayaan | VARCHAR | 100 | NO | | | Nama jenis pembiayaan |
| deskripsi | TEXT | | YES | | NULL | Deskripsi produk |
| minimal_plafond | DECIMAL | 15,2 | NO | | 0.00 | Minimal plafond |
| maksimal_plafond | DECIMAL | 15,2 | NO | | 0.00 | Maksimal plafond |
| minimal_tenor | INT | 11 | NO | | 1 | Minimal tenor (bulan) |
| maksimal_tenor | INT | 11 | NO | | 60 | Maksimal tenor (bulan) |
| rate_margin | DECIMAL | 5,2 | NO | | 0.00 | Rate margin tahunan (%) |
| biaya_admin | DECIMAL | 15,2 | NO | | 0.00 | Biaya administrasi |
| persen_denda | DECIMAL | 5,2 | NO | | 0.00 | Persentase denda keterlambatan |
| status | ENUM | | NO | | 'aktif' | aktif, tidak_aktif |
| created_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Last update time |

**Indexes**:
- PRIMARY KEY (id)
- INDEX (status)

---

### 7. pengajuan_pembiayaan

Table untuk pengajuan pembiayaan oleh anggota.

**Purpose**: Data pengajuan dan approval pembiayaan
**Engine**: InnoDB
**Collation**: utf8mb4_unicode_ci

| Column | Type | Length | Null | Key | Default | Description |
|--------|------|--------|------|-----|---------|-------------|
| id | BIGINT | 20 | NO | PRI | | Auto increment primary key |
| anggota_id | BIGINT | 20 | NO | MUL | | Foreign key ke anggota |
| jenis_id | BIGINT | 20 | NO | MUL | | Foreign key ke jenis_pembiayaan |
| kode_pembiayaan | VARCHAR | 50 | NO | UNI | | Kode pembiayaan unik |
| jumlah_pengajuan | DECIMAL | 15,2 | NO | | 0.00 | Jumlah yang diajukan |
| jumlah_disetujui | DECIMAL | 15,2 | YES | | NULL | Jumlah yang disetujui |
| jumlah_margin | DECIMAL | 15,2 | YES | | NULL | Total margin |
| total_pinjaman | DECIMAL | 15,2 | YES | | NULL | Total pinjaman (pokok+margin) |
| jangka_waktu | INT | 11 | NO | | 0 | Jangka waktu (bulan) |
| rate_margin | DECIMAL | 5,2 | NO | | 0.00 | Rate margin yang diberikan |
| tujuan | TEXT | | YES | | NULL | Tujuan penggunaan dana |
| agunan | TEXT | | YES | | NULL | Informasi agunan |
| status | ENUM | | NO | | 'diajukan' | diajukan, review, approved, cair, lunas, ditolak |
| tanggal_pengajuan | DATE | | NO | | | Tanggal pengajuan |
|tanggal_approve | DATE | | YES | | NULL | Tanggal approval |
| tanggal_cair | DATE | | YES | | NULL | Tanggal pencairan |
| approved_by | BIGINT | 20 | YES | MUL | NULL | User yang approve |
| catatan_review | TEXT | | YES | | NULL | Catatan review |
| created_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Last update time |

**Indexes**:
- PRIMARY KEY (id)
- UNIQUE KEY (kode_pembiayaan)
- INDEX (anggota_id)
- INDEX (jenis_id)
- INDEX (status)
- INDEX (tanggal_pengajuan)
- INDEX (approved_by)

**Foreign Keys**:
- `anggota_id` references `anggota(id)` ON DELETE CASCADE
- `jenis_id` references `jenis_pembiayaan(id)` ON DELETE RESTRICT
- `approved_by` references `users(id)` ON DELETE SET NULL

---

### 8. angsuran

Table untuk jadwal dan pembayaran angsuran pembiayaan.

**Purpose**: Jadwal dan tracking pembayaran angsuran
**Engine**: InnoDB
**Collation**: utf8mb4_unicode_ci

| Column | Type | Length | Null | Key | Default | Description |
|--------|------|--------|------|-----|---------|-------------|
| id | BIGINT | 20 | NO | PRI | | Auto increment primary key |
| pengajuan_id | BIGINT | 20 | NO | MUL | | Foreign key ke pengajuan_pembiayaan |
| nomor_angsuran | INT | 11 | NO | | | Nomor urut angsuran |
| tanggal_jatuh_tempo | DATE | | NO | | | Tanggal jatuh tempo |
| tanggal_bayar | DATE | | YES | | NULL | Tanggal pembayaran |
| jumlah_angsuran | DECIMAL | 15,2 | NO | | 0.00 | Total angsuran (pokok+margin) |
| pokok | DECIMAL | 15,2 | NO | | 0.00 | Jumlah pokok |
| margin | DECIMAL | 15,2 | NO | | 0.00 | Jumlah margin |
| denda | DECIMAL | 15,2 | NO | | 0.00 | Denda keterlambatan |
| status_bayar | ENUM | | NO | | 'belum' | belum, lunas, terlambat |
| keterlambatan_hari | INT | 11 | NO | | 0 | Jumlah hari keterlambatan |
| created_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Last update time |

**Indexes**:
- PRIMARY KEY (id)
- INDEX (pengajuan_id)
- INDEX (tanggal_jatuh_tempo)
- INDEX (status_bayar)
- UNIQUE KEY (pengajuan_id, nomor_angsuran)

**Foreign Keys**:
- `pengajuan_id` references `pengajuan_pembiayaan(id)` ON DELETE CASCADE

---

### 9. simpanan_pokok

Table khusus untuk tracking simpanan pokok (biasanya sekali bayar).

**Purpose**: Record simpanan pokok anggota
**Engine**: InnoDB
**Collation**: utf8mb4_unicode_ci

| Column | Type | Length | Null | Key | Default | Description |
|--------|------|--------|------|-----|---------|-------------|
| id | BIGINT | 20 | NO | PRI | | Auto increment primary key |
| anggota_id | BIGINT | 20 | NO | MUL | | Foreign key ke anggota |
| jumlah | DECIMAL | 15,2 | NO | | 0.00 | Jumlah simpanan pokok |
| tanggal_setor | DATE | | NO | | | Tanggal pembayaran |
| bukti_transaksi | VARCHAR | 255 | YES | | NULL | Nomor bukti transaksi |
| created_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | | NO | | CURRENT_TIMESTAMP | Last update time |

**Indexes**:
- PRIMARY KEY (id)
- UNIQUE KEY (anggota_id)
- INDEX (tanggal_setor)

**Foreign Keys**:
- `anggota_id` references `anggota(id)` ON DELETE CASCADE

---

## Relationships and Constraints

### Foreign Key Constraints

```sql
-- users to anggota relationship
ALTER TABLE anggota
ADD CONSTRAINT fk_anggota_user_id
FOREIGN KEY (user_id) REFERENCES users(id)
ON DELETE SET NULL ON UPDATE CASCADE;

-- anggota to alamat relationship
ALTER TABLE alamat
ADD CONSTRAINT fk_alamat_anggota_id
FOREIGN KEY (anggota_id) REFERENCES anggota(id)
ON DELETE CASCADE ON UPDATE CASCADE;

-- transaksi_simpanan relationships
ALTER TABLE transaksi_simpanan
ADD CONSTRAINT fk_transaksi_anggota_id
FOREIGN KEY (anggota_id) REFERENCES anggota(id)
ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT fk_transaksi_jenis_simpanan_id
FOREIGN KEY (jenis_simpanan_id) REFERENCES jenis_simpanan(id)
ON DELETE RESTRICT ON UPDATE CASCADE;

-- pengajuan_pembiayaan relationships
ALTER TABLE pengajuan_pembiayaan
ADD CONSTRAINT fk_pengajuan_anggota_id
FOREIGN KEY (anggota_id) REFERENCES anggota(id)
ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT fk_pengajuan_jenis_id
FOREIGN KEY (jenis_id) REFERENCES jenis_pembiayaan(id)
ON DELETE RESTRICT ON UPDATE CASCADE,
ADD CONSTRAINT fk_pengajuan_approved_by
FOREIGN KEY (approved_by) REFERENCES users(id)
ON DELETE SET NULL ON UPDATE CASCADE;

-- angsuran relationship
ALTER TABLE angsuran
ADD CONSTRAINT fk_angsuran_pengajuan_id
FOREIGN KEY (pengajuan_id) REFERENCES pengajuan_pembiayaan(id)
ON DELETE CASCADE ON UPDATE CASCADE;

-- simpanan_pokok relationship
ALTER TABLE simpanan_pokok
ADD CONSTRAINT fk_simpanan_pokok_anggota_id
FOREIGN KEY (anggota_id) REFERENCES anggota(id)
ON DELETE CASCADE ON UPDATE CASCADE;
```

## Data Integrity Rules

### Business Rules

1. **Uniqueness Constraints**
   - Email user harus unik
   - Nomor anggota harus unik
   - No. KTP anggota harus unik
   - Kode transaksi harus unik
   - Kode pembiayaan harus unik
   - Satu anggota hanya bisa memiliki satu simpanan pokok

2. **Referential Integrity**
   - Tidak bisa delete anggota yang memiliki transaksi
   - Tidak bisa delete jenis simpanan yang memiliki transaksi
   - Tidak bisa delete jenis pembiayaan yang memiliki pengajuan
   - Delete user akan set user_id di anggota menjadi NULL

3. **Data Validation**
   - Tanggal transaksi tidak boleh di masa depan
   - Jumlah transaksi harus positif
   - Saldo setelah transaksi tidak boleh negatif
   - Tanggal jatuh tempo harus > tanggal pengajuan
   - Rate margin harus >= 0

4. **Status Flow Rules**
   - Anggota: calon → aktif → (tidak_aktif/keluar/blacklist)
   - Pembiayaan: diajukan → review → approved → cair → lunas (atau ditolak)
   - Angsuran: belum → lunas (atau terlambat)

## Indexes for Performance

### Recommended Additional Indexes

```sql
-- For reporting queries
CREATE INDEX idx_transaksi_simpanan_composite ON transaksi_simpanan(anggota_id, jenis_simpanan_id, tanggal_transaksi);
CREATE INDEX idx_pengajuan_composite ON pengajuan_pembiayaan(anggota_id, status, tanggal_pengajuan);
CREATE INDEX idx_angsuran_composite ON angsuran(pengajuan_id, status_bayar, tanggal_jatuh_tempo);

-- For search functionality
CREATE INDEX idx_anggota_search ON anggota(nama_lengkap, no_anggota);
CREATE INDEX idx_transaksi_search ON transaksi_simpanan(kode_transaksi, tanggal_transaksi);
CREATE INDEX idx_pengajuan_search ON pengajuan_pembiayaan(kode_pembiayaan, tanggal_pengajuan);
```

## Sample Data

### Default Users

```sql
-- Super Admin
INSERT INTO users (name, email, password, created_at, updated_at)
VALUES ('Super Admin', 'admin@koperasi.com', '$2y$10$hash_here', NOW(), NOW());

-- Admin
INSERT INTO users (name, email, password, created_at, updated_at)
VALUES ('Administrator', 'admin2@koperasi.com', '$2y$10$hash_here', NOW(), NOW());
```

### Default Jenis Simpanan

```sql
INSERT INTO jenis_simpanan (nama_simpanan, deskripsi, minimal_setoran, minimal_saldo, is_wajib, status, created_at, updated_at) VALUES
('Simpanan Pokok', 'Simpanan wajib saat pertama kali bergabung', 100000.00, 0.00, FALSE, 'aktif', NOW(), NOW()),
('Simpanan Wajib', 'Simpanan bulanan wajib', 50000.00, 0.00, TRUE, 'aktif', NOW(), NOW()),
('Simpanan Sukarela', 'Simpanan tambahan sesuai kemampuan', 10000.00, 10000.00, FALSE, 'aktif', NOW(), NOW());
```

### Default Jenis Pembiayaan

```sql
INSERT INTO jenis_pembiayaan (nama_pembiayaan, deskripsi, minimal_plafond, maksimal_plafond, minimal_tenor, maksimal_tenor, rate_margin, status, created_at, updated_at) VALUES
('Pembiayaan Konsumtif', 'Untuk kebutuhan konsumtif', 1000000.00, 50000000.00, 6, 36, 15.00, 'aktif', NOW(), NOW()),
('Pembiayaan Produktif', 'Untuk modal usaha', 5000000.00, 500000000.00, 12, 60, 12.00, 'aktif', NOW(), NOW()),
('Pembiayaan Multiguna', 'Untuk berbagai kebutuhan', 2000000.00, 100000000.00, 6, 48, 13.00, 'aktif', NOW(), NOW());
```

## Migration Scripts

### Migration Files Location
- `/database/migrations/`

### Key Migration Files
- `2024_01_01_000001_create_users_table.php`
- `2024_01_01_000002_create_anggota_table.php`
- `2024_01_01_000003_create_alamat_table.php`
- `2024_01_01_000004_create_jenis_simpanan_table.php`
- `2024_01_01_000005_create_transaksi_simpanan_table.php`
- `2024_01_01_000006_create_jenis_pembiayaan_table.php`
- `2024_01_01_000007_create_pengajuan_pembiayaan_table.php`
- `2024_01_01_000008_create_angsuran_table.php`
- `2024_01_01_000009_create_simpanan_pokok_table.php`

### Running Migrations

```bash
# Run all migrations
php artisan migrate

# Run specific migration
php artisan migrate --path=database/migrations/2024_01_01_000001_create_users_table.php

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (WARNING: deletes all data)
php artisan migrate:fresh
```

## Backup and Recovery

### Backup Strategy

```bash
# Full database backup
mysqldump -u username -p koperasi_syariah > backup_$(date +%Y%m%d_%H%M%S).sql

# Schema only backup
mysqldump -u username -p --no-data koperasi_syariah > schema_$(date +%Y%m%d_%H%M%S).sql

# Data only backup
mysqldump -u username -p --no-create-info koperasi_syariah > data_$(date +%Y%m%d_%H%M%S).sql
```

### Recovery Process

```bash
# Restore from backup
mysql -u username -p koperasi_syariah < backup_20241211_120000.sql
```

## Performance Optimization

### Query Optimization Tips

1. **Use appropriate indexes** on frequently queried columns
2. **Avoid SELECT \*** in production queries
3. **Use LIMIT** for pagination
4. **Optimize JOIN** operations
5. **Use EXPLAIN** to analyze query performance
6. **Regular maintenance** with OPTIMIZE TABLE

### Monitoring Queries

```sql
-- Slow query log
SHOW VARIABLES LIKE 'slow_query_log';
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 1;

-- Show process list
SHOW PROCESSLIST;

-- Analyze table statistics
ANALYZE TABLE anggota;
ANALYZE TABLE transaksi_simpanan;
ANALYZE TABLE pengajuan_pembiayaan;
```

---

**Version**: 1.0.0
**Last Updated**: December 2024
**Database Version**: MySQL 8.0+ / MariaDB 10.3+