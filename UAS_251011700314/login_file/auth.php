<?php
session_start();

if (!isset($_SESSION['username']) || ($_SESSION['status'] ?? '') !== 'login') {
    header('Location: login.php');
    exit;
}
