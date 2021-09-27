## Cara Instalasi

1. `cd skripsi-sares`
2. `composer install`
3. `composer require laravel/ui`

## Konfigurasi Aplikasi

1. Duplikasi file `.env.example` menjadi `.env`
2. Buat sebuah database, misalnya 'db_sares'
3. Buat user yang dapat mengakses database 'db_sares'
4. Ubah isi file `.env` sesuai dengan konfigurasi yang dibutuhkan, terutama koneksi ke database
5. jalankan `php artisan key:generate`

## Cara Menjalankan

1. `php artisan serve`
