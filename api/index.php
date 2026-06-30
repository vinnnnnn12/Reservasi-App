<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Ruangan</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="navbar">
    <span class="brand">Reservasi Ruang Rapat</span>
    <a href="index.php">Daftar Ruangan</a>
    <a href="reservasi.php">Daftar Reservasi</a>
</div>

<div class="container">
    <h2>Daftar Ruangan</h2>
    <a href="tambah_ruangan.php" class="btn btn-tambah">+ Tambah Ruangan</a>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Ruangan</th>
            <th>Kapasitas</th>
            <th>Lokasi</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        $query = mysqli_query($conn, "SELECT * FROM ruangan ORDER BY nama_ruangan ASC");
        while ($row = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nama_ruangan'] ?></td>
            <td><?= $row['kapasitas'] ?> orang</td>
            <td><?= $row['lokasi'] ?></td>
            <td>
                <a href="edit_ruangan.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                <a href="hapus_ruangan.php?id=<?= $row['id'] ?>" class="btn btn-hapus" onclick="return confirm('Yakin ingin menghapus ruangan ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
