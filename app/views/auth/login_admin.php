<?php
session_start();

// Jika nanti kamu sudah punya database → login akan dicek di sini.
// Untuk sekarang, aku buat pengecekan password dummy dulu sebagai contoh.

if (isset($_POST["submit"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Contoh cek awal (sementara, sebelum ada DB)
    if ($nim == "" || $password == "") {
        echo "<script>alert('username dan Password wajib diisi!');</script>";
    } else {
        // Nanti di sini cek ke database
        echo "<script>alert('Login berhasil (sementara)!'); window.location='dashboard.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - RUDY Ruang Study</title>
    <link rel="stylesheet" href="../../../public/assets/css/styleregister.css">
</head>
<body>

<div class="auth-wrapper">
    <section class="auth-card image-panel">
        <div class="image-overlay">
            <img src="../../../public/assets/image/LogoRudy.png" alt="Logo Rudy" class="panel-logo">
        </div>
    </section>

    <div class="right-section">
        <h2>Masuk Sebagai Admin</h2>

        <label>Username</label>
        <input type="text" placeholder="Masukkan Username anda" autocomplete="off">

        <label>Password</label>
        <input type="password" placeholder="Masukkan Password" autocomplete="new-password">

        <button class="btn-login">Masuk</button>

    </div>

</div>

</body>
</html>
