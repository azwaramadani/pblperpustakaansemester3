<?php
session_start();

/**
 * booking_lanjutan.php
 * Menampilkan form lanjutan sesuai UI, menerima data ruangan dari session / GET / POST
 *
 * Asumsi: sebelum redirect ke halaman ini, page booking pertama menyimpan
 * $_SESSION['room_id'], $_SESSION['tanggal'], $_SESSION['jam_mulai'], $_SESSION['jam_selesai']
 *
 * Jika tidak, halaman ini akan cek $_GET / $_POST sebagai fallback.
 */

// fallback ambil dari GET/POST kalau session belum ada
$room_id    = $_SESSION['room_id']    ?? ($_GET['room_id'] ?? ($_POST['room_id'] ?? 1));
$tanggal    = $_SESSION['tanggal']    ?? ($_GET['tanggal'] ?? ($_POST['tanggal'] ?? ''));
$jam_mulai  = $_SESSION['jam_mulai']  ?? ($_GET['jam_mulai'] ?? ($_POST['jam_mulai'] ?? ''));
$jam_selesai= $_SESSION['jam_selesai']?? ($_GET['jam_selesai'] ?? ($_POST['jam_selesai'] ?? ''));

// Data ruangan — idealnya ambil dari DB berdasarkan $room_id.
// Untuk demo hardcode (tapi kamu bisa replace dengan query SELECT room)
$nama_ruangan = "Lentera Edukasi";
$kapasitas    = "2 - 4 orang";
$deskripsi    = "Ruangan khusus bimbingan dan konseling dengan suasana tenang dan privat. Cocok untuk sesi diskusi, pendampingan akademik, atau konsultasi pribadi.";
$rating       = "90% Orang Puas";
$gambar       = "../../../public/assets/image/contohruangan.png"; // sesuaikan path

// untuk menampilkan nilai tanggal/jam (opsional) — sesuai UI kamu tidak perlu menampilkannya
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lengkapi Data Peminjaman</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../../../public/assets/css/stylebooking2.css">
</head>
<body>

<header class="navbar">
    <div class="nav-left">
        <img src="../../../public/assets/image/LogoPNJ.png" class="logo-pnj" alt="Logo PNJ">
        <img src="../../../public/assets/image/LogoRudy.png" class="logo-rudy" alt="Logo Rudy">
    </div>

    <nav class="nav-center">
        <ul class="nav-menu">
            <li><a href="<?= htmlspecialchars('/home.php') ?>">Beranda</a></li>
            <li><a href="<?= htmlspecialchars('/ruangan.php') ?>">Ruangan</a></li>
            <li><a href="<?= htmlspecialchars('/riwayat.php') ?>">Riwayat</a></li>
        </ul>
    </nav>

    <div class="profile">
        <span>Hanif</span>
        <img src="../../../public/assets/image/profile.png" class="profile-img" alt="Profil">
    </div>
</header>

<main class="content">

    <!-- LEFT: card ruangan -->
    <section class="room-card">
        <img src="<?= htmlspecialchars($gambar) ?>" alt="Gambar Ruangan" class="room-img">
        <div class="room-info">
            <h3 class="room-title"><?= htmlspecialchars($nama_ruangan) ?></h3>
            <p class="room-desc"><?= htmlspecialchars($deskripsi) ?></p>
            <p class="room-cap"><strong>Kapasitas :</strong> <?= htmlspecialchars($kapasitas) ?></p>
        </div>
    </section>

    <!-- rating box -->
    <div class="rating-box"><?= htmlspecialchars($rating) ?></div>

    <!-- FORM -->
    <section class="form-container">
        <h2 class="form-title">Lengkapi Data Peminjaman</h2>

        <form action="proses_booking.php" method="POST" id="bookingForm">
            <!-- kirim data penting sebagai hidden supaya proses_booking menerima -->
            <input type="hidden" name="room_id" value="<?= htmlspecialchars($room_id) ?>">
            <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
            <input type="hidden" name="jam_mulai" value="<?= htmlspecialchars($jam_mulai) ?>">
            <input type="hidden" name="jam_selesai" value="<?= htmlspecialchars($jam_selesai) ?>">

            <label class="label">Jumlah Mahasiswa</label>
            <div class="jumlah-box">
                <button type="button" class="qty-btn" id="minusBtn">-</button>
                <input type="number" name="jumlah_peminjam" id="jumlahInput" value="1" min="1" max="50" required>
                <button type="button" class="qty-btn" id="plusBtn">+</button>
            </div>

            <label class="label">Nama penanggung jawab</label>
            <input type="text" name="nama_penanggung_jawab" required placeholder="Nama lengkap">

            <label class="label">NIM penanggung jawab</label>
            <input type="text" name="nimnip_penanggung_jawab" required placeholder="NIM/NIP">

            <label class="label">Email penanggung jawab</label>
            <input type="email" name="email_penanggung_jawab" required placeholder="email@domain.com">

            <label class="label">NIM peminjam ruangan</label>
            <input type="text" name="nimnip_peminjam" required placeholder="NIM/NIP peminjam">

            <!-- tombol -->
            <div class="btn-row">
                <a href="ruangan.php" class="btn-back">Kembali</a>
                <button type="submit" class="btn-next">Simpan</button>
            </div>
        </form>
    </section>

</main>

<footer>
    <div class="footer-container">
        <div class="footer-brand">
            <img src="../../../public/assets/image/LogoRudy.png" alt="LogoRudy">
            <p>Rudi Ruangan Studi adalah platform peminjaman ruangan perpustakaan yang membantu mahasiswa dan staf mengatur penggunaan ruang belajar dengan mudah dan efisien.</p>
        </div>

        <div class="footer-section">
            <h4>Navigasi</h4>
            <a href="#">Beranda</a>
            <a href="#">Daftar Ruangan</a>
            <a href="#">Panduan</a>
            <a href="#">Masuk</a>
        </div>

        <div class="footer-section">
            <h4>Bantuan</h4>
            <a href="#">FAQ</a>
            <a href="#">Aturan Ruangan</a>
        </div>

        <div class="footer-section">
            <h4>Kontak</h4>
            <p>PerpusPNJ@gmail.com</p>
            <p>082123456780</p>
            <p>Kampus PNJ, Depok</p>
        </div>
    </div>
</footer>

<script>
    // plus minus jumlah mahasiswa
    const minusBtn = document.getElementById('minusBtn');
    const plusBtn = document.getElementById('plusBtn');
    const jumlahInput = document.getElementById('jumlahInput');

    minusBtn && minusBtn.addEventListener('click', () => {
        let v = parseInt(jumlahInput.value || 1, 10);
        if (v > parseInt(jumlahInput.min)) jumlahInput.value = v - 1;
    });

    plusBtn && plusBtn.addEventListener('click', () => {
        let v = parseInt(jumlahInput.value || 1, 10);
        if (v < parseInt(jumlahInput.max)) jumlahInput.value = v + 1;
    });
</script>

</body>
</html>
