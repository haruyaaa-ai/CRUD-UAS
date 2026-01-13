# Taman Nasional Tesso Nilo - Sistem Manajemen Tiket & Berita

**Status**: ✅ Produksi Ready  
**Version**: 1.0.0  
**Last Updated**: 22 Desember 2025

## 🚀 Quick Start (5 Menit)

### 1. Initialize Database
```bash
Browser: http://localhost/tessonilov3/api/init_db.php
Expected: "Database initialized successfully"
```

### 2. Login sebagai Admin
```
URL: http://localhost/tessonilov3/login.html
Username: admin
Password: admin123
```

### 3. Akses Dashboard
```
Otomatis redirect ke: http://localhost/tessonilov3/dashboard.html
```

### 4. Buat Akun Customer & Pesan Tiket
```
URL: http://localhost/tessonilov3/register.html
Register → Login → http://localhost/tessonilov3/booking.html
```

---

## ✨ Fitur Utama

### Admin Dashboard
- 📊 **Statistics**: Total users, reservasi, revenue, news
- 📰 **Berita Management**: CRUD berita/laporan
- 🎫 **Reservasi Management**: Manage customer bookings
- 📊 **Reports**: Export CSV/JSON
- 👤 **Profile**: View admin info

### Customer Booking
- ✍️ **Easy Booking**: Simple form untuk pesan tiket
- 📅 **Date Selection**: Pilih tanggal kunjungan
- 🎟️ **Flexible Quantity**: Sesuaikan jumlah tiket
- ✅ **Confirmation**: Review sebelum submit
- 📋 **History**: Lihat riwayat pemesanan

### Security
- 🔐 **Authentication**: Secure login dengan session management
- 🔒 **Authorization**: Role-based access control
- 🛡️ **Input Validation**: Protection against injection attacks
- ⏱️ **Session Timeout**: 1 hour auto-logout
- 🍪 **Secure Cookies**: HttpOnly flag enabled

---

## 📁 Folder Structure

```
tessonilov3/
├── api/                    # Backend REST API
│   ├── auth/              # Login, Register, Session
│   ├── berita/            # News CRUD
│   ├── reservasi/         # Booking CRUD
│   ├── dashboard/         # Admin statistics
│   ├── report/            # Export functionality
│   └── init_db.php        # Database initialization
├── config/
│   └── database.php       # DB config & helpers
├── dashboard.html         # Admin panel
├── booking.html           # Customer booking page
├── login.html            # Login page
├── register.html         # Registration page
├── index.html            # Home page
├── berita.html           # News list
├── about.html            # About page
├── fauna.html            # Wildlife page
├── style.css             # Styling
├── script.js             # Frontend logic
├── DOKUMENTASI.md        # Full documentation
└── README.md             # This file
```

---

## 🔌 API Endpoints

### Authentication
- `POST /api/auth/login.php` - User login
- `POST /api/auth/register.php` - User registration
- `GET /api/auth/check_session.php` - Verify session
- `GET /api/auth/logout.php` - Logout

### Berita (News)
- `POST /api/berita/create.php` - Create news (admin)
- `GET /api/berita/read.php` - Get news list
- `GET /api/berita/read.php?id=1` - Get single news
- `PUT /api/berita/update.php` - Update news (admin)
- `DELETE /api/berita/delete.php` - Delete news (admin)

### Reservasi (Booking)
- `POST /api/reservasi/create.php` - Create booking
- `GET /api/reservasi/read.php` - Get bookings
- `GET /api/reservasi/read.php?id=1` - Get single booking
- `PUT /api/reservasi/update.php` - Update booking
- `DELETE /api/reservasi/delete.php` - Delete booking

### Dashboard & Reports
- `GET /api/dashboard/statistics.php` - Get statistics (admin)
- `GET /api/report/export.php?type=X&format=Y` - Export data (admin)

---

## 📊 Default Credentials

### Admin Account
- **Username**: admin
- **Password**: admin123
- **Email**: admin@tessonilo.com
- **Role**: Administrator

*Note: Ganti password admin setelah login pertama*

---

## 🔐 Default Ticket Prices

| Type | Price | Description |
|------|-------|-------------|
| Dewasa | Rp 150.000 | Untuk wisatawan dewasa |
| Anak-anak | Rp 100.000 | Untuk anak usia 5-12 tahun |
| Pelajar | Rp 125.000 | Dengan kartu pelajar valid |
| Rombongan | Rp 125.000 | Minimal 10 orang |

---

## 🧪 Testing Checklist

### User Registration
- [ ] Register dengan data valid
- [ ] Register dengan username duplicate (error)
- [ ] Register dengan email duplicate (error)
- [ ] Verify data tersimpan di database

### User Login
- [ ] Login admin berhasil
- [ ] Login customer berhasil
- [ ] Login dengan password salah (error)
- [ ] Session created & cookie set

### Admin Functions
- [ ] Create berita ✓
- [ ] Edit berita ✓
- [ ] Delete berita ✓
- [ ] View reservasi ✓
- [ ] Update reservasi status ✓
- [ ] Export CSV ✓
- [ ] Export JSON ✓
- [ ] View statistics ✓

### Customer Functions
- [ ] Create reservasi ✓
- [ ] View own reservasi ✓
- [ ] See booking history ✓
- [ ] Cannot edit others' reservasi ✓

### Security
- [ ] Session timeout after 1 hour
- [ ] Logout clears session
- [ ] Cannot access admin panel without login
- [ ] Cannot access booking without login
- [ ] CORS enabled for API

---

## ⚙️ Configuration

### Database Settings
File: `config/database.php`

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tesso_nilo_db');
```

### Session Timeout
Default: 3600 detik (1 jam)
File: `config/database.php` line 16

### API Base URL
Frontend: `/tessonilov3/api`
Update di masing-masing HTML file jika berubah path

---

## 📱 Responsive Design

- ✅ Desktop (1200px+)
- ✅ Tablet (768px - 1199px)
- ✅ Mobile (< 768px)

Menggunakan Tailwind CSS untuk responsive utilities

---

## 🌐 Browser Support

- ✅ Chrome/Chromium 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+

---

## 📦 Dependencies

### Frontend
- Tailwind CSS (CDN)
- Lucide Icons (CDN)
- Chart.js (untuk dashboard charts)
- Vanilla JavaScript (no framework)

### Backend
- PHP 7.4+ (built-in)
- MySQL 5.7+ (with mysqli)

### Server
- Apache (XAMPP included)
- CORS enabled
- Prepared statements (security)

---

## 🔧 Maintenance

### Regular Tasks
- [ ] Check session logs monthly
- [ ] Backup database weekly
- [ ] Monitor API performance
- [ ] Update password policies
- [ ] Review access logs

### Database Optimization
```sql
-- Check database size
SELECT table_name, ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
FROM information_schema.tables
WHERE table_schema = 'tesso_nilo_db';

-- Optimize tables
OPTIMIZE TABLE users, berita, reservasi, ticket_pricing;
```

---

## 🐛 Known Issues & Solutions

| Issue | Solution |
|-------|----------|
| Database not initializing | Verify MySQL running + phpMyAdmin accessible |
| Session expired too fast | Check server time + PHP session.gc_maxlifetime |
| Images not loading | CDN might be blocked, use local images |
| Charts not showing | Ensure Chart.js CDN accessible |
| API CORS error | Check browser console, verify CORS headers |

---

## 📚 Documentation

Dokumentasi lengkap tersedia di: [`DOKUMENTASI.md`](./DOKUMENTASI.md)

Sections:
- Architecture & Design
- Complete API Reference
- User Guide (Admin & Customer)
- Database Schema
- Security Implementation
- Troubleshooting Guide

---

## 🎯 Next Steps / Future Enhancements

- [ ] Payment gateway integration
- [ ] Email notification system
- [ ] SMS alerts
- [ ] Advanced reporting
- [ ] Mobile app (React Native)
- [ ] Multi-language support
- [ ] Dark mode UI
- [ ] API rate limiting
- [ ] Advanced analytics
- [ ] Seasonal pricing

---

## 📝 License & Credits

**Dibuat untuk**: Program Studi Teknik Informatika RP 23 CNS A  
**Author**: Damar Satriatama Putra (23552011300)  
**Project**: Taman Nasional Tesso Nilo Management System

---

## 📞 Support

Untuk bantuan atau pertanyaan:
- 📧 Email: admin@tessonilo.com
- 📞 Phone: +62-761-2345678
- 🏢 Website: www.tessonilo.com

---

**Happy Booking! 🌿🐘**
