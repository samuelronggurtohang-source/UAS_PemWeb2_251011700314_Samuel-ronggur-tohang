<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'Bisnis_cemilan';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo "CONNECT_ERR: " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

$res = $mysqli->query("SHOW CREATE TABLE database_utama");
if (!$res) {
    echo "QUERY_ERR: " . $mysqli->error . PHP_EOL;
    exit(1);
}

$row = $res->fetch_assoc();
echo $row['Create Table'] . PHP_EOL;
