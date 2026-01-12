
## 💻 Panduan Instalasi (Local Development)

Ikuti langkah-langkah berikut agar project dapat berjalan di komputer Anda.

### Prasyarat
* PHP >= 8.1
* Composer
* MySQL
* Node.js & NPM

### Langkah-langkah Instalasi

1.  **Clone Repository**
    Download source code project ini:
    ```bash
    git clone https://github.com/Mohmedalfin/project_payroll_joki.git
    cd nama-repo-anda
    ```

2.  **Install Dependencies (Backend)**
    Install library PHP yang dibutuhkan menggunakan Composer:
    ```bash
    composer install
    ```

3.  **Setup Environment File**
    Duplikat file konfigurasi `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```

4.  **Konfigurasi Database & API**
    Buka file `.env` di text editor, lalu atur koneksi database dan API WA:
    
    * **Database:** (Buat database kosong terlebih dahulu di phpMyAdmin)
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=nama_database_baru(bebas namanya)
        DB_USERNAME=root
        DB_PASSWORD=
        ```

5.  **Generate Application Key**
    Buat key enkripsi keamanan untuk Laravel:
    ```bash
    php artisan key:generate
    ```

6.  **Migrasi Database & Seeder**
    Jalankan perintah ini untuk membuat tabel dan mengisi data akun Admin default:
    ```bash
    php artisan db:seed --class=JabatanSeeder
    php artisan db:seed --class=KaryawanSeeder
    php artisan db:seed --class=PenggunaSeed
    ```

7.  **Setup Storage Link**
    **PENTING:** Jalankan ini agar foto bukti transaksi bisa diakses publik:
    ```bash
    php artisan storage:link
    ```

8.  **Install & Compile Dependencies (Frontend)**
    Install package Tailwind/JS dan compile asset:
    ```bash
    npm install && npm run build
    ```

9.  **Jalankan Server**
    Nyalakan server lokal Laravel:
    ```bash
    php artisan serve
    ```

10. **Selesai!**
    Buka browser dan akses: `http://localhost:8000`

---

## 🔐 Akun Default (Testing)

Gunakan akun ini untuk login pertama kali setelah menjalankan seeder:

| Role | Username / Email | Password |
| :--- | :--- | :--- |
| **Admin** | `Tachwin` | `pass123` |

---
