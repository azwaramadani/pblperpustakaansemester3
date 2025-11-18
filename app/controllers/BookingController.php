<?php
// proses_booking.php
session_start();

// pastikan method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /app/views/booking/booking_lanjutan.php');
    exit;
}

// ambil user dari session
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    $_SESSION['flash_error'] = 'Silakan login terlebih dahulu.';
    header('Location: /app/views/auth/login.php');
    exit;
}

// ambil input
$room_id = intval($_POST['room_id'] ?? 0);
$tanggal = $_POST['tanggal'] ?? null;
$jam_mulai = $_POST['jam_mulai'] ?? null;
$jam_selesai = $_POST['jam_selesai'] ?? null;
$jumlah_peminjam = intval($_POST['jumlah_peminjam'] ?? 0);
$nama_pj = trim($_POST['nama_penanggung_jawab'] ?? '');
$nimnip_pj = trim($_POST['nimnip_penanggung_jawab'] ?? '');
$email_pj = trim($_POST['email_penanggung_jawab'] ?? '');
$nimnip_peminjam = trim($_POST['nimnip_peminjam'] ?? '');

// validasi sederhana
$errs = [];
if (!$room_id) $errs[] = 'Room ID tidak valid.';
if (!$tanggal) $errs[] = 'Tanggal tidak boleh kosong.';
if (!$jam_mulai || !$jam_selesai) $errs[] = 'Jam mulai / selesai harus diisi.';
if (!$nama_pj || !$nimnip_pj || !$email_pj || !$nimnip_peminjam) $errs[] = 'Isi semua field wajib.';

if (count($errs)) {
    $_SESSION['flash_error'] = implode(' ', $errs);
    header('Location: /app/views/booking/booking_lanjutan.php');
    exit;
}

/* koneksi DB - sesuaikan path dengan file koneksi kalian */
require_once __DIR__ . '/../../config/koneksi.php'; // contoh path
// ekspektasi file koneksi membuat $koneksi atau $conn
if (isset($koneksi) && $koneksi instanceof mysqli) {
    $db = $koneksi;
} elseif (isset($conn) && $conn instanceof mysqli) {
    $db = $conn;
} else {
    // fallback - ubah credentials bila perlu
    $db = new mysqli('localhost','root','','pbl_perpustakaan');
    if ($db->connect_errno) {
        $_SESSION['flash_error'] = 'Gagal koneksi DB: ' . $db->connect_error;
        header('Location: /app/views/booking/booking_lanjutan.php');
        exit;
    }
}

// Buat kode_booking unik
$kode_booking = 'BK' . date('ymd') . strtoupper(substr(uniqid(), -6));

// Cek overlap booking (opsional tapi disarankan)
// Pastikan tidak ada booking lain di room_id dengan status Menunggu/Disetujui yang overlap
$overlap_sql = "SELECT COUNT(*) as cnt FROM booking WHERE room_id = ? AND status_booking IN ('Menunggu','Disetujui') AND tanggal = ? AND NOT (jam_selesai <= ? OR jam_mulai >= ?)";
$st = $db->prepare($overlap_sql);
if ($st) {
    $st->bind_param('isss', $room_id, $tanggal, $jam_mulai, $jam_selesai);
    $st->execute();
    $res = $st->get_result()->fetch_assoc();
    $st->close();
    if ($res && $res['cnt'] > 0) {
        $_SESSION['flash_error'] = 'Waktu peminjaman bentrok dengan booking lain.';
        header('Location: /app/views/booking/booking_lanjutan.php');
        exit;
    }
}

// Insert booking
$insert_sql = "INSERT INTO booking
    (user_id, room_id, tanggal, jam_mulai, jam_selesai, jumlah_peminjam, nama_penanggung_jawab, nimnip_penanggung_jawab, email_penanggung_jawab, nimnip_peminjam, surat_peminjaman_ruang_rapat, kode_booking, status_booking)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $db->prepare($insert_sql);
if (!$stmt) {
    $_SESSION['flash_error'] = 'Gagal prepare statement: ' . $db->error;
    header('Location: /app/views/booking/booking_lanjutan.php');
    exit;
}

// nilai untuk surat upload diset null untuk saat ini
$surat = null;
$status_booking = 'Menunggu';

// bind param -> perhatikan tipe sesuai struktur
// i = int, s = string
$stmt->bind_param(
    'iisssisssssss',
    $user_id,
    $room_id,
    $tanggal,
    $jam_mulai,
    $jam_selesai,
    $jumlah_peminjam,
    $nama_pj,
    $nimnip_pj,
    $email_pj,
    $nimnip_peminjam,
    $surat,
    $kode_booking,
    $status_booking
);

$ok = $stmt->execute();
if (!$ok) {
    $_SESSION['flash_error'] = 'Gagal menyimpan booking: ' . $stmt->error;
    $stmt->close();
    header('Location: /app/views/booking/booking_lanjutan.php');
    exit;
}
$stmt->close();

// sukses -> redirect ke riwayat dengan pesan
$_SESSION['flash_success'] = 'Booking berhasil. Kode: ' . $kode_booking;
header('Location: /riwayat.php');
exit;
