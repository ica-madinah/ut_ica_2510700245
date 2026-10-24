# UTS Rental Mobil

Aplikasi frontend katalog rental mobil menggunakan **Laravel 13**, **Blade**, **Bootstrap 5.3.8 lokal**, dan **MySQL**. Project mengikuti tema Rental Mobil pada soal UTS Rekayasa Web dan arahan tambahan bahwa seluruh pengelolaan data harus melalui seeder.

## Batas fungsi

Website hanya membaca dan menampilkan data. Fitur yang tersedia adalah beranda, daftar mobil, detail mobil, navigasi, pencarian merek/model, filter merek/transmisi, dan pengurutan harga/tahun.

**Tidak ada panel admin, login, pendaftaran, booking, upload, atau aksi menambah, mengubah, menyimpan, dan menghapus data melalui website.** Semua rute hanya menerima GET dan HEAD. Controller hanya menjalankan query baca. Form pencarian menggunakan GET. Fasilitas route upload penyimpanan lokal Laravel juga dinonaktifkan.

Pengisian dan perubahan data dilakukan melalui `database/seeders/CarSeeder.php`. Seeder adalah sumber tunggal data katalog. Jalankan ulang seeder setelah mengubah datanya; proses ini menyinkronkan tabel `cars`, termasuk menghapus baris yang tidak tercantum lagi dalam seeder.

## Sesuaikan identitas sebelum mengumpulkan

- Ganti nama folder `UTS_NIM` menjadi `UTS_NIMANDA`.
- Gunakan nama database `db_uts_nimanda` pada `.env`.
- Isi `STUDENT_NAME`, `STUDENT_NIM`, dan `STUDENT_CLASS` dalam `.env`.
- Nama repository GitHub mengikuti `UTS_NAMA_NIM_KODEKELAS`.
- Lengkapi identitas dan tautan repository pada `dokumentasi/Dokumentasi-UTS-Rental-Mobil.docx`.

Identitas masih berupa placeholder. Source belum diunggah ke GitHub.

## Kebutuhan

- PHP **8.3 atau lebih baru** dan Composer 2.
- MySQL 8 atau layanan MySQL yang disediakan Laragon/XAMPP.
- Ekstensi PHP Laravel, termasuk Ctype, cURL, DOM, Fileinfo, Filter, Hash, Mbstring, OpenSSL, PCRE, PDO, Session, Tokenizer, XML, dan **pdo_mysql**.
- Browser. Node.js tidak diperlukan untuk menjalankan aplikasi karena Bootstrap dan aset frontend sudah disimpan lokal.

Jika XAMPP/Laragon masih memakai PHP 8.2, pilih/aktifkan PHP 8.3 terlebih dahulu. Periksa versi PHP terminal dengan `php -v`.

## Instalasi

1. Ekstrak ZIP, ganti nama folder sesuai NIM, dan buka terminal di dalam folder project.
2. Jalankan:

```bash
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
```

3. Aktifkan MySQL di Laragon/XAMPP. Buat database kosong bernama `db_uts_nimanda`, misalnya melalui phpMyAdmin.
4. Isi pengaturan `.env` sesuai instalasi MySQL:

```dotenv
APP_NAME="Prima Rent"
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_uts_nimanda
DB_USERNAME=root
DB_PASSWORD=

STUDENT_NAME="Nama Lengkap Anda"
STUDENT_NIM="NIM Anda"
STUDENT_CLASS="Kode Kelas Anda"
```

5. Buat struktur tabel dan isi data melalui migration serta seeder:

```bash
php artisan optimize:clear
php artisan migrate --seed
php artisan serve
```

6. Buka `http://127.0.0.1:8000`. Daftar mobil tersedia di `/mobil`; detail contoh di `/mobil/1`.

`composer setup` juga tersedia setelah database dibuat dan `.env` disesuaikan, tetapi hanya gunakan untuk instalasi pertama karena perintah itu menghasilkan APP_KEY baru. Tidak diperlukan akun admin.

## Skema tabel cars

| Kolom | Tipe migration | Keterangan |
| --- | --- | --- |
| id | bigIncrements | Primary key |
| brand | string 80 | Merek mobil |
| model | string 100 | Model mobil |
| year | unsignedSmallInteger | Tahun kendaraan |
| rent_price | decimal 12,2 | Harga sewa per hari |
| image | string | Nama file dalam public/assets/images |
| transmission | string 20 | Manual atau Automatic |

Tabel `cars` tepat memakai tujuh kolom yang dicantumkan untuk tema Rental Mobil. Tidak ditambahkan kolom `created_at`, `updated_at`, atau `description`; model menonaktifkan timestamps. Deskripsi pada detail dirangkai dari atribut mobil yang berasal dari seeder agar skema tetap sesuai soal. Tabel internal `migrations` dibuat otomatis oleh Laravel untuk mencatat migration.

## Semua data melalui seeder

Delapan contoh mobil: Toyota Avanza, Toyota Innova, Honda Brio, Mitsubishi Xpander, Suzuki Ertiga, Daihatsu Terios, Honda HR-V, dan Toyota Fortuner.

Untuk mengganti harga, tahun, transmisi, nama, atau gambar, edit daftar `$cars` di `database/seeders/CarSeeder.php`. Untuk menambah atau menghapus unit, ubah daftar yang sama. Kemudian jalankan:

```bash
php artisan db:seed --class=CarSeeder
```

Seeder memakai ID tetap dan `upsert` di dalam transaksi. Data yang tidak ada lagi dalam daftar seeder dihapus oleh seeder. Dengan demikian, perubahan data tidak memerlukan form atau endpoint pengelolaan data.

File gambar mobil disimpan di **`public/assets/images/`**, lalu nama filenya diisi ke kolom `image` melalui seeder. Ilustrasi SVG bawaan adalah aset katalog, bukan foto resmi model pabrikan. Untuk memakai foto sendiri, salin JPG/PNG/WebP ke folder yang sama dan ganti nama file pada seeder. Website tidak menyediakan fitur upload.

## Pengaturan profil rental

Pengaturan identitas usaha di `.env`:

```dotenv
RENTAL_NAME="Prima Rent"
RENTAL_DEMO=true
RENTAL_PHONE=
RENTAL_WHATSAPP=
RENTAL_EMAIL=
RENTAL_ADDRESS="Alamat rental belum diatur"
RENTAL_HOURS="Senin–Sabtu, 08.00–18.00 WIB"
```

Nomor WhatsApp, jika diisi, menggunakan angka dengan kode negara tanpa tanda tambah, misalnya 6281234567890. Nomor ini hanya contoh format. Tautan kontak disembunyikan jika konfigurasinya kosong. Hubungan melalui WhatsApp berlangsung di layanan WhatsApp dan tidak menyimpan data melalui website katalog ini.

Jalankan `php artisan optimize:clear` setelah mengubah konfigurasi. Jangan memasukkan `.env` ke repository; `.env.example` sudah disediakan.

## Struktur project

| Lokasi | Fungsi |
| --- | --- |
| routes/web.php | Rute GET daftar dan detail |
| app/Http/Controllers/CarController.php | Query baca, pencarian, filter, pengurutan |
| app/Models/Car.php | Model dan deskripsi dari atribut seeder |
| database/migrations/2026_10_23_000001_create_cars_table.php | Struktur tabel cars |
| database/seeders/CarSeeder.php | Satu-satunya sumber pengelolaan data mobil |
| resources/views/layouts/public.blade.php | Layout Blade bersama |
| resources/views/pages/cars.blade.php | Beranda dan daftar mobil |
| resources/views/pages/detail.blade.php | Detail mobil |
| resources/views/partials/car-card.blade.php | Card Bootstrap |
| public/Bootstrap | CSS dan bundle JavaScript Bootstrap lokal |
| public/assets/images | Gambar yang digunakan seeder |
| public/css/app.css | Penyesuaian tampilan |
| tests/Feature/RentalCatalogTest.php | Pengujian katalog dan penolakan aksi tulis |
| dokumentasi | Draft dokumentasi Word dan screenshot aplikasi |

## Pemeriksaan

```bash
php artisan test
php artisan route:list
```

Pengujian otomatis mencakup skema tujuh kolom, delapan data seeder, keberadaan gambar lokal, layout Bootstrap, halaman daftar dan detail, pencarian, filter, pengurutan, kondisi kosong, escaping input, seeder berulang, dan pembatasan rute GET/HEAD. Permintaan POST/PUT/PATCH/DELETE ditolak dan tidak mengubah tabel mobil. Query halaman publik diperiksa agar tidak menjalankan INSERT/UPDATE/DELETE.

Hasil verifikasi: 16 pengujian dan 115 assertion lolos pada SQLite serta MySQL 8.0.46, menggunakan PHP 8.3.6 dan Laravel 13.38.0.

Pengujian default menggunakan SQLite `:memory:` agar database tugas tidak tersentuh. Ekstensi `pdo_sqlite` diperlukan untuk menjalankan pengujian default, tetapi aplikasi pengumpulan tetap dikonfigurasi menggunakan MySQL.

## Pengumpulan

1. Lengkapi nama, NIM, dan kelas di `.env` serta dokumentasi Word.
2. Pastikan MySQL aktif, migration dan seeder berjalan, serta daftar/detail dapat dibuka.
3. Upload source ke repository GitHub `UTS_NAMA_NIM_KODEKELAS`. Sertakan `composer.lock`, migration, seeder, gambar, dan Bootstrap lokal. Folder `vendor` dan `.env` tidak disertakan.
4. Salin URL repository yang benar ke dokumentasi Word. Screenshot contoh aplikasi telah disertakan; ganti jika identitas/tampilan diubah.
5. Periksa kembali tidak ada fitur pengelolaan data di website sebelum mengumpulkan.

## Sumber aset dan dokumentasi

- Laravel 13: https://laravel.com/docs/13.x/installation dan https://laravel.com/docs/13.x/upgrade . Lisensi framework: MIT.
- Bootstrap 5.3.8: https://getbootstrap.com/docs/5.3/getting-started/download/ . Aset sudah ada di `public/Bootstrap`; lisensi MIT disertakan.
- Foto hero ilustratif: https://images.unsplash.com/photo-1492144534655-ae79c964c9d7 . Lisensi: https://unsplash.com/license . Foto ini tidak mewakili unit rental nyata.
- Ilustrasi SVG unit dibuat sebagai bagian project. Bentuk ilustratif tidak dimaksudkan sebagai foto atau gambar teknis resmi kendaraan.
#   u t _ i c a _ 2 5 1 0 7 0 0 2 4 5  
 