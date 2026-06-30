<?php
include "koneksi.php";

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_ruangan = $_POST['nama_ruangan'];
    $kapasitas = $_POST['kapasitas'];
    $lokasi = $_POST['lokasi'];

    $stmt = mysqli_prepare($conn, "UPDATE ruangan SET nama_ruangan=?, kapasitas=?, lokasi=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sisi", $nama_ruangan, $kapasitas, $lokasi, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: index.php");
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM ruangan WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Ruangan</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="navbar">
    <span class="brand">Reservasi Ruang Rapat</span>
    <a href="index.php">Daftar Ruangan</a>
    <a href="reservasi.php">Daftar Reservasi</a>
</div>

<div class="container">
    <h2>Edit Ruangan</h2>

    <form method="POST">
        <label>Nama Ruangan</label>
        <input type="text" name="nama_ruangan" value="<?= $data['nama_ruangan'] ?>" required>

        <label>Kapasitas (orang)</label>
        <input type="number" name="kapasitas" value="<?= $data['kapasitas'] ?>" required>

        <label>Lokasi</label>
        <input type="text" name="lokasi" value="<?= $data['lokasi'] ?>" required>

        <button type="submit">Update</button>
    </form>
</div>

</body>
</html>
