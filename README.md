# 🌿 Sistem Manajemen Obat Herbal & Distribusi Geospasial (GIS)
### Proyek Intermediate 3 — Kelompok 3

Aplikasi web terintegrasi berbasis **Laravel 11** dan **MySQL** untuk manajemen produk obat herbal, transaksi kasir, inventaris gudang, serta pemantauan logistik pengiriman paket berbasis sistem informasi geografis (**GIS Leaflet**).

---

## 👥 Anggota Kelompok 3

| No | Nama Anggota | Peran / Kontribusi |
|:---:|:---|:---|
| 1 | **Sakhi Ardra Handaru** | Backend Integration, Konfigurasi Environment, Autentikasi & Devops github  |
| 2 | **Nur Laeliyah** | Desain Antarmuka (UI/UX) Apotek Herbal & database|
| 3 | **Junanti** |Database Architecture, Pengujian Sistem & QA |
| 4 | **M. Farrirudin Attar** | Sistem Geospasial (GIS), Routing, & Endpoint Pemetaan |

---

## 🚀 Fitur Utama Sistem

### 1. 🔐 Autentikasi Pengguna & Hak Akses
- Halaman **Login** dan **Registrasi** dengan tema visual **Deep Forest Green** yang elegan dan selaras dengan tema obat herbal.
- Proteksi route berbasis sesi (`auth` & `guest`).
- Manajemen peran pengguna: `admin`, `kurir`, dan `user`.

### 2. 🌿 Manajemen Produk Obat Herbal (CRUD)
- Katalog produk obat herbal alami (Minyak Habbatussauda, Madu Randu Hutan, Kapsul Temulawak, Sari Kurma, Pegagan, dsb).
- Peringatan stok menipis (*low stock indicator*) dan pemantauan masa kedaluwarsa produk (*expiration tracker*).
- Penghitungan valuasi inventaris otomatis berdasarkan harga beli riil.

### 3. 💳 Transaksi Kasir & Penjualan Herbal
- Pencatatan transaksi obat secara multi-item dengan kalkulasi subtotal dan total otomatis.
- Pengurangan stok barang secara otomatis dan aman dalam transaksi database.

### 4. 📦 Manajemen Pengiriman Paket
- CRUD lengkap untuk data ekspedisi dan paket pengiriman obat herbal ke pelanggan.
- Dilengkapi **Mini Map Picker** pada form tambah & ubah paket: cukup geser pin penanda di peta untuk mengisi koordinat **Latitude & Longitude** secara otomatis.
- Penugasan armada kurir untuk masing-masing paket.

### 5. 🗺️ Pemantauan Geospasial (GIS Leaflet)
- **Pusat Usaha**: Titik gudang operasional utama berpusat di **Indramayu, Jawa Barat** (koordinat `-6.3263889, 108.3200000`).
- **Titik Pembeli (Marker Biru)**: Sebaran titik tujuan pengiriman di berbagai kota di Indonesia.
- **Pengukuran Jarak Otomatis**: Ketika titik pembeli diklik, sistem otomatis **menarik garis lurus (polyline)** dari Indramayu ke alamat penerima dan menampilkan kalkulasi jarak dalam satuan kilometer (**km**) menggunakan algoritma *Haversine*.
- **Live Coordinate Inspector**: Klik pada area peta mana pun untuk mendapatkan angka Latitude & Longitude secara *real-time*.

---

## 🛠️ Spesifikasi Teknologi

- **Framework**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 8.0 (XAMPP Windows)
- **Database Name**: `kel3`
- **Peta Digital**: Leaflet.js 1.9.4 & OpenStreetMap Tiles
- **Tipografi**: Plus Jakarta Sans, DM Sans, & Manrope
- **Pengujian**: PHPUnit / Laravel Feature & Unit Tests

---

## ⚙️ Panduan Instalasi & Menjalankan Proyek

### 1. Kloning Repository
```bash
git clone https://github.com/skirdrax/manusia-pilihan-tuhan.git
cd manusia-pilihan-tuhan
git checkout liyah
```

### 2. Konfigurasi Environment
Pastikan file `.env` sudah sesuai dengan database MySQL Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kel3
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi & Seeding Data Riil
Jalankan migrasi dan seeder untuk mengisi seluruh data awal (user, kurir, paket berkoordinat, obat herbal, dan region):
```bash
php artisan migrate:fresh --seed
```

### 4. Menjalankan Server Lokal
```bash
php artisan serve
```
Buka browser pada: `http://127.0.0.1:8000` (atau lewat Apache XAMPP di `http://localhost/kel-3-intermediate3/public`).

---

## 🔑 Kredensial Akun Pengujian

| Peran (Role) | Email | Kata Sandi |
|:---|:---|:---|
| **Admin** | `admin@kel3.test` | `password` |
| **Kurir 1** | `kurir1@kel3.test` | `password` |
| **Kurir 2** | `kurir2@kel3.test` | `password` |
| **User Pelanggan** | `user@kel3.test` | `password` |

---

## 🧪 Menjalankan Automated Test
```bash
php artisan test
```
*Seluruh pengujian unit dan alur kerja apotek dipastikan lulus (100% PASS).*

---
&copy; 2026 **Kelompok 3** — Rekayasa Perangkat Lunak / Web Programming.
