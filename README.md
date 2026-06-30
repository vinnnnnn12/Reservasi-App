# Sistem Reservasi Ruang Rapat — Panduan Deploy ke Vercel + Aiven

## 1. Setup Database di Aiven

1. Daftar di https://aiven.io, buat service baru jenis **MySQL** (plan Free/Hobbyist)
2. Tunggu sampai status service "Running"
3. Di tab Overview, catat: **Host**, **Port**, **User**, **Password**, **Database name**
4. Download **CA Certificate** (file `ca.pem`)
5. Letakkan file `ca.pem` tersebut ke dalam folder `api/` di project ini (sejajar dengan `koneksi.php`)
6. Import `database.sql` ke database Aiven (lewat Aiven Query Editor, atau lewat MySQL client/Adminer dari laptop dengan kredensial di atas)

## 2. Push ke GitHub

```
git init
git add .
git commit -m "Sistem reservasi ruang rapat"
git branch -M main
git remote add origin <url-repo-github-kamu>
git push -u origin main
```

Catatan: file `ca.pem` aman untuk di-commit karena isinya sertifikat CA publik (bukan password/kredensial rahasia).

## 3. Deploy ke Vercel

1. Buka https://vercel.com, login pakai akun GitHub
2. Klik **"Add New Project"**, pilih repository GitHub project ini
3. Vercel akan otomatis mendeteksi `vercel.json`
4. Sebelum klik Deploy, buka bagian **"Environment Variables"**, tambahkan:

| Key | Value |
|---|---|
| DB_HOST | (host dari Aiven) |
| DB_PORT | (port dari Aiven) |
| DB_USER | (user dari Aiven, biasanya avnadmin) |
| DB_PASS | (password dari Aiven) |
| DB_NAME | (nama database dari Aiven) |

5. Klik **Deploy**, tunggu proses build selesai
6. Setelah selesai, Vercel akan kasih URL publik (misal `https://nama-project.vercel.app`) — buka dan tes aplikasinya

## Struktur Project

```
reservasi-app-vercel/
├── api/                    → semua file PHP wajib di sini (syarat runtime vercel-php)
│   ├── koneksi.php          → koneksi SSL ke Aiven pakai env variables
│   ├── ca.pem                → sertifikat SSL dari Aiven (tambahkan manual, lihat langkah 1)
│   ├── index.php
│   ├── tambah_ruangan.php
│   ├── edit_ruangan.php
│   ├── hapus_ruangan.php
│   ├── reservasi.php
│   ├── tambah_reservasi.php
│   ├── edit_reservasi.php
│   └── hapus_reservasi.php
├── style.css                → tetap di root, diakses via /style.css
├── database.sql
├── vercel.json
└── .gitignore
```

## Catatan Penting

- Semua query sudah menggunakan **prepared statements** (`mysqli_prepare`), bukan lagi penggabungan string langsung — lebih aman dari SQL Injection
- Koneksi database memakai **SSL** wajib (`MYSQLI_CLIENT_SSL`) sesuai ketentuan Aiven
- Kredensial database **tidak** ditulis langsung di kode, melainkan diambil dari Environment Variables di Vercel — jadi aman walau source code di-push ke GitHub publik
