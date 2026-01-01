# ⚡ Quick Start Guide

## 🎯 Panduan Cepat Memulai Aplikasi

### 1️⃣ Persiapan (5 menit)

```bash
# 1. Pastikan Anda berada di direktori project
cd "C:\laragon\www\INFORMATIKA PROJECT 2"

# 2. Install dependencies (jika belum)
composer install
npm install
```

### 2️⃣ Setup Database (2 menit)

```bash
# 1. Buat database di MySQL (via Laragon atau phpMyAdmin)
# Nama database: db_tubes

# 2. Jalankan migration dan seeder
php artisan migrate:fresh --seed
```

### 3️⃣ Build Assets (1 menit)

```bash
# Build frontend assets
npm run build
```

### 4️⃣ Jalankan Aplikasi (1 menit)

**Opsi A: Menggunakan Laragon (Recommended)**
1. Buka Laragon
2. Klik "Start All"
3. Akses: `http://informatika-project-2.test`

**Opsi B: Menggunakan PHP Server**
```bash
php artisan serve
# Akses: http://localhost:8000
```

### 5️⃣ Login & Explore! 🎉

**Login sebagai Admin:**
- Email: `admin@healthdashboard.com`
- Password: `password`

**Login sebagai Manager:**
- Email: `manager@healthdashboard.com`
- Password: `password`

**Login sebagai Staff:**
- Email: `staff@healthdashboard.com`
- Password: `password`

---

## 📱 Menu Utama

### 🏠 Dashboard
- Lihat KPI cards
- Monitor tren kualitas data
- Review security events
- Check quality issues

### 📋 Data Kesehatan
- **Tambah Data**: Klik tombol "Tambah Data Baru"
- **Edit Data**: Klik icon pensil di tabel
- **Lihat Detail**: Klik icon mata
- **Hapus**: Klik icon trash (dengan konfirmasi)

### 📊 Data Quality & Governance
- **Quality Logs**: Monitor log validasi
- **Validasi Data**: Klik "Validasi Semua Data"
- **Quality Report**: Lihat laporan lengkap

### 🔒 Security & User Management
- **Security Logs**: Monitor event keamanan
- **Audit Trail**: Track perubahan data
- **Risk Analysis**: Lihat CIA Triad scores
- **Users**: Kelola user dan role

---

## 🎨 Fitur Unggulan

### ✅ Automatic Quality Scoring
Setiap data yang ditambahkan akan otomatis:
- Dihitung quality score-nya (0-100%)
- Divalidasi kelengkapan field
- Dicek akurasi vital signs
- Diberi status (Valid/Pending/Invalid)

### ✅ Real-time Security Monitoring
Sistem otomatis mencatat:
- Login/logout activity
- Page access
- Data modifications
- Failed attempts
- IP addresses

### ✅ CIA Triad Implementation
Dashboard menampilkan:
- **Confidentiality**: 85% (Kerahasiaan data)
- **Integrity**: 92% (Integritas data)
- **Availability**: 88% (Ketersediaan sistem)

---

## 🔧 Troubleshooting Cepat

### ❌ Error: Database Connection
```bash
# Pastikan MySQL running
# Cek database db_tubes sudah dibuat
# Verifikasi .env: DB_DATABASE=db_tubes
```

### ❌ Error: Page Not Found
```bash
php artisan route:clear
php artisan config:clear
```

### ❌ Error: Assets Not Loading
```bash
npm run build
php artisan view:clear
```

### ❌ Error: Permission Denied
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📊 Sample Data

Setelah seeding, sistem sudah berisi:
- ✅ 3 Roles (Admin, Management, Staff)
- ✅ 3 Users (masing-masing role)
- ✅ 5 Sample health data
- ✅ Quality logs
- ✅ Permissions configured

---

## 🎯 Quick Actions

### Tambah Data Pasien Baru
1. Login sebagai Admin atau Staff
2. Klik menu "Data Kesehatan"
3. Klik "Tambah Data Baru"
4. Isi form (minimal: ID, Nama, Tanggal)
5. Klik "Simpan Data"
6. ✅ Quality score otomatis dihitung!

### Validasi Kualitas Data
1. Login sebagai Admin
2. Klik menu "Data Quality & Governance"
3. Klik "Validasi Semua Data"
4. ✅ Semua data divalidasi otomatis!

### Monitor Security
1. Login sebagai Admin
2. Klik menu "Security & User Management"
3. Pilih "Security Logs"
4. ✅ Lihat semua aktivitas real-time!

### Lihat CIA Triad
1. Login sebagai Admin
2. Klik menu "Security & User Management"
3. Pilih "Risk Analysis"
4. ✅ Lihat score Confidentiality, Integrity, Availability!

---

## 📚 Dokumentasi Lengkap

- **README.md** - Overview & instalasi lengkap
- **DEPLOYMENT.md** - Panduan deployment detail
- **FEATURES.md** - Dokumentasi fitur lengkap
- **QUICK_START.md** - Panduan ini

---

## 💡 Tips & Tricks

### 🎨 UI Tips
- Hover di KPI cards untuk efek animasi
- Klik chart untuk interaksi
- Gunakan progress bar untuk quick insight
- Badge warna menunjukkan status

### 🔒 Security Tips
- Ganti password default setelah login pertama
- Monitor security logs secara berkala
- Review audit trail untuk tracking
- Check CIA Triad scores mingguan

### 📊 Data Quality Tips
- Isi semua field untuk score 100%
- Pastikan vital signs dalam range normal
- Jalankan validasi berkala
- Review quality report bulanan

---

## ✅ Checklist Awal

Sebelum mulai menggunakan:
- [ ] Database db_tubes sudah dibuat
- [ ] Migration & seeder berhasil
- [ ] Assets sudah di-build
- [ ] Bisa login dengan user admin
- [ ] Dashboard menampilkan data
- [ ] Charts rendering dengan baik
- [ ] Bisa tambah data kesehatan baru
- [ ] Quality scoring berfungsi
- [ ] Security logs tercatat
- [ ] Semua menu accessible

---

## 🎉 Selamat!

Aplikasi **Sistem Dashboard Kesehatan Berbasis Kualitas Data dan Keamanan Informasi** sudah siap digunakan!

### Fitur Utama yang Sudah Berjalan:
✅ Dashboard dengan KPI & Charts
✅ CRUD Data Kesehatan
✅ Automatic Quality Scoring
✅ Data Quality Validation
✅ Security Logging & Monitoring
✅ Audit Trail
✅ CIA Triad Analysis
✅ Role-Based Access Control
✅ User Management
✅ Responsive UI

---

**Status**: 🚀 100% Functional - Ready to Use!

**Dibuat dengan ❤️ menggunakan Laravel 12**

