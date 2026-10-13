# 🎟️ EventHub — Sistem Pengelolaan Event Kampus Berbasis Web

> **Gaya Visual / Design System**: Neo-Brutalism (Bold Black Borders, Hard Drop-Shadows, High Contrast Vibrancy, JetBrains Mono & Space Grotesk)  
> **Versi**: 1.1 | **Tech Stack**: Laravel 13, PHP 8.4, Tailwind CSS v4, Alpine.js, SQLite/MySQL, Chart.js, HTML5-QRCode, DomPDF

---

## ⚡ 1. Tentang EventHub

EventHub adalah platform terpadu untuk ekosistem event kampus yang mendukung seluruh siklus hidup kegiatan kemahasiswaan: mulai dari publikasi, pendaftaran multi-tier, pembayaran & verifikasi instan, penerbitan E-Ticket dinamis berbasis QR Code, check-in scanner menggunakan kamera browser, pendelegasian kepanitiaan, hingga penerbitan sertifikat digital otomatis pasca-event dan ulasan peserta.

---

## 🚀 2. Panduan Instalasi & Konfigurasi Lingkungan

### Prasyarat Sistem:
- **PHP**: ^8.2 (Direkomendasikan PHP 8.4) dengan ekstensi `pdo_sqlite` / `pdo_mysql`, `gd`, `zip`, `mbstring`.
- **Node.js**: ^18.x atau ^20.x
- **Composer**: ^2.x
- **NPM** atau **Yarn**

### Langkah-langkah Setup:

```bash
# 1. Clone repositori & masuk ke direktori proyek
git clone <repository-url>
cd "Sistem Event Kampus"

# 2. Install dependensi PHP & Javascript
composer install
npm install

# 3. Konfigurasi file environment
cp .env.example .env
php artisan key:generate

# 4. Migrasi basis data & seeder data demo
touch database/database.sqlite  # Jika menggunakan SQLite
php artisan migrate:fresh --seed

# 5. Hubungkan direktori storage untuk upload banner, bukti bayar, avatar, dan sertifikat
php artisan storage:link

# 6. Build asset frontend (Tailwind v4 & Vite)
npm run build

# 7. Jalankan server lokal
php artisan serve
```

Aplikasi dapat diakses melalui browser di: `http://127.0.0.1:8000`

---

## 🔐 3. Akun Demo untuk Setiap Peran (Credentials)

Sistem telah dilengkapi seeder akun untuk 4 role pengguna:

| Role Pengguna | Email Login | Kata Sandi | Hak Akses Utama |
|---|---|---|---|
| **Administrator** | `admin@eventhub.test` | `password` | Moderasi event (Approve/Reject), kelola kategori, mutasi role pengguna, audit log trail. |
| **Organizer** | `organizer@eventhub.test` | `password` | Manajemen event, multi-tier tiket, verifikasi bayar, scanner QR, delegasi panitia, penerbitan sertifikat. |
| **Panitia (Staff)** | `panitia@eventhub.test` | `password` | Operasional check-in scan peserta pada event yang didelegasikan. |
| **Peserta** | `participant@eventhub.test` | `password` | Katalog event, checkout tiket, download E-Ticket PDF, klaim sertifikat, beri ulasan bintang & komentar. |

---

## 📖 4. Panduan Pengoperasian Berdasarkan Role

### 🏛️ Administrator
1. **Moderasi Event**: Masuk ke menu `Moderasi Event` untuk memeriksa event yang diajukan organizer (`SUBMITTED`). Klik `Setujui Event` atau `Tolak Event` dengan menyertakan catatan revisi.
2. **Master Kategori**: Tambah, ubah ikon/nama, dan hapus kategori kegiatan kampus.
3. **Manajemen Pengguna**: Mengubah role pengguna (Admin, Organizer, Participant).
4. **Audit Trail**: Memantau rekam jejak aktivitas kritis di seluruh sistem.

### 🏢 Organizer (Penyelenggara)
1. **Pembuatan Event**: Klik `Tambah Event Baru`, lengkapi formulir (judul, tanggal, lokasi/online, banner). Simpan sebagai `DRAFT` atau langsung `SUBMIT`.
2. **Konfigurasi Tiket Berjenjang**: Pada daftar event, klik `Atur Tiket` untuk menentukan tiket (Gratis/Berbayar, kuota, tanggal penjualan).
3. **Pengajuan & Publikasi**: Ajukan ke Admin. Setelah disetujui (`APPROVED`), klik tombol `Publikasikan`.
4. **Verifikasi Pembayaran**: Pada menu `Peserta`, periksa bukti transfer dari peserta untuk status pembayaran `PENDING` dan klik `Verifikasi Pembayaran`.
5. **Scanner Check-in**: Klik `Scanner QR` untuk membuka kamera web atau ketik kode registrasi manual.
6. **Pendelegasian Panitia**: Tambah akun panitia kampus ke dalam event untuk divisi Registrasi, Finance, atau Dokumentasi.
7. **Sertifikat Digital**: Tentukan template sertifikat dan terbitkan sertifikat untuk peserta yang berstatus `ATTENDED`.

### 👥 Peserta (Mahasiswa)
1. **Jelajahi & Cari Event**: Gunakan filter kategori, status harga (Free/Paid), kota, dan rentang tanggal di halaman `Jelajahi Event`.
2. **Checkout Tiket**: Pilih tier tiket yang diinginkan, pilih metode pembayaran, dan unggah bukti transfer jika berbayar.
3. **E-Ticket**: Buka menu `Tiket Saya` untuk melihat detail QR Code dinamis dan unduh PDF tiket.
4. **Presensi**: Tunjukkan QR Code tiket kepada panitia di pintu masuk event.
5. **Klaim Sertifikat & Ulasan**: Setelah hadir (`ATTENDED`), klaim sertifikat digital di menu `Sertifikat Saya` dan berikan ulasan rating di halaman event.

---

## 🧪 5. Pengujian Otomatis (Automated Testing)

Jalankan rangkaian Feature & Unit Tests untuk memverifikasi fungsionalitas core:

```bash
php artisan test --compact
```
*(Seluruh pengujian mencakup autentikasi, anti-duplicate registration [BR-01], anti-double check-in [BR-03], pembatasan ulasan & sertifikat [BR-04/07], dan isolasi otoritas event [BR-06]).*

---

## 🎨 6. Arsitektur Desain Neo-Brutalism
- **Borders**: Tebal dan tegas (`border-2`, `border-3`, `border-4` dengan warna `border-black`).
- **Shadows**: Hard-edge solid offset shadows (`shadow-[4px_4px_0px_0px_#000]`).
- **Palet Warna**:
  - Yellow Electric: `#FFE600`
  - Cyan Electric: `#00F0FF` / `#4DEEEA`
  - Green Neon: `#57EB74`
  - Soft Pink: `#FF70A6`
  - Coral / Orange: `#FF6B4A`
- **Tipografi**: Space Grotesk (Header/Titles) & JetBrains Mono (Data cards, kode tiket, timestamp).
