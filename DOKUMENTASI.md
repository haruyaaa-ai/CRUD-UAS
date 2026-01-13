# Dokumentasi Sistem Taman Nasional Tesso Nilo

## 📋 Daftar Isi
1. [Requirement & Fitur](#requirement--fitur)
2. [Arsitektur Sistem](#arsitektur-sistem)
3. [Setup & Instalasi](#setup--instalasi)
4. [Struktur Folder](#struktur-folder)
5. [API Documentation](#api-documentation)
6. [User Guide](#user-guide)
7. [Testing](#testing)

---

## ✅ Requirement & Fitur

### 1. **Backend & Frontend Terintegrasi**
- ✅ Backend PHP dengan API RESTful
- ✅ Frontend HTML5 + Tailwind CSS
- ✅ Session management dengan PHP dan JavaScript
- ✅ CORS enabled untuk komunikasi API

### 2. **Dashboard Admin**
- ✅ Interface untuk pengelolaan sistem
- ✅ Menu navigasi utama (Dashboard, Berita, Reservasi, Laporan, Profil)
- ✅ Statistik real-time (users, reservasi, revenue, news)
- ✅ Chart untuk visualisasi data

### 3. **Sistem CRUD (Create, Read, Update, Delete)**
- ✅ **Berita Management**: Create, Read, Update, Delete berita/laporan
- ✅ **Reservasi Management**: Admin dapat mengelola semua reservasi
- ✅ **User Management**: Register, Login, Profile management

### 4. **Session & Authentication**
- ✅ Login dengan validasi backend
- ✅ Session PHP timeout 1 jam
- ✅ Secure cookies (HttpOnly)
- ✅ Role-based access (Admin vs Customer)
- ✅ Session check di setiap request API
- ✅ Frontend validation untuk session expire

### 5. **Fitur Pemesanan Tiket (Customer)**
- ✅ Halaman booking publik untuk pelanggan
- ✅ Form pemesanan dengan validasi
- ✅ Konfirmasi pemesanan
- ✅ History pemesanan personal
- ✅ Status pemesanan tracking (Pending, Confirmed, Cancelled)

### 6. **Report & Export**
- ✅ Export data reservasi ke CSV/JSON
- ✅ Export data berita ke CSV/JSON
- ✅ Generate PDF report (HTML format)
- ✅ Akses hanya untuk admin

---

## 🏗️ Arsitektur Sistem

### Frontend Architecture
```
Frontend (HTML/CSS/JavaScript)
├── Public Pages (index.html, berita.html, about.html, fauna.html)
├── Auth Pages (login.html, register.html)
├── Customer Page (booking.html)
└── Admin Page (dashboard.html)
```

### Backend Architecture
```
Backend (PHP + MySQL)
├── API Endpoints (RESTful)
│   ├── /auth/ (login, logout, register, check_session)
│   ├── /berita/ (create, read, update, delete)
│   ├── /reservasi/ (create, read, update, delete)
│   ├── /dashboard/ (statistics)
│   └── /report/ (export, export_pdf)
└── Database (MySQL)
    ├── users
    ├── berita
    ├── reservasi
    └── ticket_pricing
```

### Session Flow
```
User Login
    ↓
POST /auth/login.php
    ↓
Verify credentials
    ↓
Create session
    ↓
Set secure cookie
    ↓
Return user data
    ↓
Store in sessionStorage
    ↓
Redirect based on role
```

---

## 🚀 Setup & Instalasi

### Prerequisites
- XAMPP (Apache, PHP 7.4+, MySQL 5.7+)
- Modern browser (Chrome, Firefox, Safari, Edge)
- Text editor (VS Code, Sublime, etc.)

### Step 1: Persiapan Database
```bash
1. Buka XAMPP Control Panel
2. Start Apache dan MySQL
3. Buka phpMyAdmin di http://localhost/phpmyadmin
```

### Step 2: Inisialisasi Database
```bash
1. Akses URL: http://localhost/tessonilov3/api/init_db.php
2. Tunggu sampai muncul message "Database initialized successfully"
3. Database dan tables sudah siap digunakan
```

### Step 3: Default Admin User
Setelah inisialisasi, user admin tersedia:
- **Username**: admin
- **Password**: admin123
- **Email**: admin@tessonilo.com

### Step 4: Testing Login
```bash
1. Buka http://localhost/tessonilov3/login.html
2. Masukkan credentials admin
3. Seharusnya redirect ke dashboard.html
```

---

## 📁 Struktur Folder

```
tessonilov3/
├── config/
│   └── database.php              # Konfigurasi database & helpers
├── api/
│   ├── auth/
│   │   ├── login.php             # Login API
│   │   ├── logout.php            # Logout API
│   │   ├── register.php          # Register API
│   │   └── check_session.php     # Check session API
│   ├── berita/
│   │   ├── create.php            # Create berita
│   │   ├── read.php              # Get berita (single/list)
│   │   ├── update.php            # Update berita
│   │   └── delete.php            # Delete berita
│   ├── reservasi/
│   │   ├── create.php            # Create reservasi
│   │   ├── read.php              # Get reservasi (single/list)
│   │   ├── update.php            # Update reservasi
│   │   └── delete.php            # Delete reservasi
│   ├── dashboard/
│   │   └── statistics.php        # Get dashboard stats
│   ├── report/
│   │   ├── export.php            # Export CSV/JSON
│   │   └── export_pdf.php        # Export PDF
│   └── init_db.php               # Database initialization
├── index.html                    # Home publik
├── login.html                    # Login page
├── register.html                 # Register page
├── booking.html                  # Customer booking page
├── dashboard.html                # Admin dashboard
├── about.html                    # About page
├── fauna.html                    # Fauna/Satwa page
├── berita.html                   # News list page
├── style.css                     # Custom CSS
└── script.js                     # Main JavaScript
```

---

## 🔌 API Documentation

### Authentication APIs

#### 1. Login
```
POST /api/auth/login.php
Content-Type: application/json

Request:
{
    "username": "admin",
    "password": "admin123"
}

Response Success (200):
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user_id": 1,
        "username": "admin",
        "email": "admin@tessonilo.com",
        "full_name": "Administrator",
        "role": "admin",
        "session_id": "session_id_hash"
    }
}

Response Error (401):
{
    "success": false,
    "message": "Username or password incorrect"
}
```

#### 2. Register
```
POST /api/auth/register.php
Content-Type: application/json

Request:
{
    "username": "budi",
    "email": "budi@example.com",
    "password": "password123",
    "full_name": "Budi Santoso",
    "phone": "08123456789"
}

Response Success (201):
{
    "success": true,
    "message": "Registration successful",
    "data": {
        "user_id": 3,
        "username": "budi",
        "email": "budi@example.com",
        "full_name": "Budi Santoso",
        "role": "customer"
    }
}
```

#### 3. Check Session
```
GET /api/auth/check_session.php

Response Success (200):
{
    "success": true,
    "message": "Session valid",
    "data": {
        "user_id": 1,
        "username": "admin",
        "email": "admin@tessonilo.com",
        "full_name": "Administrator",
        "role": "admin",
        "is_logged_in": true
    }
}

Response Error (401):
{
    "success": false,
    "message": "Not authenticated"
}
```

#### 4. Logout
```
GET /api/auth/logout.php

Response:
{
    "success": true,
    "message": "Logout successful"
}
```

### Berita (News) APIs

#### 1. Create Berita (Admin Only)
```
POST /api/berita/create.php
Authorization: Session Required
Content-Type: application/json

Request:
{
    "title": "Laporan Konservasi Baru",
    "content": "Isi laporan konservasi...",
    "category": "Konservasi"
}

Response Success (201):
{
    "success": true,
    "message": "Berita created successfully",
    "data": {
        "id": 7,
        "title": "Laporan Konservasi Baru",
        "content": "Isi laporan konservasi...",
        "category": "Konservasi",
        "date": "2025-12-22 10:30:00"
    }
}
```

#### 2. Read Berita (Public)
```
GET /api/berita/read.php              # Get all berita (paginated)
GET /api/berita/read.php?id=1         # Get single berita
GET /api/berita/read.php?page=2&limit=10

Response:
{
    "success": true,
    "message": "Berita list retrieved successfully",
    "data": {
        "data": [...],
        "pagination": {
            "page": 1,
            "limit": 10,
            "total": 6,
            "pages": 1
        }
    }
}
```

#### 3. Update Berita (Admin Only)
```
PUT /api/berita/update.php
Authorization: Session Required
Content-Type: application/json

Request:
{
    "id": 1,
    "title": "Judul baru",
    "content": "Konten baru",
    "category": "Riset"
}

Response Success:
{
    "success": true,
    "message": "Berita updated successfully",
    "data": { "id": 1 }
}
```

#### 4. Delete Berita (Admin Only)
```
DELETE /api/berita/delete.php
Authorization: Session Required
Content-Type: application/json

Request:
{
    "id": 1
}

Response Success:
{
    "success": true,
    "message": "Berita deleted successfully",
    "data": { "id": 1 }
}
```

### Reservasi (Booking) APIs

#### 1. Create Reservasi (Customer)
```
POST /api/reservasi/create.php
Authorization: Session Required
Content-Type: application/json

Request:
{
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "phone": "08123456789",
    "date_booking": "2025-12-25",
    "tickets": 4,
    "notes": "Ingin fasilitas khusus"
}

Response Success (201):
{
    "success": true,
    "message": "Reservation created successfully",
    "data": {
        "id": 1,
        "user_id": 3,
        "name": "Budi Santoso",
        "date_booking": "2025-12-25",
        "tickets": 4,
        "total_price": 500000,
        "status": "pending"
    }
}
```

#### 2. Read Reservasi
```
GET /api/reservasi/read.php              # Get user's reservasi
GET /api/reservasi/read.php?id=1         # Get specific reservasi
GET /api/reservasi/read.php?page=1       # Pagination

Note: Customer hanya bisa lihat reservasi mereka sendiri.
      Admin bisa lihat semua.

Response:
{
    "success": true,
    "data": {
        "data": [...],
        "pagination": {
            "page": 1,
            "limit": 10,
            "total": 5,
            "pages": 1
        }
    }
}
```

#### 3. Update Reservasi
```
PUT /api/reservasi/update.php
Authorization: Session Required
Content-Type: application/json

Request:
{
    "id": 1,
    "name": "Budi Santoso Baru",
    "email": "budi_baru@example.com",
    "phone": "08987654321",
    "date_booking": "2025-12-26",
    "tickets": 5,
    "status": "confirmed"  # Only admin can change status
}

Response Success:
{
    "success": true,
    "message": "Reservasi updated successfully",
    "data": { "id": 1 }
}
```

#### 4. Delete Reservasi
```
DELETE /api/reservasi/delete.php
Authorization: Session Required
Content-Type: application/json

Request:
{
    "id": 1
}

Response Success:
{
    "success": true,
    "message": "Reservasi deleted successfully",
    "data": { "id": 1 }
}
```

### Dashboard APIs

#### Get Statistics
```
GET /api/dashboard/statistics.php
Authorization: Admin Only

Response:
{
    "success": true,
    "message": "Statistics retrieved successfully",
    "data": {
        "total_users": 5,
        "total_reservations": 10,
        "confirmed_reservations": 7,
        "total_revenue": 3500000,
        "total_news": 6,
        "reservations_by_status": {
            "pending": 2,
            "confirmed": 7,
            "cancelled": 1
        },
        "monthly_data": [...]
    }
}
```

### Report APIs

#### Export CSV/JSON
```
GET /api/report/export.php?type=reservasi&format=csv
GET /api/report/export.php?type=berita&format=json
Authorization: Admin Only

Response: File download
```

---

## 👤 User Guide

### Untuk Admin

#### 1. Login Admin
1. Buka http://localhost/tessonilov3/login.html
2. Masukkan username: `admin`
3. Masukkan password: `admin123`
4. Klik Masuk → Redirect ke Dashboard

#### 2. Dashboard Features
- **Dashboard View**: Lihat statistik real-time
- **Kelola Berita**: CRUD berita/laporan
- **Kelola Reservasi**: Update status reservasi customer
- **Laporan**: Export data ke CSV/JSON
- **Profil**: Lihat informasi profil admin

#### 3. Mengelola Berita
1. Klik menu "Kelola Berita"
2. Klik "+ Tambah Berita"
3. Isi form (Judul, Kategori, Konten)
4. Klik "Simpan"

#### 4. Mengelola Reservasi
1. Klik menu "Kelola Reservasi"
2. Filter atau search reservasi
3. Klik tombol "Edit" untuk ubah status
4. Update status dan klik "Simpan"

#### 5. Export Laporan
1. Klik menu "Laporan"
2. Pilih jenis laporan (Reservasi/Berita)
3. Pilih format (CSV/JSON)
4. File akan otomatis didownload

### Untuk Customer

#### 1. Registrasi
1. Buka http://localhost/tessonilov3/register.html
2. Isi form registrasi (Username, Email, Nama, Phone, Password)
3. Klik "REGISTRASI"
4. Redirect ke login page

#### 2. Login
1. Buka http://localhost/tessonilov3/login.html
2. Masukkan username dan password
3. Klik "Masuk"
4. Redirect ke halaman booking

#### 3. Pesan Tiket
1. Pada halaman booking, isi form:
   - Nama Lengkap
   - Email
   - Nomor Telepon
   - Tanggal Kunjungan
   - Jumlah Tiket
   - Catatan (opsional)
2. Review total harga
3. Klik "Pesan Sekarang"
4. Confirm pemesanan
5. Pemesanan berhasil dengan nomor booking

#### 4. Lihat Pemesanan
1. Di halaman booking, scroll ke bawah ke section "Pemesanan Anda"
2. Lihat 3 pemesanan terbaru dengan status

---

## 🧪 Testing

### Unit Testing

#### Test Login Admin
```bash
1. POST /api/auth/login.php
   - Username: admin
   - Password: admin123
   Expected: Success + redirect to dashboard
   
2. POST /api/auth/login.php
   - Username: admin
   - Password: wrong
   Expected: Error "Username or password incorrect"
```

#### Test Register Customer
```bash
1. POST /api/auth/register.php
   - Username: testuser
   - Email: test@example.com
   - Full Name: Test User
   - Phone: 08123456789
   - Password: test123
   Expected: Success registration + redirect to login
   
2. POST /api/auth/register.php
   - Username: admin (already exists)
   Expected: Error "Username already exists"
```

#### Test Create Berita (Admin Only)
```bash
1. POST /api/berita/create.php (with session)
   - Title: Test News
   - Content: Test content
   - Category: Konservasi
   Expected: Success + new berita created
   
2. POST /api/berita/create.php (without session)
   Expected: Error 401 "Unauthorized access"
```

#### Test Create Reservasi (Customer Only)
```bash
1. POST /api/reservasi/create.php (with customer session)
   - Name: Test Booking
   - Email: test@example.com
   - Phone: 08123456789
   - Date: 2025-12-25
   - Tickets: 4
   Expected: Success + reservation created
   
2. GET /api/reservasi/read.php (customer)
   Expected: Only their own reservations
   
3. GET /api/reservasi/read.php (admin)
   Expected: All reservations
```

#### Test Session Management
```bash
1. Login → Check session via check_session.php
   Expected: Session valid
   
2. Wait > 1 hour → Check session
   Expected: Session expired message
   
3. Logout → Check session
   Expected: Not authenticated
```

#### Test Export
```bash
1. GET /api/report/export.php?type=reservasi&format=csv (admin)
   Expected: CSV file downloaded
   
2. GET /api/report/export.php?type=berita&format=json (admin)
   Expected: JSON file downloaded
   
3. GET /api/report/export.php (customer)
   Expected: Error 403 "Unauthorized access"
```

### Integration Testing

#### Complete User Flow: Customer
```
1. Register account
   ✓ Access register.html
   ✓ Fill form & submit
   ✓ Database insert berhasil
   
2. Login
   ✓ Access login.html
   ✓ Fill credentials
   ✓ Session created
   ✓ Redirect to booking.html
   
3. Make booking
   ✓ Fill booking form
   ✓ Confirm reservation
   ✓ Database insert + notification
   
4. View bookings
   ✓ Load personal bookings
   ✓ Show booking status
   
5. Logout
   ✓ Destroy session
   ✓ Clear cookies
   ✓ Redirect to login
```

#### Complete User Flow: Admin
```
1. Login
   ✓ Admin credentials
   ✓ Redirect to dashboard
   
2. View statistics
   ✓ Load dashboard data
   ✓ Display charts
   
3. Manage berita
   ✓ Create berita
   ✓ Edit berita
   ✓ Delete berita
   
4. Manage reservasi
   ✓ View all reservasi
   ✓ Filter by status
   ✓ Update status
   
5. Export reports
   ✓ Export CSV
   ✓ Export JSON
   
6. Logout
   ✓ Destroy session
```

### Security Testing

#### Session Security
- ✓ Session timeout after 1 hour
- ✓ Cookie HttpOnly flag enabled
- ✓ CORS validation
- ✓ Authorization check on each endpoint

#### Input Validation
- ✓ Required field validation
- ✓ Email format validation
- ✓ SQL injection prevention (prepared statements)
- ✓ XSS prevention (output escaping)

#### Authorization
- ✓ Admin-only endpoints blocked for customers
- ✓ Customers can only access own reservations
- ✓ Role-based access control

---

## 📊 Database Schema

### Users Table
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'customer') DEFAULT 'customer',
    phone VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

### Berita Table
```sql
CREATE TABLE berita (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
)
```

### Reservasi Table
```sql
CREATE TABLE reservasi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    date_booking DATE NOT NULL,
    tickets INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)
```

### Ticket Pricing Table
```sql
CREATE TABLE ticket_pricing (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

---

## 🔒 Security Features Implemented

1. **Password Hashing**: Using bcrypt (password_hash)
2. **Session Management**: PHP session with 1-hour timeout
3. **Secure Cookies**: HttpOnly flag enabled
4. **CORS**: Enabled for API requests
5. **SQL Injection Prevention**: Prepared statements
6. **XSS Prevention**: Output escaping
7. **Authorization**: Role-based access control
8. **Input Validation**: Required fields, format validation

---

## 📝 Troubleshooting

### Database Connection Error
```
Error: "Database connection failed"
Solution:
1. Pastikan MySQL service running
2. Check database credentials in config/database.php
3. Buka phpMyAdmin check status
```

### Session Expired
```
Error: "Session expired" atau "Not authenticated"
Solution:
1. Login lagi
2. Session timeout default 1 jam
3. Check browser cookies setting
```

### API Not Found (404)
```
Error: "API endpoint not found"
Solution:
1. Check API URL path
2. Verify file exists in api/ folder
3. Check request method (GET/POST/PUT/DELETE)
```

### CORS Error
```
Error: "CORS policy blocked"
Solution:
1. CORS sudah enabled di database.php
2. Check browser console for detail error
3. Verify request origin
```

---

## 📞 Support & Contact

Untuk pertanyaan atau issues:
- Email: admin@tessonilo.com
- Phone: +62-761-2345678

---

**Dokumentasi Terakhir Updated**: 22 Desember 2025
**Version**: 1.0.0
