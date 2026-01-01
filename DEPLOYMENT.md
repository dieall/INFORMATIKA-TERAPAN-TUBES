# 🚀 Panduan Deployment - Health Dashboard System

## Persiapan Awal

### Requirements
- ✅ PHP 8.2 atau lebih tinggi
- ✅ MySQL 5.7+ atau MariaDB 10.3+
- ✅ Composer 2.x
- ✅ Node.js 18+ & NPM
- ✅ Laragon (untuk development di Windows)

## 📋 Langkah-langkah Deployment

### 1. Setup Database

```sql
-- Buat database baru
CREATE DATABASE db_tubes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Verifikasi database
SHOW DATABASES LIKE 'db_tubes';
```

### 2. Clone/Copy Project

```bash
# Pastikan project ada di folder Laragon
cd C:\laragon\www\INFORMATIKA PROJECT 2

# Atau copy project ke folder tersebut
```

### 3. Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install
```

### 4. Environment Configuration

```bash
# Copy environment file (sudah ada)
# Pastikan .env sudah dikonfigurasi dengan benar

# Generate application key
php artisan key:generate
```

Konfigurasi `.env`:
```env
APP_NAME="Health Dashboard"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_tubes
DB_USERNAME=root
DB_PASSWORD=

# Untuk production, set APP_DEBUG=false
```

### 5. Database Migration & Seeding

```bash
# Jalankan migrations
php artisan migrate:fresh

# Seed data awal (users, roles, sample data)
php artisan db:seed

# Atau gabungkan keduanya
php artisan migrate:fresh --seed
```

### 6. Build Assets

```bash
# Production build
npm run build

# Untuk development
npm run dev
```

### 7. Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 8. Set Permissions (Linux/Mac)

```bash
# Set storage permissions
chmod -R 775 storage bootstrap/cache

# Set ownership (adjust user:group as needed)
chown -R www-data:www-data storage bootstrap/cache
```

### 9. Start Application

#### Menggunakan Laragon (Recommended untuk Windows):
1. Buka Laragon
2. Start All Services
3. Akses: `http://informatika-project-2.test`

#### Menggunakan PHP Built-in Server:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
Akses: `http://localhost:8000`

#### Menggunakan Apache/Nginx:
Configure virtual host untuk project ini.

## 🔐 Default Login Credentials

Setelah seeding, gunakan credentials berikut:

### Administrator
- **Email**: admin@healthdashboard.com
- **Password**: password
- **Role**: Administrator
- **Access**: Full system access

### Management
- **Email**: manager@healthdashboard.com
- **Password**: password
- **Role**: Management
- **Access**: Dashboard, reports, view data

### Staff
- **Email**: staff@healthdashboard.com
- **Password**: password
- **Role**: Staff
- **Access**: Data entry, basic operations

⚠️ **PENTING**: Ganti password default setelah deployment!

## 🧪 Testing

### 1. Test Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
# Jika berhasil, akan menampilkan PDO object
```

### 2. Test Routes
```bash
php artisan route:list
# Verifikasi semua routes terdaftar
```

### 3. Test Login
1. Akses aplikasi di browser
2. Login dengan user admin
3. Verifikasi dashboard muncul dengan benar
4. Test navigasi ke semua menu

### 4. Test Features
- ✅ Dashboard menampilkan KPI
- ✅ Chart.js rendering dengan benar
- ✅ CRUD Health Data berfungsi
- ✅ Quality validation berjalan
- ✅ Security logs tercatat
- ✅ Audit trail tracking

## 📊 Monitoring

### Check Logs
```bash
# Application logs
tail -f storage/logs/laravel.log

# Security logs (via database)
# Akses menu Security > Security Logs
```

### Performance Monitoring
```bash
# Clear cache jika perlu
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🔧 Troubleshooting

### Problem: Page 500 Error
**Solution**:
```bash
# Enable debug mode temporarily
# Edit .env: APP_DEBUG=true
# Check storage/logs/laravel.log
```

### Problem: Assets Not Loading
**Solution**:
```bash
npm run build
php artisan view:clear
```

### Problem: Database Connection Failed
**Solution**:
```bash
# Verify MySQL is running
# Check .env database credentials
# Test connection: php artisan tinker >>> DB::connection()->getPdo();
```

### Problem: Permission Denied
**Solution**:
```bash
# Windows (Laragon): Run as Administrator
# Linux/Mac: chmod -R 775 storage bootstrap/cache
```

### Problem: Routes Not Found
**Solution**:
```bash
php artisan route:clear
php artisan route:cache
```

## 🔒 Security Checklist

- [ ] Change default passwords
- [ ] Set `APP_DEBUG=false` in production
- [ ] Configure proper `.env` values
- [ ] Set up HTTPS (SSL certificate)
- [ ] Configure CORS if needed
- [ ] Set up regular backups
- [ ] Configure rate limiting
- [ ] Review security logs regularly

## 📦 Backup Strategy

### Database Backup
```bash
# Manual backup
mysqldump -u root -p db_tubes > backup_$(date +%Y%m%d).sql

# Restore backup
mysql -u root -p db_tubes < backup_20231228.sql
```

### Application Backup
```bash
# Backup entire application
tar -czf health-dashboard-backup-$(date +%Y%m%d).tar.gz /path/to/project
```

## 🔄 Update Procedure

```bash
# 1. Backup database dan files
# 2. Pull/copy new code
# 3. Install dependencies
composer install
npm install

# 4. Run migrations
php artisan migrate

# 5. Build assets
npm run build

# 6. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 7. Re-optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📞 Support & Maintenance

### Regular Maintenance Tasks

**Daily**:
- Monitor security logs
- Check error logs
- Verify system uptime

**Weekly**:
- Review data quality metrics
- Check disk space
- Database optimization

**Monthly**:
- Full system backup
- Security audit
- Performance review
- Update dependencies

### Contact

Untuk bantuan teknis atau pertanyaan:
- Email: support@healthdashboard.com
- Documentation: README.md

---

## ✅ Deployment Checklist

Sebelum go-live, pastikan:

- [ ] Database `db_tubes` sudah dibuat
- [ ] Environment variables dikonfigurasi
- [ ] Migrations berhasil dijalankan
- [ ] Seeder berhasil dijalankan
- [ ] Assets sudah di-build
- [ ] Default users bisa login
- [ ] Dashboard menampilkan data
- [ ] Semua menu accessible
- [ ] Charts rendering dengan benar
- [ ] CRUD operations berfungsi
- [ ] Security logging aktif
- [ ] Audit trail berfungsi
- [ ] Quality validation berjalan
- [ ] Error handling berfungsi
- [ ] Responsive design OK
- [ ] Performance acceptable
- [ ] Backup strategy in place
- [ ] Documentation complete

---

**Status**: ✅ Ready for Production Deployment

**Last Updated**: December 28, 2025

