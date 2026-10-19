<p align="center">
  <img src="public/img/rupa_bass_ascii.svg" width="650" alt="Stockify - Rupa Bass ASCII Logo" />
</p>

<h1 align="center">Stockify</h1>

<p align="center">
  <strong>A Modern Inventory &amp; Stock Management System Built with Laravel</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Status-Active_Development-success?style=for-the-badge" alt="Status" />
</p>

**Stockify** adalah aplikasi manajemen stok dan inventaris berbasis web yang dikembangkan menggunakan framework **Laravel**. Dirancang untuk membantu pencatatan barang, pelacakan stok masuk/keluar, serta pengelolaan data inventaris dengan alur kerja yang rapi dan terstruktur.

### ✨ Fitur Utama
- 📦 **Manajemen Produk & Kategori:** Input, edit, hapus, dan kategorisasi stok barang.
- 📊 **Pelacakan Stok:** Monitor jumlah stok secara real-time.
- 🔐 **Autentikasi & Otorisasi:** Sistem login aman dan manajemen akses pengguna.
- 🎨 **Antarmuka Bersih & Responsif:** Pengalaman pengguna yang nyaman di berbagai resolusi layar.

Pastikan environment lokal Anda telah terinstal:
- **PHP** >= 8.2
- **Composer**
- **Node.js & NPM**
- **MySQL** / MariaDB / SQLite

1. **Clone repository:**
   ```bash
   git clone https://github.com/grendot-coder/stockify.git
   cd stockify
   ```

2. **Install dependency PHP & JavaScript:**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database & Migrasi:**
   Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`, lalu jalankan:
   ```bash
   php artisan migrate
   ```

5. **Jalankan Development Server:**
   ```bash
   php artisan serve
   ```
   Akses aplikasi di: `http://localhost:8000`
   
Dikembangkan dengan dedikasi oleh **Grendy Agusti**  
- GitHub: [@grendot-coder](https://github.com/grendot-coder)
- Portfolio: [Grendy Agusti Retro Desktop](https://github.com/grendot-coder)

Proyek ini berlisensi [MIT License](LICENSE).
