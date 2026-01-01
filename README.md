# Sistem Dashboard Kesehatan Berbasis Kualitas Data dan Keamanan Informasi

Aplikasi Laravel 12 yang mengintegrasikan tiga modul penting: **Reporting & Dashboards**, **Security & User Management**, dan **Data Quality & Governance**.

## 🎯 Fitur Utama

### 1️⃣ Module 8 – Reporting & Dashboards
**Peran**: Decision Layer

**Fitur**:
- ✅ Dashboard monitoring kesehatan real-time
- ✅ Visualisasi KPI & tren data kesehatan
- ✅ Grafik interaktif (Chart.js)
- ✅ Laporan untuk berbagai stakeholder
- ✅ Evaluasi efektivitas dashboard

**Halaman**:
- Dashboard utama dengan KPI cards
- Tren kualitas data (7 hari terakhir)
- Distribusi diagnosis
- Data kesehatan terbaru
- Security events monitoring

### 2️⃣ Module 11 – Security & User Management
**Peran**: Protection Layer

**Fitur**:
- ✅ CIA Triad Implementation (Confidentiality, Integrity, Availability)
- ✅ Role-Based Access Control (Admin, Management, Staff)
- ✅ Security Logs & Monitoring
- ✅ Audit Trail untuk tracking perubahan
- ✅ Risk Analysis Dashboard
- ✅ User Management

**Halaman**:
- Security Logs dengan event tracking
- Audit Trail dengan detail perubahan
- Risk Analysis (CIA Triad)
- User Management

### 3️⃣ Module 12 – Data Quality & Governance
**Peran**: Trust Layer

**Fitur**:
- ✅ Validasi kualitas data (akurasi, kelengkapan)
- ✅ Automatic quality scoring
- ✅ Data governance & compliance
- ✅ Quality metrics (Completeness, Accuracy, Validity)
- ✅ Quality logs & reporting
- ✅ Impact analysis pada analitik

**Halaman**:
- Data Quality Dashboard
- Quality Logs
- Comprehensive Quality Report

## 🚀 Teknologi

- **Framework**: Laravel 12
- **Frontend**: Bootstrap 5, Chart.js
- **Database**: MySQL (db_tubes)
- **PHP**: 8.2+
- **Node.js**: 18+

## 📦 Instalasi

### 1. Clone atau Setup Project
```bash
cd C:\laragon\www\INFORMATIKA PROJECT 2
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
File `.env` sudah dikonfigurasi dengan:
```env
DB_CONNECTION=mysql
DB_DATABASE=db_tubes
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migration & Seeder
```bash
php artisan migrate:fresh --seed
```

### 6. Compile Assets
```bash
npm run build
# atau untuk development
npm run dev
```

### 7. Jalankan Aplikasi
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 👥 Default Users

Setelah menjalankan seeder, tersedia 3 user default:

| Email | Password | Role | Permissions |
|-------|----------|------|-------------|
| admin@healthdashboard.com | password | Administrator | Full Access |
| manager@healthdashboard.com | password | Management | Dashboard, Reports, View Data |
| staff@healthdashboard.com | password | Staff | Data Entry, Basic Access |

## 📊 Struktur Database

### Tables:
1. **roles** - Role management dengan permissions
2. **users** - User accounts dengan RBAC
3. **health_data** - Data kesehatan pasien
4. **data_quality_logs** - Log validasi kualitas data
5. **security_logs** - Log aktivitas keamanan
6. **audit_trails** - Tracking perubahan data

## 🎨 Fitur UI/UX

- ✅ Sidebar navigasi yang modern dan responsif
- ✅ Dashboard dengan KPI cards yang informatif
- ✅ Grafik interaktif menggunakan Chart.js
- ✅ Color-coded status indicators
- ✅ Responsive design untuk semua device
- ✅ Modern gradient design
- ✅ Icon-rich interface (Bootstrap Icons)

## 🔒 Security Features

### CIA Triad Implementation:
- **Confidentiality**: Role-based access control, authentication
- **Integrity**: Audit trails, data validation
- **Availability**: Error handling, logging

### Security Monitoring:
- Real-time security event logging
- Failed login attempts tracking
- User activity monitoring
- IP address tracking
- Risk analysis dashboard

## 📈 Data Quality Features

### Quality Dimensions:
1. **Completeness** - Kelengkapan field data
2. **Accuracy** - Validasi nilai vital signs
3. **Validity** - Status validasi data
4. **Consistency** - Konsistensi data

### Automatic Scoring:
- Sistem menghitung quality score otomatis (0-100%)
- Validasi range untuk vital signs
- Alert untuk data yang perlu review

## 📁 Struktur Folder

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── HealthDataController.php
│   │   ├── DataQualityController.php
│   │   └── SecurityController.php
│   └── Middleware/
│       ├── CheckPermission.php
│       └── LogSecurityEvent.php
├── Models/
│   ├── User.php
│   ├── Role.php
│   ├── HealthData.php
│   ├── DataQualityLog.php
│   ├── SecurityLog.php
│   └── AuditTrail.php

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── dashboard/
│   │   └── index.blade.php
│   ├── health-data/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── data-quality/
│   │   ├── index.blade.php
│   │   └── report.blade.php
│   └── security/
│       ├── index.blade.php
│       ├── audit-trail.blade.php
│       ├── risk-analysis.blade.php
│       └── users.blade.php

database/
├── migrations/
└── seeders/
    └── DatabaseSeeder.php
```

## 🎯 Cara Penggunaan

### 1. Login
- Akses `http://localhost:8000`
- Login dengan salah satu user default
- Redirect ke dashboard

### 2. Dashboard
- Lihat KPI cards (Total Pasien, Records, Kualitas Data)
- Monitor tren kualitas data
- Lihat security events terbaru
- Review quality issues

### 3. Data Kesehatan
- Tambah data pasien baru
- Edit data existing
- Lihat detail lengkap
- Automatic quality scoring

### 4. Data Quality
- Monitor quality logs
- Jalankan validasi data
- Lihat comprehensive report
- Track quality metrics

### 5. Security
- Monitor security logs
- Review audit trail
- Analyze CIA Triad scores
- Manage users

## 🔧 Troubleshooting

### Error: Database Connection
```bash
# Pastikan MySQL running di Laragon
# Cek database db_tubes sudah dibuat
```

### Error: Permission Denied
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
```

### Error: Assets Not Found
```bash
# Rebuild assets
npm run build
```

## 📝 Catatan Penting

1. **Database**: Pastikan database `db_tubes` sudah dibuat di MySQL
2. **Laragon**: Aplikasi ini dioptimalkan untuk Laragon di Windows
3. **PHP Version**: Minimal PHP 8.2
4. **Node Version**: Minimal Node.js 18

## 🎓 Kontribusi Module

### Module 8 (Reporting & Dashboards):
- Dashboard monitoring real-time
- Visualisasi data dengan Chart.js
- KPI tracking
- Multi-stakeholder reporting

### Module 11 (Security & User Management):
- CIA Triad implementation
- RBAC system
- Security logging & monitoring
- Risk analysis

### Module 12 (Data Quality & Governance):
- Quality validation engine
- Automatic scoring
- Quality metrics tracking
- Governance compliance

## 📞 Support

Untuk pertanyaan atau issue, silakan hubungi tim development.

---

**Dibuat dengan ❤️ menggunakan Laravel 12**

**Status**: ✅ Production Ready - 100% Functional
