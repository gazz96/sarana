# 📋 Notification System Testing Guide
## Sistem SARPRAS - sarana.test

---

## 🎯 **TEST USERS (Password: password123)**

### **All Roles Available:**
```
1. bagas@gmail.com     → Admin    (bagas)
2. adit@gmail.com      → Admin    (adit) 
3. fadlan@gmail.com    → Admin    (fadlan)
4. guru@gmail.com      → Guru     (guru)
5. teknisi@gmail.com   → Teknisi  (teknisi)
6. keuangan@gmail.com  → Keuangan (keuangan)
7. lembaga@gmail.com   → Lembaga  (lembaga)
```

**Password untuk semua:** `password123`

---

## ✅ **SETUP VERIFICATION**

### **1. Database Status:**
```bash
# Check notification tables exist
mysql -u root sarana -e "SHOW TABLES LIKE '%notification%';"

# Expected output:
# notifications
# notification_preferences

# Check data
mysql -u root sarana -e "SELECT COUNT(*) as total FROM notification_preferences;"

# Expected: 7 (one for each user)
```

### **2. Test Routes:**
```bash
# Basic test route
curl http://sarana.test/test-notifications

# Create test notification
curl http://sarana.test/test-notification-bell

# API endpoints (tanpa login)
curl http://sarana.test/api/notifications/unread-count
curl http://sarana.test/api/notifications/unread
```

---

## 🔄 **WORKFLOW TESTING SCENARIOS**

### **Scenario 1: Problem Creation & Notifications**

**Step 1: Login sebagai Guru**
```
URL: http://sarana.test/auth
Email: guru@gmail.com
Password: password123
```

**Step 2: Buat Problem Baru**
```
URL: http://sarana.test/problems/create
- Pilih barang yang rusak
- Deskripsikan masalah
- Klik "Simpan Draft"
- Klik "Ajukan" 
```

**Expected Notifications:**
- ✅ Teknisi mendapat notifikasi "Problem Baru Dibuat"
- ✅ Admin mendapat notifikasi "Problem Diajukan"

**Step 3: Logout & Login sebagai Teknisi**
```
URL: http://sarana.test/auth
Email: teknisi@gmail.com  
Password: password123
```

**Step 4: Accept Problem**
```
URL: http://sarana.test/problems
- Cari problem baru dari guru
- Klik "Terima Tugas"
```

**Expected Notifications:**
- ✅ Guru mendapat notifikasi "Problem Diterima Teknisi"
- ✅ Admin mendapat notifikasi status update

**Step 5: Finish Problem**
```
URL: http://sarana.test/problems/{id}/edit
- Update progress & catatan
- Klik "Selesai Dikerjakan"
```

**Expected Notifications:**
- ✅ Guru mendapat notifikasi "Problem Selesai Dikerjakan"  
- ✅ Admin & Management mendapat notifikasi approval needed

**Step 6: Approval Chain**
```
Login sebagai Admin → Approve problem
Login sebagai Management → Approve problem  
Login sebagai Keuangan → Final approval
```

**Expected Final Notifications:**
- ✅ Semua stakeholders mendapat notifikasi "Problem Selesai"

---

### **Scenario 2: Test Notification Bell UI**

**Step 1: Login sebagai Admin**
```
URL: http://sarana.test/auth
Email: bagas@gmail.com
Password: password123
```

**Step 2: Cek Notification Bell**
```
1. Lihat di navbar bagian atas
2. Harus ada icon 🔔 bell
3. Badge count menunjukkan unread notifications
```

**Step 3: Click Bell**
```
1. Klik icon bell
2. Dropdown notifications muncul
3. Daftar notifications dengan icons & timestamps
4. Klik "Lihat Detail" untuk navigasi ke problem
```

**Step 4: Mark as Read**
```
1. Buka salah satu notification
2. Badge count berkurang
3. Notification di-mark as read otomatis
```

**Step 5: Mark All as Read**
```
1. Buka notification dropdown
2. Klik "Tandai Semua Dibaca"
3. Badge count hilang (0 unread)
```

---

## 🧪 **API TESTING**

### **Test dengan Session Auth (Browser Required)**

**Method 1: Browser DevTools**
```javascript
// Setelah login, buka browser console
fetch('/api/notifications/unread-count')
  .then(r => r.json())
  .then(console.log)

// Expected response:
// {
//   "unread_count": 3,
//   "authenticated": true
// }

fetch('/api/notifications/unread')
  .then(r => r.json())
  .then(console.log)

// Expected response:
// {
//   "notifications": [...],
//   "unread_count": 3,
//   "authenticated": true
// }
```

**Method 2: Manual Testing**
```bash
# 1. Login via browser
# 2. Copy laravel_session cookie
# 3. Test dengan curl

curl --cookies "laravel_session=your_cookie" \
     http://sarana.test/api/notifications/unread-count

curl --cookies "laravel_session=your_cookie" \
     http://sarana.test/api/notifications/unread
```

---

## 📊 **DATABASE VERIFICATION**

### **Check Notifications Data**
```sql
-- Total notifications per user
SELECT u.name, COUNT(n.id) as total_notifications
FROM users u
LEFT JOIN notifications n ON u.id = n.notifiable_id 
    AND n.notifiable_type = 'App\\\Models\\\User'
GROUP BY u.id, u.name
ORDER BY total_notifications DESC;

-- Unread notifications per user
SELECT u.name, 
       COUNT(CASE WHEN n.read_at IS NULL THEN 1 END) as unread_count
FROM users u
LEFT JOIN notifications n ON u.id = n.notifiable_id
    AND n.notifiable_type = 'App\\\Models\\\User'
GROUP BY u.id, u.name
ORDER BY unread_count DESC;

-- Recent notifications dengan details
SELECT u.name as recipient,
       JSON_EXTRACT(n.data, '$.event') as event,
       JSON_EXTRACT(n.data, '$.event_name') as event_name,
       JSON_EXTRACT(n.data, '$.message') as message,
       n.read_at,
       n.created_at
FROM notifications n
JOIN users u ON n.notifiable_id = u.id
WHERE n.notifiable_type = 'App\\\Models\\\User'
ORDER BY n.created_at DESC
LIMIT 10;
```

---

## 🐛 **TROUBLESHOOTING**

### **Problem: Notification Bell Tidak Muncul**
```bash
# 1. Check auth status
php artisan tinker
>>> auth()->check()
>>> auth()->user()->name

# 2. Clear cache
php artisan cache:clear
php artisan view:clear

# 3. Check layout file
# Pastikan @include('components.notification-bell') ada di layouts.blade.php
```

### **Problem: Notifications Tidak Muncul**
```bash
# 1. Test create notification
curl http://sarana.test/test-notification-bell

# 2. Check database
mysql -u root sarana -e "SELECT COUNT(*) FROM notifications;"

# 3. Check user notification preferences
mysql -u root sarana -e "SELECT * FROM notification_preferences WHERE user_id = 1;"
```

### **Problem: API Returns 401/Unauthenticated**
```bash
# API routes butuh login, test setelah login via browser
# Atau cek apakah session cookie benar
```

### **Problem: Email Tidak Terkirim**
```bash
# Check mail configuration
php artisan config:cache
grep MAIL .env

# Test mail configuration
php artisan tinker
>>> Mail::raw('Test email', fn($msg) => $msg->to('your@email.com'));
>>> Mail::send(...)
```

---

## 📈 **SUCCESS CRITERIA**

✅ **Database Setup:**
- [x] Tables `notifications` & `notification_preferences` exist
- [x] Semua users punya notification preferences
- [x] Existing data tidak terganggu

✅ **UI Components:**
- [ ] Notification bell muncul di navbar (test after login)
- [ ] Badge count berfungsi dengan benar
- [ ] Dropdown notifications menampilkan data
- [ ] Click notification navigates ke problem details

✅ **Workflow Notifications:**
- [ ] Problem created → notifications sent
- [ ] Status changes → notifications sent  
- [ ] Approval chain → notifications sent
- [ ] All role-specific notifications working

✅ **API Endpoints:**
- [x] `/api/notifications/unread-count` returns proper JSON
- [x] `/api/notifications/unread` returns notification data
- [ ] Mark as read functionality working
- [ ] Mark all as read working

---

## 🚀 **NEXT STEPS**

Setelah notification system verified working:

1. **Enhanced Dashboard** - Add charts & analytics
2. **Advanced Reporting** - Export Excel/PDF functionality  
3. **Photo Upload** - Document damage reports with images
4. **Mobile Optimization** - Improve mobile responsiveness

---

**Last Updated:** 2026-02-03
**Status:** Ready for Testing 🎯