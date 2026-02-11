# Petunjuk Penggunaan Geo Field Mapper

Dokumen ini menjelaskan cara menggunakan setiap fitur aplikasi Geo Field Mapper.

---

## Daftar Isi

1. [Halaman Muka](#1-halaman-muka)
2. [Daftar & Masuk](#2-daftar--masuk)
3. [Dashboard](#3-dashboard)
4. [Peta](#4-peta)
5. [Lahan (Areas)](#5-lahan-areas)
6. [Import Data](#6-import-data)
7. [Survey](#7-survey)
8. [Profil](#8-profil)

---

## 1. Halaman Muka

**URL:** `/` (halaman pertama saat aplikasi dibuka)

**Deskripsi:** Halaman pengenalan aplikasi yang tampil sebelum login.

**Cara menggunakan:**

- **Belum login:** Anda akan melihat judul "Pemetaan Lahan & Data Geografis", deskripsi singkat, dan tiga kartu fitur (Lahan, Import, Survey). Gunakan tombol **Masuk** untuk login atau **Daftar Akun** untuk membuat akun baru.
- **Sudah login:** Tombol **Buka Dashboard** akan mengarahkan ke dashboard. Atau gunakan menu **Dashboard** di header.
- **Header:** Di kanan atas selalu tersedia link Masuk/Daftar (jika belum login) atau Dashboard (jika sudah login).

---

## 2. Daftar & Masuk

### Daftar Akun Baru

**URL:** `/register`

**Langkah:**

1. Klik **Daftar** dari halaman muka atau header.
2. Isi **Nama** (nama tampilan).
3. Isi **Email** (untuk login).
4. Isi **Password** (minimal 8 karakter) dan **Konfirmasi Password**.
5. Klik **Register**.
6. Setelah berhasil, Anda akan diarahkan ke dashboard.

### Masuk (Login)

**URL:** `/login`

**Langkah:**

1. Klik **Masuk** dari halaman muka atau header.
2. Masukkan **Email** dan **Password**.
3. Centang **Remember me** jika ingin tetap login di perangkat ini.
4. Klik **Log in**.
5. Setelah berhasil, Anda akan diarahkan ke dashboard.

**Akun contoh (setelah menjalankan seed):**

| Email | Password |
|-------|----------|
| admin@geofieldmap.local | password |
| budi@geofieldmap.local | password |
| siti@geofieldmap.local | password |
| ahmad@geofieldmap.local | password |

---

## 3. Dashboard

**URL:** `/dashboard`

**Deskripsi:** Halaman utama setelah login, berisi pintasan ke semua fitur.

**Cara menggunakan:**

- Empat kartu menuju:
  - **Peta** — Lihat peta dengan semua layer (lahan, data terimport, response survey).
  - **Lahan** — Kelola area/lahan (gambar polygon, atribut).
  - **Import Data** — Upload file CSV atau XLSX dan petakan kolom ke koordinat.
  - **Survey** — Buat dan isi survey lapangan dengan lokasi.
- Klik salah satu kartu untuk masuk ke fitur tersebut.
- Menu navigasi di atas (Peta, Lahan, Import Data, Survey) juga bisa digunakan dari halaman mana pun setelah login.

---

## 4. Peta

**URL:** `/map`

**Deskripsi:** Satu peta yang menampilkan semua data geografis dalam bentuk layer yang bisa dihidup-matikan.

**Cara menggunakan:**

1. **Melihat peta:** Peta OpenStreetMap tampil dengan zoom default. Gunakan scroll/touch atau tombol +/- untuk zoom, drag untuk menggeser.
2. **Layer:**
   - **Lahan (Area):** Polygon batas lahan yang sudah Anda buat di fitur Lahan. Klik polygon untuk melihat popup (nama, kode, luas).
   - **Data terimport:** Titik-titik dari hasil Import Data. Klik titik untuk melihat popup atribut.
   - **Response survey:** Titik (atau polygon) lokasi response survey. Klik untuk melihat isi jawaban.
3. **Toggle layer:** Di bawah peta ada tiga checkbox. Centang atau hapus centang untuk menampilkan atau menyembunyikan masing-masing layer (Lahan, Data terimport, Response survey).

**Catatan:** Data yang tampil hanya milik akun Anda (per user).

---

## 5. Lahan (Areas)

**URL:** `/areas`

**Deskripsi:** Mengelola area/lahan dalam bentuk polygon di peta, beserta atribut (nama, kode, luas, catatan).

### 5.1 Melihat Daftar Lahan

- Di kanan peta (atau di bawah pada layar kecil) tampil **Daftar Area**.
- Setiap item menampilkan nama, kode (jika ada), dan luas (jika ada).
- Dari daftar Anda bisa **Edit** atau **Hapus** area.

### 5.2 Menambah Area Baru (Gambar Polygon)

1. Klik tombol **Tambah Area (Gambar Polygon)** di header, atau gunakan ikon polygon di toolbar peta (jika tampil).
2. Di peta, klik titik-titik untuk membentuk polygon. Titik terakhir harus dihubungkan ke titik pertama (double-klik atau klik ikon centang untuk menyelesaikan).
3. Setelah polygon selesai, **modal form** akan muncul.
4. Isi:
   - **Nama** (wajib)
   - **Kode** (opsional)
   - **Luas (ha)** (opsional, angka)
   - **Catatan** (opsional)
5. Klik **Simpan**. Area akan tersimpan dan muncul di daftar serta di layer Lahan di peta.

### 5.3 Mengedit Area

- Dari **Daftar Area:** Klik **Edit** pada item yang ingin diubah.
- Dari **peta:** Klik polygon lalu klik link **Edit** di dalam popup.
- Di modal edit, ubah Nama, Kode, Luas, atau Catatan. Boundary (polygon) bisa diubah jika Anda mengisi ulang dari peta (biasanya lewat flow edit yang menyertakan peta).
- Klik **Simpan** untuk menyimpan perubahan.

### 5.4 Menghapus Area

- Dari daftar: Klik **Hapus** pada item, lalu konfirmasi di dialog.
- Area akan hilang dari daftar dan dari layer Lahan di peta.

---

## 6. Import Data

**URL:** `/imports`

**Deskripsi:** Mengunggah file CSV atau XLSX yang berisi koordinat (latitude, longitude), lalu memilih kolom mana yang dipetakan. Hasilnya ditampilkan sebagai titik di layer "Data terimport" di peta.

### 6.1 Upload File

1. Buka **Import Data** dari menu atau dashboard.
2. Pilih file dengan format **CSV** atau **XLSX** (maks. 10 MB). Hanya format ini yang diterima sistem.
3. Klik **Upload & Pilih Kolom**.
4. Jika file valid, Anda akan diarahkan ke halaman **Pilih Kolom untuk Koordinat**.

### 6.2 Memilih Kolom (Mapping)

1. **Nama mapping:** Isi nama untuk import ini (mis. "Lokasi Kantor", "Titik Sample") — wajib.
2. **Kolom Latitude:** Pilih dari dropdown kolom yang berisi nilai latitude (angka).
3. **Kolom Longitude:** Pilih dari dropdown kolom yang berisi nilai longitude (angka).
4. **Kolom atribut:** Centang kolom-kolom yang ingin ditampilkan di popup peta (nama, deskripsi, dll).
5. Lihat **Preview data** (10 baris pertama) untuk memastikan pemilihan kolom benar.
6. Klik **Simpan & Tampilkan di Peta**.

**Catatan:**

- Setiap baris harus memiliki nilai lat dan lng yang valid (angka). Baris yang kosong atau tidak valid akan dilewati.
- Setelah berhasil, Anda akan diarahkan ke Peta; titik-titik akan tampil di layer "Data terimport".

### 6.3 Melihat Data Terimport di Peta

- Buka **Peta** (`/map`). Pastikan checkbox **Data terimport** dicentang.
- Klik salah satu titik untuk melihat popup berisi atribut yang Anda pilih saat mapping.

---

## 7. Survey

**URL:** `/surveys` (daftar survey)

**Deskripsi:** Membuat definisi survey (nama + field custom: teks, angka, pilihan, teks panjang), lalu mengisi survey dengan jawaban dan lokasi (titik di peta). Response bisa dilihat per survey dan ditampilkan di peta.

### 7.1 Daftar Survey

- Buka **Survey** dari menu atau dashboard.
- Tampil daftar survey yang Anda buat: nama, slug, dan jumlah response.
- Dari setiap baris Anda bisa: **Lihat**, **Edit**, **Isi Survey**, **Hapus**.

### 7.2 Membuat Survey Baru

1. Klik **Buat Survey**.
2. Isi **Nama Survey** (wajib).
3. **Field (pertanyaan):**
   - Klik **+ Tambah field** untuk menambah pertanyaan.
   - Setiap field punya:
     - **Label** (teks yang dilihat responden)
     - **Tipe:** Teks, Angka, Teks panjang, atau Pilihan
     - **Wajib diisi:** centang jika harus diisi
     - **Opsi:** untuk tipe Pilihan, isi satu opsi per baris di textarea
   - Gunakan **Hapus field** untuk menghapus field yang tidak dipakai.
4. Klik **Simpan Survey**.

Survey akan muncul di daftar survey.

### 7.3 Mengedit Survey

1. Di daftar survey, klik **Edit** pada survey yang ingin diubah.
2. Ubah **Nama Survey** dan/atau field (tambah, hapus, ubah label/tipe/opsi).
3. Klik **Simpan Perubahan**.

### 7.4 Mengisi Survey (Response)

1. Di daftar survey, klik **Isi Survey** (atau dari detail survey klik **Isi Survey**).
2. Isi semua pertanyaan sesuai tipe (teks, angka, pilihan, teks panjang).
3. **Lokasi:**
   - Klik tombol **Klik peta untuk pilih lokasi**, lalu klik satu titik di peta. Koordinat akan tercatat.
   - Lokasi opsional; jika tidak dipilih, response tetap bisa disimpan (konfirmasi jika lokasi kosong).
4. Klik **Simpan Response**.
5. Anda akan diarahkan ke halaman detail survey; response baru tampil di daftar response.

### 7.5 Melihat Response Survey

- **Per survey:** Klik **Lihat** pada survey. Di bawah definisi field, tampil daftar **Response** dengan tanggal, lokasi (jika ada), dan jawaban.
- **Di peta:** Buka **Peta** (`/map`). Pastikan checkbox **Response survey** dicentang. Titik (dan polygon jika ada) response akan tampil; klik untuk melihat isi jawaban di popup.

### 7.6 Menghapus Survey

- Di daftar survey, klik **Hapus** pada survey yang ingin dihapus, lalu konfirmasi. Semua response survey tersebut juga akan terhapus.

---

## 8. Profil

**URL:** `/profile` (setelah login)

**Deskripsi:** Mengubah informasi akun (nama, email) dan password.

**Cara menggunakan:**

1. Klik nama Anda di kanan atas, lalu **Profile** (atau akses langsung `/profile`).
2. **Update Profil:** Ubah **Nama** dan/atau **Email**, lalu klik **Save**.
3. **Update Password:** Isi **Current Password**, **New Password**, dan **Confirm Password**, lalu klik **Save**.
4. **Hapus Akun:** Jika tersedia, ikuti petunjuk di halaman untuk menghapus akun (biasanya dengan konfirmasi password).

---

## Ringkasan URL Penting

| Fitur | URL |
|-------|-----|
| Halaman muka | `/` |
| Login | `/login` |
| Daftar | `/register` |
| Dashboard | `/dashboard` |
| Peta | `/map` |
| Lahan | `/areas` |
| Import Data | `/imports` |
| Survey (daftar) | `/surveys` |
| Buat survey | `/surveys/create` |
| Isi survey | `/surveys/{id}/fill` |
| Profil | `/profile` |

---

*Dokumen ini mengacu pada versi aplikasi Geo Field Mapper dengan fitur: Lahan (polygon + atribut), Import Data (CSV/XLSX → peta), dan Survey (form dinamis + lokasi).*
