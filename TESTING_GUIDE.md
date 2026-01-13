# TESTING & VERIFICATION GUIDE
## Taman Nasional Tesso Nilo System

**Purpose**: Complete testing guide to verify all features work correctly  
**Duration**: ~30 minutes for full test cycle  
**Last Updated**: 22 Desember 2025

---

## 🧪 PRE-TESTING SETUP

### 1. Start Services
```bash
1. Open XAMPP Control Panel
2. Click "Start" for Apache
3. Click "Start" for MySQL
4. Wait for both to show "Running"
```

### 2. Initialize Database
```
1. Open browser
2. Go to: http://localhost/tessonilov3/api/init_db.php
3. Wait for: "Database initialized successfully"
4. Database is now ready
```

---

## ✅ TEST SUITE 1: AUTHENTICATION & AUTHORIZATION

### Test 1.1: Admin Login
**Purpose**: Verify admin authentication works

```
Steps:
1. Open: http://localhost/tessonilov3/login.html
2. Enter Username: admin
3. Enter Password: admin123
4. Click "MASUK"
5. Wait for redirect

Expected Result:
✅ No errors in browser console
✅ Redirect to dashboard.html
✅ Dashboard loads with statistics
✅ Welcome message shows "admin"

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 1.2: Wrong Password
**Purpose**: Verify error handling for wrong credentials

```
Steps:
1. Open: http://localhost/tessonilov3/login.html
2. Enter Username: admin
3. Enter Password: wrongpassword
4. Click "MASUK"

Expected Result:
✅ Error message: "Username or password incorrect"
✅ Not redirected
✅ Page remains on login

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 1.3: Customer Registration
**Purpose**: Verify customer can register new account

```
Steps:
1. Open: http://localhost/tessonilov3/register.html
2. Enter Username: testcustomer
3. Enter Email: test@example.com
4. Enter Full Name: Test Customer
5. Enter Phone: 08123456789
6. Enter Password: testpass123
7. Click "REGISTRASI"

Expected Result:
✅ Success message: "Registrasi berhasil!"
✅ Redirect to login.html after 2 seconds
✅ Data inserted in database

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 1.4: Customer Login
**Purpose**: Verify registered customer can login

```
Steps:
1. Open: http://localhost/tessonilov3/login.html
2. Enter Username: testcustomer
3. Enter Password: testpass123
4. Click "MASUK"

Expected Result:
✅ Redirect to booking.html
✅ Name displays: "Test Customer"
✅ Email pre-filled: test@example.com
✅ Dashboard loads

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 1.5: Logout
**Purpose**: Verify logout destroys session

```
Steps:
1. In booking.html, click "Logout" button
2. In confirm dialog, click "Logout"
3. Wait for redirect

Expected Result:
✅ Redirect to login.html
✅ Session cleared
✅ Cannot access booking.html without login

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 1.6: Session Timeout
**Purpose**: Verify session expires after 1 hour

```
Steps:
1. Login as customer
2. Open browser dev tools (F12)
3. Go to Console
4. Find session time in code
5. Wait or manually test expiry

Expected Result:
✅ After 1 hour of inactivity
✅ Next API call returns 401
✅ Redirect to login required

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

---

## 📊 TEST SUITE 2: DASHBOARD & STATISTICS

### Test 2.1: Dashboard Load
**Purpose**: Verify dashboard loads correctly for admin

```
Steps:
1. Login as admin (admin/admin123)
2. Wait for dashboard.html to load
3. Look at page content

Expected Result:
✅ Dashboard header displays
✅ Sidebar menu visible
✅ Statistics cards display
✅ Charts render without errors
✅ No JavaScript errors (F12 Console)

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 2.2: Statistics Display
**Purpose**: Verify statistics show correct data

```
Steps:
1. On dashboard, check stat cards:
   - Total Pengguna
   - Total Reservasi
   - Reservasi Confirmed
   - Total Pendapatan

Expected Result:
✅ All cards display values
✅ Values are numbers (not errors)
✅ Icons display correctly
✅ Layout is responsive

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 2.3: Charts Rendering
**Purpose**: Verify charts display correctly

```
Steps:
1. On dashboard, scroll down
2. Look for "Statistik Reservasi" chart
3. Look for "Data Bulanan" chart

Expected Result:
✅ Doughnut chart displays
✅ Line chart displays
✅ Chart labels visible
✅ No console errors
✅ Colors are distinct

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 2.4: Menu Navigation
**Purpose**: Verify all menu items work

```
Steps:
1. Click "Kelola Berita" menu
2. Verify berita list appears
3. Click "Kelola Reservasi" menu
4. Verify reservasi list appears
5. Click "Laporan" menu
6. Verify report options appear
7. Click "Profil" menu
8. Verify profile info appears

Expected Result:
✅ All menu items clickable
✅ Content changes on click
✅ No console errors
✅ Menu item highlights (active state)

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

---

## 📰 TEST SUITE 3: BERITA (NEWS) CRUD

### Test 3.1: Create Berita
**Purpose**: Verify admin can create news

```
Steps:
1. Login as admin
2. Click "Kelola Berita" menu
3. Click "+ Tambah Berita" button
4. Fill form:
   - Judul: "Berita Test"
   - Kategori: "Konservasi"
   - Konten: "Ini adalah berita test"
5. Click "Simpan"

Expected Result:
✅ Alert: "Berita berhasil ditambahkan"
✅ Form closes
✅ New berita appears in list
✅ Database has new entry

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 3.2: Read Berita
**Purpose**: Verify berita list displays correctly

```
Steps:
1. On "Kelola Berita" page
2. Look at berita list table
3. Check columns: Judul, Kategori, Tanggal, Aksi

Expected Result:
✅ Table displays with multiple rows
✅ All columns visible
✅ Edit and Hapus buttons present
✅ Pagination working (if many items)

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 3.3: Update Berita
**Purpose**: Verify berita can be edited

```
Steps:
1. On berita list, click "Edit" button
2. Modify fields:
   - Judul: "Berita Test Updated"
   - Kategori: "Riset"
3. Click "Simpan"

Expected Result:
✅ Alert: "Berita berhasil diperbarui"
✅ Form closes
✅ List updates with new data
✅ Changes reflected in database

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 3.4: Delete Berita
**Purpose**: Verify berita can be deleted

```
Steps:
1. On berita list, click "Hapus" button
2. Confirm in dialog

Expected Result:
✅ Confirmation dialog appears
✅ Alert: "Berita berhasil dihapus"
✅ Item removed from list
✅ Deleted from database

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 3.5: Customer Access
**Purpose**: Verify customers can read but not modify

```
Steps:
1. Logout from admin
2. Open: http://localhost/tessonilov3/berita.html
3. Check news list

Expected Result:
✅ Customer can view news list
✅ No CRUD buttons visible
✅ Can click "Baca Selengkapnya"
✅ No authentication needed (public)

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

---

## 🎫 TEST SUITE 4: RESERVASI (BOOKING) CRUD

### Test 4.1: Customer Create Booking
**Purpose**: Verify customer can create booking

```
Steps:
1. Login as customer (testcustomer/testpass123)
2. On booking page, fill form:
   - Nama: Test Customer
   - Email: test@example.com
   - Telepon: 08123456789
   - Tanggal: 2025-12-25
   - Jumlah Tiket: 2
   - Catatan: Booking test
3. Click "Pesan Sekarang"
4. Confirm in dialog
5. Click "Konfirmasi & Pesan"

Expected Result:
✅ Total price: Rp 250.000 (2 x 125.000)
✅ Confirmation modal displays
✅ Success modal shows booking number
✅ Booking saved to database
✅ Status is 'pending'

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 4.2: View Own Bookings
**Purpose**: Verify customer sees only own bookings

```
Steps:
1. On booking page
2. Scroll to "Pemesanan Anda" section
3. Check if recent booking appears

Expected Result:
✅ Recent booking visible
✅ Shows booking number
✅ Shows date and ticket count
✅ Shows status (pending/confirmed)
✅ Max 3 bookings displayed

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 4.3: Admin View All Bookings
**Purpose**: Verify admin can see all bookings

```
Steps:
1. Login as admin
2. Click "Kelola Reservasi" menu
3. Check reservasi list

Expected Result:
✅ All bookings visible (not just admin's)
✅ Includes customer booking from Test 4.1
✅ Table shows: Nama, Email, Tanggal, Tiket, Total, Status, Aksi
✅ Can see customer's name and details

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 4.4: Admin Update Booking Status
**Purpose**: Verify admin can change booking status

```
Steps:
1. In reservasi list, click "Edit" on customer booking
2. Modal opens with booking details
3. Change Status: pending → confirmed
4. Click "Simpan"

Expected Result:
✅ Alert: "Reservasi berhasil diperbarui"
✅ Status changes to confirmed
✅ Database updated

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 4.5: Customer Cannot Update Status
**Purpose**: Verify customer can't change status field

```
Steps:
1. Login as customer
2. Try to open booking details (if API exists)

Expected Result:
✅ Customer can only update name/email/phone
✅ Status field disabled/hidden for customer
✅ API prevents status change

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 4.6: Admin Delete Booking
**Purpose**: Verify admin can delete booking

```
Steps:
1. Create another test booking
2. In admin panel, click "Delete" on that booking
3. Confirm deletion

Expected Result:
✅ Booking removed from list
✅ Deleted from database
✅ Alert: "Reservasi berhasil dihapus"

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

---

## 📊 TEST SUITE 5: REPORTS & EXPORT

### Test 5.1: Export Reservasi CSV
**Purpose**: Verify CSV export functionality

```
Steps:
1. Login as admin
2. Click "Laporan" menu
3. Click "Export CSV" button (Reservasi)
4. Check downloaded file

Expected Result:
✅ File downloads: reservasi_report_*.csv
✅ File can open in Excel
✅ Contains all reservation fields
✅ UTF-8 encoded properly
✅ Multiple rows if bookings exist

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 5.2: Export Berita JSON
**Purpose**: Verify JSON export functionality

```
Steps:
1. In admin panel, go to "Laporan"
2. Click "Export JSON" button (Berita)
3. Check downloaded file

Expected Result:
✅ File downloads: berita_report_*.json
✅ File is valid JSON
✅ Can parse in text editor
✅ Contains news objects
✅ Pretty-printed format

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 5.3: Customer Cannot Export
**Purpose**: Verify export is admin-only

```
Steps:
1. Login as customer
2. Try to access: /api/report/export.php
3. Check response

Expected Result:
✅ Error 403: "Unauthorized access"
✅ File doesn't download
✅ Cannot access report features

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 5.4: PDF Report
**Purpose**: Verify PDF export works

```
Steps:
1. In admin panel, would use:
   GET /api/report/export_pdf.php?type=reservasi
2. Check file

Expected Result:
✅ File downloads or displays
✅ HTML formatted correctly
✅ Header shows title & date
✅ Table with all data
✅ Professional appearance

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

---

## 🔐 TEST SUITE 6: SECURITY

### Test 6.1: SQL Injection Prevention
**Purpose**: Verify SQL injection is prevented

```
Steps:
1. Try login with username: admin' OR '1'='1
2. Try with password with SQL characters

Expected Result:
✅ No SQL error shown
✅ Login fails with normal error message
✅ Prepared statements prevent injection

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 6.2: XSS Prevention
**Purpose**: Verify XSS attacks are prevented

```
Steps:
1. Try to create berita with title: <script>alert('XSS')</script>
2. Check if script executes

Expected Result:
✅ Script doesn't execute
✅ Text displayed as-is (escaped)
✅ Page remains secure

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 6.3: CORS Security
**Purpose**: Verify CORS headers are set

```
Steps:
1. Open browser dev tools (F12)
2. Network tab
3. Make API request
4. Check response headers

Expected Result:
✅ Response has CORS headers
✅ Access-Control-Allow-Origin present
✅ Proper content-type headers

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 6.4: Password Security
**Purpose**: Verify passwords are hashed

```
Steps:
1. Login to phpMyAdmin
2. Check users table
3. Look at password field

Expected Result:
✅ Passwords are hashed (60 chars, bcrypt)
✅ Not stored in plain text
✅ Hash changes each time (different salt)

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

---

## 📱 TEST SUITE 7: RESPONSIVENESS

### Test 7.1: Mobile Responsive
**Purpose**: Verify pages work on mobile

```
Steps:
1. Open Chrome DevTools (F12)
2. Toggle device toolbar (Ctrl+Shift+M)
3. Select iPhone 12 / iPad
4. Test each page:
   - login.html
   - register.html
   - booking.html
   - dashboard.html
5. Check layout, buttons, forms

Expected Result:
✅ Layout adapts to screen
✅ Text readable (no overflow)
✅ Buttons clickable (not too small)
✅ Forms easy to fill
✅ No horizontal scrolling

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 7.2: Tablet Responsive
**Purpose**: Verify pages work on tablets

```
Steps:
1. Toggle device: iPad Pro
2. Test same pages as above
3. Check layout

Expected Result:
✅ Sidebar may adapt
✅ Content readable
✅ Functionality preserved
✅ No console errors

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

---

## 🐛 TEST SUITE 8: ERROR HANDLING

### Test 8.1: Missing Required Fields
**Purpose**: Verify form validation

```
Steps:
1. On booking form, leave fields empty
2. Try to submit

Expected Result:
✅ Browser validation prevents submit
✅ Fields highlight/show error
✅ Error message displays

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 8.2: Invalid Email
**Purpose**: Verify email validation

```
Steps:
1. Register with email: "notanemail"
2. Try to submit

Expected Result:
✅ Validation prevents submit
✅ Error message: "Invalid email"

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 8.3: Past Date Selection
**Purpose**: Verify date validation

```
Steps:
1. On booking form
2. Try to select past date

Expected Result:
✅ Date picker prevents past dates
✅ Error alert: "Tanggal tidak boleh di masa lalu"

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

### Test 8.4: API Error Response
**Purpose**: Verify error handling

```
Steps:
1. Try to create duplicate username during register
2. Check response

Expected Result:
✅ Error message: "Username already exists"
✅ User sees friendly error
✅ No technical stack trace shown

Actual Result:
[ ] Pass [ ] Fail
Notes: ___________________________
```

---

## 📋 FINAL VERIFICATION CHECKLIST

### Functionality
- [ ] All 25 API endpoints responding
- [ ] All CRUD operations working
- [ ] Session management working
- [ ] Reports exporting correctly

### Security
- [ ] No SQL injection possible
- [ ] No XSS vulnerabilities
- [ ] Authorization enforced
- [ ] Passwords hashed

### UI/UX
- [ ] Forms responsive
- [ ] Error messages clear
- [ ] Success messages displaying
- [ ] Charts rendering

### Performance
- [ ] Pages load < 3 seconds
- [ ] No console errors
- [ ] Charts render smoothly
- [ ] API responses < 1 second

### Documentation
- [ ] README.md complete
- [ ] DOKUMENTASI.md detailed
- [ ] All endpoints documented
- [ ] Setup instructions clear

---

## ✅ TEST COMPLETION SUMMARY

**Test Date**: _______________  
**Tester Name**: _______________  
**Total Tests**: 40+  
**Passed**: _____ / _____  
**Failed**: _____ / _____  
**Skipped**: _____ / _____  

### Overall Status
- [ ] All tests passed ✅
- [ ] Some tests failed ⚠️
- [ ] Critical issues found ❌

### Issues Found
```
1. ________________________________
2. ________________________________
3. ________________________________
```

### Recommendations
```
1. ________________________________
2. ________________________________
3. ________________________________
```

---

**Testing Complete!** 🎉

System is ready for production deployment if all tests passed.

For any issues found, refer to DOKUMENTASI.md troubleshooting section.
