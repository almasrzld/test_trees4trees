# TEST FULL-STACK DEVELOPER – TREES4TREES

## Platform Merge Database Lahan GEKO & BHL

Repository ini berisi implementasi **platform web** untuk melakukan **merge database lahan** antara **GEKO (main database)** dan **BHL (legacy database)** sesuai dengan ketentuan pada soal test Full-Stack Developer Trees4Trees.

---

## Tujuan

Membangun aplikasi web untuk:

- Merge database BHL ke database utama GEKO
- Menyesuaikan struktur data BHL agar mengikuti struktur GEKO
- Mengidentifikasi data duplikat dan data baru
- Menyediakan fitur filtering dan statistik hasil merge

---

## Sumber Data

Terdapat 2 sumber database dalam bentuk CSV:

1. **GEKO (Main Database)**
   Database lahan utama yang digunakan oleh Trees4Trees.
2. **BHL (Legacy Database)**
   Database lama yang perlu dimigrasikan ke struktur GEKO.

---

## Ketentuan & Implementasi

### 1. Platform Web

- Dibangun menggunakan **Laravel**
- Proses merge dilakukan di backend (PHP + MySQL)
- **Tidak menggunakan Excel** untuk merge data

---

### 2. Penyesuaian Struktur Database

- Struktur database BHL disesuaikan dengan struktur GEKO
- Kolom yang tidak tersedia pada data BHL akan diisi dengan `NULL`
- Data hasil merge disimpan pada tabel utama `lahans`

---

### 3. Aturan Merge Data

- Database GEKO menjadi acuan utama
- Identifikasi duplikasi berdasarkan **`lahan_no`**

| Kondisi Data               | Perlakuan                  |
| -------------------------- | -------------------------- |
| Data BHL belum ada di GEKO | Disimpan sebagai data baru |
| Data BHL sudah ada di GEKO | Ditandai sebagai duplicate |
| Data GEKO                  | Selalu dipertahankan       |

---

### 4. Identifikasi Data

Setiap data dapat diidentifikasi berdasarkan:

- `source_data` → GEKO / BHL
- `is_duplicate` → true / false

---

## Desain Database

### Tabel `lahans` (Data Final / Hasil Merge)

| Kolom        | Keterangan               |
| ------------ | ------------------------ |
| lahan_no     | Nomor lahan              |
| farmer_no    | Nomor petani             |
| village      | Desa                     |
| kecamatan    | Kecamatan                |
| city         | Kota / Kabupaten         |
| province     | Provinsi                 |
| source_data  | Sumber data (GEKO / BHL) |
| is_duplicate | Status duplikasi         |
| created_at   | Timestamp                |
| updated_at   | Timestamp                |

---

### Tabel `lahan_raw_data` (Data Mentah / Audit)

Digunakan untuk menyimpan data mentah dari masing-masing sumber.

| Kolom        | Keterangan         |
| ------------ | ------------------ |
| lahan_no     | Nomor lahan        |
| source_data  | GEKO / BHL         |
| is_duplicate | Status duplikasi   |
| raw_payload  | Data mentah (JSON) |

---

## Identifikasi Duplikat

Duplikasi ditentukan jika:

```
lahan_no dari BHL sudah ada di database GEKO
```

Contoh query identifikasi duplikat:

```sql
SELECT lahan_no
FROM lahan_raw_data
GROUP BY lahan_no
HAVING COUNT(*) > 1;
```

---

## Fitur Filtering & Statistik

### Filtering Data

Filter data berdasarkan:

- Desa
- Kecamatan
- Kota / Kabupaten

---

### Statistik (Hasil Merge)

Menampilkan informasi:

- Total lahan
- Total petani (unik)
- Total lahan dari GEKO
- Total lahan dari BHL

---

## Tampilan UI

- Menggunakan **Tailwind CSS**

- Penanda warna data:
    - GEKO → Hijau
    - BHL → Biru
    - Duplicate → Merah

- Mendukung pagination untuk data besar

---

## Cara Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi secara lokal:

1. Clone repository

    ```bash
    git clone https://github.com/almasrzld/test_trees4trees
    ```

2. Masuk ke folder project

    ```bash
    cd test_trees4trees
    ```

3. Install dependency PHP

    ```bash
    composer install
    ```

4. Install dependency frontend

    ```bash
    npm install
    ```

5. Salin file environment

    ```bash
    cp .env.example .env
    ```

6. Generate application key

    ```bash
    php artisan key:generate
    ```

7. Atur koneksi database pada file `.env`, lalu jalankan migrasi

    ```bash
    php artisan migrate
    ```

8. Tambahkan file data CSV ke folder berikut:

    ```
    storage/app/data/
    ```

    - `LahanGEKO.csv`
    - `LahanBHL.csv`

9. Import data GEKO

    ```bash
    php artisan import:lahan GEKO
    ```

10. Import data BHL

    ```bash
    php artisan import:lahan BHL
    ```

11. Build asset frontend

    ```bash
    npm run build
    ```

12. Jalankan server aplikasi

    ```bash
    php artisan serve
    ```

Aplikasi dapat diakses melalui:

```
http://127.0.0.1:8000
```

---

## Teknologi

- Laravel
- PHP
- MySQL
- Blade
- Tailwind CSS
- Eloquent ORM

---

## Kesimpulan

Seluruh kebutuhan pada soal test telah terpenuhi:

- Merge database tanpa Excel
- Struktur mengikuti GEKO
- Identifikasi data duplikat dan data baru
- Filtering lokasi
- Statistik total lahan dan petani

---

## Kandidat

**Almas Rizaldi**
Full Stack Developer Candidate – Trees4Trees
