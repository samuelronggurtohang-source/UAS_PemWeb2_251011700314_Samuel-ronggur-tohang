<<?php
    include "../koneksi.php";

    session_start();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM tb_login  WHERE username='$username' AND password='$password'";

    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) > 0) {

        $_SESSION['username'] = $username;
        $_SESSION['status'] = "login";

        header("Location: ../index.php");
    } else {

        echo "<script>
            alert('Login Gagal! Periksa username dan password Anda.');
            window.location.href='../login.php';
          </script>";
    }
    ?>