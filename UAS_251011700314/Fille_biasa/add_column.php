<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "jadwal_kuliah";
$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Gagal terkoneksi: " . mysqli_connect_error());
}

// Cek apakah kolom jam_masuk sudah ada
$checkColumn = mysqli_query($koneksi, "SHOW COLUMNS FROM tb_jadwal LIKE 'jam_masuk'");
if (mysqli_num_rows($checkColumn) == 0) {
    // Kolom belum ada, tambahkan
    $addColumn = "ALTER TABLE tb_jadwal ADD COLUMN jam_masuk DATETIME DEFAULT CURRENT_TIMESTAMP";
    if (mysqli_query($koneksi, $addColumn)) {
        echo "✓ Kolom jam_masuk berhasil ditambahkan";
    } else {
        echo "✗ Gagal menambahkan kolom: " . mysqli_error($koneksi);
    }
} else {
    echo "✓ Kolom jam_masuk sudah ada";
}

mysqli_close($koneksi);
?>
