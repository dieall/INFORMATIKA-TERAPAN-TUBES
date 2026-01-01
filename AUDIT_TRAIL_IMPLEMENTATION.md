# 🔍 Audit Trail Implementation

## Overview

Audit Trail sekarang **otomatis berfungsi** untuk tracking semua perubahan data dalam sistem.

---

## ✅ Yang Sudah Diimplementasikan

### 1️⃣ **Observer Pattern untuk HealthData**

**File**: `app/Observers/HealthDataObserver.php`

Observer akan otomatis log setiap:
- ✅ **Create** - Saat data baru dibuat
- ✅ **Update** - Saat data diubah (mencatat nilai lama & baru)
- ✅ **Delete** - Saat data dihapus
- ✅ **Restore** - Saat data di-restore (soft delete)

**Informasi yang Dicatat:**
- User ID (siapa yang melakukan)
- Action type (create/update/delete)
- Model type & ID
- Old values (nilai sebelum perubahan)
- New values (nilai setelah perubahan)
- IP Address
- User Agent
- Timestamp

---

### 2️⃣ **Auto-Registration di AppServiceProvider**

**File**: `app/Providers/AppServiceProvider.php`

Observer di-register secara otomatis saat aplikasi boot:

```php
public function boot(): void
{
    HealthData::observe(HealthDataObserver::class);
}
```

---

### 3️⃣ **Audit Trail View**

**Route**: `/security/audit-trail`

**Features:**
- ✅ List semua audit trails
- ✅ Filter by user, action, model
- ✅ Show old vs new values comparison
- ✅ Modal detail untuk melihat perubahan
- ✅ Pagination

---

## 🧪 Testing Audit Trail

### **Test Create:**

1. Login sebagai user manapun
2. Buka menu **Data Kesehatan**
3. Klik **"Tambah Data Baru"**
4. Isi form dan **Submit**
5. Buka menu **Security → Audit Trail**
6. ✅ Akan ada log dengan action **"create"**

**Expected Log:**
```
Action: CREATE
Model: HealthData
Model ID: 7
User: Admin System
Old Values: null
New Values: {patient_id: "P007", patient_name: "Test", ...}
```

---

### **Test Update:**

1. Buka menu **Data Kesehatan**
2. Klik icon **Edit (pensil)** pada data manapun
3. Ubah beberapa field (misal: nama pasien, diagnosis)
4. **Submit**
5. Buka menu **Security → Audit Trail**
6. ✅ Akan ada log dengan action **"update"**

**Expected Log:**
```
Action: UPDATE
Model: HealthData
Model ID: 1
User: Admin System
Old Values: {
  "patient_name": "Budi Santoso",
  "diagnosis": "Hipertensi"
}
New Values: {
  "patient_name": "Budi Santoso Updated",
  "diagnosis": "Diabetes"
}
```

---

### **Test Delete:**

1. Buka menu **Data Kesehatan**
2. Klik icon **Trash** pada data manapun
3. Konfirmasi delete
4. Buka menu **Security → Audit Trail**
5. ✅ Akan ada log dengan action **"delete"**

**Expected Log:**
```
Action: DELETE
Model: HealthData
Model ID: 2
User: Admin System
Old Values: {patient_id: "P002", ...} (semua data yang dihapus)
New Values: null
```

---

## 📋 Audit Trail View Features

### **Main Table Columns:**
- ✅ Waktu (timestamp)
- ✅ User (siapa yang melakukan)
- ✅ Action (create/update/delete/restore)
- ✅ Model Type (HealthData, User, dll)
- ✅ Model ID
- ✅ IP Address
- ✅ Detail Button (modal)

### **Detail Modal:**
Shows JSON comparison of old vs new values:
- **Old Values**: Nilai sebelum perubahan (untuk update/delete)
- **New Values**: Nilai setelah perubahan (untuk create/update)

---

## 🔧 Extending Audit Trail

### **Add Observer untuk Model Lain:**

**Example: User Model**

1. **Create Observer:**
```bash
php artisan make:observer UserObserver --model=User
```

2. **Implement Logic:**
```php
// app/Observers/UserObserver.php
public function updated(User $user): void
{
    if (Auth::check()) {
        AuditTrail::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model_type' => User::class,
            'model_id' => $user->id,
            'old_values' => $user->getOriginal(),
            'new_values' => $user->getChanges(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
```

3. **Register Observer:**
```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
    HealthData::observe(HealthDataObserver::class);
    User::observe(UserObserver::class); // Add this
}
```

---

## 🎯 Use Cases

### **Compliance & Regulatory:**
- Track who accessed/modified patient data
- Provide audit logs for compliance requirements
- Healthcare data protection (HIPAA, etc)

### **Security Investigation:**
- Identify unauthorized modifications
- Track data breaches
- Forensic analysis

### **Data Integrity:**
- Verify data changes are legitimate
- Rollback capability (knowing old values)
- Data quality tracking

### **Operational:**
- Monitor user activity
- Training & quality assurance
- Dispute resolution

---

## 📊 Database Schema

### **audit_trails Table:**

```sql
CREATE TABLE audit_trails (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(255), -- create, update, delete, view
    model_type VARCHAR(255), -- App\Models\HealthData
    model_id BIGINT UNSIGNED,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(255) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

---

## 🔍 Query Examples

### **Get all changes by user:**
```php
$userAudits = AuditTrail::where('user_id', $userId)
    ->orderBy('created_at', 'desc')
    ->get();
```

### **Get all changes for specific record:**
```php
$recordHistory = AuditTrail::where('model_type', HealthData::class)
    ->where('model_id', $recordId)
    ->orderBy('created_at', 'desc')
    ->get();
```

### **Get recent updates:**
```php
$recentUpdates = AuditTrail::where('action', 'update')
    ->where('created_at', '>=', now()->subDays(7))
    ->with('user')
    ->get();
```

---

## ✅ Benefits

### **Automatic:**
- ✅ No need to manually log changes
- ✅ Works for all CRUD operations
- ✅ Consistent across the application

### **Comprehensive:**
- ✅ Captures all data changes
- ✅ Includes metadata (IP, user agent)
- ✅ Shows before & after values

### **Secure:**
- ✅ Cannot be modified by users
- ✅ Tied to authentication
- ✅ Permanent record

---

## 🚀 Performance Considerations

### **Database:**
- Audit trails table will grow over time
- Consider archiving old records (>6 months)
- Add indexes on frequently queried columns:
  ```sql
  CREATE INDEX idx_user_id ON audit_trails(user_id);
  CREATE INDEX idx_model ON audit_trails(model_type, model_id);
  CREATE INDEX idx_created_at ON audit_trails(created_at);
  ```

### **Observer:**
- Observer runs synchronously
- For high-traffic apps, consider:
  - Queuing audit logs
  - Batch processing
  - Asynchronous logging

---

## 📝 Best Practices

### **Do:**
- ✅ Review audit logs regularly
- ✅ Set up alerts for suspicious activity
- ✅ Archive old logs for performance
- ✅ Restrict audit log access to admins only

### **Don't:**
- ❌ Don't delete audit logs
- ❌ Don't disable observers in production
- ❌ Don't log sensitive data like passwords
- ❌ Don't allow users to modify audit logs

---

## 🎯 Status

**Implementation**: ✅ **Complete & Working**

**Tested**: ✅ **All CRUD operations logged**

**Production Ready**: ✅ **Yes**

---

**Created**: 28 December 2025
**Version**: 1.0.0

