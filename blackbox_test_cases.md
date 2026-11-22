# Black Box Test Cases - Sistem Event Kampus

**Website**: http://127.0.0.1:8000  
**Test Approach**: Black box functional testing tanpa akses kode. Fokus pada input-output, UI behavior, access control.  
**Roles**: Guest, Participant, Organizer, Admin, Dosen.

## Test Credentials (from seeder)

- Admin: admin@eventhub.test / password
- Organizer: organizer@eventhub.test / password
- Participant: participant@eventhub.test / password
- Participant 2: siti@eventhub.test / password
- Dosen: dosen@eventhub.test / password
- Dosen unverified: dosen.baru@eventhub.test / password

## 1. Guest (Public User)

### 1.1 Halaman Beranda (`/`)
- **TC-GUEST-01**: Akses halaman utama tanpa login.
  - Expected: Menampilkan hero slider, kategori event, event terpopuler, tombol login/register.
- **TC-GUEST-02**: Klik kategori event (e.g., "Seminar Nasional").
  - Expected: Redirect ke `/events?category=seminar-nasional`.
- **TC-GUEST-03**: Klik "Lihat detail event" pada card event.
  - Expected: Navigasi ke halaman detail event (`/events/{slug}`).
- **TC-GUEST-04**: Klik "Lihat event publik" di navbar.
  - Expected: Redirect ke `/events`.

### 1.2 Katalog Event (`/events`)
- **TC-GUEST-05**: Filter event berdasarkan kategori (dropdown atau chip).
  - Expected: Hanya tampilkan event dengan kategori terpilih.
- **TC-GUEST-06**: Filter event berdasarkan status (upcoming, completed).
  - Expected: UI update sesuai filter.
- **TC-GUEST-07**: Klik card event untuk masuk detail.
  - Expected: Halaman detail event tampil dengan deskripsi, waktu, lokasi, tiket.

### 1.3 Detail Event (`/events/{slug}`)
- **TC-GUEST-08**: Verifikasi informasi event (judul, deskripsi, tanggal, tempat, kapasitas).
- **TC-GUEST-09**: Pilih tiket (jika ada pilihan) -> klik "Daftar sekarang".
  - Expected: Redirect ke halaman login (karena guest tidak bisa daftar).
- **TC-GUEST-10**: Klik "Verifikasi sertifikat" di footer.
  - Expected: Navigasi ke `/verify-certificate`.

### 1.4 Verifikasi Sertifikat (`/verify-certificate`)
- **TC-GUEST-11**: Input nomor sertifikat valid (e.g., CERT-2026-908123).
  - Expected: Menampilkan informasi sertifikat (nama peserta, event, tanggal terbit).
- **TC-GUEST-12**: Input nomor sertifikat invalid.
  - Expected: Pesan error "Sertifikat tidak ditemukan".

### 1.5 Auth Pages
- **TC-GUEST-13**: Akses `/login` -> input credential salah.
  - Expected: Pesan error "Kredensial tidak valid".
- **TC-GUEST-14**: Akses `/register` -> isi form registrasi organisasi.
  - Expected: Submit sukses -> redirect ke dashboard sesuai role.
- **TC-GUEST-15**: Akses `/register` -> pilih role "participant".
  - Expected: Form berubah (tanpa field organisasi).

## 2. Participant (Peserta)

### 2.1 Dashboard Participant (`/participant/tickets`)
- **TC-PART-01**: Login sebagai participant -> redirect ke halaman tiket.
- **TC-PART-02**: Filter tiket berdasarkan status (aktif, selesai, dibatalkan).
- **TC-PART-03**: Klik card tiket untuk melihat detail.
  - Expected: Modal atau halaman detail tiket muncul (kode registrasi, event, status).
- **TC-PART-04**: Klik "Unduh tiket" (jika tersedia).
  - Expected: File PDF/download mulai.

### 2.2 Sertifikat Participant (`/participant/certificates`)
- **TC-PART-05**: Lihat daftar sertifikat yang sudah diterbitkan.
- **TC-PART-06**: Klik "Unduh sertifikat" (jika tersedia).
  - Expected: File sertifikat PDF terunduh.
- **TC-PART-07**: Verifikasi sertifikat dari halaman ini (tombol verifikasi).
  - Expected: Redirect ke `/verify-certificate` dengan nomor sertifikat terisi.

### 2.3 Profil Participant (`/participant/profile`)
- **TC-PART-08**: Edit informasi profil (nama, telepon, bio).
  - Expected: Data tersimpan, tampil updated.
- **TC-PART-09**: Ganti password.
  - Expected: Password berubah, perlu login ulang.

### 2.4 Pendaftaran Event
- **TC-PART-10**: Dari halaman detail event, pilih tiket -> klik "Daftar sekarang".
  - Expected: Redirect ke halaman checkout (`/events/{event}/checkout/{ticket}`).
- **TC-PART-11**: Pada halaman checkout, isi data pendaftaran (jika required) -> submit.
  - Expected: Pembuatan registrasi, status "pending" atau "confirmed" (jika gratis).
- **TC-PART-12**: Untuk event berbayar, upload bukti pembayaran.
  - Expected: Status berubah menjadi "pending verification".

### 2.5 Review Event
- **TC-PART-13**: Pada event yang sudah attended (`ATTENDED`), beri rating dan komentar.
  - Expected: Review tersimpan, tampil di halaman event.

### 2.6 Notifikasi
- **TC-PART-14**: Bell icon notification -> tampil daftar notifikasi.
- **TC-PART-15**: Klik notifikasi (e.g., "Sertifikat siap diunduh").
  - Expected: Redirect ke halaman terkait.

## 3. Organizer (Penyelenggara)

### 3.1 Dashboard Organizer (`/organizer/dashboard`)
- **TC-ORG-01**: Login sebagai organizer -> redirect ke dashboard.
- **TC-ORG-02**: Statistik dashboard (total event, peserta, pendapatan).
- **TC-ORG-03**: Quick links ke "Event saya", "Buat event baru".

### 3.2 Manajemen Event (`/organizer/events`)
- **TC-ORG-04**: Lihat daftar event yang dibuat.
- **TC-ORG-05**: Filter event berdasarkan status (draft, submitted, published, completed).
- **TC-ORG-06**: Klik event untuk edit detail.
- **TC-ORG-07**: Tombol "Hapus event" -> konfirmasi.
  - Expected: Event dihapus (soft delete).

### 3.3 Buat Event Baru (`/organizer/events/create`)
- **TC-ORG-08**: Isi form lengkap (judul, kategori, deskripsi, waktu, lokasi, kapasitas, audience).
- **TC-ORG-09**: Tambahkan tiket (gratis/berbayar, kuota, periode penjualan).
- **TC-ORG-10**: Submit event.
  - Expected: Event status "draft" atau "submitted" (tergantung konfigurasi).

### 3.4 Moderasi Pendaftaran
- **TC-ORG-11**: Akses daftar pendaftar per event.
- **TC-ORG-12**: Verifikasi bukti pembayaran -> ubah status menjadi "confirmed".
- **TC-ORG-13**: Batalkan pendaftaran (dengan alasan).

### 3.5 Check-in Peserta
- **TC-ORG-14**: Halaman check-in (QR scanner atau manual input kode registrasi).
- **TC-ORG-15**: Scan QR code peserta -> status menjadi "attended".
- **TC-ORG-16**: Generate sertifikat untuk peserta yang attended.
  - Expected: Sertifikat terbit, nomor unik.

### 3.6 Laporan & Analitik
- **TC-ORG-17**: Export daftar peserta (CSV/PDF).
- **TC-ORG-18**: Lihat grafik pendaftaran per hari.

## 4. Admin (Administrator Sistem)

### 4.1 Dashboard Admin (`/admin/dashboard`)
- **TC-ADMIN-01**: Login admin -> redirect ke dashboard admin.
- **TC-ADMIN-02**: Statistik sistem (total user, event, organisasi).

### 4.2 Moderasi Event (`/admin/moderation`)
- **TC-ADMIN-03**: Lihat daftar event dengan status "submitted".
- **TC-ADMIN-04**: Review detail event -> approve.
  - Expected: Status event berubah menjadi "published".
- **TC-ADMIN-05**: Review detail event -> reject dengan alasan.
  - Expected: Status "rejected", notifikasi ke organizer.

### 4.3 Manajemen Kategori (`/admin/categories`)
- **TC-ADMIN-06**: Tambah kategori baru (nama, slug, icon).
- **TC-ADMIN-07**: Edit kategori exist.
- **TC-ADMIN-08**: Hapus kategori (pastikan tidak digunakan oleh event).

### 4.4 Manajemen User (`/admin/users`)
- **TC-ADMIN-09**: Lihat daftar user dengan filter role.
- **TC-ADMIN-10**: Edit role user (e.g., ubah participant jadi organizer).
- **TC-ADMIN-11**: Verifikasi akun dosen (toggle verified_at).
- **TC-ADMIN-12**: Nonaktifkan akun (suspend).

### 4.5 Audit Log (`/admin/audit-logs`)
- **TC-ADMIN-13**: Lihat log aktivitas (user, action, timestamp).
- **TC-ADMIN-14**: Filter log berdasarkan user atau action.

### 4.6 Konfigurasi Sistem
- **TC-ADMIN-15**: Akses pengaturan umum (jika ada).
- **TC-ADMIN-16**: Ubah setting seperti minimal password length, email sender.

## 5. Dosen (Lecturer)

### 5.1 Dashboard Dosen
- **TC-DOSEN-01**: Login dosen verified -> redirect ke dashboard dosen.
- **TC-DOSEN-02**: Akses event dengan audience "LECTURER".
- **TC-DOSEN-03**: Daftar sebagai peserta event.

### 5.2 Verifikasi Dosen
- **TC-DOSEN-04**: Dosen unverified (`verified_at = null`) -> akses dashboard.
  - Expected: Pesan "Akun belum diverifikasi, silakan hubungi admin".

## 6. Cross-Role Access Control

### 6.1 Unauthorized Access
- **TC-ACL-01**: Guest akses `/participant/tickets`.
  - Expected: Redirect ke login page.
- **TC-ACL-02**: Participant akses `/admin/dashboard`.
  - Expected: 403 Forbidden atau redirect ke home.
- **TC-ACL-03**: Organizer akses `/admin/moderation`.
  - Expected: 403 Forbidden.
- **TC-ACL-04**: Dosen akses `/organizer/events`.
  - Expected: 403 Forbidden.

### 6.2 Session Management
- **TC-SESS-01**: Login di dua browser berbeda -> logout di satu browser.
  - Expected: Session lain tetap aktif (atau tergantung konfigurasi).
- **TC-SESS-02**: Token expired setelah waktu tertentu.
  - Expected: Redirect ke login dengan pesan "Session expired".

## 7. Functional Flows (End-to-End)

### 7.1 Flow Pendaftaran Event Berbayar
1. Guest browse event -> pilih tiket berbayar -> redirect login.
2. Participant login -> checkout -> upload bukti bayar -> status pending.
3. Organizer verifikasi pembayaran -> status confirmed.
4. Participant hadir -> check-in oleh panitia -> status attended.
5. Organizer generate sertifikat -> participant unduh sertifikat.

### 7.2 Flow Pembuatan Event oleh Organizer
1. Organizer buat event draft -> isi detail & tiket -> submit untuk moderasi.
2. Admin review event -> approve -> status published.
3. Event muncul di katalog public -> peserta daftar.

## 8. UI/UX & Validation

### 8.1 Form Validation
- **TC-VALID-01**: Required field kosong -> error message.
- **TC-VALID-02**: Email format invalid.
- **TC-VALID-03**: Password kurang dari 8 karakter.
- **TC-VALID-04**: Tanggal event di masa lalu (harus future).
- **TC-VALID-05**: Kapasitas event kurang dari 1.

### 8.2 Responsiveness
- **TC-RESP-01**: Akses via mobile viewport (breakpoint).
- **TC-RESP-02**: Menu navbar toggle pada mobile.

### 8.3 Error Pages
- **TC-ERROR-01**: Akses URL tidak ada (`/undefined`).
  - Expected: 404 page dengan navigasi kembali.
- **TC-ERROR-02**: Server error (500).
  - Expected: Friendly error page.

## 9. Performance & Security

### 9.1 Load Time
- **TC-PERF-01**: Halaman utama load < 3 detik.
- **TC-PERF-02**: Halaman dengan banyak data (event list) pagination bekerja.

### 9.2 Input Sanitization
- **TC-SEC-01**: Input script tag di form komentar.
  - Expected: Script tidak dieksekusi (escaped).
- **TC-SEC-02**: File upload dengan ekstensi berbahaya.
  - Expected: Ditolak dengan pesan error.

---

## Eksekusi Test

**Tools yang bisa digunakan**:
- Browser manual testing (Chrome DevTools)
- Postman/curl untuk API endpoints (jika ada)
- Selenium/Playwright untuk automation (opsional)

**Prioritas**:
1. Critical path: pendaftaran event, pembayaran, check-in.
2. Access control: pastikan role isolation.
3. Validation: input sanitization, error handling.

**Laporan Bug**: Catat URL, role, steps, expected vs actual, screenshot jika perlu.