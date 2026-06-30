-- Database untuk Sistem Reservasi Ruang Rapat Sederhana

CREATE DATABASE IF NOT EXISTS reservasi_db;
USE reservasi_db;

CREATE TABLE ruangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_ruangan VARCHAR(100) NOT NULL,
    kapasitas INT NOT NULL,
    lokasi VARCHAR(100) NOT NULL
);

CREATE TABLE reservasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ruangan_id INT NOT NULL,
    nama_pemesan VARCHAR(100) NOT NULL,
    tanggal DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    keperluan VARCHAR(150) NOT NULL,
    FOREIGN KEY (ruangan_id) REFERENCES ruangan(id) ON DELETE CASCADE
);

-- contoh data
INSERT INTO ruangan (nama_ruangan, kapasitas, lokasi) VALUES
('Ruang Rapat A', 10, 'Lantai 1'),
('Ruang Rapat B', 20, 'Lantai 2');

INSERT INTO reservasi (ruangan_id, nama_pemesan, tanggal, jam_mulai, jam_selesai, keperluan) VALUES
(1, 'Andi Saputra', '2026-07-10', '09:00:00', '11:00:00', 'Rapat Divisi Marketing');
