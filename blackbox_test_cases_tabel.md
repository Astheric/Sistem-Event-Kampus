# Black Box Test Cases - Sistem Event Kampus (Format Tabel)

**Website**: http://127.0.0.1:8000  
**Metode**: Black box testing tanpa akses kode internal.  
**Role**: Guest, Participant, Organizer, Admin, Dosen.  
**Kredensial**:
- Admin: admin@eventhub.test / password
- Organizer: organizer@eventhub.test / password
- Participant: participant@eventhub.test / password
- Participant 2: siti@eventhub.test / password
- Dosen: dosen@eventhub.test / password
- Dosen unverified: dosen.baru@eventhub.test / password

## Tabel Test Cases

### 1. Guest (Pengunjung Publik)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan |
|---------|----------|----------------------|-------------------|------------|
| TC-G-01 | Akses halaman utama (`/`) tanpa login. | Tampil hero slider, kategori event, event terpopuler, tombol login/register. | | |
| TC-G-02 | Klik kategori "Seminar Nasional" di hero section. | Redirect ke `/events?category=seminar-nasional`. | | |
| TC-G-03 | Klik "Lihat detail event" pada card event di beranda. | Navigasi ke halaman detail event (`/events/{slug}`). | | |
| TC-G-04 | Klik "Lihat event publik" di navbar. | Redirect ke `/events`. | | |
| TC-G-05 | Filter event berdasarkan kategori di halaman katalog (`/events`). | Hanya tampilkan event dengan kategori terpilih. | | |
| TC-G-06 | Filter event berdasarkan status "upcoming" vs "completed". | UI update sesuai filter. | | |
| TC-G-07 | Klik card event di katalog untuk masuk detail. | Halaman detail event tampil dengan deskripsi, waktu, lokasi, tiket. | | |
| TC-G-08 | Pilih tiket di detail event -> klik "Daftar sekarang". | Redirect ke halaman login (karena guest tidak bisa daftar). | | |
| TC-G-09 | Input nomor sertifikat valid (CERT-2026-908123) di `/verify-certificate`. | Tampil informasi sertifikat: nama peserta, event, tanggal terbit. | | |
| TC-G-10 | Input nomor sertifikat invalid di `/verify-certificate`. | Pesan error "Sertifikat tidak ditemukan". | | |
| TC-G-11 | Akses `/login` -> input email/password salah. | Pesan error "Kredensial tidak valid". | | |
| TC-G-12 | Akses `/register` -> pilih role "organizer", isi form lengkap. | Submit sukses, redirect ke dashboard organizer. | | |
| TC-G-13 | Akses `/register` -> pilih role "participant". | Form berubah (tanpa field organisasi). | | |

### 2. Participant (Peserta)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan |
|---------|----------|----------------------|-------------------|------------|
| TC-P-01 | Login sebagai participant (participant@eventhub.test / password). | Redirect ke `/participant/tickets`. | | |
| TC-P-02 | Filter tiket berdasarkan status "aktif" di halaman tiket. | Hanya tampilkan tiket dengan status confirmed. | | |
| TC-P-03 | Klik card tiket untuk melihat detail. | Modal atau halaman detail tiket muncul (kode registrasi, event, status). | | |
| TC-P-04 | Klik "Unduh tiket" (jika tersedia). | File PDF/download mulai. | | |
| TC-P-05 | Lihat daftar sertifikat di `/participant/certificates`. | Tampilkan sertifikat yang sudah diterbitkan. | | |
| TC-P-06 | Klik "Unduh sertifikat". | File sertifikat PDF terunduh. | | |
| TC-P-07 | Edit informasi profil (nama, telepon, bio) di `/participant/profile`. | Data tersimpan, tampil updated. | | |
| TC-P-08 | Ganti password di halaman profil. | Password berubah, perlu login ulang. | | |
| TC-P-09 | Dari detail event, pilih tiket -> klik "Daftar sekarang". | Redirect ke halaman checkout (`/events/{event}/checkout/{ticket}`). | | |
| TC-P-10 | Pada checkout, isi data pendaftaran -> submit. | Registrasi dibuat, status "pending" atau "confirmed" (jika gratis). | | |
| TC-P-11 | Upload bukti pembayaran untuk event berbayar. | Status berubah menjadi "pending verification". | | |
| TC-P-12 | Beri rating dan komentar pada event yang sudah attended. | Review tersimpan, tampil di halaman event. | | |
| TC-P-13 | Klik bell icon notification -> tampil daftar notifikasi. | Notifikasi unread/read tampil. | | |
| TC-P-14 | Klik notifikasi "Sertifikat siap diunduh". | Redirect ke halaman sertifikat. | | |

### 3. Organizer (Penyelenggara)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan |
|---------|----------|----------------------|-------------------|------------|
| TC-O-01 | Login sebagai organizer (organizer@eventhub.test / password). | Redirect ke `/organizer/dashboard`. | | |
| TC-O-02 | Lihat statistik dashboard (total event, peserta, pendapatan). | Angka statistik sesuai data. | | |
| TC-O-03 | Klik "Event saya" di sidebar. | Redirect ke `/organizer/events`. | | |
| TC-O-04 | Filter event berdasarkan status (draft, submitted, published, completed). | List update sesuai filter. | | |
| TC-O-05 | Klik event untuk edit detail. | Form edit muncul dengan data current. | | |
| TC-O-06 | Tombol "Hapus event" -> konfirmasi "Ya". | Event dihapus (soft delete), hilang dari list. | | |
| TC-O-07 | Buat event baru (`/organizer/events/create`) dengan form lengkap. | Event dibuat, status "draft" atau "submitted". | | |
| TC-O-08 | Tambahkan tiket berbayar (harga, kuota, periode penjualan). | Tiket tersimpan di event. | | |
| TC-O-09 | Akses daftar pendaftar per event. | Tabel peserta dengan status registrasi. | | |
| TC-O-10 | Verifikasi bukti pembayaran -> ubah status menjadi "confirmed". | Status registrasi berubah, notifikasi ke peserta. | | |
| TC-O-11 | Batalkan pendaftaran dengan alasan. | Status menjadi "cancelled", kuota tiket kembali. | | |
| TC-O-12 | Scan QR code peserta di halaman check-in. | Status peserta menjadi "attended". | | |
| TC-O-13 | Generate sertifikat untuk peserta yang attended. | Sertifikat terbit dengan nomor unik. | | |
| TC-O-14 | Export daftar peserta (CSV). | File CSV terunduh dengan data lengkap. | | |

### 4. Admin (Administrator Sistem)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan |
|---------|----------|----------------------|-------------------|------------|
| TC-A-01 | Login sebagai admin (admin@eventhub.test / password). | Redirect ke `/admin/dashboard`. | | |
| TC-A-02 | Lihat statistik sistem (total user, event, organisasi). | Angka sesuai data seeder. | | |
| TC-A-03 | Akses halaman moderasi event (`/admin/moderation`). | Tampilkan event dengan status "submitted". | | |
| TC-A-04 | Review detail event -> klik "Approve". | Status event berubah menjadi "published". | | |
| TC-A-05 | Review detail event -> klik "Reject" dengan alasan. | Status "rejected", notifikasi ke organizer. | | |
| TC-A-06 | Tambah kategori baru di `/admin/categories`. | Kategori muncul di dropdown pembuatan event. | | |
| TC-A-07 | Edit kategori exist. | Perubahan tersimpan. | | |
| TC-A-08 | Hapus kategori yang belum digunakan event. | Kategori hilang dari list. | | |
| TC-A-09 | Lihat daftar user di `/admin/users`. | Tabel user dengan kolom role, verified status. | | |
| TC-A-10 | Ubah role user dari participant menjadi organizer. | Role berubah, user bisa akses dashboard organizer. | | |
| TC-A-11 | Verifikasi akun dosen (toggle verified_at). | Status verified berubah. | | |
| TC-A-12 | Nonaktifkan akun (suspend). | User tidak bisa login. | | |
| TC-A-13 | Lihat audit log (`/admin/audit-logs`). | Log aktivitas dengan timestamp, user, action. | | |
| TC-A-14 | Filter log berdasarkan user tertentu. | Hanya log user tersebut yang tampil. | | |

### 5. Dosen (Lecturer)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan |
|---------|----------|----------------------|-------------------|------------|
| TC-D-01 | Login sebagai dosen verified (dosen@eventhub.test / password). | Redirect ke dashboard dosen (atau home). | | |
| TC-D-02 | Akses event dengan audience "LECTURER". | Bisa melihat dan mendaftar event tersebut. | | |
| TC-D-03 | Login sebagai dosen unverified (dosen.baru@eventhub.test). | Pesan "Akun belum diverifikasi, silakan hubungi admin". | | |

### 6. Access Control & Security

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan |
|---------|----------|----------------------|-------------------|------------|
| TC-AC-01 | Guest akses `/participant/tickets`. | Redirect ke `/login`. | | |
| TC-AC-02 | Participant akses `/admin/dashboard`. | 403 Forbidden atau redirect ke home. | | |
| TC-AC-03 | Organizer akses `/admin/moderation`. | 403 Forbidden. | | |
| TC-AC-04 | Dosen akses `/organizer/events`. | 403 Forbidden. | | |
| TC-AC-05 | Input tag `<script>alert('xss')</script>` di form komentar. | Script tidak dieksekusi (ter‑escape). | | |
| TC-AC-06 | Upload file dengan ekstensi `.php` sebagai bukti pembayaran. | Ditolak, pesan error "Format file tidak diizinkan". | | |
| TC-AC-07 | Login di dua browser berbeda -> logout di satu browser. | Session di browser lain tetap aktif atau expired sesuai config. | | |

### 7. Functional Flows (End-to-End)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan |
|---------|----------|----------------------|-------------------|------------|
| TC-F-01 | **Flow pendaftaran event berbayar**:<br>1. Guest → pilih tiket berbayar → redirect login.<br>2. Participant login → checkout → upload bukti bayar.<br>3. Organizer verifikasi pembayaran → status confirmed.<br>4. Participant hadir → check‑in oleh panitia.<br>5. Organizer generate sertifikat → participant unduh sertifikat. | Semua step berjalan lancar, status berubah sesuai alur. | | |
| TC-F-02 | **Flow pembuatan event oleh organizer**:<br>1. Organizer buat event draft → submit untuk moderasi.<br>2. Admin review → approve.<br>3. Event muncul di katalog public.<br>4. Participant daftar. | Event published, bisa dilihat dan didaftar oleh peserta. | | |

### 8. UI/UX & Validation

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan |
|---------|----------|----------------------|-------------------|------------|
| TC-U-01 | Submit form pendaftaran dengan required field kosong. | Error message di field yang kosong. | | |
| TC-U-02 | Input email "bukan-email" di form login. | Error "Format email tidak valid". | | |
| TC-U-03 | Input password kurang dari 8 karakter di form registrasi. | Error "Password minimal 8 karakter". | | |
| TC-U-04 | Set tanggal event di masa lalu di form buat event. | Error "Tanggal event harus di masa depan". | | |
| TC-U-05 | Set kapasitas event = 0. | Error "Kapasitas harus minimal 1". | | |
| TC-U-06 | Akses via mobile viewport (375px width). | Layout responsif, menu navbar jadi hamburger. | | |
| TC-U-07 | Akses URL tidak ada (`/undefined`). | 404 page dengan navigasi kembali ke home. | | |
| TC-U-08 | Server error (simulasikan dengan mematikan DB). | Friendly error page "Terjadi kesalahan sistem". | | |

## Petunjuk Pengisian Tabel

1. **Hasil di Lapangan**: Diisi setelah menjalankan test. Catat apa yang benar‑benar terjadi (error message, redirect URL, UI behavior).
2. **Kesimpulan**:
   - **Berhasil**: Jika hasil sesuai harapan.
   - **Gagal**: Jika ada discrepancy. Tambahkan catatan bug singkat.
3. **Lampiran Screenshot**: Untuk bug, screenshot bisa disimpan dengan nama `TC-ID-bug.png`.

## Prioritas Testing

1. **High**: TC-G-01, TC-G-11, TC-P-01, TC-P-09, TC-O-01, TC-A-01, TC-AC-01 (critical path & auth).
2. **Medium**: Flow end‑to‑end (TC-F-01, TC-F-02), form validation.
3. **Low**: UI responsiveness, error pages.

**Total Test Cases**: 58 skenario.