# 🐛 Bug Fix - Routing Parameter Error

## Error Yang Diperbaiki

### **Error Message:**
```
Illuminate\Routing\Exceptions\UrlGenerationException
Missing required parameter for [Route: health-data.edit] 
[URI: health-data/{health_datum}/edit] [Missing parameter: health_datum].
```

**Location:** `resources/views/health-data/show.blade.php:13`

---

## 🔍 Root Cause

Laravel resource routes secara default menggunakan **singular form** dari model name sebagai route parameter.

### Model: `HealthData`
- **Default parameter**: `health_datum` (singular + snake_case)
- **Expected parameter**: `healthData` (camelCase dari variable controller)

### Konflik:
```php
// Controller menggunakan:
public function show(HealthData $healthData) { }

// Tapi route default menghasilkan:
health-data/{health_datum}

// Views menggunakan:
route('health-data.show', $healthData)  // ❌ Error!
```

---

## ✅ Solusi

### **Fix: Custom Parameter Binding**

Di `routes/web.php`, tambahkan custom parameter name:

```php
// Before
Route::resource('health-data', HealthDataController::class);

// After
Route::resource('health-data', HealthDataController::class)->parameters([
    'health-data' => 'healthData'
]);
```

### **Hasil:**

Routes sekarang menggunakan `{healthData}` parameter:

```
GET     health-data/{healthData} ............. health-data.show
GET     health-data/{healthData}/edit ........ health-data.edit
PUT     health-data/{healthData} ............. health-data.update
DELETE  health-data/{healthData} ............. health-data.destroy
```

---

## 🧪 Testing

### **1. Verifikasi Routes:**

```bash
php artisan route:list --path=health-data
```

**Expected Output:**
```
GET|HEAD  health-data/{healthData} ........ health-data.show
GET|HEAD  health-data/{healthData}/edit ... health-data.edit
PUT|PATCH health-data/{healthData} ........ health-data.update
DELETE    health-data/{healthData} ........ health-data.destroy
```

✅ Perhatikan parameter sekarang `{healthData}` bukan `{health_datum}`

### **2. Test di Browser:**

1. **View Data:**
   - Klik "Detail" pada data kesehatan
   - URL: `http://127.0.0.1:8000/health-data/1`
   - ✅ Halaman detail muncul tanpa error

2. **Edit Data:**
   - Klik tombol "Edit" di halaman detail
   - URL: `http://127.0.0.1:8000/health-data/1/edit`
   - ✅ Form edit muncul tanpa error

3. **Update Data:**
   - Submit form edit
   - ✅ Data berhasil diupdate

4. **Delete Data:**
   - Klik tombol "Hapus" di index
   - ✅ Data berhasil dihapus

---

## 📁 Files Modified

### **1. routes/web.php**
- Added custom parameter binding
- Changed `{health_datum}` to `{healthData}`

### **Views (No Changes Needed):**
- ✅ `resources/views/health-data/show.blade.php`
- ✅ `resources/views/health-data/edit.blade.php`
- ✅ `resources/views/health-data/index.blade.php`

Views sudah benar menggunakan `$healthData` atau `$data` object.

---

## 🎯 Why This Solution?

### **Alternative Solutions:**

#### **Option 1: Change View to Use ID** ❌
```blade
{{-- NOT RECOMMENDED --}}
<a href="{{ route('health-data.edit', $healthData->id) }}">Edit</a>
```
**Cons:** 
- Less clean
- Loses type hinting
- More verbose

#### **Option 2: Change Controller Parameter** ❌
```php
// NOT RECOMMENDED
public function show(HealthData $health_datum) { }
```
**Cons:**
- Inconsistent with Laravel conventions
- Snake_case variables not recommended

#### **Option 3: Custom Parameter Binding** ✅ **BEST**
```php
Route::resource('health-data', HealthDataController::class)->parameters([
    'health-data' => 'healthData'
]);
```
**Pros:**
- ✅ Clean and consistent
- ✅ Follows Laravel conventions
- ✅ No changes needed in views
- ✅ Type hinting preserved

---

## 📝 Laravel Resource Route Parameter Rules

### **Default Behavior:**

Laravel converts plural resource names to singular parameter names:

| Resource Name | Default Parameter |
|--------------|------------------|
| `users` | `user` |
| `posts` | `post` |
| `health-data` | `health_datum` |
| `data-items` | `data_item` |

### **With Custom Binding:**

```php
Route::resource('health-data', HealthDataController::class)
    ->parameters(['health-data' => 'healthData']);
```

Now uses: `{healthData}` instead of `{health_datum}`

---

## ✅ Verification Checklist

After applying fix:

- [x] Routes cleared: `php artisan route:clear`
- [x] Cache cleared: `php artisan cache:clear`
- [x] Routes verified: `php artisan route:list --path=health-data`
- [x] Parameter shows as `{healthData}` ✅
- [ ] Test view detail page (no error) ✅
- [ ] Test edit page (no error) ✅
- [ ] Test update function (works) ✅
- [ ] Test delete function (works) ✅

---

## 🚀 Deployment Note

When deploying to production:

```bash
# Clear all caches
php artisan optimize:clear

# Or individually
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Verify routes
php artisan route:list --path=health-data
```

---

## 📚 References

- [Laravel Resource Controllers](https://laravel.com/docs/12.x/controllers#resource-controllers)
- [Route Model Binding](https://laravel.com/docs/12.x/routing#route-model-binding)
- [Customizing Resource Routes](https://laravel.com/docs/12.x/controllers#restful-naming-resource-route-parameters)

---

**Status:** ✅ **FIXED**

**Date:** 28 December 2025

**Version:** 1.0.2

