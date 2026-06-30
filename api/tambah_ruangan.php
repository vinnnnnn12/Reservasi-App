<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_ruangan = $_POST['nama_ruangan'];
    $kapasitas = $_POST['kapasitas'];
    $lokasi = $_POST['lokasi'];

    $stmt = mysqli_prepare($conn, "INSERT INTO ruangan (nama_ruangan, kapasitas, lokasi) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sis", $nama_ruangan, $kapasitas, $lokasi);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Ruangan</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="navbar">
    <span class="brand">Reservasi Ruang Rapat</span>
    <a href="index.php">Daftar Ruangan</a>
    <a href="reservasi.php">Daftar Reservasi</a>
</div>

<div class="container">
    <h2>Tambah Ruangan</h2>

    <form method="POST">
        <label>Nama Ruangan</label>
        <input type="text" name="nama_ruangan" required>

        <label>Kapasitas (orang)</label>
        <input type="number" name="kapasitas" required>

        <label>Lokasi</label>
        <input type="text" name="lokasi" required>

        <button type="submit">Simpan</button>
    </form>
</div>

</body>
</html>
