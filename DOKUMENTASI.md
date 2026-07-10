# 📚 DOKUMENTASI PROJECT RUDY
## Sistem Peminjaman Ruangan Perpustakaan — PNJ

> **Nama Aplikasi:** RUDY - Ruang Study  
> **Versi:** 1.0.0  
> **Maintainer:** Kelompok 4, RUDY Developers  
> **Repo:** https://github.com/azwaramadani/pblperpustakaan  
> **Dibuat dengan:** PHP Native (Custom MVC Framework), MySQL, Composer

---

## 📋 Daftar Isi

1. [Gambaran Umum Aplikasi](#1-gambaran-umum-aplikasi)
2. [Teknologi & Dependency](#2-teknologi--dependency)
3. [Struktur Folder Lengkap](#3-struktur-folder-lengkap)
4. [Penjelasan Detail Setiap Folder & File](#4-penjelasan-detail-setiap-folder--file)
   - [index.php — Entry Point](#indexphp--entry-point)
   - [config/ — Konfigurasi Global](#config--konfigurasi-global)
   - [core/ — Fondasi Framework MVC](#core--fondasi-framework-mvc)
   - [app/controllers/ — Controller Layer](#appcontrollers--controller-layer)
   - [app/models/ — Model Layer](#appmodels--model-layer)
   - [app/views/ — View Layer](#appviews--view-layer)
   - [public/ — Aset Publik](#public--aset-publik)
   - [storage/ — File Upload](#storage--file-upload)
   - [vendor/ — Dependency Composer](#vendor--dependency-composer)
5. [Alur Request (Flow) Aplikasi](#5-alur-request-flow-aplikasi)
6. [Alur Fitur Utama](#6-alur-fitur-utama)
   - [Alur Registrasi & Login](#alur-registrasi--login)
   - [Alur Booking Ruangan (User)](#alur-booking-ruangan-user)
   - [Alur Manajemen Admin](#alur-manajemen-admin)
7. [Database & Tabel](#7-database--tabel)
8. [Sistem Routing](#8-sistem-routing)
9. [Sistem Autentikasi & Sesi](#9-sistem-autentikasi--sesi)
10. [Fitur-Fitur Aplikasi](#10-fitur-fitur-aplikasi)
11. [Catatan Teknis & Bug yang Diketahui](#11-catatan-teknis--bug-yang-diketahui)

---

## 1. Gambaran Umum Aplikasi

**RUDY (Ruang Study)** adalah sistem informasi peminjaman ruangan di area perpustakaan PNJ (Politeknik Negeri Jakarta). Aplikasi ini dibuat sebagai project mata kuliah PBL (Project Based Learning) semester 3.

### Aktor dalam Sistem

| Aktor | Deskripsi |
|---|---|
| **User / Peminjam** | Mahasiswa, Dosen, atau Tenaga Kependidikan PNJ yang telah memiliki akun aktif |
| **Admin** | Pengelola perpustakaan, dapat memvalidasi akun, mengatur ruangan, dan membuat booking sendiri |

### Fitur Utama

- ✅ Registrasi user dengan 3 role: **Mahasiswa**, **Dosen**, **Tenaga Kependidikan**
- ✅ Validasi akun Mahasiswa oleh Admin (wajib upload bukti aktivasi KuBaca)
- ✅ Peminjaman ruangan 2-step (pilih jadwal → isi data anggota)
- ✅ Validasi jadwal real-time (anti-bentrok, jam operasional 09:00–15:00)
- ✅ Sistem pencegahan race-condition saat booking (database transaction + `FOR UPDATE`)
- ✅ Notifikasi email via SMTP (PHPMailer) — untuk reset password
- ✅ Riwayat peminjaman dan fitur pembatalan (dengan sanksi blokir 3x batal/hari)
- ✅ Sistem feedback/rating ruangan (Puas / Tidak Puas)
- ✅ Dashboard admin dengan statistik real-time
- ✅ Export laporan ke Excel (.xlsx) via PHPSpreadsheet

---

## 2. Teknologi & Dependency

| Teknologi | Peran |
|---|---|
| **PHP 8.x** | Bahasa pemrograman utama |
| **MySQL** | Database (via PDO) |
| **Laragon** | Local development server |
| **Composer** | Dependency manager |
| **PHPMailer** | Pengiriman email (reset password) |
| **PHPSpreadsheet** | Export data ke file Excel (.xlsx) |
| **vlucas/phpdotenv** | Membaca variabel environment dari file `.env` |
| **Vanilla CSS** | Styling halaman (tidak pakai framework CSS) |

### File `.env`
Berisi konfigurasi sensitif yang tidak boleh masuk ke repository:
```
MAIL_HOST=...
MAIL_PORT=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM=...
```

---

## 3. Struktur Folder Lengkap

```
pblperpustakaan/
│
├── index.php                   ← Entry point tunggal aplikasi (Front Controller)
├── .env                        ← Variabel environment (kredensial email, dll)
├── .gitignore
├── composer.json               ← Definisi dependency Composer
├── composer.lock
├── DOKUMENTASI.md              ← File ini
│
├── config/
│   ├── app.php                 ← Konfigurasi global aplikasi (DB, URL, path upload)
│   └── mail.php                ← Konfigurasi SMTP email
│
├── core/
│   ├── router.php              ← Sistem routing URL → Controller → Method
│   ├── controller.php          ← Base class Controller (view, model, redirect)
│   ├── model.php               ← Base class Model (koneksi PDO, query, transaksi)
│   ├── session.php             ← Manajemen sesi (set, get, flash, destroy, guard)
│   └── helper.php              ← Fungsi global (sendMail, uploadFile, jsonResponse, dll)
│
├── app/
│   ├── controllers/
│   │   ├── HomeController.php       ← Landing page
│   │   ├── AuthController.php       ← Login, Register, Logout, Reset Password
│   │   ├── UserController.php       ← Halaman user (home, ruangan, riwayat, profil)
│   │   ├── BookingController.php    ← Proses booking (step1, step2, store, edit, cancel)
│   │   ├── AdminController.php      ← Panel admin (dashboard, data ruangan, data akun)
│   │   ├── FeedbackController.php   ← Submit & tampil feedback ruangan
│   │   └── RoomController.php       ← (Minimal, sebagian besar dihandle AdminController)
│   │
│   ├── models/
│   │   ├── user.php            ← Model tabel `user`
│   │   ├── admin.php           ← Model tabel `admin`
│   │   ├── booking.php         ← Model tabel `booking`
│   │   ├── room.php            ← Model tabel `room`
│   │   ├── feedback.php        ← Model tabel `feedback`
│   │   └── PasswordReset.php   ← Model tabel `password_resets`
│   │
│   └── views/
│       ├── home/
│       │   └── index.php           ← Landing page (halaman pertama yang dilihat pengunjung)
│       ├── auth/
│       │   ├── login_user.php          ← Form login
│       │   ├── register_pilihrole.php  ← Pilih role (Mahasiswa/Dosen/Tendik)
│       │   ├── register_usermahasiswa.php ← Form register mahasiswa
│       │   ├── register_userdosen.php     ← Form register dosen
│       │   ├── register_usertendik.php    ← Form register tenaga kependidikan
│       │   ├── fix_registration.php       ← Upload ulang bukti setelah ditolak admin
│       │   ├── forgot_password.php        ← Form input email untuk reset password
│       │   └── reset_password.php         ← Form ganti password baru
│       ├── user/
│       │   ├── home.php            ← Dashboard user (daftar ruangan tersedia)
│       │   ├── ruangan.php         ← Halaman katalog semua ruangan
│       │   ├── booking_step1.php   ← Form booking: pilih tanggal & jam
│       │   ├── booking_step2.php   ← Form booking: isi data anggota & penanggung jawab
│       │   ├── riwayat.php         ← Riwayat peminjaman user
│       │   ├── feedback_form.php   ← Form feedback setelah booking selesai
│       │   ├── view_profile.php    ← Halaman lihat profil user
│       │   └── edit_profile.php    ← Form edit profil user
│       └── admin/
│           ├── dashboard.php           ← Dashboard admin (statistik + booking hari ini)
│           ├── data_peminjaman.php     ← Tabel semua data booking user
│           ├── data_admincreatebooking.php ← Tabel booking yang dibuat oleh admin
│           ├── data_ruangan.php        ← Kelola ruangan (lihat, edit, hapus)
│           ├── add_ruangan.php         ← Form tambah ruangan baru
│           ├── edit_ruangan.php        ← Form edit data ruangan
│           ├── feedback_ruangan.php    ← Lihat semua feedback per ruangan
│           ├── data_akun.php           ← Kelola akun user (validasi, blokir, hapus)
│           ├── admin_bookingstep1.php  ← Form booking admin: pilih jadwal
│           └── admin_bookingstep2.php  ← Form booking admin: isi data peminjam
│
├── public/
│   ├── captcha.php             ← Script generate gambar CAPTCHA
│   └── assets/
│       ├── css/                ← File CSS per halaman
│       │   ├── sylelogin.css
│       │   ├── styleregister.css
│       │   ├── stylehome.css
│       │   ├── styleruangan.css
│       │   ├── stylebooking1.css
│       │   ├── stylebooking2.css
│       │   ├── styleriwayat.css
│       │   ├── styleprofile.css
│       │   ├── styleforgotpassword.css
│       │   └── styleadmin.css
│       └── image/              ← Gambar statis (gambar ruangan yang di-upload)
│
├── storage/
│   └── uploads/
│       ├── bukti_aktivasi/         ← File upload bukti KuBaca mahasiswa
│       └── surat_peminjaman_ruangrapat/ ← (Fitur surat peminjaman ruang rapat)
│
└── vendor/                     ← Library pihak ketiga (dikelola Composer, jangan diedit manual)
```

---

## 4. Penjelasan Detail Setiap Folder & File

### `index.php` — Entry Point

File ini adalah **Front Controller** tunggal. Semua request HTTP masuk melalui satu file ini.

**Yang dilakukan `index.php`:**
1. **Load Composer autoload** — Mengaktifkan library PHPMailer, PHPSpreadsheet, dan phpdotenv.
2. **Load `.env`** — Membaca variabel environment (kredensial SMTP email) via `vlucas/phpdotenv`.
3. **Daftarkan `spl_autoload_register`** — Sistem autoload kustom yang secara otomatis mencari dan memuat file class sesuai urutan prioritas:
   - Cari di `core/` dulu
   - Kalau tidak ada, cari di `app/models/` (nama file lowercase)
   - Kalau tidak ada, cari di `app/controllers/`
4. **Load `config/app.php`** — Mengaktifkan fungsi `app_config()` global.
5. **Load `core/helper.php`** — Mengaktifkan semua fungsi helper global.
6. **Jalankan Router** — Membuat instance `Router` dan memanggil `run()`.

---

### `config/` — Konfigurasi Global

#### `config/app.php`
Berisi satu fungsi `app_config()` yang mengembalikan array konfigurasi global. Dibaca oleh hampir seluruh bagian aplikasi.

| Key | Isi | Kegunaan |
|---|---|---|
| `app_name` | `'RUDY - Ruang Study'` | Nama aplikasi |
| `app_tagline` | `'Sistem Peminjaman Ruangan Perpustakaan'` | Tagline |
| `base_url` | `http://localhost/pblperpustakaan` | Base URL untuk redirect & link asset |
| `timezone` | `Asia/Jakarta` | Timezone default PHP |
| `database` | `[host, username, password, dbname, charset]` | Konfigurasi koneksi PDO MySQL |
| `upload_paths` | `[bukti_aktivasi, surat_peminjaman]` | Path absolut folder upload |
| `session_lifetime` | `3600` (1 jam) | Lifetime sesi |
| `cancel_limit_per_day` | `2` | Batas batal booking per hari (meski di code implementasinya cek >= 3) |

#### `config/mail.php`
Membaca konfigurasi SMTP dari environment variable `.env`. Dikonsumsi oleh fungsi `sendMail()` di `helper.php`.

---

### `core/` — Fondasi Framework MVC

Folder ini adalah "jantung" dari custom MVC framework yang dibangun dari nol.

#### `core/router.php` — Router

Membaca URL `?route=Controller/method/param` lalu mendispatching ke controller yang tepat.

**Format URL:** `?route={NamaController}/{namaMethod}/{param1}/{param2}/...`

**Contoh:**
| URL | Controller | Method | Parameter |
|---|---|---|---|
| `?route=Auth/login` | `AuthController` | `login` | - |
| `?route=Booking/step1/3` | `BookingController` | `step1` | `3` (room_id) |
| `?route=Admin/dashboard` | `AdminController` | `dashboard` | - |

**Cara kerja:**
1. Ambil nilai `$_GET['route']`, default ke `home/index`.
2. Pecah string dengan `/` → `['controllerName', 'methodName', ...params]`.
3. Ubah nama controller jadi `PascalCase + "Controller"`.
4. Validasi file controller ada, lalu instansiasi dan panggil method dengan params.

#### `core/controller.php` — Base Controller

Parent class yang diextend semua controller. Menyediakan 3 method helper:

| Method | Kegunaan |
|---|---|
| `model(string $model)` | Memuat file model dari `app/models/` dan mengembalikan instance-nya |
| `view(string $view, array $data)` | Memuat file view dari `app/views/`, mengekstrak `$data` ke variabel lokal |
| `redirect(string $path)` | Redirect ke URL relatif (otomatis prepend `base_url`) |

**Catatan:** Sebagian controller (terutama `AuthController`, `BookingController`) **tidak** extend `Controller` dan langsung pakai `require` untuk load view. Ini inkonsistensi dalam codebase.

#### `core/model.php` — Base Model

Parent class semua model. Menyimpan koneksi PDO dan menyediakan method dasar:

| Method | Kegunaan |
|---|---|
| `query(string $sql, array $params)` | Eksekusi prepared statement PDO |
| `begin()` | Mulai database transaction |
| `commit()` | Commit transaction |
| `rollback()` | Rollback transaction |

Koneksi dibuat sekali di constructor menggunakan konfigurasi dari `app_config()['database']`.

#### `core/session.php` — Session Manager

Class `Session` (static methods) sebagai wrapper aman untuk `$_SESSION`. Semua method memanggil `start()` secara otomatis.

| Method | Kegunaan |
|---|---|
| `set($key, $value)` | Simpan data ke session |
| `get($key)` | Ambil data dari session |
| `setOld($data)` | Simpan data form lama (untuk repopulate form setelah error) |
| `getOld()` | Ambil data form lama (langsung dihapus setelah dibaca — one-time read) |
| `flash($key)` | Ambil flash message (langsung dihapus setelah dibaca) |
| `regenerate()` | Regenerate session ID (keamanan setelah login) |
| `destroy()` | Hancurkan sesi (logout) |
| `preventCache()` | Set header HTTP agar halaman tidak dicache browser |
| `checkUserLogin()` | Guard: redirect ke home jika user belum login |
| `checkAdminLogin()` | Guard: redirect ke home jika admin belum login |

#### `core/helper.php` — Global Helper Functions

Fungsi-fungsi global yang bisa dipanggil dari mana saja:

| Fungsi | Kegunaan |
|---|---|
| `sendMail($to, $subject, $body)` | Kirim email via SMTP menggunakan PHPMailer |
| `generateBookingCode(): string` | Generate kode booking unik format `BK-YYYYMMDD-XXXX` |
| `uploadFile($file, $targetDir)` | Validasi & pindahkan file upload (max 5MB, format jpg/jpeg/png/pdf) |
| `jsonResponse($data)` | Output JSON dan exit (untuk endpoint AJAX) |
| `format_indo_date($dateTime, $showTime)` | Format tanggal ke format Indonesia (e.g., "10 Jul 2026") |

---

### `app/controllers/` — Controller Layer

Layer yang menerima request HTTP, memproses logika bisnis, memanggil model, dan menentukan view mana yang ditampilkan.

#### `HomeController.php`
Sangat minimal. Hanya redirect atau menampilkan landing page.

#### `AuthController.php`
Menangani semua proses autentikasi:

| Method | Fungsi |
|---|---|
| `login()` | Tampilkan halaman login |
| `loginProcess()` | Proses form login — cek admin dulu, lalu user. Validasi: akun tidak ditemukan, diblokir, ditolak, menunggu, password salah |
| `logout()` | Destroy session & redirect login |
| `registerRole()` | Tampilkan halaman pilih role |
| `chooseRole()` | Proses pilihan role → redirect ke form register yang sesuai |
| `registerMahasiswa()` | GET: tampilkan form. POST: validasi + upload bukti KuBaca + simpan ke DB (status: Menunggu) |
| `registerDosen()` | GET: tampilkan form. POST: validasi + simpan (status: Disetujui langsung) |
| `registerTendik()` | GET: tampilkan form. POST: validasi + simpan (status: Disetujui langsung) |
| `fixRegistration()` | Tampilkan form upload ulang bukti untuk mahasiswa yang ditolak |
| `submitFixRegistration()` | Proses upload ulang, ganti status jadi Menunggu, logout user |
| `forgotPassword()` | Tampilkan form input email |
| `sendResetLink()` | Buat token reset, simpan ke DB, kirim email berisi link reset |
| `resetPassword()` | Tampilkan form ganti password (validasi token) |
| `updatePassword()` | Simpan password baru, tandai token sebagai used |

#### `UserController.php`
Halaman-halaman yang diakses user setelah login:

| Method | Fungsi |
|---|---|
| `home()` | Dashboard user: tampilkan daftar ruangan dengan status real-time |
| `ruangan()` | Katalog semua ruangan |
| `riwayat()` | Riwayat peminjaman milik user yang login |
| `viewProfile()` | Lihat profil |
| `editProfile()` | GET: form edit profil. POST: validasi & simpan perubahan |

#### `BookingController.php`
Controller terbesar dan paling kompleks (~1400 baris). Menangani seluruh siklus hidup booking:

**Flow Booking User:**
| Method | Fungsi |
|---|---|
| `step1($roomId)` | Tampilkan form pilih tanggal & jam untuk ruangan yang dipilih. Menampilkan interval waktu yang sudah dipesan user lain |
| `step2()` | Proses POST dari step1. Validasi tanggal, jam, cek bentrok jadwal → tampilkan form isi anggota |
| `store()` | Proses POST final. Validasi semua field, cek kapasitas, cek anggota 1x/hari, simpan booking dengan database transaction (anti race-condition). Return JSON response |

**Flow Edit Booking User:**
| Method | Fungsi |
|---|---|
| `editForm($bookingId)` | Tampilkan form edit (preload data lama), mirip step1 |
| `editStep2()` | Proses POST dari editForm, validasi, tampilkan form isi anggota |
| `update()` | Simpan perubahan booking |
| `cancel($bookingId)` | Batalkan booking. Hitung total batal hari ini, blokir akun jika >= 3x |

**Flow Booking Admin:**
| Method | Fungsi |
|---|---|
| `adminStep1($roomId)` | Form booking untuk admin (mirip step1 user) |
| `adminStep2()` | Proses jadwal + tampilkan form anggota versi admin |
| `adminStore()` | Simpan booking oleh admin (mengisi field `admin_id`, bukan `user_id`) |
| `adminEditForm($bookingId)` | Edit booking yang dibuat admin |
| `adminEditStep2()` | Proses edit jadwal oleh admin |
| `adminUpdate()` | Simpan perubahan booking admin |
| `adminCancel($bookingId)` | Batalkan booking yang dibuat admin |

**Validasi Booking (Private Methods):**
| Method | Aturan |
|---|---|
| `validateTanggalPeminjaman($tanggal)` | Tidak boleh tanggal lampau, tidak boleh Sabtu/Minggu |
| `validateJamPeminjaman($mulai, $selesai, $tanggal)` | Jam operasional 09:00–15:00, durasi maks 3 jam, tidak boleh jam istirahat (12:00–13:00) |

#### `AdminController.php`
Panel administrasi lengkap (~1050 baris):

| Method | Fungsi |
|---|---|
| `dashboard()` | Dashboard dengan statistik: user baru hari ini, perlu divalidasi, booking hari ini, ruangan aktif, total user. Tabel booking hari ini dengan filter & pagination |
| `dataPeminjaman()` | Tabel semua data booking dengan filter (tanggal, role, jurusan, prodi, keyword) + pagination + export Excel |
| `exportPeminjaman()` | Download laporan booking dalam format .xlsx |
| `dataFromAdminCreateBooking()` | Tabel booking yang dibuat khusus oleh admin |
| `updateStatus()` | Update status booking (Disetujui/Ditolak/Dibatalkan/Selesai) |
| `dataRuangan()` | Lihat semua ruangan beserta statistik booking & rating |
| `addRuangan()` | Form tambah ruangan |
| `storeRuangan()` | Simpan ruangan baru ke database |
| `editRuangan($roomId)` | Form edit ruangan |
| `updateRuangan()` | Simpan perubahan data ruangan |
| `deleteRuangan()` | Hapus ruangan (dan file gambarnya) |
| `feedbackRuangan($roomId)` | Lihat semua feedback untuk satu ruangan |
| `exportRuangan()` | Download laporan data ruangan dalam format .xlsx |
| `dataAkun()` | Kelola akun user: dua tabel terpisah (akun aktif vs. akun pending/ditolak/diblokir) |
| `updateUserStatus()` | Setujui atau tolak pendaftaran user. Penolakan wajib sertakan alasan |
| `exportAkun()` | Download laporan data akun user dalam format .xlsx |
| `deleteUser()` | Soft delete akun user (set `status_akun = 'Dihapus'`, isi `deleted_at`) |
| `unblockUser()` | Buka blokir akun user |

#### `FeedbackController.php`
| Method | Fungsi |
|---|---|
| `submit()` | Proses submit feedback dari user setelah booking selesai |

---

### `app/models/` — Model Layer

Setiap model extend class `Model` dari `core/model.php` dan berkomunikasi langsung dengan satu tabel database.

#### `user.php` — Model `user`
Tabel: `user`

Mengelola semua operasi data user: registrasi (3 role berbeda), login, update profil, update password, validasi NIM/email unik, blokir, soft delete, dan query kompleks dengan sorting + filter + pagination.

Key methods: `findById`, `findByNIMNIP`, `findByEmail`, `registerMahasiswa`, `registerDosen`, `registerTendik`, `updateStatus`, `blockUser`, `deleteById`, `usergetAllSortedPaginated`, `userregistgetAllSortedPaginated`

#### `admin.php` — Model `admin`
Tabel: `admin`

Sangat minimalis — hanya untuk operasi tabel admin.

Key methods: `loginAdmin`, `findById`, `findByEmailAdmin`

#### `booking.php` — Model `booking`
Tabel: `booking`

Model terbesar (~800 baris). Mengelola seluruh data peminjaman ruangan.

Key methods:
- `createBookingSafe($data)` — Insert booking dengan database transaction `BEGIN/COMMIT/ROLLBACK` + `SELECT ... FOR UPDATE` untuk mencegah race condition
- `hasOverlap(...)` — Cek apakah ada jadwal yang bentrok di ruangan & tanggal yang sama
- `memberAlreadyBooked($nimnip, $tanggal)` — Cek apakah NIM/NIP sudah booking di tanggal itu
- `markFinishedBookings()` — Auto-update status menjadi 'Selesai' untuk booking yang waktunya sudah lewat
- `cancelByUser(...)` — Set status 'Dibatalkan' + catat `waktu_cancel`
- `countCancellationsToday($userId)` — Hitung jumlah pembatalan hari ini (untuk auto-blokir)
- `getAllSortedPaginated(...)` — Query dengan filter + sorting + pagination untuk admin
- `getHistoryByUser($user_id)` — Riwayat booking milik user tertentu

#### `room.php` — Model `room`
Tabel: `room`

Mengelola data ruangan.

Key methods: `getAll`, `findById`, `getAllWithStats` (JOIN dengan booking & feedback untuk statistik), `create`, `update`, `deleteById`, `countActiveRooms`

#### `feedback.php` — Model `feedback`
Tabel: `feedback`

Mengelola feedback (rating puas/tidak puas + komentar) dari user setelah booking selesai.

Key methods: `getByRoom`, `findByBooking`, `create`, `puasPercent` (hitung % puas 0–100), `feedbackgetAllSortedPaginated`

#### `PasswordReset.php` — Model `password_resets`
Tabel: `password_resets`

Mengelola token reset password yang dikirim via email.

Key methods: `createToken`, `findValidToken` (cek token belum expired & belum dipakai), `markUsed`

---

### `app/views/` — View Layer

File PHP yang berisi HTML beserta sedikit logika tampilan (echo variabel, loop data, kondisi). Variabel dari controller diekstrak menjadi variabel lokal oleh `extract($data)` di `core/controller.php`.

**Struktur:** Dibagi per aktor/fitur:

| Subfolder | Isi |
|---|---|
| `home/` | Landing page untuk pengunjung yang belum login |
| `auth/` | Halaman login, register (3 varian role), fix registrasi, lupa & reset password |
| `user/` | Halaman untuk user yang sudah login (home, katalog ruangan, booking step 1 & 2, riwayat, feedback, profil) |
| `admin/` | Semua halaman panel admin |

**Pola Flash Message:** Setiap view menerima variabel `$flash` berisi array `['success' => '...', 'error' => '...']` untuk menampilkan notifikasi dari operasi sebelumnya.

---

### `public/` — Aset Publik

Satu-satunya folder yang seharusnya bisa diakses langsung via URL (selain `index.php`).

| File/Folder | Kegunaan |
|---|---|
| `captcha.php` | Script PHP yang menggenerate gambar CAPTCHA dan menyimpan kode ke session. Dipanggil sebagai `<img src="captcha.php">` |
| `assets/css/` | File CSS per halaman. Tidak ada CSS framework — semuanya vanilla CSS |
| `assets/image/` | Gambar statis (foto ruangan yang diupload admin) |

**CSS Files:**

| File | Untuk Halaman |
|---|---|
| `sylelogin.css` | Login |
| `styleregister.css` | Register (semua varian) |
| `stylehome.css` | Dashboard user |
| `styleruangan.css` | Katalog ruangan |
| `stylebooking1.css` | Booking Step 1 |
| `stylebooking2.css` | Booking Step 2 |
| `styleriwayat.css` | Riwayat peminjaman |
| `styleprofile.css` | Profil user |
| `styleforgotpassword.css` | Lupa/Reset password |
| `styleadmin.css` | Seluruh panel admin |

---

### `storage/` — File Upload

Folder untuk menyimpan file yang diupload oleh user. Tidak boleh diakses langsung via URL (tidak ada `.htaccess` yang mengizinkan).

| Subfolder | Isi |
|---|---|
| `uploads/bukti_aktivasi/` | File bukti aktivasi akun KuBaca yang diupload mahasiswa saat registrasi |
| `uploads/surat_peminjaman_ruangrapat/` | File surat peminjaman untuk ruang rapat (fitur ini tampaknya belum sepenuhnya diimplementasikan) |

---

### `vendor/` — Dependency Composer

Dikelola sepenuhnya oleh Composer. **Jangan edit manual.** Berisi:
- `phpmailer/phpmailer` — Library pengiriman email
- `phpoffice/phpspreadsheet` — Library export Excel
- `vlucas/phpdotenv` — Library baca file `.env`

---

## 5. Alur Request (Flow) Aplikasi

```
Browser → index.php
             │
             ├── Load Composer autoload
             ├── Load .env
             ├── Register spl_autoload
             ├── Load config/app.php
             ├── Load core/helper.php
             │
             └── $router->run()
                       │
                       ├── Baca $_GET['route'] → "Auth/login"
                       ├── Parse → Controller: "AuthController", Method: "login"
                       │
                       └── AuthController->login()
                                 │
                                 ├── Session::checkUserLogin() [guard]
                                 ├── $flash = getFlashMessages()
                                 │
                                 └── require 'app/views/auth/login_user.php'
                                           │
                                           └── Browser menerima HTML
```

---

## 6. Alur Fitur Utama

### Alur Registrasi & Login

```
[Pengunjung]
    │
    ▼
Landing Page (home/index)
    │
    ▼
Klik "Daftar" → register_pilihrole.php
    │
    ├── Pilih Mahasiswa
    │       └── Isi form + upload bukti KuBaca
    │               └── Status: MENUNGGU (perlu validasi admin)
    │
    ├── Pilih Dosen
    │       └── Isi form
    │               └── Status: DISETUJUI (langsung bisa login)
    │
    └── Pilih Tenaga Kependidikan
            └── Isi form
                    └── Status: DISETUJUI (langsung bisa login)

[Admin melihat akun MENUNGGU di Data Akun]
    │
    ├── SETUJUI → status: Disetujui → user bisa login
    └── TOLAK + alasan → status: Ditolak → user diarahkan upload ulang
```

### Alur Booking Ruangan (User)

```
[User Login]
    │
    ▼
Home / Ruangan (lihat daftar ruangan tersedia)
    │
    ▼ Klik "Booking Sekarang" pada ruangan pilihan
    │
STEP 1 — Pilih Tanggal & Jam (booking_step1.php)
    │   ◄ Validasi: bukan weekend, tidak lampau, jam 09:00-15:00,
    │              tidak jam istirahat, durasi maks 3 jam
    │
    ▼ Submit → BookingController::step2()
    │
STEP 2 — Isi Data Peminjam (booking_step2.php)
    │   ◄ Isi: nama PJ, NIM/NIP PJ, email PJ, NIM anggota
    │
    ▼ Submit (AJAX POST) → BookingController::store()
    │
    ├── Validasi semua field
    ├── Validasi NIM anggota ada di database
    ├── Cek kapasitas ruangan (min-max)
    ├── Cek anggota tidak boleh booking 2x di hari yang sama
    │
    ▼ Simpan ke DB (dengan database transaction + FOR UPDATE lock)
    │
    ▼ Return JSON { success: true/false, message: "..." }
    │
    └── Redirect ke halaman riwayat
```

### Alur Manajemen Admin

```
[Admin Login]
    │
    ▼
Dashboard (statistik real-time + booking hari ini)
    │
    ├── Data Peminjaman → filter, sort, export Excel
    │       └── Update Status Booking
    │
    ├── Data Ruangan → CRUD ruangan
    │       └── Lihat Feedback per Ruangan
    │
    ├── Data Akun → validasi akun Mahasiswa
    │       └── Setujui / Tolak / Blokir / Hapus
    │
    └── Buat Booking Sendiri (untuk tamu eksternal)
```

---

## 7. Database & Tabel

Nama database: **`pblperpustakaan`**

### Tabel `user`
Menyimpan data semua user (Mahasiswa, Dosen, Tenaga Kependidikan).

| Kolom | Keterangan |
|---|---|
| `user_id` | Primary Key |
| `nim_nip` | NIM (Mahasiswa) atau NIP (Dosen/Tendik), unik |
| `nama` | Nama lengkap |
| `email` | Email, unik |
| `password` | Password ter-hash (bcrypt via `password_hash`) |
| `no_hp` | Nomor HP |
| `role` | `Mahasiswa` / `Dosen` / `Tenaga Kependidikan` |
| `jurusan` | Untuk Mahasiswa & Dosen |
| `program_studi` | Untuk Mahasiswa |
| `unit` | Untuk Tenaga Kependidikan |
| `status_akun` | `Menunggu` / `Disetujui` / `Ditolak` / `Diblokir` / `Dihapus` |
| `bukti_aktivasi` | Path file bukti KuBaca (Mahasiswa) |
| `rejection_reason` | Alasan penolakan dari admin |
| `created_at` | Waktu registrasi |
| `deleted_at` | Waktu soft delete |

### Tabel `admin`
Menyimpan data akun admin.

| Kolom | Keterangan |
|---|---|
| `admin_id` | Primary Key |
| `username` | Username untuk login |
| `password` | Password ter-hash |
| `email` | Email admin |

### Tabel `room`
Data ruangan yang bisa dipinjam.

| Kolom | Keterangan |
|---|---|
| `room_id` | Primary Key |
| `nama_ruangan` | Nama ruangan (e.g., "Ruang A", "Ruang Rapat") |
| `kapasitas_min` | Kapasitas minimum peminjam |
| `kapasitas_max` | Kapasitas maksimum peminjam |
| `deskripsi` | Deskripsi fasilitas ruangan |
| `status` | `Tersedia` / `Tidak Tersedia` |
| `gambar_ruangan` | Path gambar (bisa URL eksternal atau path lokal) |

### Tabel `booking`
Data transaksi peminjaman ruangan.

| Kolom | Keterangan |
|---|---|
| `booking_id` | Primary Key |
| `kode_booking` | Kode unik format `BK-YYYYMMDD-XXXX` |
| `user_id` | FK ke `user` (null jika dibuat admin) |
| `admin_id` | FK ke `admin` (null jika dibuat user) |
| `room_id` | FK ke `room` |
| `tanggal` | Tanggal peminjaman |
| `jam_mulai` | Jam mulai |
| `jam_selesai` | Jam selesai |
| `jumlah_peminjam` | Total jumlah orang (PJ + anggota) |
| `nama_penanggung_jawab` | Nama PJ |
| `nimnip_penanggung_jawab` | NIM/NIP PJ |
| `email_penanggung_jawab` | Email PJ |
| `nimnip_peminjam` | Daftar NIM/NIP anggota, dipisahkan koma (`NIM1,NIM2,NIM3`) |
| `status_booking` | `Disetujui` / `Ditolak` / `Dibatalkan` / `Selesai` |
| `waktu_booking` | Timestamp saat booking dibuat |
| `waktu_cancel` | Timestamp saat booking dibatalkan |
| `created_at` | Timestamp insert DB |

### Tabel `feedback`
Feedback dan rating dari user setelah booking selesai.

| Kolom | Keterangan |
|---|---|
| `feedback_id` | Primary Key |
| `booking_id` | FK ke `booking` |
| `user_id` | FK ke `user` |
| `room_id` | FK ke `room` |
| `puas` | `1` (Puas) / `0` (Tidak Puas) |
| `komentar` | Komentar teks bebas |
| `tanggal_feedback` | Timestamp feedback diberikan |

### Tabel `password_resets`
Token satu kali pakai untuk reset password.

| Kolom | Keterangan |
|---|---|
| `id` | Primary Key |
| `user_id` | FK ke `user` |
| `token` | Token acak 64 karakter hex |
| `expired_at` | Batas waktu token (30 menit dari dibuat) |
| `used` | `0` belum dipakai / `1` sudah dipakai |

---

## 8. Sistem Routing

Aplikasi menggunakan **Query String routing** — semua request dikirim ke `index.php` dan dibedakan via parameter `?route=`:

```
http://localhost/pblperpustakaan/?route=Controller/method/param
```

**Tidak menggunakan `.htaccess` URL rewriting** — URL masih terlihat dengan `?route=`.

**Format routing:**
```
?route=Auth/login           → AuthController::login()
?route=User/home            → UserController::home()
?route=Booking/step1/5      → BookingController::step1(5)
?route=Admin/dashboard      → AdminController::dashboard()
?route=Admin/editRuangan/3  → AdminController::editRuangan(3)
```

---

## 9. Sistem Autentikasi & Sesi

Aplikasi memiliki **dua sesi terpisah** yang berjalan bersamaan:

| Sesi | Key Session | Diatur di |
|---|---|---|
| **User** | `user_id`, `nama`, `nim_nip`, `role`, `no_hp`, `email`, `jurusan`, `program_studi` | `AuthController::loginProcess()` |
| **Admin** | `admin_id`, `username` | `AuthController::loginProcess()` |

**Guard (proteksi halaman):**
- `Session::checkUserLogin()` — diletakkan di awal setiap method yang hanya boleh diakses user login. Redirect ke home jika tidak ada `user_id`.
- `Session::checkAdminLogin()` — diletakkan di awal setiap method admin. Redirect ke home jika tidak ada `admin_id`.

**Security measures:**
- Password di-hash dengan `password_hash($password, PASSWORD_DEFAULT)` (bcrypt)
- Session ID di-regenerate setelah login sukses (`Session::regenerate()`)
- Cookie session menggunakan flag `httponly: true` dan `samesite: Strict`
- Input form di-trim dan disanitasi sebelum diproses

---

## 10. Fitur-Fitur Aplikasi

### Untuk User

| Fitur | Deskripsi |
|---|---|
| **Registrasi multi-role** | Daftar sebagai Mahasiswa (perlu validasi), Dosen, atau Tendik |
| **Login terpusat** | Satu form untuk semua role (user & admin dibedakan otomatis) |
| **Lihat ruangan** | Katalog ruangan lengkap dengan info kapasitas dan status |
| **Booking 2-step** | Step 1: pilih jadwal. Step 2: isi data peminjam |
| **Anti-bentrok jadwal** | Real-time cek ketersediaan jadwal saat booking |
| **Edit booking** | Ubah jadwal dan anggota booking yang masih aktif |
| **Batalkan booking** | Dengan catatan: 3x batal dalam 1 hari → akun diblokir otomatis |
| **Riwayat peminjaman** | Lihat semua booking dengan statusnya |
| **Feedback & rating** | Beri feedback (Puas/Tidak Puas + komentar) setelah booking selesai |
| **Edit profil** | Ubah nama, NIM/NIP, no HP, email |
| **Reset password** | Via link yang dikirim ke email |

### Untuk Admin

| Fitur | Deskripsi |
|---|---|
| **Dashboard statistik** | Statistik real-time: user baru, perlu validasi, booking hari ini, ruangan aktif, total user |
| **Kelola akun** | Validasi (setujui/tolak), blokir, hapus (soft delete) akun user |
| **Kelola ruangan** | Tambah, edit, hapus ruangan. Upload gambar atau URL manual |
| **Kelola booking** | Lihat, filter, sort semua data booking. Update status manual |
| **Buat booking** | Admin dapat membuat booking atas nama pihak luar/tamu |
| **Lihat feedback** | Lihat semua feedback per ruangan dengan summary statistik |
| **Export Excel** | Export laporan Peminjaman, Ruangan, dan Akun User ke file .xlsx |

---

## 11. Catatan Teknis & Bug yang Diketahui

> Ini adalah catatan dari pembacaan kode, bukan berdasarkan testing langsung.

### ⚠️ Inkonsistensi Arsitektur

1. **Sebagian controller tidak extend `Controller`** — `AuthController`, `BookingController` tidak extend base `Controller` dan menggunakan `require` langsung untuk load view. Ini berbeda dengan pola yang dituju oleh `core/controller.php`.

2. **Penamaan file model lowercase tapi class PascalCase** — File `booking.php` berisi class `Booking`. Autoloader di `index.php` mencari file dengan `strtolower($class)`, jadi ini berfungsi untuk model tapi tidak konsisten dengan controller.

### 🐛 Bug yang Teridentifikasi

1. **`format_indo_date()` selalu tampilkan waktu** — Di `core/helper.php` baris 95:
   ```php
   if ($showTime = True) {  // Assignment, bukan comparison!
   ```
   Harusnya `if ($showTime === true)`. Akibatnya, waktu selalu ditampilkan meski parameter `$showTime` adalah `false`.

2. **CAPTCHA tidak aktif di register Mahasiswa** — Validasi CAPTCHA di `registerMahasiswa()` dikomentari, sementara di `registerDosen()` dan `registerTendik()` validasinya ada tapi menggunakan `$$_POST['captcha_input']` (variable variable yang salah).

3. **`$booking` tidak terdefinisi di `step2()`** — Di `BookingController::step2()` baris 120:
   ```php
   $initialMembers = $bookingModel->splitMembers($booking['nimnip_peminjam'] ?? '');
   ```
   Variabel `$booking` tidak pernah diisi di konteks pembuatan booking baru. Ini tidak error hanya karena ada fallback `?? ''`.

4. **`countBookingToday()` parameter tidak konsisten** — Method ini tidak menerima parameter `$date` tapi mencoba pakai `$date ?? date(...)`. Harusnya parameter wajib.

5. **`configapp()['cancel_limit_per_day']` tidak dipakai** — Nilai `2` di konfigurasi tidak digunakan; kode di `BookingController::cancel()` hardcode memeriksa `>= 3`.

### 📝 Hal yang Perlu Diperhatikan Saat Merombak

- **Penamaan** — Nama aplikasi di beberapa tempat berbeda: "RUDY", "LibRoomPNJ", "LibRoom PNJ". Perlu disatukan.
- **CSS per halaman** — Tidak ada CSS global. Setiap halaman punya file CSS sendiri yang mengulang banyak deklarasi.
- **Tidak ada middleware** — Guard login hanya lewat pemanggilan manual `Session::checkUserLogin()` di setiap method. Tidak ada sistem middleware terpusat.
- **Tidak ada templating** — Tidak ada layout/template system. Header dan footer diulang di setiap file view.

---

*Dokumentasi ini dibuat secara otomatis berdasarkan pembacaan seluruh source code project pada 10 Juli 2026.*
