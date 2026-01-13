# 🎫 USER FLOW BARU - CUSTOMER BOOKING

## Alur Login & Akses Customer

### Sebelumnya (OLD FLOW)
```
Customer Login → booking.html (halaman pemesanan saja)
```

### Sekarang (NEW FLOW) ✅
```
Customer Login → index.html (halaman utama + informasi + pemesanan tiket)
```

---

## Fitur di Index.html untuk Customer yang Login

### 1. **Hero Section**
- Judul: "Taman Nasional Tesso Nilo"
- Deskripsi singkat tentang taman

### 2. **Informasi Taman**
3 card yang menampilkan:
- 📍 Lokasi taman
- 🕐 Jam operasional
- 👥 Informasi pengunjung

### 3. **Tentang Tesso Nilo**
- Deskripsi lengkap tentang taman
- Keanekaragaman hayati
- Spesies yang ada

### 4. **Fitur Pemesanan (HANYA untuk customer yang sudah login)**

Ketika customer login, mereka akan melihat:

#### Form Pemesanan:
```
- Nama Lengkap (auto-fill dari data login)
- Email (auto-fill dari data login)  
- Nomor Telepon
- Tanggal Kunjungan (date picker, minimal hari ini)
- Jumlah Tiket (dengan tombol +/-)
```

#### Display Harga:
```
✓ Harga per Tiket: Rp 125.000
✓ Total Harga: Otomatis update sesuai jumlah tiket
```

#### Tombol Aksi:
```
✓ "Pesan Sekarang" - Submit pemesanan
```

#### Riwayat Pemesanan:
```
✓ Menampilkan 3 pemesanan terakhir
✓ Menampilkan Status: Pending/Confirmed/Cancelled
✓ Info: ID, Tanggal, Jumlah Tiket
```

---

## User Experience Flow

### Admin
```
1. Login dengan username: admin, password: admin123
2. Redirect ke: /dashboard.html
3. Bisa manage berita, reservasi, export laporan
4. Tidak bisa ke index.html (atau bisa, tapi booking section hidden)
```

### Customer (Publik)
```
1. Buka index.html
2. Lihat informasi taman
3. Belum ada form pemesanan (booking section hidden)
4. Klik "Daftar" atau "Login" di header
5. Register atau Login
```

### Customer (Sudah Login)
```
1. Login dengan akun customer
2. Redirect ke: /index.html
3. Otomatis tampil form pemesanan di bawah informasi
4. Form sudah auto-fill: Nama & Email
5. Isi data lainnya (Telepon, Tanggal, Jumlah)
6. Harga otomatis dihitung
7. Klik "Pesan Sekarang"
8. Muncul modal sukses dengan Booking ID: TK-{id}
9. Bisa lihat riwayat pemesanan di bawah form
10. Bisa logout via header
```

---

## Fitur yang Ditambahkan

### 1. **Session Detection**
```javascript
const isLoggedIn = sessionStorage.getItem('isLoggedIn') === 'true';
const userData = JSON.parse(sessionStorage.getItem('user_data'));

// Jika customer login, tampilkan booking section
if (isLoggedIn && userData.role === 'customer') {
    document.getElementById('bookingSection').classList.remove('hidden');
}
```

### 2. **Auto-fill Form**
```javascript
document.getElementById('bookingName').value = userData.full_name;
document.getElementById('bookingEmail').value = userData.email;
```

### 3. **Price Calculation**
```javascript
const tickets = parseInt(document.getElementById('bookingTickets').value);
const pricePerTicket = 125000;
const totalPrice = tickets * pricePerTicket;
// Update display: "Rp 125.000" format
```

### 4. **Ticket Quantity Buttons**
```
[−] [1] [+] 

Fitur:
- Minus: Kurangi 1 (min: 1)
- Plus: Tambah 1 (max: 100)
- Otomatis update harga
```

### 5. **Booking History**
```javascript
// Fetch dari /api/reservasi/read.php
// Tampilkan 3 pemesanan terakhir
// Status color-coded:
// - Pending: Yellow
// - Confirmed: Green
// - Cancelled: Red
```

### 6. **Success Modal**
```
Modal muncul setelah berhasil booking:
✓ Ucapan "Pemesanan Berhasil!"
✓ Booking Number: TK-{id}
✓ Tombol "Tutup"
✓ Reset form setelah ditutup
```

---

## Responsive Design

### Mobile (< 768px)
```
- Stacked layout (full width)
- Form fields penuh lebar
- Info cards stacked vertical
```

### Tablet (768px - 1024px)
```
- 2-column grid untuk info cards
- Form lebih nyaman dibaca
```

### Desktop (> 1024px)
```
- 3-column grid untuk info cards
- 2-column form layout
- Optimal spacing
```

---

## API Endpoints yang Digunakan

### Submit Booking
```
POST /tessonilov3/api/reservasi/create.php

Request:
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "08123456789",
  "date_booking": "2025-12-23",
  "tickets": 2,
  "total_price": 250000
}

Response (Success):
{
  "success": true,
  "message": "Booking created successfully",
  "data": {
    "id": 123,
    "user_id": 5,
    ...
  }
}
```

### Load Booking History
```
GET /tessonilov3/api/reservasi/read.php

Response:
{
  "success": true,
  "message": "Bookings retrieved",
  "data": [
    {
      "id": 123,
      "name": "John Doe",
      "date_booking": "2025-12-23",
      "tickets": 2,
      "status": "pending"
    },
    ...
  ]
}
```

---

## Session Management

### Session Attributes yang Digunakan
```javascript
sessionStorage:
- isLoggedIn: 'true' atau 'false'
- user_data: 
  {
    "user_id": 5,
    "username": "johndoe",
    "email": "john@example.com",
    "full_name": "John Doe",
    "role": "customer",
    "session_id": "abc123..."
  }
```

### Cookie yang Diset (Backend)
```
auth_token: session_id
HttpOnly: true
Secure: false (untuk localhost)
Lifetime: 3600 detik (1 jam)
```

---

## Validasi Form

### Field Requirement
```
✓ Nama Lengkap: Required (auto-fill dari login)
✓ Email: Required, email format (auto-fill dari login)
✓ Nomor Telepon: Required
✓ Tanggal Kunjungan: Required, minimal hari ini
✓ Jumlah Tiket: Required, min 1, max 100
```

### Client-side Validation
```javascript
HTML5 attributes:
- required
- type="email"
- type="tel"
- type="date"
- min/max attributes
```

### Server-side Validation
```php
// Di /api/reservasi/create.php
- Check user authenticated
- Validate all fields present
- Validate date format
- Validate phone format
- Check user has sufficient privilege
```

---

## Logout Flow

Ketika customer klik logout:
```
1. Session destroyed di server
2. sessionStorage cleared di client
3. Redirect ke index.html (publik view)
4. Booking section auto-hidden
```

---

## Backward Compatibility

### Booking.html Masih Ada
```
- Masih bisa diakses direct: /booking.html
- Tapi ditampilkan notice: "Gunakan index.html"
- Semua functionality tetap sama
- Untuk backward compatibility
```

---

## Keuntungan User Flow Baru

### Untuk Customer:
```
✅ 1 halaman untuk semua: info + booking
✅ Lebih intuitif: lihat info dulu, baru pesan
✅ Contoh spesies & fasilitas membuat mereka tertarik
✅ Lebih user-friendly
```

### Untuk Admin:
```
✅ Lebih jelas role separation
✅ Customer tidak bisa akses admin features
✅ Auto-redirect berdasarkan role
✅ Lebih aman & terorganisir
```

### Untuk Developer:
```
✅ Single page untuk semua info + booking
✅ Lebih mudah maintain
✅ Reusable components
✅ Cleaner code structure
```

---

## Testing Checklist

- [ ] Admin login → redirect ke /dashboard.html
- [ ] Customer login → redirect ke /index.html
- [ ] Customer lihat info taman di index.html
- [ ] Customer lihat booking form (sebelumnya hidden)
- [ ] Form auto-fill nama & email
- [ ] Harga update saat jumlah tiket berubah
- [ ] Tombol +/- untuk quantity bekerja
- [ ] Submit booking berhasil
- [ ] Modal sukses muncul dengan booking ID
- [ ] Riwayat pemesanan tampil
- [ ] Logout bekerja
- [ ] Publik user (tidak login) tidak lihat booking form
- [ ] Responsive di mobile/tablet/desktop
- [ ] Date picker minimal hari ini
- [ ] Quantity min 1, max 100

---

## Notes

- Default harga: Rp 125.000 per tiket (bisa diubah di UI)
- Booking status default: "pending"
- Session timeout: 1 jam
- Auto-fill fields tidak bisa diedit customer (opsional)
- Booking history menampilkan 3 terakhir
