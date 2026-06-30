<?php
// koneksi ke database Aiven (pakai SSL + environment variables)

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = mysqli_init();

// path ke file sertifikat SSL dari Aiven
mysqli_ssl_set($conn, NULL, NULL, __DIR__ . "/ca.pem", NULL, NULL);

$berhasil = mysqli_real_connect(
    $conn,
    $host,
    $user,
    $pass,
    $dbname,
    $port,
    NULL,
    MYSQLI_CLIENT_SSL
);

if (!$berhasil) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
