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
$res = $mysqli->query("SELECT @@GLOBAL.sql_mode AS m");
if (!$res) { echo "ERR: " . $mysqli->error . PHP_EOL; exit(1); }
$row = $res->fetch_assoc();
$mode = $row['m'] ?? '';
if ($mode === null) $mode = '';
$new = $mode;
$new = str_replace('STRICT_TRANS_TABLES', '', $new);
$new = str_replace('STRICT_ALL_TABLES', '', $new);
// clean repeated commas
$new = preg_replace('/,+/', ',', $new);
// trim leading/trailing commas
$new = trim($new, ',');
if ($new === '') $new = 'NO_ENGINE_SUBSTITUTION';

if ($mysqli->query("SET GLOBAL sql_mode='" . $mysqli->real_escape_string($new) . "'")) {
    echo "GLOBAL sql_mode changed to: " . $new . PHP_EOL;
} else {
    echo "Failed to set GLOBAL sql_mode: " . $mysqli->error . PHP_EOL;
}
