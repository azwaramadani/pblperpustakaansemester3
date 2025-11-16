<?php
// Dummy data (tidak terhubung ke database)
$nama_ruangan = "Lentera Edukasi";
$kapasitas = "2 - 4 orang";
$deskripsi = "Ruangan khusus bimbingan dan konseling dengan suasana tenang 
dan privat. Cocok untuk sesi diskusi, pendampingan akademik, atau 
konsultasi pribadi.";
$rating = "90% Orang Puas";
$gambar = "../../../public/assets/image/contohruangan.png";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Waktu Peminjaman</title>
    <link rel="stylesheet" href="../../../public/assets/css/stylebooking1.css">
</head>
<body>

<header class="navbar">
    <!-- Kiri -->
    <div class="nav-left">
    <img src="../../../public/assets/image/LogoPNJ.png" alt="Logo PNJ" height="40">
    <img src="../../../public/assets/image/LogoRudy.png" alt="Logo Rudy" height="40">
    </div>

    <!-- Tengah -->
    <nav class="nav-center">
        <ul class="nav-menu">
            <li><a href="home.php">Beranda</a></li>
            <li><a href="ruangan.php">Ruangan</a></li>
            <li><a href="riwayat.php">Riwayat</a></li>
        </ul>
    </nav>

    <!-- Kanan -->
    <div class="profile">
        <span>Hanif</span>
        <img src="profile.png" class="profile-img" alt="Profil">
    </div>
</header>


<!-- KONTEN -->
<div class="content">

    <!-- CARD RUANGAN -->
    <div class="room-card">
        <img src="../../../public/assets/image/contohruangan.png" class="room-img">

        <div class="room-info">
            <h2><?= $nama_ruangan ?></h2>
            <p><?= $deskripsi ?></p>
            <p><strong>Kapasitas:</strong> <?= $kapasitas ?></p>
        </div>
    </div>

    <div class="rating-box"><?= $rating ?></div>

    <!-- FORM PILIH WAKTU -->
    <div class="form-container">
        <h2>Pilih tanggal dan jam peminjaman</h2>

        <form action="data_peminjaman.php" method="POST">
            <div class="input-tanggal">
            <label>Pilih tanggal</label>
            <input type="date" name="tanggal" required>
            </div>
            <div class="time-row">
                <div>
                    <label>Pilih jam mulai</label>
                    <input type="time" name="mulai" required>
                </div>
                
                <div class="sampai">
                <p> Sampai </p>
                </div>

                <div>
                    <label>Pilih jam selesai</label>
                    <input type="time" name="selesai" required>
                </div>
            </div>

            <div class="btn-row">
                <a href="ruangan.php" class="btn-back">Kembali</a>
                <button type="submit" class="btn-next">Lanjut</button>
            </div>

        </form>
    </div>
</div>

<footer>
    <div class="footer-container">

        <div class="footer-brand">
            <img src="../../../public/assets/image/LogoRudy.png" alt="Logo Rudy">
            <p>Rudi Ruangan Studi adalah platform peminjaman ruangan perpustakaan yang membantu mahasiswa dan staf mengatur penggunaan ruang belajar
dengan mudah dan efisien.</p>
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

</body>
</html>
