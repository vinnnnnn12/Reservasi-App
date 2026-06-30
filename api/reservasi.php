<?php include "koneksi.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Reservasi</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="navbar">
    <span class="brand">Reservasi Ruang Rapat</span>
    <a href="index.php">Daftar Ruangan</a>
    <a href="reservasi.php">Daftar Reservasi</a>
</div>

<div class="container">
    <h2>Daftar Reservasi</h2>
    <a href="tambah_reservasi.php" class="btn btn-tambah">+ Tambah Reservasi</a>

    <form method="GET" class="filter-box">
        <label>Filter berdasarkan ruangan</label>
        <select name="ruangan_id" onchange="this.form.submit()">
            <option value="">-- Semua Ruangan --</option>
            <?php
            $ruangan_list = mysqli_query($conn, "SELECT * FROM ruangan");
            while ($r = mysqli_fetch_assoc($ruangan_list)) {
                $selected = (isset($_GET['ruangan_id']) && $_GET['ruangan_id'] == $r['id']) ? "selected" : "";
                echo "<option value='{$r['id']}' $selected>{$r['nama_ruangan']}</option>";
            }
            ?>
        </select>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Ruangan</th>
            <th>Nama Pemesan</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Keperluan</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        if (isset($_GET['ruangan_id']) && $_GET['ruangan_id'] != "") {
            $ruangan_id = $_GET['ruangan_id'];
            $stmt = mysqli_prepare($conn, "SELECT reservasi.*, ruangan.nama_ruangan FROM reservasi JOIN ruangan ON reservasi.ruangan_id = ruangan.id WHERE reservasi.ruangan_id = ? ORDER BY tanggal ASC, jam_mulai ASC");
            mysqli_stmt_bind_param($stmt, "i", $ruangan_id);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
        } else {
            $query = mysqli_query($conn, "SELECT reservasi.*, ruangan.nama_ruangan FROM reservasi JOIN ruangan ON reservasi.ruangan_id = ruangan.id ORDER BY tanggal ASC, jam_mulai ASC");
        }

        while ($row = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nama_ruangan'] ?></td>
            <td><?= $row['nama_pemesan'] ?></td>
            <td><?= date("d-m-Y", strtotime($row['tanggal'])) ?></td>
            <td><span class="badge"><?= substr($row['jam_mulai'], 0, 5) ?> - <?= substr($row['jam_selesai'], 0, 5) ?></span></td>
            <td><?= $row['keperluan'] ?></td>
            <td>
                <a href="edit_reservasi.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                <a href="hapus_reservasi.php?id=<?= $row['id'] ?>" class="btn btn-hapus" onclick="return confirm('Yakin ingin membatalkan reservasi ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
