# Simple Landing Page

## System Requirements

 - PHP 8.1
 - Composer

Jika ingin menggunakan docker untuk menjalankan project ini maka arsitektur prosesor harus menggunakan amd64 atau arm64.

## Menjalankan Project Tanpa Docker
Jika requirment diatas telah terpenuhi makan dapat melanjutkan ke bagian [Menjalankan project](#menjalankan-project).

## Menjalankan Project dengan docker

### Build image untuk laravel dan nginx
Langkah ini hanya dilakukan sekali, jika anda sudah memiliki image nya, anda tidak perlu melakukannya lagi. jalankan perintah berikut:
- `docker compose build`

### Kendala yang mungkin terjadi jika menggunakan docker
Ada kemungkinan folder storage di laravel tidak dapat diakses karena permasalahan permission, untuk hal tersebut silahkan ubah permission folder tersebut dengan mengikut langkah berikut:
 - masuk terlebih dahulu ke container: `docker exec -it landing-php bash`
 - ubah permission folder storage : `php chmod -R 775 storage`
 - apabila diperlukan coba ubah permission untuk folder bootstrap juga: `chmod -R 775 bootstrap/cache`


## Menjalankan Project
Setup awal:
- lakukan instalasi terlebih dahulu dengan menjalankan commad: `composer install`
- copy file `.env.example` menjadi `.env` sesuaikan dengan configurasi database anda. Apabila menggunakan docker maka tidak perlu untuk mengubah configurasi file `.env`
- generate key laravel: `php artisan key:generate`

Migration dan generate dummy data:
- jalankan perintah migration sekaligus seeding: `php artisan migrate:fresh --seed` 

## Dokumentasi API
Untuk dokumentasi API nya dapat dilihat pada [Dokumentasi API Postman](https://documenter.getpostman.com/view/11575131/2s8YY9wSr6)
