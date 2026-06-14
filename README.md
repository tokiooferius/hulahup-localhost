# Hulahup App 🍱

Aplikasi kantin digital kampus — Laravel 12 + MySQL (XAMPP).

---

## ⚡ Cara Jalanin di Localhost (XAMPP)

### Syarat
- PHP 8.2+
- Composer
- Node.js 20+
- XAMPP (Apache + MySQL aktif)

---

### Langkah-langkah

**1. Buka XAMPP → Start Apache & MySQL**

**2. Buat database di phpMyAdmin**
- Buka `http://localhost/phpmyadmin`
- Klik **New** → nama database: `hulahup_db` → **Create**

**3. Install dependencies**
```bash
composer install
npm install
```

**4. Generate APP_KEY**
```bash
php artisan key:generate
```

**5. Jalankan migrasi database**
```bash
php artisan migrate
```

**6. Build frontend (Tailwind/Vite)**
```bash
npm run build
```

**7. Jalankan server**
```bash
php artisan serve
```

Buka browser → `http://localhost:8000` ✅

---

### Kalau mau dev mode (auto-reload CSS/JS)
Buka 2 terminal:
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

---

## 🔐 Default Config (.env)
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hulahup_db
DB_USERNAME=root
DB_PASSWORD=        ← kosong (default XAMPP)
```

Kalau XAMPP MySQL kamu pakai password, ubah `DB_PASSWORD` di file `.env`.

---

## 👤 Role User
| Role | Akses |
|------|-------|
| `admin` | Dashboard admin, kelola user |
| `ibu_kantin` | Dashboard kantin, kelola menu/voucher |
| `mahasiswa` / `user` | Order, cart, history |

Buat akun admin manual lewat phpMyAdmin: ubah kolom `role` di tabel `users` jadi `admin`.
