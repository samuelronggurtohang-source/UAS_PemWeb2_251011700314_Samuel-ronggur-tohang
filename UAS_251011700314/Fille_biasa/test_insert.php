<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'Bisnis_cemilan';

$Nomor = '';
$Cemilan = 'TEST_ITEM_' . time();
$Total_Terjual = '0';
$Harga_jual = '1000';
$Stok = 'Aman';
$info = 'Laris';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) { echo "CONNECT_ERR: " . $mysqli->connect_error . PHP_EOL; exit(1); }

$sql = "INSERT INTO database_utama
(Nomor, Cemilan, Total_Terjual, Harga_jual, Stok, info)
VALUES
('" . $mysqli->real_escape_string($Nomor) . "','" . $mysqli->real_escape_string($Cemilan) . "','" . $mysqli->real_escape_string($Total_Terjual) . "','" . $mysqli->real_escape_string($Harga_jual) . "','" . $mysqli->real_escape_string($Stok) . "','" . $mysqli->real_escape_string($info) . "')";

if ($mysqli->query($sql)) {
    echo "Insert OK. Insert ID: " . $mysqli->insert_id . PHP_EOL;
} else {
    echo "Insert Error: " . $mysqli->error . PHP_EOL;
}
