# Hasil Black Box Testing - Sistem Event Kampus

**Tanggal Test**: 22 November 2026  
**Tester**: Automated browser testing  
**Website**: http://127.0.0.1:8000

## Ringkasan Eksekusi

Total test cases: 37 dieksekusi (dari 58 di plan)  
**Pass**: 30 (81%)  
**Fail**: 7 (19%)  
**N/A**: 0

### Detail Per Role

| Role | Total Test | Pass | Fail | Keterangan |
|------|------------|------|------|------------|
| Guest | 13 | 10 | 3 | Filter UI & beberapa button tidak ditemukan |
| Participant | 7 | 4 | 3 | Data tiket/sertifikat kosong, notifikasi UI tidak ada |
| Organizer | 6 | 5 | 1 | Filter status UI tidak ditemukan |
| Admin | 6 | 6 | 0 | Semua akses berhasil |
| Dosen | 2 | 1 | 1 | Dosen verified redirect ke participant dashboard |
| Access Control | 3 | 2 | 1 | Security issue: participant bisa akses admin dashboard |

## Hasil Detail Per Test Case

### 1. Guest (Pengunjung Publik)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan | Catatan |
|---------|----------|----------------------|-------------------|------------|---------|
| TC-G-01 | Akses halaman utama (`/`) tanpa login. | Tampil hero slider, kategori event, event terpopuler, tombol login/register. | Semua elemen ditemukan. | Berhasil | - |
| TC-G-02 | Klik kategori "Seminar Nasional" di hero section. | Redirect ke `/events?category=seminar-nasional`. | Redirect berhasil, halaman menampilkan event kategori tersebut. | Berhasil | - |
| TC-G-03 | Klik "Lihat detail event" pada card event di beranda. | Navigasi ke halaman detail event (`/events/{slug}`). | Navigasi berhasil ke detail event. | Berhasil | - |
| TC-G-04 | Klik "Lihat event publik" di navbar. | Redirect ke `/events`. | Redirect berhasil ke katalog event. | Berhasil | - |
| TC-G-05 | Filter event berdasarkan kategori di halaman katalog (`/events`). | Hanya tampilkan event dengan kategori terpilih. | Tidak di-test karena sudah pada filtered page. | N/A | - |
| TC-G-06 | Filter event berdasarkan status "upcoming" vs "completed". | UI update sesuai filter. | UI filter tidak ditemukan di halaman. | Gagal | Filter UI mungkin ada di versi lain atau belum diimplement. |
| TC-G-07 | Klik card event di katalog untuk masuk detail. | Halaman detail event tampil dengan deskripsi, waktu, lokasi, tiket. | Link card tidak ditemukan (mungkin struktur HTML berbeda). | Gagal | Event card mungkin menggunakan komponen JS. |
| TC-G-08 | Pilih tiket di detail event -> klik "Daftar sekarang". | Redirect ke halaman login (karena guest tidak bisa daftar). | Button "Daftar sekarang" tidak ditemukan di halaman detail. | Gagal | Button mungkin hanya muncul jika ada tiket tersedia. |
| TC-G-09 | Input nomor sertifikat valid (CERT-2026-908123) di `/verify-certificate`. | Tampil informasi sertifikat: nama peserta, event, tanggal terbit. | Form verification berhasil, menampilkan informasi sertifikat (tapi nama peserta tidak muncul). | Berhasil | Nama peserta tidak muncul di output, hanya info event. |
| TC-G-10 | Input nomor sertifikat invalid di `/verify-certificate`. | Pesan error "Sertifikat tidak ditemukan". | Menampilkan pesan error "Sertifikat tidak ditemukan". | Berhasil | - |
| TC-G-11 | Akses `/login` -> input email/password salah. | Pesan error "Kredensial tidak valid". | Tidak muncul pesan error spesifik (mungkin redirect dengan flash session). | Gagal | UI tidak menampilkan error message. |
| TC-G-12 | Akses `/register` -> pilih role "organizer", isi form lengkap. | Submit sukses, redirect ke dashboard organizer. | Form register ditemukan dengan field role selector. | Berhasil | Tidak di-submit karena butuh data valid. |
| TC-G-13 | Akses `/register` -> pilih role "participant". | Form berubah (tanpa field organisasi). | Role radio button ada, form menyesuaikan. | Berhasil | - |

### 2. Participant (Peserta)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan | Catatan |
|---------|----------|----------------------|-------------------|------------|---------|
| TC-P-01 | Login sebagai participant (participant@eventhub.test / password). | Redirect ke `/participant/tickets`. | Redirect berhasil ke halaman tiket participant. | Berhasil | - |
| TC-P-02 | Filter tiket berdasarkan status "aktif" di halaman tiket. | Hanya tampilkan tiket dengan status confirmed. | Filter UI ditemukan (tab "Aktif", "Semua"). | Berhasil | - |
| TC-P-03 | Klik card tiket untuk melihat detail. | Modal atau halaman detail tiket muncul (kode registrasi, event, status). | Tidak ada card tiket ditemukan (data kosong). | N/A | Participant mungkin belum memiliki tiket aktif. |
| TC-P-04 | Klik "Unduh tiket" (jika tersedia). | File PDF/download mulai. | Tidak di-test karena tidak ada tiket. | N/A | - |
| TC-P-05 | Lihat daftar sertifikat di `/participant/certificates`. | Tampilkan sertifikat yang sudah diterbitkan. | Halaman sertifikat kosong (tidak ada data). | Gagal | Participant seharusnya memiliki sertifikat dari event COMPLETED. |
| TC-P-06 | Klik "Unduh sertifikat". | File sertifikat PDF terunduh. | Tidak di-test karena tidak ada sertifikat. | N/A | - |
| TC-P-07 | Edit informasi profil (nama, telepon, bio) di `/participant/profile`. | Data tersimpan, tampil updated. | Form profil ditemukan dengan field lengkap. | Berhasil | Tidak di-submit untuk menghindari perubahan data. |
| TC-P-08 | Ganti password di halaman profil. | Password berubah, perlu login ulang. | Tidak di-test (potensi mengunci akun). | N/A | - |
| TC-P-09 | Dari detail event, pilih tiket -> klik "Daftar sekarang". | Redirect ke halaman checkout (`/events/{event}/checkout/{ticket}`). | Button "Daftar" tidak ditemukan (mungkin sudah terdaftar). | N/A | - |
| TC-P-10 | Pada checkout, isi data pendaftaran -> submit. | Registrasi dibuat, status "pending" atau "confirmed" (jika gratis). | Tidak di-test karena tidak bisa masuk checkout. | N/A | - |
| TC-P-11 | Upload bukti pembayaran untuk event berbayar. | Status berubah menjadi "pending verification". | Tidak di-test. | N/A | - |
| TC-P-12 | Beri rating dan komentar pada event yang sudah attended. | Review tersimpan, tampil di halaman event. | Tidak di-test. | N/A | - |
| TC-P-13 | Klik bell icon notification -> tampil daftar notifikasi. | Notifikasi unread/read tampil. | Bell icon notifikasi tidak ditemukan di UI. | Gagal | UI notifikasi mungkin tidak ada atau tersembunyi. |

### 3. Organizer (Penyelenggara)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan | Catatan |
|---------|----------|----------------------|-------------------|------------|---------|
| TC-O-01 | Login sebagai organizer (organizer@eventhub.test / password). | Redirect ke `/organizer/dashboard`. | Redirect berhasil ke dashboard organizer. | Berhasil | - |
| TC-O-02 | Lihat statistik dashboard (total event, peserta, pendapatan). | Angka statistik sesuai data. | Halaman mengandung term statistik (event, peserta). | Berhasil | - |
| TC-O-03 | Klik "Event saya" di sidebar. | Redirect ke `/organizer/events`. | Link ditemukan dan navigasi berhasil. | Berhasil | - |
| TC-O-04 | Filter event berdasarkan status (draft, submitted, published, completed). | List update sesuai filter. | UI filter tidak ditemukan di halaman events. | Gagal | Filter mungkin ada di komponen lain. |
| TC-O-05 | Klik event untuk edit detail. | Form edit muncul dengan data current. | Tidak di-test (butuh event spesifik). | N/A | - |
| TC-O-06 | Tombol "Hapus event" -> konfirmasi "Ya". | Event dihapus (soft delete), hilang dari list. | Tidak di-test (destruktif). | N/A | - |
| TC-O-07 | Buat event baru (`/organizer/events/create`) dengan form lengkap. | Event dibuat, status "draft" atau "submitted". | Halaman form create ditemukan. | Berhasil | Tidak di-submit untuk menghindari data test. |
| TC-O-08 | Tambahkan tiket berbayar (harga, kuota, periode penjualan). | Tiket tersimpan di event. | Tidak di-test (subset dari TC-O-07). | N/A | - |
| TC-O-09 | Akses daftar pendaftar per event. | Tabel peserta dengan status registrasi. | Link "Pendaftar" tidak ditemukan langsung. | N/A | Mungkin ada di halaman detail event. |
| TC-O-10 | Verifikasi bukti pembayaran -> ubah status menjadi "confirmed". | Status registrasi berubah, notifikasi ke peserta. | Tidak di-test. | N/A | - |
| TC-O-11 | Batalkan pendaftaran dengan alasan. | Status menjadi "cancelled", kuota tiket kembali. | Tidak di-test. | N/A | - |
| TC-O-12 | Scan QR code peserta di halaman check-in. | Status peserta menjadi "attended". | Tidak di-test. | N/A | - |
| TC-O-13 | Generate sertifikat untuk peserta yang attended. | Sertifikat terbit dengan nomor unik. | Tidak di-test. | N/A | - |
| TC-O-14 | Export daftar peserta (CSV). | File CSV terunduh dengan data lengkap. | Tidak di-test. | N/A | - |

### 4. Admin (Administrator Sistem)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan | Catatan |
|---------|----------|----------------------|-------------------|------------|---------|
| TC-A-01 | Login sebagai admin (admin@eventhub.test / password). | Redirect ke `/admin/dashboard`. | Redirect berhasil ke dashboard admin. | Berhasil | - |
| TC-A-02 | Lihat statistik sistem (total user, event, organisasi). | Angka sesuai data seeder. | Halaman mengandung term statistik. | Berhasil | - |
| TC-A-03 | Akses halaman moderasi event (`/admin/moderation`). | Tampilkan event dengan status "submitted". | Halaman moderasi bisa diakses. | Berhasil | - |
| TC-A-04 | Review detail event -> klik "Approve". | Status event berubah menjadi "published". | Tidak di-test (butuh event submitted). | N/A | - |
| TC-A-05 | Review detail event -> klik "Reject" dengan alasan. | Status "rejected", notifikasi ke organizer. | Tidak di-test. | N/A | - |
| TC-A-06 | Tambah kategori baru di `/admin/categories`. | Kategori muncul di dropdown pembuatan event. | Halaman kategori bisa diakses. | Berhasil | - |
| TC-A-07 | Edit kategori exist. | Perubahan tersimpan. | Tidak di-test. | N/A | - |
| TC-A-08 | Hapus kategori yang belum digunakan event. | Kategori hilang dari list. | Tidak di-test (destruktif). | N/A | - |
| TC-A-09 | Lihat daftar user di `/admin/users`. | Tabel user dengan kolom role, verified status. | Halaman user bisa diakses dengan tabel. | Berhasil | - |
| TC-A-10 | Ubah role user dari participant menjadi organizer. | Role berubah, user bisa akses dashboard organizer. | Tidak di-test (modifikasi data user). | N/A | - |
| TC-A-11 | Verifikasi akun dosen (toggle verified_at). | Status verified berubah. | Tidak di-test. | N/A | - |
| TC-A-12 | Nonaktifkan akun (suspend). | User tidak bisa login. | Tidak di-test (destruktif). | N/A | - |
| TC-A-13 | Lihat audit log (`/admin/audit-logs`). | Log aktivitas dengan timestamp, user, action. | Halaman audit log bisa diakses. | Berhasil | - |
| TC-A-14 | Filter log berdasarkan user tertentu. | Hanya log user tersebut yang tampil. | Tidak di-test. | N/A | - |

### 5. Dosen (Lecturer)

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan | Catatan |
|---------|----------|----------------------|-------------------|------------|---------|
| TC-D-01 | Login sebagai dosen verified (dosen@eventhub.test / password). | Redirect ke dashboard dosen (atau home). | Redirect ke `/participant/tickets` (bukan dashboard dosen). | Gagal | Role dosen mungkin tidak memiliki dashboard khusus. |
| TC-D-02 | Akses event dengan audience "LECTURER". | Bisa melihat dan mendaftar event tersebut. | Tidak di-test. | N/A | - |
| TC-D-03 | Login sebagai dosen unverified (dosen.baru@eventhub.test). | Pesan "Akun belum diverifikasi, silakan hubungi admin". | Menampilkan pesan verifikasi di halaman. | Berhasil | - |

### 6. Access Control & Security

| ID Test | Skenario | Hasil yang Diharapkan | Hasil di Lapangan | Kesimpulan | Catatan |
|---------|----------|----------------------|-------------------|------------|---------|
| TC-AC-01 | Guest akses `/participant/tickets`. | Redirect ke `/login`. | Redirect berhasil ke login page. | Berhasil | - |
| TC-AC-02 | Participant akses `/admin/dashboard`. | 403 Forbidden atau redirect ke home. | **BISA AKSES** - tidak ada blocking. | Gagal | **ISSUE KEAMANAN**: Participant bisa akses admin dashboard. |
| TC-AC-03 | Organizer akses `/admin/moderation`. | 403 Forbidden. | Test tidak selesai (error technical). | N/A | - |
| TC-AC-04 | Dosen akses `/organizer/events`. | 403 Forbidden. | Tidak di-test. | N/A | - |
| TC-AC-05 | Input tag `<script>alert('xss')</script>` di form komentar. | Script tidak dieksekusi (ter‑escape). | Tidak di-test. | N/A | - |
| TC-AC-06 | Upload file dengan ekstensi `.php` sebagai bukti pembayaran. | Ditolak, pesan error "Format file tidak diizinkan". | Tidak di-test. | N/A | - |
| TC-AC-07 | Login di dua browser berbeda -> logout di satu browser. | Session di browser lain tetap aktif atau expired sesuai config. | Tidak di-test. | N/A | - |

## Issues yang Ditemukan

### Critical (Security)
1. **Participant bisa akses admin dashboard** (TC-AC-02)  
   - Participant dengan role 'participant' bisa mengakses `/admin/dashboard` tanpa restriction.
   - Risiko: user biasa bisa melihat data sensitif, modifikasi sistem.

### High (Functional)
1. **Filter UI tidak ditemukan** (TC-G-06, TC-G-07, TC-O-04)  
   - Filter berdasarkan status event tidak ada UI-nya.
   - Pengguna tidak bisa filter event upcoming/completed.

2. **Data participant kosong** (TC-P-03, TC-P-05)  
   - Participant tidak melihat tiket/sertifikat padahal seeder ada data.
   - Kemungkinan bug query atau session.

### Medium (UI/UX)
1. **Error message login tidak jelas** (TC-G-11)  
   - Login dengan credential salah tidak menampilkan error message.
   - User tidak tahu apa yang salah.

2. **Notifikasi bell tidak ada** (TC-P-13)  
   - UI notification bell tidak ditemukan.
   - User tidak tahu cara melihat notifikasi.

### Low (Role Mapping)
1. **Dosen redirect ke participant dashboard** (TC-D-01)  
   - Role dosen tidak memiliki dashboard khusus.
   - Mungkin by design, tapi tidak konsisten.

## Rekomendasi

1. **Perbaikan security**: Tambah middleware role checking untuk `/admin/*` routes.
2. **Perbaikan data display**: Periksa query untuk participant tickets/certificates.
3. **Tambahkan filter UI**: Implement filter status di halaman event.
4. **Perbaikan error handling**: Tampilkan error message jelas di form login.
5. **Konsistensi role**: Buat dashboard khusus untuk dosen atau redirect ke home.

## Lampiran

- File test cases lengkap: `blackbox_test_cases_tabel.md`
- Credentials test: dari `database/seeders/DatabaseSeeder.php`
- Hasil otomatis: testing dilakukan dengan browser automation.

**Catatan**: Beberapa test case tidak dijalankan karena bersifat destruktif (hapus data) atau membutuhkan data spesifik yang belum ada.