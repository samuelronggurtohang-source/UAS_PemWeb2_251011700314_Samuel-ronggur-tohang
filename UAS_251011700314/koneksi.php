<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "jadwal_kuliah";
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("gagal terkoneksi");
}
?>