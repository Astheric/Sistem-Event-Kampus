# EventHub — Design System v2 "Soft Dashboard"

> Dokumen ini hanya membahas desain: gaya visual, token, ikon, layout, komponen, dan perilaku tampilan.
> Menggantikan gaya Neo-Brutalism yang lama.
> Referensi visual: dashboard "Wings" (gambar yang dikirim). Warna di dokumen ini adalah perkiraan dari gambar tersebut, lalu disesuaikan agar lolos kontras WCAG AA.

---

## 1. Latar belakang dan tujuan

Masukan dosen pembimbing:

1. Tampilan UI masih kurang baik.
2. Informasi kapasitas belum ada: kapasitas saat ini, kapasitas kosong, dan kapasitas total.
3. Perlu role **dosen**, yang bisa mengikuti event yang dikhususkan untuk dosen.

Target versi 2:

| Target | Wujud di desain |
|---|---|
| UI lebih modern dan rapi | Gaya "Soft Dashboard": kartu putih membulat, ungu sebagai warna utama, bayangan lembut, ilustrasi datar, ikon garis Feather |
| Kapasitas jelas | Komponen meter kapasitas dengan tiga angka: **Terisi**, **Tersisa**, **Total kapasitas** |
| Role dosen | Label peran "Dosen", pilihan audiens event (Umum / Mahasiswa / Dosen), lencana audiens, dan keadaan tombol daftar yang jelas |

---

## 2. Bahan dari referensi dan penerapannya

| Elemen di referensi | Ciri visual | Penerapan di EventHub |
|---|---|---|
| Sidebar kiri | Ikon + teks, item aktif berlatar lavender dengan teks ungu | Sidebar per role (admin, organizer, peserta/dosen) |
| Header | Judul halaman besar, kolom pencarian, lonceng, avatar | Judul halaman, pencarian event, lonceng notifikasi, avatar |
| Tiga kartu statistik | Angka besar, label kecil, ikon dalam kapsul pastel | Ringkasan di dashboard setiap role |
| "Ongoing Event" | Dua kartu berbanner ilustrasi, judul, jadwal, jumlah anggota | Kartu event berjalan, ditambah meter kapasitas |
| Kalender mini | Hari terpilih berupa lingkaran ungu, titik warna sebagai penanda event | Kalender event di dashboard peserta dan organizer |
| "Upcoming Events" | Kartu kecil: tanggal besar, judul, jam, lokasi, tumpukan avatar | Daftar event mendatang |
| Kartu "Upgrade" | Ilustrasi + satu tombol ungu | Kartu ajakan sesuai role |
| Latar | Putih keabu-abuan dengan cahaya mint dan lavender yang kabur | Dua blob gradien statis di belakang konten |

Yang tidak dibawa: nama "Wings", ilustrasi aslinya, teks bahasa Inggris, dan emoji pada judul ("Dashboard" dengan tangan melambai, lonceng, jam). Pengganti emoji adalah ikon Feather (bagian 5). Ilustrasi harus buatan sendiri atau dari sumber berlisensi.

---

## 3. Prinsip desain

1. **Lembut, bukan datar.** Pemisah antar-area memakai bayangan halus dan ruang kosong, bukan garis hitam tebal.
2. **Satu elemen yang menonjol: meter kapasitas.** Ini permintaan dosen, jadi ia mendapat perlakuan visual paling kuat. Elemen lain dibuat tenang.
3. **Warna berarti sesuatu.** Ungu untuk aksi utama, hijau/kuning/merah untuk status kapasitas, satu warna pastel per kelompok audiens. Warna tidak dipakai hanya untuk hiasan.
4. **Angka besar, label kecil.** Dipakai untuk statistik, sesuai pola referensi.
5. **Teks pendek, huruf kecil biasa (sentence case), kata kerja aktif.** Tombol menyebut hasilnya: "Daftar sekarang", bukan "Submit". Aksi yang sama punya nama yang sama di seluruh alur (tombol "Terbitkan" menghasilkan toast "Diterbitkan").
6. **Ikon garis, bukan emoji.** Satu set ikon, satu gaya garis, di seluruh aplikasi.
7. **Ilustrasi sebagai penanda.** Dipakai di banner event, keadaan kosong, dan kartu ajakan. Tidak di setiap kartu.

---

## 4. Design tokens

### 4.1 Warna

**Merek (ungu)**

| Token | Hex | Pemakaian |
|---|---|---|
| `brand-50` | `#F5F0FF` | Latar item sidebar aktif, latar kapsul info |
| `brand-100` | `#EBE2FF` | Hover ringan, jalur (track) meter kapasitas |
| `brand-200` | `#D9C8FF` | Cincin fokus |
| `brand-500` | `#8650F5` | Titik kalender, border input saat fokus |
| `brand-600` | `#6D2AEF` | Tombol utama, hari terpilih, ikon dan teks aktif (kontras ±6,5:1 di atas putih) |
| `brand-700` | `#5A1FCC` | Hover tombol utama |
| `brand-900` | `#2E1170` | Teks di atas latar `brand-100` |

**Permukaan dan teks**

| Token | Hex | Pemakaian |
|---|---|---|
| `canvas` | `#F6F5FB` | Latar halaman |
| `surface` | `#FFFFFF` | Kartu |
| `surface-soft` | `#FBFAFE` | Kartu bersarang di dalam kartu |
| `line` | `#ECE9F6` | Border tipis, pemisah tabel |
| `ink` | `#17142A` | Judul, angka |
| `muted` | `#6B6880` | Teks sekunder (kontras ±5,4:1) |
| `subtle` | `#8E8AA0` | Ikon biasa dan placeholder. Jangan untuk teks penting (kontras hanya ±3,3:1) |

**Pastel untuk kapsul ikon dan banner**

| Nama | Latar | Ikon/teks | Dipakai untuk |
|---|---|---|---|
| `sky` | `#E3E9FF` | `#4361EE` | Total event, tiket |
| `mint` | `#D8F6EF` | `#0B8F79` | Hadir, terbit |
| `sun` | `#FFF0C2` | `#8A5A00` | Menunggu, peringatan |
| `rose` | `#FFE2E7` | `#B4232F` | Ditolak, dibatalkan |
| `peach` (banner) | `#FFC070` | `#17142A` | Banner event kategori hangat |
| `violet` (banner) | `#6F45CF` | putih | Banner event kategori utama |

**Status kapasitas**

| Status | Aturan | Warna bar | Latar lencana | Teks lencana |
|---|---|---|---|---|
| Tersedia | terisi kurang dari 80% | `#12B886` | `#DCFCE7` | `#166534` |
| Hampir penuh | 80% sampai 99% | `#F5A524` | `#FEF3C7` | `#92400E` |
| Penuh | 100% | `#EF4444` | `#FEE2E2` | `#B91C1C` |
| Ditutup | event dibatalkan atau selesai | `#B8B5C9` | `#EEEDF4` | `#4B4860` |

**Lencana audiens** (selalu disertai ikon dan teks, jadi tidak bergantung pada warna)

| Audiens | Latar | Teks | Ikon Feather |
|---|---|---|---|
| Umum | `#EEF0F6` | `#3B3F5C` | `globe` |
| Mahasiswa | `#E3E9FF` | `#2F46B8` | `book-open` |
| Dosen | `#FFE4F1` | `#A1175E` | `briefcase` |

### 4.2 Tipografi

Satu keluarga huruf: **Plus Jakarta Sans** (bobot 400, 500, 600, 700, 800). Bentuknya geometris dan ramah, mirip karakter referensi, dan angkanya mudah dibaca. Font dimuat dari server sendiri (self-hosted) agar tidak bergantung pada CDN. Cadangan: `ui-sans-serif, system-ui, sans-serif`.

Semua angka (statistik, kapasitas, jam) memakai angka tabular supaya lebar angka seragam saat berubah.

| Peran | Ukuran / tinggi baris | Bobot | Contoh |
|---|---|---|---|
| Judul halaman | 28 / 36 | 800 | "Dashboard" |
| Angka statistik | 36 / 40 | 700 | "124" |
| Judul kartu | 20 / 28 | 700 | "Event berlangsung" |
| Judul item | 16 / 24 | 600 | Nama event |
| Isi | 14 / 22 | 400 | Deskripsi, jadwal |
| Keterangan | 12 / 18 | 500 | Label statistik, lencana |

Panjang baris isi maksimal 75 karakter. Tidak memakai huruf kapital semua untuk label dan tidak memakai font monospace untuk label kecil. Kode tiket (`EVT-2026-XXXXXX`) boleh memakai font monospace karena kode itu perlu dieja per karakter.

### 4.3 Bentuk, bayangan, jarak

| Token | Nilai | Pemakaian |
|---|---|---|
| `radius-card` | 24px | Kartu utama |
| `radius-inner` | 16px | Kartu bersarang, banner, input |
| `radius-chip` | 12px | Kapsul ikon |
| `radius-pill` | 9999px | Tombol, lencana, bar kapasitas |
| `shadow-card` | `0 8px 30px -12px rgb(109 42 239 / .18)` | Kartu utama |
| `shadow-pop` | `0 16px 40px -16px rgb(23 20 42 / .28)` | Dropdown, modal |
| Satuan jarak | kelipatan 4px | Padding kartu 24px, jarak antar-kartu 24px |

Radius dibuat berjenjang (24, 16, 12, pil), tidak satu ukuran untuk semuanya, agar hierarki kartu terlihat.

**Latar halaman.** `canvas` ditambah dua blob gradien kabur yang tetap di tempat: mint `#B9F5D8` di tengah (opasitas 40%) dan lavender `#D9C8FF` di kiri atas (opasitas 50%). Blob tidak bergerak. Bahan kaca (putih 70% + blur) hanya untuk topbar dan kartu yang menumpuk di atas blob. Kartu lain memakai putih solid agar teks terbaca.

---

## 5. Ikon

### 5.1 Aturan

- **Set ikon: [Feather Icons](https://feathericons.com/)** (lisensi MIT). Hanya ikon garis. Dipakai sebagai SVG sebaris agar warnanya mengikuti teks.
- **Tidak ada emoji di antarmuka**: judul, tombol, label, lencana, notifikasi, toast, dan data demo. Semua emoji di tampilan lama (misalnya penanda kapasitas di daftar event) diganti ikon Feather.
- Gaya: kotak 24px, tanpa isi, garis 2px untuk ukuran 16 sampai 20px dan 1,75px untuk 24px ke atas, ujung dan sambungan garis membulat.
- Warna ikon memakai warna teks di sekitarnya (`currentColor`). Ikon menu aktif `brand-600`, ikon menu biasa `subtle`.
- Ikon yang tidak ada di Feather tidak diambil dari set lain, supaya gayanya seragam. Pakai pengganti terdekat (tabel 5.3), atau gambar sendiri dengan aturan gaya yang sama.

### 5.2 Ukuran

| Ukuran | Pemakaian |
|---|---|
| 16px | Keterangan di kartu (jam, lokasi, tanggal), ikon di dalam lencana |
| 20px | Menu sidebar, tombol, kolom input, topbar |
| 24px | Di dalam kapsul statistik 56px |

Ikon di samping teks berjarak 8px. Ikon yang berdiri sendiri wajib punya label untuk pembaca layar dan tooltip.

### 5.3 Pemetaan ikon

**Navigasi**

| Menu | Ikon Feather |
|---|---|
| Dashboard | `home` |
| Jelajahi event | `compass` |
| Event saya | `calendar` |
| Tiket saya | `tag` |
| Sertifikat / Sertifikat saya | `award` |
| Profil | `user` |
| Peserta | `list` |
| Scanner | `camera` |
| Panitia | `user-plus` |
| Moderasi event | `check-square` |
| Kategori | `grid` |
| Pengguna | `users` |
| Audit log | `file-text` |
| Pengaturan | `settings` |
| Keluar | `log-out` |

**Topbar dan kontrol**

| Fungsi | Ikon Feather |
|---|---|
| Pencarian | `search` |
| Notifikasi | `bell` |
| Menu (mobile) | `menu` |
| Bulan sebelumnya / berikutnya | `chevron-left` / `chevron-right` |
| Filter | `filter` |
| Tambah | `plus` |
| Ubah | `edit-2` |
| Hapus | `trash-2` |
| Unduh / ekspor | `download` |
| Unggah | `upload` |
| Tautan keluar | `external-link` |
| Tutup | `x` |

**Informasi event**

| Info | Ikon Feather |
|---|---|
| Tanggal | `calendar` |
| Jam | `clock` |
| Lokasi | `map-pin` |
| Online | `globe` |
| Ulasan / rating | `star` |
| Peserta | `users` |

**Status**

| Status | Ikon Feather |
|---|---|
| Berhasil, tersedia, valid | `check-circle` |
| Peringatan, hampir penuh, tiket sudah dipakai | `alert-triangle` |
| Galat, ditolak, penuh, tidak valid | `x-circle` |
| Menunggu, belum diverifikasi | `clock` |
| Informasi | `info` |
| Ditutup | `lock` |

**Statistik dashboard**

| Kartu | Ikon Feather |
|---|---|
| Tiket aktif | `tag` |
| Event dihadiri, peserta hadir | `user-check` |
| Dibatalkan, ditolak | `x-circle` |
| Total event | `calendar` |
| Menunggu verifikasi / moderasi | `clock` |
| Event terbit | `check-circle` |

**Pengganti karena tidak ada di Feather:** topi toga diganti `book-open` (mahasiswa), tiket diganti `tag`, dan kode QR diganti `camera` (scanner). Ikon kategori event dipilih dari daftar ikon Feather, bukan mengetik karakter emoji.

---

## 6. Layout dan responsivitas

### 6.1 Kerangka halaman (desktop 1280px ke atas)

```text
┌──────────┬──────────────────────────────────────────────────────────┐
│ Logo     │ Judul halaman        [ Cari event...    ]  [lonceng] (A) │  topbar 72px
├──────────┼───────────────────────────────────────┬──────────────────┤
│ Dashboard│ ┌────────┐ ┌────────┐ ┌────────┐      │  Kalender        │
│ Event    │ │ Stat 1 │ │ Stat 2 │ │ Stat 3 │      │  (mini)          │
│ Tiket    │ └────────┘ └────────┘ └────────┘      │                  │
│ Sertifikat│ Event berlangsung         Lihat semua│                  │
│ Profil   │ ┌─────────────┐ ┌─────────────┐       ├──────────────────┤
│          │ │ banner      │ │ banner      │       │  Kartu ajakan    │
│ sidebar  │ │ judul       │ │ judul       │       │  (ilustrasi +    │
│ 240px    │ │ 142/200 ▓▓▓░│ │ 90/200 ▓▓░░ │       │   satu tombol)   │
│          │ └─────────────┘ └─────────────┘       │                  │
│          │ Event mendatang           Lihat semua │   rail 320px     │
│          │ ┌──────┐ ┌──────┐ ┌──────┐            │                  │
│          │ └──────┘ └──────┘ └──────┘            │                  │
└──────────┴───────────────────────────────────────┴──────────────────┘
```

Semua teks rata kiri. Angka statistik rata kiri di dalam kartu, kapsul ikon di kanan atas, seperti referensi.

### 6.2 Titik henti

| Lebar | Perilaku |
|---|---|
| 1280px ke atas | Sidebar 240px, konten utama 3 kolom, rail kanan 320px |
| 768 sampai 1279px | Sidebar menyusut jadi 80px (hanya ikon, label muncul sebagai tooltip), rail kanan pindah ke bawah konten utama |
| Di bawah 768px | Sidebar diganti navigasi bawah (maksimal 5 item), kartu satu kolom, tabel digeser horizontal di dalam wadahnya sendiri |

Lebar konten maksimal 1280px dan berada di tengah pada layar yang lebih lebar.

### 6.3 Menu sidebar per role

| Role | Item menu |
|---|---|
| Admin | Dashboard, Moderasi event, Kategori, Pengguna, Audit log |
| Organizer | Dashboard, Event saya, Peserta, Scanner, Panitia, Sertifikat |
| Peserta (mahasiswa dan dosen) | Dashboard, Jelajahi event, Tiket saya, Sertifikat saya, Profil |

Item aktif: latar `brand-50`, ikon dan teks `brand-600`, bobot 600. Item biasa: ikon `subtle`, teks `muted`.

---

## 7. Komponen inti

Kartu utama: putih, radius 24px, padding 24px, `shadow-card`. Kartu di dalam kartu: `surface-soft`, radius 16px, padding 16px.

### 7.1 Kartu statistik

- Tinggi sekitar 128px. Angka (36/40, bobot 700) di atas, label (14, `muted`) di bawahnya.
- Kapsul ikon 56px di kanan atas, radius 12px, pasangan warna pastel dengan ikon Feather 24px.
- Satu kartu, satu angka. Tidak ada grafik mini di dalam kartu statistik.

### 7.2 Kartu event (katalog dan "Event berlangsung")

```text
┌──────────────────────────────┐
│ ┌──────────────────────────┐ │
│ │ [Dosen]                  │ │  lencana audiens di pojok kiri atas banner
│ │  banner 16:9 (ilustrasi) │ │  radius 16
│ └──────────────────────────┘ │
│ Creator Meetup               │  judul 16/24 bobot 600
│ [calendar] 10 Agu 2026       │  keterangan 12, ikon 16, muted
│ [clock] 10.15 - 12.30        │
│ [map-pin] Aula Fasilkom      │
│ 142 / 200 terisi   Tersedia  │  meter kapasitas varian compact
│ ▓▓▓▓▓▓▓░░░░░                 │
│ Gratis                [Lihat]│  harga terendah + tombol
└──────────────────────────────┘
```

- Banner memakai gambar unggahan organizer. Bila kosong, tampilkan latar warna kategori (`violet` atau `peach`) dengan ilustrasi bawaan per kategori.
- Seluruh kartu adalah satu tautan ke halaman detail. Tombol "Lihat" hanya penegas visual.
- Tidak ada animasi mengangkat atau membesar saat hover. Cukup bayangan menguat sedikit dan cincin fokus yang jelas bagi pengguna keyboard.

### 7.3 Kartu event mendatang

Kartu kecil seperti referensi: tanggal besar ("18 Agu"), judul, ikon `clock` + waktu, ikon `map-pin` + tempat. Di bagian bawah, tumpukan avatar peserta pada referensi diganti teks kapasitas ("58 kursi tersisa"), karena identitas peserta lain tidak perlu diperlihatkan ke publik. Tumpukan avatar hanya tampil di dashboard organizer untuk event miliknya.

### 7.4 Kalender mini

- Hari terpilih: lingkaran `brand-600` dengan angka putih.
- Hari yang punya event: titik 6px di bawah angka. Warna titik mengikuti audiens: abu untuk umum, biru untuk mahasiswa, merah muda untuk dosen.
- Memilih hari menyaring daftar "Event mendatang" ke hari itu.
- Tombol bulan sebelumnya dan berikutnya (`chevron-left`, `chevron-right`) berupa tombol ikon berlabel.

### 7.5 Kartu ajakan (pengganti kartu "Upgrade")

Ilustrasi, satu kalimat, satu tombol ungu. Isinya mengikuti role:

| Role | Kalimat | Tombol |
|---|---|---|
| Organizer | "Punya acara baru? Ajukan sekarang agar tampil di katalog." | Buat event |
| Dosen | "Ada event khusus dosen yang bisa Anda ikuti." | Lihat event dosen |
| Mahasiswa | "Temukan seminar dan workshop kampus minggu ini." | Jelajahi event |

### 7.6 Topbar dan identitas pengguna

Judul halaman di kiri, pencarian di tengah, lonceng dan avatar di kanan. Di samping avatar tampil nama dan **label peran** dalam teks kecil `muted`: "Mahasiswa", "Dosen", "Organizer", atau "Admin". Lonceng menampilkan titik ungu bila ada notifikasi belum dibaca.

---

## 8. Tombol, form, tabel, notifikasi, keadaan kosong

**Tombol.** Tinggi 44px, bentuk pil, teks 14 bobot 600, ikon 20px di kiri teks bila perlu.

| Jenis | Gaya |
|---|---|
| Utama | Latar `brand-600`, teks putih, hover `brand-700` |
| Sekunder | Latar `brand-50`, teks `brand-700`, hover `brand-100` |
| Netral | Putih, border `line`, teks `ink` |
| Bahaya | Latar `#FEE2E2`, teks `#B91C1C`, hanya untuk aksi hapus atau tolak |
| Nonaktif | Latar `#EEEDF4`, teks `#8E8AA0`, selalu disertai teks alasan di dekatnya |

Satu tombol utama per kartu atau per halaman. Fokus keyboard: cincin 3px `brand-200` dengan jarak 2px.

**Form.** Label di atas input, teks bantuan di bawah. Input tinggi 48px, radius 16px, border `line`, latar putih. Saat fokus border berubah ke `brand-500`. Pesan galat ditulis di bawah input dengan ikon `alert-circle` kecil: sebutkan apa yang salah dan cara memperbaikinya ("Tanggal selesai harus setelah tanggal mulai"), tanpa kata maaf.

**Tabel** (peserta, pengguna, audit log): header 12/18 bobot 600 `muted` di atas garis `line`, baris 56px tanpa zebra, hover baris `surface-soft`. Kolom status memakai lencana, bukan teks polos.

**Notifikasi (toast).** Muncul di kanan atas, radius 16px, ikon status di kiri (`check-circle`, `alert-circle`, `alert-triangle`, `info`), hilang sendiri setelah 5 detik, bisa ditutup dengan tombol `x`.

**Keadaan kosong.** Ilustrasi kecil, satu kalimat penjelas, satu tombol aksi.

| Tempat | Teks | Tombol |
|---|---|---|
| Tiket saya | "Belum ada tiket. Daftar ke event untuk melihat tiket di sini." | Jelajahi event |
| Event saya (organizer) | "Belum ada event. Buat event pertama Anda." | Buat event |
| Hasil pencarian | "Tidak ada event yang cocok. Coba ubah filter atau kata kunci." | Atur ulang filter |

---

## 9. Kapasitas (permintaan dosen)

### 9.1 Tiga angka

| Istilah di UI | Arti |
|---|---|
| **Terisi** (kapasitas saat ini) | Kursi yang sudah dipakai. Pendaftar yang masih menunggu verifikasi pembayaran ikut dihitung karena kursinya sudah dipegang. Pendaftaran yang dibatalkan tidak dihitung |
| **Tersisa** (kapasitas kosong) | Kursi yang masih bisa dipesan: Total dikurangi Terisi, tidak pernah di bawah nol |
| **Total kapasitas** | Batas kursi event yang diisi organizer |

Kapasitas event dan kuota tiket berbeda. Kuota tiket membatasi satu jenis tiket, sedangkan kapasitas event membatasi seluruh jenis tiket sekaligus. Halaman detail event menampilkan keduanya: meter besar untuk kapasitas event, meter kecil untuk tiap jenis tiket.

### 9.2 Status dan teks

| Kondisi | Lencana (ikon) | Teks pendukung | Tombol daftar |
|---|---|---|---|
| Terisi kurang dari 80% | Tersedia (`check-circle`) | "58 kursi tersisa" | Aktif |
| 80% sampai 99% | Hampir penuh (`alert-triangle`) | "Tinggal 12 kursi" | Aktif |
| 100% | Penuh (`x-circle`) | "Kursi sudah penuh" | Nonaktif |
| Event dibatalkan atau selesai | Ditutup (`lock`) | "Pendaftaran ditutup" | Nonaktif |

Status tidak hanya ditandai warna: setiap kondisi punya ikon, teks lencana, dan teks pendukung.

### 9.3 Empat varian tampilan

| Varian | Tempat | Isi |
|---|---|---|
| Compact | Kartu event di katalog, dashboard, halaman moderasi admin | Teks "142 / 200 terisi", lencana status, bar tipis (8px) |
| Full | Halaman detail event, kolom kanan | Tiga ubin angka (Terisi, Tersisa, Total kapasitas), bar tebal (12px) dengan persentase, rincian per jenis tiket |
| Row | Tabel "Event saya" milik organizer | Teks "142 / 200" dan bar 96px di dalam sel tabel |
| Live | Halaman scanner check-in | Hadir, terkonfirmasi, dan total kapasitas, angka berubah setiap hasil scan |

Varian Full:

```text
┌─ Kapasitas ──────────────────── [ ! Hampir penuh ] ┐
│  ┌─────────┐   ┌─────────┐   ┌─────────┐           │
│  │   168   │   │   32    │   │   200   │           │
│  │ Terisi  │   │ Tersisa │   │  Total  │           │
│  └─────────┘   └─────────┘   └─────────┘           │
│  ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░   84%             │
│                                                    │
│  Reguler    Rp 25.000   ▓▓▓▓▓░░  10 dari 50 sisa   │
│  Early bird Rp 15.000   ▓▓▓▓▓▓▓  Habis             │
└────────────────────────────────────────────────────┘
```

Ubin angka: latar `surface-soft`, radius 16px, angka 30/36 bobot 700 di atas, label 12 `muted` di bawah. Hanya angka "Tersisa" yang berwarna mengikuti status (hijau tua, kuning tua, atau merah tua). Dua angka lain tetap `ink`. Ubin tidak memakai ikon, agar tidak ramai.

### 9.4 Hasil scan (varian Live)

Setelah satu scan, tampil kartu besar di atas daftar check-in terbaru:

| Hasil | Warna | Ikon | Teks |
|---|---|---|---|
| Berhasil | Hijau | `check-circle` | "Check-in berhasil. Selamat datang, [nama]." |
| Tiket sudah dipakai | Kuning | `alert-triangle` | "Tiket sudah digunakan pada [jam] oleh [panitia]." |
| Belum dibayar | Kuning | `clock` | "Pembayaran belum diverifikasi." |
| Tidak valid | Merah | `x-circle` | "Kode tidak ditemukan atau milik event lain." |

### 9.5 Gerak dan aksesibilitas kapasitas

- Bar kapasitas dibaca pembaca layar sebagai kemajuan dengan teks lengkap berbahasa Indonesia, misalnya "168 dari 200 kursi terisi, 32 tersisa".
- Isi bar beranimasi sekali dari kosong ke nilai akhir (400ms) saat halaman dimuat. Tidak ada animasi lagi sesudahnya, dan animasi dimatikan bila pengguna memilih mengurangi gerak.

---

## 10. Audiens event dan role dosen

### 10.1 Siapa bisa mendaftar ke event apa

| Audiens event | Mahasiswa | Dosen | Organizer / admin |
|---|---|---|---|
| Umum | Boleh | Boleh | Boleh |
| Mahasiswa | Boleh | Tidak | Tidak |
| Dosen | Tidak | Boleh | Tidak |

Tabel ini menentukan keadaan tombol daftar di bagian 10.4. Bila dosen kelak diizinkan ikut event mahasiswa, hanya tabel ini dan teks alasannya yang berubah.

### 10.2 Lencana audiens

Lencana (warna dan ikon di bagian 4.1) tampil di pojok kiri atas banner kartu event dan di samping judul pada halaman detail. Bentuk pil, tinggi 24px, ikon 16px + teks 12 bobot 600.

### 10.3 Pilihan audiens di form event

Tiga pilihan berbentuk kartu segmen berdampingan, masing-masing dengan ikon, nama, dan satu baris penjelasan:

```text
┌───────────────┐ ┌───────────────┐ ┌───────────────┐
│ [globe]       │ │ [book-open]   │ │ [briefcase]   │
│ Umum          │ │ Mahasiswa     │ │ Dosen         │
│ Semua akun    │ │ Hanya akun    │ │ Hanya akun    │
│ bisa daftar   │ │ mahasiswa     │ │ dosen         │
└───────────────┘ └───────────────┘ └───────────────┘
```

Pilihan terpilih: border `brand-600` 2px dan latar `brand-50`. Bawaan: Umum. Di bawah kolom kapasitas ada teks bantuan: "Kapasitas adalah batas peserta untuk semua jenis tiket."

### 10.4 Katalog dan halaman detail

- **Katalog:** tambahkan filter **Audiens** berupa chip (Semua, Umum, Mahasiswa, Dosen) dengan ikon, dan sakelar **Masih ada kursi**.
- Event khusus dosen tetap tampil di katalog untuk semua pengunjung lengkap dengan lencananya. Yang dibatasi hanya tombol daftar.
- **Keadaan tombol daftar** di halaman detail, diperiksa dari atas ke bawah (aturan pertama yang cocok yang dipakai):

| Kondisi | Tombol | Teks di bawah tombol |
|---|---|---|
| Event ditutup | Nonaktif: "Daftar sekarang" | "Pendaftaran ditutup." |
| Sudah terdaftar | Sekunder: "Lihat tiket saya" | "Anda sudah terdaftar." |
| Belum masuk | Utama: "Masuk untuk mendaftar" | tidak ada |
| Audiens tidak sesuai | Nonaktif: "Daftar sekarang" | "Event ini khusus dosen." atau "Event ini khusus mahasiswa." |
| Kursi penuh | Nonaktif: "Daftar sekarang" | "Kursi sudah penuh." |
| Lainnya | Utama: "Daftar sekarang" | Sisa kursi, mis. "58 kursi tersisa" |

Tombol nonaktif tidak disembunyikan, supaya alasannya terlihat.

### 10.5 Halaman daftar akun

Pilihan peran berupa tiga kartu pilih: **Mahasiswa** (`book-open`), **Dosen** (`briefcase`), **Organizer** (`calendar`). Kolom tambahan muncul sesuai pilihan:

| Pilihan | Kolom tambahan | Teks bantuan |
|---|---|---|
| Mahasiswa | tidak ada | |
| Dosen | NIDN | "NIDN dipakai untuk memastikan akun ini milik dosen." |
| Organizer | Nama organisasi | "Nama ini tampil sebagai penyelenggara event." |

### 10.6 Dashboard dosen

Struktur sama dengan dashboard peserta (bagian 11.1). Tambahan: bagian **Untuk Anda** di bawah "Event berlangsung", berisi event khusus dosen yang akan datang dalam bentuk kartu event, dan kartu ajakan "Ada event khusus dosen yang bisa Anda ikuti."

---

## 11. Panduan per halaman

### 11.1 Kartu statistik di dashboard

Tiga kartu per role, mengikuti pola referensi (Meetings, Attended, Rejected):

| Role | Kartu 1 (`sky`) | Kartu 2 (`mint`) | Kartu 3 |
|---|---|---|---|
| Peserta / dosen | Tiket aktif | Event dihadiri | Dibatalkan (`rose`) |
| Organizer | Total event | Peserta hadir | Menunggu verifikasi bayar (`sun`) |
| Admin | Menunggu moderasi (`sun`) | Event terbit (`mint`) | Event ditolak (`rose`) |

Di bawah kartu statistik organizer ada kartu "Kapasitas event saya": daftar event aktif dengan meter varian Row, diurutkan dari yang paling penuh.

### 11.2 Halaman lain

| Halaman | Perubahan desain |
|---|---|
| Beranda | Hero singkat dengan pencarian, deretan kartu event unggulan lengkap dengan meter kapasitas, ajakan ke katalog |
| Katalog | Kartu event baru, filter audiens dan sakelar "Masih ada kursi" |
| Detail event | Kolom kiri: banner, deskripsi, jadwal, ulasan. Kolom kanan: lencana audiens, meter kapasitas varian Full, daftar tiket, tombol daftar |
| Checkout | Ringkasan event dan sisa kursi di sisi kanan, satu langkah unggah bukti |
| Tiket saya dan detail tiket | Kartu tiket dengan QR besar, status berlencana, tombol unduh PDF |
| Sertifikat saya | Kartu sertifikat dengan status "Belum diklaim" atau "Tersedia" |
| Dashboard organizer | Bagian 11.1, kalender, daftar event berjalan |
| Daftar event organizer | Tabel dengan kolom kapasitas (varian Row) dan audiens |
| Form event | Pilihan audiens (10.3) dan teks bantuan kapasitas |
| Scanner | Varian Live dan kartu hasil scan (9.4) |
| Moderasi admin | Tampilkan audiens, kapasitas, dan jenis tiket agar admin bisa menilai kelengkapan |
| Pengguna admin | Pilihan peran: Mahasiswa, Dosen, Organizer, Admin |
| Login dan daftar | Kartu di tengah dengan ilustrasi di sisi kiri (desktop), pilihan peran berupa kartu pilih (10.5) |
| Verifikasi sertifikat | Valid: kartu hijau dengan ikon `check-circle`, nama, event, tanggal. Tidak valid: kartu merah dengan ikon `x-circle`, pesan jelas, dan kolom kode untuk mencoba lagi |

---

## 12. Aksesibilitas dan gerak

- Teks isi minimal kontras 4,5:1. `muted` (`#6B6880`) lolos. `subtle` (`#8E8AA0`) hanya untuk ikon dan placeholder.
- Teks di atas latar pastel memakai pasangan warna gelap yang sudah ditentukan. Jangan memakai teks putih di atas pastel.
- Semua elemen interaktif punya fokus yang terlihat (cincin 3px) dan bisa dipakai dengan keyboard. Area sentuh minimal 44 x 44px.
- Ikon yang berdiri sendiri (lonceng, panah kalender, tutup) punya label untuk pembaca layar. Ikon yang menemani teks disembunyikan dari pembaca layar agar tidak dibaca dua kali.
- Banner event punya teks alternatif berisi judul event. Ilustrasi hiasan tanpa teks alternatif.
- Gerak dibatasi: satu animasi isi bar kapasitas saat halaman dimuat dan transisi bayangan saat hover. Tidak ada animasi masuk di setiap bagian halaman. Pengaturan "kurangi gerak" mematikan semuanya.
- Konten tidak bergantung pada warna: status kapasitas, audiens, dan hasil scan selalu disertai ikon dan teks.

---

## 13. Daftar periksa desain

**Gaya**
- [ ] Tidak ada lagi border hitam tebal atau bayangan keras.
- [ ] Tidak ada emoji di halaman mana pun (cek juga judul, tombol, data demo).
- [ ] Semua ikon berasal dari Feather dengan gaya garis yang sama.
- [ ] Hanya satu tombol utama per kartu atau halaman.
- [ ] Radius berjenjang dipakai konsisten (24, 16, 12, pil).

**Kapasitas**
- [ ] Setiap kartu event menampilkan "terisi / total" dan lencana status.
- [ ] Halaman detail event menampilkan tiga angka: Terisi, Tersisa, Total kapasitas.
- [ ] Setiap status kapasitas punya ikon, teks lencana, dan teks pendukung.
- [ ] Halaman scanner menampilkan hadir, terkonfirmasi, dan total kapasitas.

**Role dosen**
- [ ] Label peran "Dosen" tampil di topbar.
- [ ] Lencana audiens tampil di kartu dan halaman detail event.
- [ ] Pilihan audiens ada di form event.
- [ ] Setiap kondisi tombol daftar di 10.4 punya tampilan dan teks alasan.
- [ ] Halaman daftar akun punya pilihan Dosen dengan kolom NIDN.

**Responsif dan aksesibilitas**
- [ ] Tampilan nyaman di lebar 375px, 768px, dan 1280px.
- [ ] Kontras teks lolos 4,5:1 dan fokus keyboard terlihat di semua halaman.