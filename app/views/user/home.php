<?php
session_start();
require_once '../../../config/database.php';
$user_id = $_SESSION['user_id'] ?? 2;
$query_user = "SELECT nama, email FROM user WHERE user_id = '$user_id'";
$result_user = mysqli_query($connection, $query_user);
$user_data = mysqli_fetch_assoc($result_user);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rudy Ruang Study</title>
  <link rel="stylesheet" href="../../../public/assets/css/stylehome.css">
</head>

<body>
<header class="navbar">
  <div class="logo">
    <img src="../../../public/assets/image/LogoPNJ.png" alt="Logo PNJ" height="40">
    <img src="../../../public/assets/image/LogoRudy.png" alt="Logo Rudy" height="40">
  </div>
  <nav class="nav-menu">
    <a href="home.php" class="active">Beranda</a>
    <a href="ruangan.php">Ruangan</a>
    <a href="riwayat.php">Riwayat</a>
  </nav>
  <div class="profile">
        <img src="../../../public/assets/image/userlogo.png" alt="User">
        <div class="user-name">
            <p><?= htmlspecialchars($user_data['nama']) ?></p>
        </div>
    </div>
</header>

<main>
  <section class="hero">
    <div class="hero-text">
      <p class="intro">Selamat Datang di <span>Rudy</span></p>
      <h1>Ruang Study untuk Semua Mahasiswa!</h1>
      <p class="description">
        Atur jadwal belajar dan diskusi dengan mudah melalui Rudy. Temukan ruangan study nyaman,
        praktis, dan siap digunakan kapan pun.
      </p>
      <div class="btn-group">
        <a href="ruangan.php" class="btn primary">Lihat daftar Ruangan</a>
        <a href="#" id="lihat-cara-booking"class="btn secondary">Lihat Cara Booking</a>
      </div>
    </div>
    <div class="hero-visual">
      <img src="../../../public/assets/image/LogoRudy.png" alt="Logo Rudy">
    </div>
  </section>


  <section class="fitur">
    <div class="fitur-row">
      <div class="fitur-item">
        <img src="../../../public/assets/image/cepat.png" alt="Cepat">
        <h3>Cepat & Praktis</h3>
        <p>Pemesanan ruangan hanya beberapa klik.</p>
      </div>
      <div class="fitur-item">
        <img src="../../../public/assets/image/terintegrasi.png" alt="Terintegrasi">
        <h3>Terintegrasi Perpustakaan</h3>
        <p>Data ruangan langsung dari perpustakaan.</p>
      </div>
      <div class="fitur-item">
        <img src="../../../public/assets/image/transparan.png" alt="Fleksibel">
        <h3>Fleksibel & Transparan</h3>
        <p>Atur jadwal sesuai kebutuhanmu.</p>
      </div>
      <div class="fitur-item">
        <img src="../../../public/assets/image/mudahdigunakan.png" alt="Mudah">
        <h3>Mudah Digunakan</h3>
        <p>Antarmuka ramah mahasiswa.</p>
      </div>
    </div>
  </section>

  <section class="ruangan-section">
  <div class="rooms-container">

    <?php if(!empty($data['rooms'])): ?>
      <?php foreach($data['rooms'] as $room): ?>

        <article class="card">
            <img src="../../../public/assets/image/contohruangan.png" 
                 alt="<?= $room['nama_ruangan']; ?>">

            <div class="card-body">
              <h3><?= $room['nama_ruangan']; ?></h3>
              <p>Kapasitas: <?= $room['kapasitas_min']; ?> - <?= $room['kapasitas_max']; ?> orang</p>
              <p>Status: 
                <span class="status <?= strtolower(str_replace(' ', '_', $room['status'])); ?>">
                    <?= $room['status']; ?>
                </span>
              </p>

              <a href="booking_step1.php?room_id=<?= $room['room_id']; ?>">
                <button type="button" class="btn primary block booking-trigger">Booking sekarang</button>
              </a>
            </div>
        </article>

      <?php endforeach; ?>
    <?php else: ?>

      <p>Tidak ada data ruangan tersedia.</p>

    <?php endif; ?>

  </div>
</section>

  <section id="cara-booking" class="steps">
    <h2>Cara Menggunakan Rudy</h2>
    <ol>
      <li>Login ke akunmu.</li>
      <li>Pilih ruangan yang ingin kamu pinjam.</li>
      <li>Isi formulir peminjaman.</li>
      <li>Dapatkan kode booking.</li>
      <li>Tunjukkan kode booking ke admin perpustakaan.</li>
      <li>Ruangan siap dipakai.</li>
    </ol>
  </section>
</main>

<footer class="footer">
  <div class="footer-brand">
    <img src="../../../public/assets/image/LogoRudy.png" alt="Rudy">
    <p>Rudi Ruangan Studi adalah platform peminjaman ruangan perpustakaan yang membantu mahasiswa dan staf mengatur penggunaan ruang belajar
dengan mudah dan efisien.</p>
  </div>
  <div class="footer-nav">
    <div>
      <h4>Navigasi</h4>
      <a href="#">Beranda</a>
      <a href="#">Ruangan</a>
      <a id="navigasipanduan" href="#">Panduan</a>
      <a href="#">Masuk</a>
    </div>
    <div>
      <h4>Bantuan</h4>  
      <a href="#">FAQ</a>
      <a id="bantuanpanduan" href="#">Panduan</a>
      <a href="#">Alur</a>
      <a href="#">Akun</a>
    </div>
    <div>
      <h4>Kontak</h4>
      <a href="mailto:PerpusPNJ@email.com">PerpusPNJ@email.com</a>
      <a href="tel:0822123456780">0822123456780</a>
      <p>Kampus PNJ, Depok</p>
    </div>
  </div>
</footer>
</body>
</html>
