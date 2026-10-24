#  Bank Sampah RT — Web Admin & Mobile App System

Sistem Manajemen Bank Sampah tingkat RT (Rukun Tetangga) dirancang untuk mempermudah warga dalam menyetor sampah dan membantu pengelola (Pak RT/Admin) dalam merekapitulasi data setoran, melakukan verifikasi, menimbang bobot, serta mengonversi sampah menjadi poin dan saldo rupiah secara terintegrasi.

---

## 🛠️ Tech Stack & Arsitektur

* **Web Admin (Dashboard Pengelola):**
  * **Framework:** Laravel 8 (PHP 7.4 / 8.0+)
  * **Database:** MySQL
  * **Frontend & UI:** Bootstrap 5, Glassmorphism CSS, FontAwesome 6
  * **Data Visualization:** Chart.js (Donut Progress & Daily Analytics Bar Chart)
* **Mobile App (Aplikasi Warga):**
  * **Engine:** MIT App Inventor (Native WebViewer Integration)
  * **Image Processing:** Auto-Compression via PHP GD Library (Resized max 1000px, 70% Quality)

---

## ✨ Fitur Utama

### 👨‍💼 1. Web Dashboard Admin (Pak RT)
* **Ringkasan Progress Real-time:** Visualisasi persentase pengumpulan sampah Organik vs Non-Organik dengan Chart Donut interaktif.
* **Project Analytics Harian:** Grafik batang analisis frekuensi setoran warga per hari untuk mengetahui tingkat keaktifan warga.
* **Sistem Verifikasi Setoran:**
  * Penimbangan bobot ($kg$) setoran warga.
  * Kalkulasi otomatis poin dan saldo rupiah berdasarkan jenis sampah:
    * **Organik:** $1\text{ kg} = 10\text{ Poin} \mid \text{Rp } 1.000$
    * **Non-Organik:** $1\text{ kg} = 30\text{ Poin} \mid \text{Rp } 3.000$
* **Manajemen Akun Warga:** Fitur CRUD lengkap untuk menambah, memperbarui password, dan menghapus akun warga/admin.
* **Ekspor Laporan Spreadsheet/Excel:** Fitur ekspor data setoran ke format CSV/Excel dengan dukungan *Hybrid Download* (Lancar dibuka via PC Browser maupun *WebViewer App Inventor* menggunakan protokol `ActivityStarter`).

### 📱 2. Mobile App (Warga RT)
* **Autentikasi Akun:** Login berbasis NIK/Username dengan filterisasi role otomatis (Admin / User).
* **Setor Sampah via Kamera:** Pengunggahan bukti foto sampah langsung dari kamera HP dengan kompresi otomatis di server backend.
* **Dompet Digital Warga (Wallet API):** Menampilkan total akumulasi poin, akumulasi saldo rupiah, total bobot sampah yang disetor, serta riwayat status verifikasi.

---

## 📡 Dokumentasi API (Endpoints)

| HTTP Method | Endpoint | Fungsi | Parameter Utama |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/login` | Autentikasi User & Admin | `username`, `password` |
| `POST` | `/api/upload-sampah` | Unggah Bukti Foto Setoran Sampah | `user_id`, `jenis_sampah`, `file`/`foto` |
| `GET` | `/api/user-wallet` | Ambil Data Poin, Saldo, & Riwayat | `user_id` |

---

## 🚀 Panduan Instalasi (Backend Laravel)

1. **Clone Repositori:**
   ```bash
   git clone [https://github.com/SatriadyNu6roho/BankSampah-management-.git](https://github.com/SatriadyNu6roho/BankSampah-management-.git)
   cd BankSampah-management-

 langkah-langkah lain:

1.Instal Dependensi PHP:

Bash
composer install

2.Konfigurasi Environment (.env):
Salin file .env.example menjadi .env:

Bash
cp .env.example .env

Atur koneksi database di file .env:

Cuplikan kode
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bank_sampah_db
DB_USERNAME=root
DB_PASSWORD=

3.Generate App Key & Jalankan Migrasi Database:

Bash
php artisan key:generate
php artisan migrate --seed

4.Buat Symlink Storage Gambar:

Bash
php artisan storage:link

5.Jalankan Server Lokal (Support Akses HP/LAN):

Bash
php artisan serve --host=0.0.0.0 --port=8000

6.Pengaturan Aplikasi Mobile (MIT App Inventor)
 1.Impor berkas mobile-app/Bank_Sampah_RT12.aia ke dalam MIT App Inventor.

 2.Sesuaikan IP server pada variabel URL di blok Screen1, Screen2, dan ScreenAdmin dengan alamat IP IPv4 laptop kamu (contoh: http://192.168.1.206:8000).

 3.Build file APK atau jalankan via MIT Companion di HP Android.

 ---

 #CATATAN BESAR
 APABILA TIDAK BISA BERJALAN JUGA DENGAN CARA TERSEBUT. DISARANKAN UNTUK TURN OFF WINDOWS DEFENDER FIREWALL
