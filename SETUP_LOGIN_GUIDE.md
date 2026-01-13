# 🚀 PANDUAN LOGIN & SETUP DATABASE

## Jika Melihat Error "Terjadi kesalahan. Silakan coba lagi."

### Langkah 1: Inisialisasi Database
Pertama kali menggunakan sistem, Anda HARUS menginisialisasi database terlebih dahulu.

**Cara 1: Menggunakan Setup Page (REKOMENDASI)**
1. Buka browser: `http://localhost/tessonilov3/setup.html`
2. Klik tombol **"Inisialisasi Database"**
3. Tunggu sampai muncul pesan **"Database initialized successfully"** (hijau)
4. Klik **"Lanjut ke Login"** atau buka `http://localhost/tessonilov3/login.html`

**Cara 2: Menggunakan Direct API**
1. Buka browser: `http://localhost/tessonilov3/api/init_db.php`
2. Tunggu sampai muncul pesan JSON: `{"success":true,"message":"Database initialized successfully"}`
3. Kemudian baru login di: `http://localhost/tessonilov3/login.html`

---

## Langkah 2: Login dengan Kredensial Default

Setelah database siap, gunakan kredensial ini:

```
Username: admin
Password: admin123
```

**Cara Login:**
1. Buka: `http://localhost/tessonilov3/login.html`
2. Masukkan username: `admin`
3. Masukkan password: `admin123`
4. Klik tombol **"MASUK"**
5. Otomatis redirect ke dashboard admin

---

## Jika Masih Error Login

### Cek 1: Verifikasi Database Sudah Dibuat
```
1. Buka phpMyAdmin: http://localhost/phpmyadmin
2. Cari database: tesso_nilo_db
3. Harus ada 4 tabel: users, berita, reservasi, ticket_pricing
4. Di tabel users harus ada 1 user dengan role "admin"
```

### Cek 2: Lihat Browser Console (F12)
```
1. Tekan F12 pada keyboard
2. Buka tab "Console"
3. Lihat error messages yang muncul
4. Screenshot error dan reportkan
```

### Cek 3: Cek MySQL Service
```
1. Pastikan XAMPP Apache & MySQL running (hijau)
2. Jika berwarna merah, start ulang services
3. Tunggu sampai fully started sebelum login
```

### Cek 4: Clear Browser Cache
```
1. Tekan Ctrl + Shift + Delete
2. Pilih "All Time"
3. Check: Cookies, Cached Images, Cached Files
4. Klik "Clear data"
5. Refresh halaman login
```

---

## Troubleshooting Umum

### Error: "Terjadi kesalahan. Silakan coba lagi." (tanpa pesan spesifik)

**Penyebab Kemungkinan:**
- ❌ Database belum diinisialisasi
- ❌ Koneksi database gagal
- ❌ PHP Session tidak berfungsi
- ❌ File API tidak ditemukan

**Solusi:**
```
1. Pastikan sudah jalankan setup.html
2. Periksa phpMyAdmin ada database tesso_nilo_db
3. Cek Apache & MySQL running
4. Lihat browser console (F12) untuk detail error
```

### Error: "Database connection failed"

**Penyebab:**
- MySQL tidak berjalan
- Username/password database salah

**Solusi:**
```
1. Buka XAMPP Control Panel
2. Pastikan MySQL service running (START jika belum)
3. Tunggu 2-3 detik sampai fully running
4. Coba refresh login page
```

### Session Tidak Tersimpan

**Penyebab:**
- Browser blocker/privacy tools
- Cookie disabled

**Solusi:**
```
1. Buka Settings Browser
2. Privacy & Security → Cookies
3. Pastikan Allow Cookies
4. Refresh halaman
```

---

## Credential List

### Administrator
```
Username: admin
Password: admin123
Email: admin@tessonilo.com
Full Name: Administrator
Role: admin
```

### Untuk Customer
Dapat membuat akun baru via Register Page: `http://localhost/tessonilov3/register.html`

---

## Fitur Setelah Login

### Admin Dashboard (`/dashboard.html`)
- ✅ Lihat statistik sistem
- ✅ Manage berita (CRUD)
- ✅ Manage reservasi pelanggan
- ✅ Export laporan (CSV/JSON/PDF)
- ✅ Lihat profil admin

### Customer Booking (`/booking.html`)
- ✅ Buat reservasi tiket
- ✅ Lihat history booking
- ✅ Hitung harga otomatis
- ✅ Konfirmasi pemesanan

---

## Quick Checklist Sebelum Login

- [ ] XAMPP Apache running (hijau)
- [ ] XAMPP MySQL running (hijau)
- [ ] Sudah jalankan setup.html
- [ ] Database created di phpMyAdmin
- [ ] 4 tabel ada di database
- [ ] Admin user ada di tabel users
- [ ] Browser cache sudah clear
- [ ] Cookies browser enabled
- [ ] Mencoba di browser yang berbeda

---

## Support

Jika masih error setelah mengikuti semua langkah:

1. Lihat console error (F12)
2. Screenshot error message
3. Check phpMyAdmin connection
4. Restart XAMPP services
5. Clear browser cache & cookies
6. Coba di browser lain (Chrome, Firefox, Edge)

**File Penting:**
- Setup: `/setup.html`
- Login: `/login.html`
- Database Init: `/api/init_db.php`
- Config: `/config/database.php`

---

## 📌 MOST IMPORTANT

**Jangan lupa jalankan SETUP terlebih dahulu!**
Klik link ini: `http://localhost/tessonilov3/setup.html`

Baru kemudian login dengan:
- Username: `admin`
- Password: `admin123`
