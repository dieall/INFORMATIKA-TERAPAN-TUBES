# 👥 User Management - Complete Guide

## Overview

Sistem User Management lengkap untuk mengelola user, roles, dan permissions dalam aplikasi.

---

## ✅ Features Yang Sudah Diimplementasikan

### 1️⃣ **User CRUD Operations**
- ✅ **Create** - Tambah user baru dengan role
- ✅ **Read** - List dan detail user
- ✅ **Update** - Edit user information
- ✅ **Delete** - Hapus user (dengan proteksi)

### 2️⃣ **Role-Based Access Control (RBAC)**
- ✅ Admin (Full access)
- ✅ Management (Dashboard & reports)
- ✅ Staff (Data entry)

### 3️⃣ **User Information**
- Name, Email, Password
- Role assignment
- Department
- Phone number
- Status (Active/Inactive)
- Last login tracking

### 4️⃣ **Security Features**
- ✅ Password hashing (bcrypt)
- ✅ Email uniqueness validation
- ✅ Self-delete protection
- ✅ Activity tracking

---

## 📱 User Management Pages

### **1. User List (`/users`)**

**Features:**
- Table view dengan semua users
- Role badges (color-coded)
- Status indicators (Active/Inactive)
- Last login timestamp
- Action buttons (View/Edit/Delete)
- Pagination (15 per page)

**Columns:**
| Column | Description |
|--------|-------------|
| User | Avatar + Name |
| Email | User email address |
| Role | Badge showing role |
| Department | User department |
| Phone | Contact number |
| Status | Active/Inactive |
| Last Login | Time ago format |
| Aksi | CRUD buttons |

---

### **2. Create User (`/users/create`)**

**Form Sections:**

**A. Informasi Personal:**
- ✅ Nama Lengkap (required)
- ✅ Email (required, unique)
- ✅ Password (required, min 8 chars)
- ✅ Konfirmasi Password (required)

**B. Role & Akses:**
- ✅ Role (dropdown: Admin/Management/Staff)
- ✅ Department (optional)
- ✅ Phone (optional)
- ✅ Status (Active switch)

**Validation:**
```php
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users',
'password' => 'required|min:8|confirmed',
'role_id' => 'required|exists:roles,id',
'phone' => 'nullable|string|max:20',
'department' => 'nullable|string|max:100',
```

---

### **3. Edit User (`/users/{id}/edit`)**

**Same as Create, with:**
- ✅ Pre-filled form data
- ✅ Password optional (empty = no change)
- ✅ Email unique except current user
- ✅ Update button instead of Create

**Password Behavior:**
- Kosongkan = tidak mengubah password
- Isi baru = update password dengan hash baru

---

### **4. User Detail (`/users/{id}`)**

**Displays:**

**A. Informasi User:**
- Full name, Email
- Role dengan permissions list
- Department, Phone
- Status badge
- Last login (date + time ago)
- Last login IP address
- Registration date
- Last updated date

**B. Activity Summary:**
- Security logs count
- Data created count

**C. Recent Security Logs:**
- Last 10 security events
- Event type, Severity
- IP address, Description
- Timestamp

---

## 🔐 Roles & Permissions

### **Admin Role**

**Permissions:**
```json
[
    "dashboard.view",
    "users.view", "users.create", "users.edit", "users.delete",
    "health_data.view", "health_data.create", "health_data.edit", "health_data.delete",
    "data_quality.view", "data_quality.manage",
    "security.view", "security.manage",
    "reports.view", "reports.export"
]
```

**Access:**
- ✅ Full system access
- ✅ User management
- ✅ All CRUD operations
- ✅ Security monitoring
- ✅ Data quality management

---

### **Management Role**

**Permissions:**
```json
[
    "dashboard.view",
    "health_data.view",
    "data_quality.view",
    "security.view",
    "reports.view", "reports.export"
]
```

**Access:**
- ✅ Dashboard viewing
- ✅ Read-only data access
- ✅ Reports & analytics
- ✅ No CRUD operations

---

### **Staff Role**

**Permissions:**
```json
[
    "dashboard.view",
    "health_data.view", "health_data.create", "health_data.edit"
]
```

**Access:**
- ✅ Basic dashboard
- ✅ Data entry (create/edit)
- ✅ No delete operations
- ✅ No admin access

---

## 🧪 Testing User Management

### **Test 1: Create New User**

1. Login sebagai **Admin**
2. Menu **Security → User Management**
3. Klik **"Tambah User Baru"**
4. Isi form:
   - Name: `Test User`
   - Email: `test@example.com`
   - Password: `password123`
   - Confirm: `password123`
   - Role: `Staff`
   - Department: `Testing`
   - Phone: `08123456789`
   - Status: ✅ Active
5. Klik **"Simpan User"**
6. ✅ Redirect ke list dengan success message
7. ✅ User baru muncul di table

---

### **Test 2: Edit User**

1. Di User List, klik icon **Pensil (Edit)**
2. Ubah beberapa field:
   - Name: `Test User Updated`
   - Department: `Quality Assurance`
3. Password: *kosongkan* (tidak diubah)
4. Klik **"Update User"**
5. ✅ Data terupdate
6. ✅ Password lama masih bisa dipakai

---

### **Test 3: View User Detail**

1. Di User List, klik icon **Mata (View)**
2. ✅ Muncul detail lengkap user
3. ✅ Permissions ditampilkan sebagai badges
4. ✅ Activity summary (logs count)
5. ✅ Recent security logs (jika ada)

---

### **Test 4: Delete User**

1. Di User List, klik icon **Trash**
2. Konfirmasi delete
3. ✅ User terhapus (jika bukan diri sendiri)
4. ✅ Success message

**Self-Delete Protection:**
- Jika coba hapus akun sendiri:
- ❌ Error: "Anda tidak dapat menghapus akun sendiri"

---

### **Test 5: Password Update**

1. Edit user
2. Isi **Password Baru**: `newpassword123`
3. Isi **Konfirmasi**: `newpassword123`
4. Update
5. Logout
6. Login dengan password baru
7. ✅ Berhasil login

---

## 🔒 Security Features

### **1. Password Security**
```php
// Password di-hash dengan bcrypt
$validated['password'] = Hash::make($validated['password']);

// Min 8 characters
'password' => 'required|string|min:8|confirmed'
```

### **2. Email Uniqueness**
```php
// Create: must be unique
'email' => 'required|email|unique:users'

// Update: unique except current user
'email' => [
    'required', 
    'email', 
    Rule::unique('users')->ignore($user->id)
]
```

### **3. Self-Delete Protection**
```php
if ($user->id === auth()->id()) {
    return redirect()->back()
        ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
}
```

### **4. Activity Tracking**
- Every login tracked in `security_logs`
- Last login timestamp saved
- Last login IP saved
- User agent recorded

---

## 📊 Database Schema

### **users Table:**
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY,
    role_id BIGINT UNSIGNED NULL,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255),
    phone VARCHAR(255) NULL,
    department VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT true,
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
);
```

### **roles Table:**
```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(255) UNIQUE,
    display_name VARCHAR(255),
    description TEXT NULL,
    permissions JSON NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## 🎯 Use Cases

### **HR Management:**
- Onboard new employees
- Update employee information
- Offboard (deactivate) employees
- Track employee activity

### **Access Control:**
- Assign appropriate roles
- Restrict sensitive features
- Monitor user permissions
- Revoke access when needed

### **Audit & Compliance:**
- Track who has access
- Monitor user activity
- Generate access reports
- Compliance documentation

### **Security:**
- Identify suspicious accounts
- Monitor failed logins
- Track password changes
- IP-based analysis

---

## 🚀 Advanced Features (Future)

### **Potential Enhancements:**

1. **Two-Factor Authentication (2FA)**
2. **Password reset via email**
3. **User groups/teams**
4. **Custom permissions per user**
5. **Bulk user import (CSV)**
6. **User activity dashboard**
7. **Session management**
8. **Device tracking**

---

## 📝 Best Practices

### **Do:**
- ✅ Use strong passwords (min 8 chars)
- ✅ Assign appropriate roles
- ✅ Deactivate instead of delete
- ✅ Review user access regularly
- ✅ Monitor suspicious activity

### **Don't:**
- ❌ Share admin credentials
- ❌ Give everyone admin access
- ❌ Use weak passwords
- ❌ Leave inactive accounts active
- ❌ Ignore security logs

---

## 🔍 Troubleshooting

### **Problem: Cannot delete user**
**Solution:**
- Check if user is yourself (protected)
- Check if user has active sessions
- Check database constraints

### **Problem: Email already exists**
**Solution:**
- Use different email
- Check if user exists with that email
- Soft-deleted users still occupy email

### **Problem: Password not updating**
**Solution:**
- Ensure password & confirmation match
- Check minimum length (8 chars)
- Clear browser cache

---

## ✅ Status

**Implementation**: ✅ **Complete & Working**

**Features**:
- ✅ User CRUD operations
- ✅ Role-Based Access Control
- ✅ Password management
- ✅ Activity tracking
- ✅ Security features

**Production Ready**: ✅ **Yes**

---

## 📱 Navigation

**Sidebar Menu:**
```
Security & User Management
├── Security Logs
├── Audit Trail
├── Risk Analysis
└── User Management ⭐ (New!)
```

**Routes:**
```
GET    /users ...................... users.index
GET    /users/create ............... users.create
POST   /users ...................... users.store
GET    /users/{user} ............... users.show
GET    /users/{user}/edit .......... users.edit
PUT    /users/{user} ............... users.update
DELETE /users/{user} ............... users.destroy
```

---

**Created**: 28 December 2025
**Version**: 1.0.0

