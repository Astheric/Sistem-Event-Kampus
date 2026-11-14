# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-11-14

### Added
- Soft Dashboard UI (token, ikon Feather, komponen Blade, layout sidebar).
- Capacity meter (Terisi, Tersisa, Total kapasitas) dan status kapasitas.
- Role dosen dengan audiens event (Umum, Mahasiswa, Dosen).
- Verifikasi akun organizer dan dosen.
- Bukti pembayaran pada disk privat dengan route berotorisasi.
- Jendela check-in berbasis waktu.
- Aturan pendaftaran yang dapat diperluas.

### Changed
- Status event dinormalisasi (APPROVED/ONGOING dihapus, menjadi PUBLISHED).
- Transisi status event diperketat.
- Ekspor CSV dipindah ke `ParticipantCsvExporter`.
- README versi PHP disamakan dengan composer.

### Fixed
- Panitia dapat mengakses scanner, daftar peserta, verifikasi bayar, ekspor sesuai divisi.
- `verifyPayment` dengan guard status.
- Checkout memeriksa status event, kapasitas, dan audiens.
- Moderasi admin hanya dari SUBMITTED.
- Pembatalan event membatalkan registrasi aktif.
- Check-in memeriksa waktu dan status event.
- BR-01 ditangani di dalam transaksi.
- Sertifikat massal melaporkan kegagalan.

## [1.1.0] - 2026-11-14

### Added
- Baseline awal EventHub.
