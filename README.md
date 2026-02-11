# Geo Field Mapper

**Pemetaan Lahan & Data Geografis** — Aplikasi web untuk mengelola lahan (polygon), mengimpor data koordinat dari file, dan menjalankan survey lapangan dengan lokasi di peta.

---

## Tentang Aplikasi

Geo Field Mapper memungkinkan pengguna untuk:

- **Menggambar dan mengelola area lahan** dalam bentuk polygon di peta, dengan atribut nama, kode, luas, catatan, dan warna poligon.
- **Mengimpor data geografis** dari file **CSV** atau **XLSX**, memetakan kolom latitude/longitude dan atribut, lalu menampilkannya sebagai titik di peta.
- **Membuat dan mengisi survey lapangan** dengan field kustom (teks, angka, pilihan) dan merekam lokasi (titik atau polygon) di peta.

Semua data ditampilkan dalam satu peta interaktif dengan layer yang dapat dihidup-matikan (Lahan, Data terimport, Response survey).

---

## Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| **Lahan (Areas)** | Gambar polygon di peta, pilih warna, simpan batas lahan beserta nama, kode, luas, dan catatan. Daftar area dengan tombol Lihat, Edit, Hapus. |
| **Import Data** | Upload file CSV atau XLSX (maks. 10 MB), pilih kolom lat/lng dan atribut, simpan sebagai titik di layer "Data terimport". |
| **Survey** | Buat definisi survey dengan field kustom; isi survey dengan form dinamis dan pilih lokasi di peta (titik/polygon). |
| **Peta** | Satu peta dengan layer Lahan, Data terimport, dan Response survey; toggle per layer, popup informasi, OpenStreetMap. |

---

## Stack Teknologi

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Blade, Alpine.js, Tailwind CSS
- **Peta:** Leaflet, Leaflet.draw
- **Data:** MySQL; import file dengan Maatwebsite Excel (CSV & XLSX)
- **Auth:** Laravel Breeze (Blade)

---

## Persyaratan

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL (nama database disarankan: `geofieldmap_db`)

---

## Instalasi

1. **Clone atau salin** proyek ke folder (mis. `geo field mapper`).

2. **Buat database MySQL:**
   ```sql
   CREATE DATABASE geofieldmap_db;
   ```

3. **Salin environment:**
   ```bash
   cp .env.example .env
   ```
   Atur di `.env`:
   - `DB_DATABASE=geofieldmap_db`
   - `DB_USERNAME=...`
   - `DB_PASSWORD=...`

4. **Instal dependensi & setup:**
   ```bash
   composer install
   php artisan key:generate
   php artisan migrate
   npm install && npm run build
   ```

5. **(Opsional) Seed user contoh:**
   ```bash
   php artisan db:seed --class=UserSeeder
   ```
   Login contoh: `admin@geofieldmap.local` / `password`

6. **Jalankan server:**
   ```bash
   php artisan serve
   ```
   Atau gunakan Laragon/Apache; akses sesuai konfigurasi (mis. `http://geofieldmap.test`).

---

## Penggunaan Singkat

1. **Daftar / Login** dari halaman muka atau menu.
2. **Dashboard** — Pintasan ke Peta, Lahan, Import Data, Survey.
3. **Peta** — Lihat semua layer; toggle Lahan, Data terimport, Response survey.
4. **Lahan** — Klik "Tambah Area", gambar polygon di peta, isi form (nama, kode, luas, warna, catatan), simpan.
5. **Import Data** — Upload file CSV atau XLSX, pilih kolom lat/lng dan atribut, simpan; titik muncul di peta.
6. **Survey** — Buat survey (nama + field), lalu isi survey (form + pilih lokasi di peta).

Panduan lengkap ada di **[PETUNJUK-PENGGUNAAN.md](PETUNJUK-PENGGUNAAN.md)**.

---

## Profil Developer

| | |
|--|--|
| **Nama** | *Kusuma Dewangga* |
| **Email** | *kdewangga85@gmail.com* |
| **GitHub** | *https://github.com/sukasapi* |
| **Role** | *[Full Stack Developer, SAP Functional FICO, IT Governance, IT Consultant]* |


---

## Lisensi

Proyek ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).
