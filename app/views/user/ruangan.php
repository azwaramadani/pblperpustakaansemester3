<?php
$user = $data['user'];
$ruangan = $data['rooms'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Ruangan | Rudy Ruang Study</title>

    <link rel="stylesheet" href="<?= app_config()['base_url'] ?>/assets/css/styleruangan.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<header class="navbar">
  <div class="logo">
    <img src="<?= app_config()['base_url'] ?>/assets/image/LogoPNJ.png" height="40">
    <img src="<?= app_config()['base_url'] ?>/assets/image/LogoRudy.png" height="40">
  </div>

  <nav class="nav-menu">
    <a href="?route=User/home">Beranda</a>
    <a href="?route=User/ruangan" class="active">Ruangan</a>
    <a href="?route=User/riwayat">Riwayat</a>
  </nav>

  <div class="profile">
        <img src="<?= app_config()['base_url'] ?>/assets/image/userlogo.png" alt="User">
        <div class="user-name">
            <p><?= htmlspecialchars($user['nama']) ?></p>
        </div>
    </div>
</header>

<main>
    <section class="title-section">
        <h2 class="title">Daftar Ruangan</h2>
        <p class="subtitle">Pilih ruangan untuk kegiatan belajarmu.</p>
    </section>

    <div class="room-container">

        <?php if (empty($ruangan)): ?>
            <p class="no-room">Tidak ada ruangan tersedia saat ini.</p>
        <?php else: ?>

            <?php foreach ($ruangan as $r): ?>
                <div class="room-card">

                    <img src="<?= app_config()['base_url'] ?>/assets/image/contohruangan.png"
                         alt="<?= htmlspecialchars($r['nama_ruangan']) ?>" class="room-img">

                    <div class="room-info">
                        <h3><?= htmlspecialchars($r['nama_ruangan']) ?></h3>
                        <p>Kapasitas: <?= $r['kapasitas_min'] ?> - <?= $r['kapasitas_max'] ?> orang</p>
                        <p>Status: <span class="status tersedia"><?= $r['status'] ?></span></p>
                    </div>

                    <a href="?route=Booking/step1/<?= $r['room_id'] ?>" class="btn-book">Booking sekarang</a>

                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</main>

</body>
</html>
