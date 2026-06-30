<?php
include "koneksi.php";

$id = $_GET['id'];
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
        // cek bentrok, tapi data reservasi ini sendiri (id yang sama) tidak ikut dihitung
        $stmt = mysqli_prepare($conn, "SELECT * FROM reservasi 
            WHERE ruangan_id = ? 
            AND tanggal = ? 
            AND jam_mulai < ? 
            AND jam_selesai > ?
            AND id != ?");
        mysqli_stmt_bind_param($stmt, "isssi", $ruangan_id, $tanggal, $jam_selesai, $jam_mulai, $id);
        mysqli_stmt_execute($stmt);
        $cek = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($cek) > 0) {
            $error = "Jadwal bentrok! Ruangan ini sudah dipesan pada jam tersebut. Silakan pilih jam atau ruangan lain.";
        } else {
            $stmt2 = mysqli_prepare($conn, "UPDATE reservasi SET 
                ruangan_id=?, 
                nama_pemesan=?, 
                tanggal=?, 
                jam_mulai=?, 
                jam_selesai=?, 
                keperluan=? 
                WHERE id=?");
            mysqli_stmt_bind_param($stmt2, "isssssi", $ruangan_id, $nama_pemesan, $tanggal, $jam_mulai, $jam_selesai, $keperluan, $id);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

            header("Location: reservasi.php");
            exit;
        }
    }
}

$stmt = mysqli_prepare($conn, "SELECT * FROM reservasi WHERE id=?");
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
    <title>Edit Reservasi</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

<div class="navbar">
    <span class="brand">Reservasi Ruang Rapat</span>
    <a href="index.php">Daftar Ruangan</a>
    <a href="reservasi.php">Daftar Reservasi</a>
</div>

<div class="container">
    <h2>Edit Reservasi</h2>

    <?php if ($error != "") { ?>
        <div class="pesan-error"><?= $error ?></div>
    <?php } ?>

    <form method="POST">
        <label>Pilih Ruangan</label>
        <select name="ruangan_id" required>
            <?php
            $ruangan_list = mysqli_query($conn, "SELECT * FROM ruangan");
            while ($r = mysqli_fetch_assoc($ruangan_list)) {
                $sel = ($r['id'] == $data['ruangan_id']) ? "selected" : "";
                echo "<option value='{$r['id']}' $sel>{$r['nama_ruangan']} (kapasitas {$r['kapasitas']} orang)</option>";
            }
            ?>
        </select>

        <label>Nama Pemesan</label>
        <input type="text" name="nama_pemesan" value="<?= $data['nama_pemesan'] ?>" required>

        <label>Tanggal</label>
        <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" required>

        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" value="<?= substr($data['jam_mulai'], 0, 5) ?>" required>

        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" value="<?= substr($data['jam_selesai'], 0, 5) ?>" required>

        <label>Keperluan</label>
        <input type="text" name="keperluan" value="<?= $data['keperluan'] ?>" required>

        <button type="submit">Update</button>
    </form>
</div>

</body>
</html>
