    <?php
/**
 * ==============================================
 * KONFIGURASI GLOBAL APLIKASI RUDY
 * ==============================================
 * File ini berfungsi sebagai pusat pengaturan aplikasi.
 * Semua bagian (controller, model, helper, dsb) bisa membaca nilai-nilai di sini
 * menggunakan fungsi app_config().
 */

return [

    # ============================================
    # IDENTITAS APLIKASI
    # ============================================
    'app_name'     => 'RUDY - Ruang Study',
    'app_tagline'  => 'Sistem Peminjaman Ruangan Perpustakaan',
    'version'      => '1.0.0',

    # ============================================
    # PENGATURAN WAKTU & BASE URL
    # ============================================
    # Ganti base_url jika dipindah ke hosting (contoh: https://rudy.perpustakaanpnj.ac.id)
    'base_url'     => 'http://localhost/pblperpustakaan/public',
    'timezone'     => 'Asia/Jakarta',

    # ============================================
    # DATABASE (MySQL)
    # ============================================
    # Digunakan oleh config/database.php untuk koneksi PDO
    'database' => [
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname'   => 'rudy_db',
        'charset'  => 'utf8mb4'
    ],

    # ============================================
    # EMAIL / PHPMailer
    # ============================================
    # Gunakan App Password dari Gmail (bukan password biasa!)
    'mail' => [
        'host'       => 'smtp.gmail.com',
        'username'   => 'emailkamu@gmail.com',      # Ganti dengan email pengirim
        'password'   => 'GMAIL_APP_PASSWORD_16',    # Ganti dengan app password
        'port'       => 587,
        'encryption' => 'tls',
        'from_email' => 'emailkamu@gmail.com',      # Ganti dengan email pengirim
        'from_name'  => 'RUDY System - Notifikasi',
    ],

    # ============================================
    # PATH UNTUK FILE UPLOAD
    # ============================================
    # Semua path absolut menuju folder upload
    'upload_paths' => [
        'bukti_aktivasi'   => __DIR__ . '/../storage/uploads/bukti_aktivasi/',
        'surat_peminjaman' => __DIR__ . '/../storage/uploads/surat_peminjaman/',
    ],

    # ============================================
    # PENGATURAN SESSION & KEAMANAN
    # ============================================
    # session_lifetime: waktu aktif login user/admin (dalam detik)
    'session_lifetime' => 3600, # 1 jam

    # limit pembatalan booking (untuk pemblokiran akun otomatis)
    'cancel_limit_per_day' => 2,

    # ============================================
    # KONFIGURASI TAMBAHAN (OPSIONAL)
    # ============================================
    'developer' => [
        'maintainer' => 'Tim Back-End RUDY',
        'github_repo' => 'https://github.com/tim-rudy',
    ],
];
