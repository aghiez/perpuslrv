# Perpustakaan SMKN 2 Pekalongan — Laravel Handoff

Paket ini berisi semua file yang dibutuhkan untuk mengimplementasikan
desain prototype ke dalam aplikasi Laravel.

---

## 📁 Struktur Folder

```
laravel-handoff/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php         ← Login, Register, Logout
│   │   ├── BukuController.php         ← Katalog, Detail, Ulasan + API
│   │   ├── PeminjamanController.php   ← Pinjam, Kembalikan + API
│   │   └── BeritaController.php       ← Berita + API
│   └── Models/
│       ├── Buku.php                   ← Model buku + scopes
│       ├── Anggota.php                ← Model user (extends Authenticatable)
│       └── Peminjaman.php             ← Model peminjaman + hitung denda
│
├── database/migrations/
│   ├── ..._create_bukus_table.php
│   ├── ..._create_anggota_table.php
│   ├── ..._create_peminjaman_table.php
│   └── ..._create_ulasan_berita_table.php
│
├── routes/
│   ├── web.php                        ← Semua route web
│   └── api.php                        ← API endpoints (opsional)
│
└── resources/views/
    ├── layouts/
    │   └── perpustakaan.blade.php     ← Layout utama (sidebar + topbar)
    └── perpustakaan/
        ├── home.blade.php
        ├── katalog.blade.php
        ├── detail.blade.php
        ├── login.blade.php
        ├── peminjaman.blade.php
        └── partials/
            └── book-card.blade.php    ← Reusable card component
```

---

## 🚀 Langkah Instalasi

### 1. Buat project Laravel baru

```bash
composer create-project laravel/laravel perpustakaan-smkn2
cd perpustakaan-smkn2
```

### 2. Konfigurasi database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan_smkn2
DB_USERNAME=root
DB_PASSWORD=
```

Buat database di MySQL:
```sql
CREATE DATABASE perpustakaan_smkn2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Salin file dari handoff ini

Salin semua file ke folder Laravel:

```bash
# Models
cp laravel-handoff/app/Models/*.php app/Models/

# Controllers
cp laravel-handoff/app/Http/Controllers/*.php app/Http/Controllers/

# Migrations
cp laravel-handoff/database/migrations/*.php database/migrations/

# Routes
cp laravel-handoff/routes/web.php routes/web.php
cp laravel-handoff/routes/api.php routes/api.php

# Views
cp -r laravel-handoff/resources/views/* resources/views/
```

### 4. Daftarkan model Anggota sebagai Auth provider

Edit `config/auth.php`:

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model'  => App\Models\Anggota::class,  // ← ganti dari User::class
    ],
],
```

### 5. Salin CSS dari prototype

Ambil seluruh blok `<style>` dari file `Perpustakaan SMKN 2 Pekalongan.html`
dan simpan ke:

```
public/css/perpustakaan.css
```

### 6. Jalankan migration dan seeder

```bash
php artisan migrate

# Buat seeder data contoh (opsional)
php artisan make:seeder BukuSeeder
php artisan db:seed
```

### 7. Tambahkan route dark mode toggle

Di `routes/web.php`, tambahkan:

```php
Route::post('/toggle-dark', function () {
    session(['dark_mode' => !session('dark_mode', false)]);
    return back();
})->name('toggle.dark');
```

### 8. Install Laravel Sanctum (untuk API)

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 9. Jalankan server

```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## 🗄️ Schema Database

### Tabel `bukus`
| Kolom           | Tipe         | Keterangan                  |
|-----------------|--------------|-----------------------------|
| id              | bigint PK    |                             |
| judul           | varchar      | Judul buku                  |
| pengarang       | varchar      | Nama pengarang              |
| kategori        | varchar      | Fiksi, Sains, dll           |
| penerbit        | varchar      |                             |
| tahun_terbit    | year         |                             |
| isbn            | varchar      | Unique                      |
| jumlah_halaman  | integer      |                             |
| deskripsi       | text         |                             |
| cover_color     | varchar      | CSS color (oklch/hex)       |
| stok            | integer      | Total stok                  |
| stok_tersedia   | integer      | Stok yang bisa dipinjam     |
| rating          | decimal(2,1) | Rata-rata rating            |
| rating_count    | integer      | Jumlah ulasan               |
| lokasi_rak      | varchar      | Misal: "Rak A-3"            |

### Tabel `anggota`
| Kolom    | Tipe    | Keterangan                        |
|----------|---------|-----------------------------------|
| nis      | varchar | NIS siswa (unique)                |
| nip      | varchar | NIP guru/staff (nullable, unique) |
| nama     | varchar |                                   |
| email    | varchar | unique                            |
| role     | enum    | siswa/guru/staff/admin            |
| kelas    | varchar | X RPL 1, XI TKJ 2, dst           |

### Tabel `peminjaman`
| Kolom                | Tipe    | Keterangan                   |
|----------------------|---------|------------------------------|
| kode_peminjaman      | varchar | PJM-2026-001                 |
| anggota_id           | FK      |                              |
| buku_id              | FK      |                              |
| tanggal_pinjam       | date    |                              |
| tanggal_jatuh_tempo  | date    | +14 hari dari tgl pinjam     |
| tanggal_kembali      | date    | nullable                     |
| status               | enum    | dipinjam/terlambat/dikembalikan |
| denda                | integer | Rp 500/hari keterlambatan    |

---

## 🔌 API Endpoints

Jika menggunakan frontend JS terpisah (Vue/React/Next.js):

```
GET  /api/v1/buku               ← Daftar buku (filter, sort, pagination)
GET  /api/v1/buku/{id}          ← Detail buku + ulasan
GET  /api/v1/berita             ← Daftar berita

# Butuh auth token (Sanctum)
GET  /api/v1/peminjaman         ← Peminjaman milik user login
POST /api/v1/peminjaman         ← Buat peminjaman baru
```

Contoh fetch dari JavaScript:
```javascript
// Ambil daftar buku
const res = await fetch('/api/v1/buku?kategori=Fiksi&sort=rating');
const { data } = await res.json();

// Pinjam buku (butuh CSRF token)
await fetch('/api/v1/peminjaman', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({ buku_id: 5 }),
});
```

---

## 📋 Fitur yang Sudah Disiapkan

- ✅ Autentikasi dengan NIS/NIP
- ✅ Katalog buku dengan filter, sort, dan pencarian
- ✅ Detail buku dengan ulasan dan rating
- ✅ Sistem peminjaman (maks. 3 buku)
- ✅ Hitung denda otomatis (Rp 500/hari)
- ✅ Update status terlambat otomatis
- ✅ Berita & pengumuman
- ✅ Dark mode (session-based)
- ✅ Responsive sidebar (collapsible)
- ✅ API endpoints untuk SPA/fetch

## 🔧 Fitur yang Perlu Dikembangkan

- [ ] Admin panel (CRUD buku, manajemen anggota)
- [ ] Notifikasi email jatuh tempo
- [ ] Barcode/QR scanner untuk scan buku
- [ ] Export laporan PDF (peminjaman, denda)
- [ ] Sistem antrian buku yang sedang dipinjam
- [ ] Upload foto cover buku

---

## 📞 Kontak Pengembangan

Perpustakaan SMKN 2 Pekalongan  
Jl. Perintis Kemerdekaan No.1, Pekalongan, Jawa Tengah  
Email: perpustakaan@smkn2pkl.sch.id
