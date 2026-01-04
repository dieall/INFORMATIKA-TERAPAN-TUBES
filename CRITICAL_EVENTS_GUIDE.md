# Critical Event & Failed Attempts Tracking System

## 📋 Cara Kerja Critical Event

### 1️⃣ **Failed Login Attempt Tracking**

Setiap kali ada percobaan login yang gagal, sistem otomatis mencatat:

```
Failed Attempt #1 → Severity: MEDIUM (2% risk)
Failed Attempt #2 → Severity: HIGH (10% risk) ⚠️ CRITICAL EVENT CREATED
Failed Attempt #3-4 → Severity: HIGH (terus bertambah)
Failed Attempt #5+ → Severity: CRITICAL (90% risk) 🚨 CRITICAL ALERT
```

### 2️⃣ **Threshold Configuration**

- **Medium**: 1 failed attempt (default)
- **High**: 2+ failed attempts dalam 30 menit ← Critical Event dimulai dari sini
- **Critical**: 5+ failed attempts dalam 30 menit

### 3️⃣ **Data yang Dicatat per Failed Attempt**

| Field | Contoh |
|-------|--------|
| Email/Username | user@example.com |
| IP Address | 192.168.1.100 |
| Attempt Number | #1, #2, #3, ... |
| Time Window | 30 minutes |
| Severity | medium, high, critical |
| User Agent | Mozilla/5.0 (Windows NT 10.0...) |
| Timestamp | 2026-01-04 14:02:24 |

---

## 🎯 Cara Mengakses Critical Events

### **Metode 1: Via Admin Dashboard**

1. Login ke sistem dengan akun Admin
2. Klik menu **Security** di sidebar
3. Pilih **Critical Events** (menu baru)
4. Akan menampilkan:
   - Statistics: Critical count, High count, Failed logins
   - Top IPs dengan most failed attempts
   - Filter dan search capabilities
   - Detail modal per event

### **Metode 2: Direct URL**

```
http://127.0.0.1:8000/security/critical-events
```

### **Metode 3: Via Failed Attempts Page**

1. Buka: http://127.0.0.1:8000/security/failed-attempts
2. Lihat all failed attempts dengan detail tracking
3. Lihat trend 7 hari terakhir
4. Identify top attacked emails

---

## 📊 Critical Event Statistics

Pada dashboard Critical Events, Anda akan melihat:

```
┌─────────────────────────────────────────────────────────┐
│  CRITICAL EVENTS STATISTICS (Last 30 Days)             │
├─────────────────────────────────────────────────────────┤
│ Critical Events:    5  🚨                               │
│ High Severity:      12 ⚠️                               │
│ Failed Logins:      47 🔐                               │
│ Today Critical:     0  📅                               │
└─────────────────────────────────────────────────────────┘
```

---

## 🔍 Cara Critical Events Bertambah

### **Skenario 1: Brute Force Attack**

```
User A mencoba login dengan password salah:

Time: 14:00:00 - Attempt #1 (MEDIUM)
Time: 14:00:30 - Attempt #2 (HIGH) ← CRITICAL EVENT CREATED #1
Time: 14:01:00 - Attempt #3 (HIGH) ← CRITICAL EVENT CREATED #2
Time: 14:01:30 - Attempt #4 (HIGH) ← CRITICAL EVENT CREATED #3
Time: 14:02:00 - Attempt #5 (CRITICAL) ← CRITICAL EVENT CREATED #4 🚨
Time: 14:02:30 - Attempt #6 (CRITICAL) ← CRITICAL EVENT CREATED #5 🚨
```

**Hasil**: 5 Critical Events tercatat dalam 2.5 menit

### **Skenario 2: Multiple IPs**

Jika 3 IP address berbeda mencoba brute force, setiap IP:

```
IP 1 (192.168.1.1):    5 failed attempts → CRITICAL
IP 2 (192.168.1.2):    3 failed attempts → HIGH  
IP 3 (192.168.1.3):    2 failed attempts → HIGH

Total: 3 Critical Event Records
```

### **Skenario 3: Targeted Account**

Satu email di-target berkali-kali:

```
admin@health.com - 12 failed attempts in 30 min → CRITICAL
```

---

## 🛡️ Security Features

### **Automatic Detection**
- ✅ Real-time monitoring
- ✅ Automatic severity escalation
- ✅ IP-based tracking
- ✅ Email/Username pattern analysis

### **Analytics**
- 📊 Top IPs dengan most attempts
- 📊 Top emails dengan most attacks
- 📊 7-day trend analysis
- 📊 Browser/Device fingerprinting

### **Alert System**
- 🔴 Critical threshold: 5+ attempts
- 🟡 High threshold: 2+ attempts
- 🔵 Medium threshold: 1+ attempt

---

## 💡 Filtering & Search

### **Critical Events Page Filters**

```
Filter Severity:
  └─ Critical Only
  └─ High Only
  └─ All (Critical + High)

Filter Event Type:
  └─ Failed Login
  └─ Unauthorized Access

Date Range:
  └─ From Date
  └─ To Date
```

### **Failed Attempts Page Filters**

```
Search by IP:     192.168.1.100
Search by Email:  admin@healthdashboard.com
Filter Severity:  critical, high, medium
```

---

## 📈 Understanding the Metrics

### **1. Critical Event Card**
```
Failed Attempts in last 30 days
that reached CRITICAL severity
```

### **2. High Severity Card**
```
Failed Attempts that reached
HIGH severity (2+ attempts)
```

### **3. Failed Logins Card**
```
Total failed login attempts
regardless of threshold
```

### **4. Today Critical**
```
Critical events that happened
exactly on today's date
```

---

## 🔐 Use Cases

### **Use Case 1: Admin Monitoring**
```
Setiap hari, admin membuka:
Security → Critical Events

Melihat:
- Apakah ada IP suspicious
- Apakah ada account yang di-target
- Trend naik atau turun

Action:
- Blokir IP jika perlu
- Reset password user jika perlu
- Investigate user access patterns
```

### **Use Case 2: Security Incident**
```
Terjadi login failures spike

Admin buka:
- Critical Events → sort by date
- Failed Attempts → filter by IP/email
- Audit Trail → lihat akses sebelumnya

Kemudian:
- Block IP address
- Force password reset
- Generate security alert
```

### **Use Case 3: Compliance Reporting**
```
Untuk audit/compliance:
- Export critical events last month
- Generate risk report
- Show protection measures
- Demonstrate logging capabilities
```

---

## 🎬 Demo Steps

### **Step 1: Generate Failed Login Attempts**
1. Logout dari aplikasi
2. Go to login page
3. Try with correct email, WRONG password 2 times
4. Each attempt akan di-record

### **Step 2: Check Critical Events**
1. Login dengan akun admin
2. Go to: http://127.0.0.1:8000/security/critical-events
3. Akan see:
   - Statistics board
   - Top failed IPs
   - Event list

### **Step 3: View Failed Attempts Details**
1. Go to: http://127.0.0.1:8000/security/failed-attempts
2. Akan see:
   - Top emails dengan attempts
   - 7-day trend
   - Complete failed login log

### **Step 4: Deep Dive into Details**
1. Click "Lihat" button pada any critical event
2. Modal akan show:
   - Full event details
   - Metadata (attempt count, threshold)
   - User Agent / Browser info
   - IP geolocation ready for integration

---

## 📱 Integration Ready Features

- ✅ Email alerts (siap diintegrasikan)
- ✅ SMS notifications (siap diintegrasikan)
- ✅ IP blocking/whitelist (siap diintegrasikan)
- ✅ 2FA enforcement (siap diintegrasikan)
- ✅ Account lockout (siap diintegrasikan)

---

## 🔧 Technical Details

### **Database Tables Used**

```
security_logs
  ├─ event_type: 'failed_login'
  ├─ severity: 'critical' | 'high' | 'medium'
  ├─ status: 'failed'
  └─ metadata: { attempt_number, threshold, ... }

audit_trails
  ├─ action: 'failed_login_attempt'
  ├─ model_type: 'Security\FailedAttempt'
  └─ new_values: { failed_attempts, severity, ... }
```

### **Time Window**
- 30 minutes untuk penghitungan attempts
- Auto-reset setelah 30 menit

---

