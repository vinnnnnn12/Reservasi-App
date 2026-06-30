<?php
include "koneksi.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ruangan_id = $_POST['ruangan_id'];
    $nama_pemesan = $_POST['nama_pemesan'];
    $tanggal = $_POST['tanggal'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $keperluan = $_POST['keperluan'];

    if ($jam_selesai <= $jam_mulai) {
        $error = "Jam selesai harus lebih besar dari jam mulai.";
    } else {
        // cek apakah ada reservasi lain di ruangan & tanggal yang sama dengan jam yang bentrok
        $stmt = mysqli_prepare($conn, "SELECT * FROM reservasi 
            WHERE ruangan_id = ? 
            AND tanggal = ? 
            AND jam_mulai < ? 
            AND jam_selesai > ?");
        mysqli_stmt_bind_param($stmt, "isss", $ruangan_id, $tanggal, $jam_selesai, $jam_mulai);
        mysqli_stmt_execute($stmt);
        $cek = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($cek) > 0) {
            $error = "Jadwal bentrok! Ruangan ini sudah dipesan pada jam tersebut. Silakan pilih jam atau ruangan lain.";
        } else {
            $stmt2 = mysqli_prepare($conn, "INSERT INTO reservasi (ruangan_id, nama_pemesan, tanggal, jam_mulai, jam_selesai, keperluan) 
                VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt2, "isssss", $ruangan_id, $nama_pemesan, $tanggal, $jam_mulai, $jam_selesai, $keperluan);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

            header("Location: reservasi.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Reservasi</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="navbar">
    <span class="brand">Reservasi Ruang Rapat</span>
    <a href="index.php">Daftar Ruangan</a>
    <a href="reservasi.php">Daftar Reservasi</a>
</div>

<div class="container">
    <h2>Tambah Reservasi</h2>

    <?php if ($error != "") { ?>
        <div class="pesan-error"><?= $error ?></div>
    <?php } ?>

    <form method="POST">
        <label>Pilih Ruangan</label>
        <select name="ruangan_id" required>
            <option value="">-- Pilih Ruangan --</option>
            <?php
            $ruangan_list = mysqli_query($conn, "SELECT * FROM ruangan");
            while ($r = mysqli_fetch_assoc($ruangan_list)) {
                $sel = (isset($_POST['ruangan_id']) && $_POST['ruangan_id'] == $r['id']) ? "selected" : "";
                echo "<option value='{$r['id']}' $sel>{$r['nama_ruangan']} (kapasitas {$r['kapasitas']} orang)</option>";
            }
            ?>
        </select>

        <label>Nama Pemesan</label>
        <input type="text" name="nama_pemesan" value="<?= isset($_POST['nama_pemesan']) ? $_POST['nama_pemesan'] : '' ?>" required>

        <label>Tanggal</label>
        <input type="date" name="tanggal" value="<?= isset($_POST['tanggal']) ? $_POST['tanggal'] : '' ?>" required>

        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" value="<?= isset($_POST['jam_mulai']) ? $_POST['jam_mulai'] : '' ?>" required>

        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" value="<?= isset($_POST['jam_selesai']) ? $_POST['jam_selesai'] : '' ?>" required>

        <label>Keperluan</label>
        <input type="text" name="keperluan" value="<?= isset($_POST['keperluan']) ? $_POST['keperluan'] : '' ?>" required>

        <button type="submit">Pesan Ruangan</button>
    </form>
</div>

</body>
</html>
