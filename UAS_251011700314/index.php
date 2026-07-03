<?php
include 'login_file/auth.php';
include 'koneksi.php';

$nim = "";
$nama_mahasiswa = "";
$kode_kelas = "";
$Ruang = "";
$Matakuliah = "";
$nama_dosen = "";
$jam_masuk = "";
$Gambar_Absensi = "";
$edit_id = "";
$error = "";
$sukses = "";
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$searchKeyword = "";
if ($search !== "") {
    $searchKeyword = mysqli_real_escape_string($koneksi, $search);
}

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $op == 'delete') {
    $id = $_GET['id'] ?? '';
    $id = mysqli_real_escape_string($koneksi, $id);
    if ($id !== '') {
        $sqlDelete = "DELETE FROM tb_jadwal WHERE nim = '$id'";
        if (mysqli_query($koneksi, $sqlDelete)) {
            $sukses = "Data berhasil dihapus";
            header('Location: index.php');
            exit;
        } else {
            $error = "Gagal menghapus data: " . mysqli_error($koneksi);
        }
    } else {
        $error = "ID tidak ditemukan untuk penghapusan";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'] ?? '';
    $sql1  = "SELECT * FROM tb_jadwal WHERE nim = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $nim = $r1['nim'] ?? '';
    $nama_mahasiswa = $r1['nama_mahasiswa'] ?? '';
    $kode_kelas = $r1['kode_kelas'] ?? '';
    $Ruang = $r1['Ruang'] ?? '';
    $Matakuliah = $r1['Matakkuliah'] ?? '';
    $nama_dosen = $r1['nama_dosen'] ?? '';
    $jam_masuk = $r1['jam_masuk'] ?? '';
    $Gambar_Absensi = $r1['Gambar_Absensi'] ?? '';
    $edit_id = $nim;

    if ($nim == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $edit_id = trim($_POST['edit_id'] ?? '');
    $nim = trim($_POST['nim'] ?? '');
    $nama_mahasiswa = trim($_POST['nama_mahasiswa'] ?? '');
    $kode_kelas = trim($_POST['kode_kelas'] ?? '');
    $Ruang = trim($_POST['Ruang'] ?? '');
    $Matakuliah = trim($_POST['Matakuliah'] ?? '');
    $nama_dosen = trim($_POST['nama_dosen'] ?? '');
    $jam_masuk = trim($_POST['jam_masuk'] ?? '');
    $Gambar_Absensi = trim($_POST['existing_gambar'] ?? '');
    $fileUpload = $_FILES['Gambar_Absensi'] ?? null;
    $fileProvided = $fileUpload && $fileUpload['error'] !== UPLOAD_ERR_NO_FILE;

    if ($nim == "" || $nama_mahasiswa == "" || $kode_kelas == "" || $Ruang == "" || $Matakuliah == "" || $nama_dosen == "" || $jam_masuk == "" || (!$fileProvided && !$edit_id && $Gambar_Absensi == "")) {
        $error = "Semua field wajib diisi";
    } else {
        if ($fileProvided && $fileUpload['error'] === UPLOAD_ERR_OK) {
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExt = strtolower(pathinfo($fileUpload['name'], PATHINFO_EXTENSION));
            if (!in_array($fileExt, $allowedExt)) {
                $error = "Format gambar harus JPG, JPEG, PNG, atau GIF.";
            } else {
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0755, true);
                }
                $newFileName = 'absensi_' . uniqid() . '.' . $fileExt;
                $uploadPath = 'uploads/' . $newFileName;
                if (move_uploaded_file($fileUpload['tmp_name'], $uploadPath)) {
                    $Gambar_Absensi = $uploadPath;
                } else {
                    $error = "Gagal mengunggah gambar.";
                }
            }
        } elseif (!$edit_id && !$Gambar_Absensi) {
            $error = "Gambar Absensi wajib diupload.";
        }

        if (!$error) {
            $nim = mysqli_real_escape_string($koneksi, $nim);
            $nama_mahasiswa = mysqli_real_escape_string($koneksi, $nama_mahasiswa);
            $kode_kelas = mysqli_real_escape_string($koneksi, $kode_kelas);
            $Ruang = mysqli_real_escape_string($koneksi, $Ruang);
            $Matakuliah = mysqli_real_escape_string($koneksi, $Matakuliah);
            $nama_dosen = mysqli_real_escape_string($koneksi, $nama_dosen);
            $jam_masuk = mysqli_real_escape_string($koneksi, $jam_masuk);
            $Gambar_Absensi = mysqli_real_escape_string($koneksi, $Gambar_Absensi);

            if ($edit_id !== '') {
                $edit_id = mysqli_real_escape_string($koneksi, $edit_id);
                $sql = "UPDATE tb_jadwal SET nama_mahasiswa='$nama_mahasiswa', kode_kelas='$kode_kelas', Ruang='$Ruang', Matakkuliah='$Matakuliah', nama_dosen='$nama_dosen', jam_masuk='$jam_masuk', Gambar_Absensi='$Gambar_Absensi' WHERE nim='$edit_id'";
                if (mysqli_query($koneksi, $sql)) {
                    $sukses = "Data berhasil diupdate";
                } else {
                    $error = mysqli_error($koneksi);
                }
            } else {
                $checkSql = "SELECT COUNT(*) AS jumlah FROM tb_jadwal WHERE nim = '$nim'";
                $checkRes = mysqli_query($koneksi, $checkSql);
                $checkRow = mysqli_fetch_assoc($checkRes);
                if (($checkRow['jumlah'] ?? 0) > 0) {
                    $error = "NIM $nim sudah ada. Gunakan NIM lain.";
                } else {
                    $sql = "INSERT INTO tb_jadwal (nim,nama_mahasiswa,kode_kelas,Ruang,Matakkuliah,nama_dosen,jam_masuk,Gambar_Absensi) VALUES ('$nim','$nama_mahasiswa','$kode_kelas','$Ruang','$Matakuliah','$nama_dosen','$jam_masuk','$Gambar_Absensi')";
                    if (mysqli_query($koneksi, $sql)) {
                        $sukses = "Data berhasil disimpan";
                        $nim = "";
                        $nama_mahasiswa = "";
                        $kode_kelas = "";
                        $Ruang = "";
                        $Matakuliah = "";
                        $nama_dosen = "";
                        $jam_masuk = "";
                        $Gambar_Absensi = "";
                    } else {
                        $error = mysqli_error($koneksi);
                    }
                }
            }

            if ($sukses) {
                $edit_id = "";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jadwal Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background: #f4f6f9;
            color: #212529;
        }

        .wrapper {
            display: flex;
        }

        .content {
            padding: 20px;
            width: 100%;
            color: #212529;
        }

        header.page-header {
            background: #000000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 20px 0;
            margin-bottom: 30px;
            color: #ffffff;
        }

        header.page-header .page-title {
            margin-bottom: 6px;
            font-size: 1.5rem;
            font-weight: 600;
            color: #ffffff;
        }

        header.page-header .page-subtitle {
            margin-bottom: 0;
            color: #ffffff;
        }

        .page-header .btn {
            border-color: #444;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .header-logo {
            width: 64px;
            height: auto;
            border-radius: 10px;
            background: #ffffff;
            padding: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .card {
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .card .card-header {
            background: #f8f9fa !important;
            color: #212529 !important;
        }

        .btn-primary {
            background: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: #0b5ed7;
            border-color: #0a58ca;
        }

        .form-control,
        .form-select {
            background: #ffffff;
            color: #212529;
            border: 1px solid #ced4da;
        }

        .form-control:focus,
        .form-select:focus {
            background: #ffffff;
            color: #212529;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .table {
            color: #212529;
            border-color: #dee2e6;
        }

        .table th,
        .table td {
            border-color: #dee2e6;
        }

        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }

        .offcanvas {
            background: #ffffff;
            color: #212529;
        }

        .offcanvas-header {
            border-bottom: 1px solid #dee2e6;
        }

        .offcanvas-body .list-group-item {
            background: #ffffff;
            border: 1px solid #dee2e6;
            color: #212529;
        }

        .offcanvas-body .list-group-item:hover,
        .offcanvas-body .list-group-item:focus {
            background: #f8f9fa;
            color: #212529;
        }
    </style>
</head>

<body class="bg-light">
    <?php include 'file_tampilan/header.php'; ?>
    <div class="wrapper">

        <?php include 'file_tampilan/sidebar.php'; ?>

        <div class="content">
            <div class="text-center mb-4">
                <h2 class="m-0">Data Jadwal Kuliah</h2>
            </div>
            <div class="container mt-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white">
                        Create / Edit Data Jadwal Kuliah
                    </div>
                    <div class="card-body">
                        <?php
                        if ($error) {
                        ?>
                            <div class='alert alert-danger' role='alert'><?php echo $error ?>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="card-body">
                            <?php
                            if ($sukses) {
                            ?>
                                <div class='alert alert-success' role='alert'><?php echo $sukses ?>
                                </div>
                            <?php
                            }
                            ?>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
                                <input type="hidden" name="existing_gambar" value="<?= htmlspecialchars($Gambar_Absensi) ?>">
                                <div class="mb-3">
                                    <label for="nim" class="form-label">NIM</label>
                                    <input type="text" class="form-control" id="nim" name="nim" value="<?= htmlspecialchars($nim) ?>" <?= $edit_id ? 'readonly' : '' ?> placeholder="">
                                    <?php if (!$edit_id) { ?>
                                        <div class="form-text"></div>
                                    <?php } else { ?>
                                        <div class="form-text"></div>
                                    <?php } ?>
                                </div>
                                <div class="mb-3"> <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label> <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa" value="<?= $nama_mahasiswa ?>"> </div>
                                <div class="mb-3"> <label for="kode_kelas" class="form-label">Kode Kelas</label> <input type="text" class="form-control" id="kode_kelas" name="kode_kelas" value="<?= $kode_kelas ?>"> </div>
                                <div class="mb-3"> <label for="Ruang" class="form-label">Ruang</label> <input type="text" class="form-control" id="Ruang" name="Ruang" value="<?= $Ruang ?>"> </div>
                                <div class="mb-3">
                                    <label for="Matakuliah" class="form-label">Matakuliah</label>
                                    <select class="form-select" id="Matakuliah" name="Matakuliah" required>
                                        <option value="">Pilih Matakuliah</option>
                                        <option value="aljabar linier" <?= $Matakuliah === 'aljabar linier' ? 'selected' : '' ?>>Aljabar Linier</option>
                                        <option value="algoritma" <?= $Matakuliah === 'algoritma' ? 'selected' : '' ?>>Algoritma</option>
                                        <option value="sistem basis data" <?= $Matakuliah === 'sistem basis data' ? 'selected' : '' ?>>Sistem Basis Data</option>
                                        <option value="pengantar kecerdasan buatan" <?= $Matakuliah === 'pengantar kecerdasan buatan' ? 'selected' : '' ?>>Pengantar Kecerdasan Buatan</option>
                                    </select>
                                </div>
                                <div class="mb-3"> <label for="nama_dosen" class="form-label">Nama Dosen</label> <input type="text" class="form-control" id="nama_dosen" name="nama_dosen" value="<?= $nama_dosen ?>"> </div>
                                <div class="mb-3"> <label for="jam_masuk" class="form-label">Jam/Tanggal Masuk</label> <input type="datetime-local" class="form-control" id="jam_masuk" name="jam_masuk" value="<?= $jam_masuk ?>"> </div>
                                <div class="mb-3">
                                    <label for="Gambar_Absensi" class="form-label">Gambar Absensi</label>
                                    <input class="form-control" type="file" id="Gambar_Absensi" name="Gambar_Absensi" accept="image/*">
                                    <?php if ($Gambar_Absensi) { ?>
                                        <div class="form-text">File saat ini: <a href="<?= htmlspecialchars($Gambar_Absensi) ?>" target="_blank">Lihat gambar</a></div>
                                    <?php } ?>
                                </div>
                                <div class="mb-3"> <input type="submit" name="simpan" value="Simpan Data" class="btn btn-success w-100"> </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="card">
                        <div class="card-header text-white bg-secondary">
                            Data Jadwal Kuliah
                        </div>
                        <div class="card-body">
                            <form method="GET" class="row g-2 mb-3">
                                <div class="col-md-9">
                                    <input type="text" name="search" class="form-control" placeholder="Cari NIM, nama, kelas, ruang, matakuliah, atau dosen" value="<?= htmlspecialchars($search) ?>">
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                                    <a href="index.php" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </form>

                            <?php if ($search !== '') { ?>
                                <div class="alert alert-info py-2">Menampilkan hasil pencarian untuk: <strong><?= htmlspecialchars($search) ?></strong></div>
                            <?php } ?>

                            <table class="table table-hover table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">NIM</th>
                                        <th scope="col">Nama Mahasiswa</th>
                                        <th scope="col">Kode Kelas</th>
                                        <th scope="col">Ruang</th>
                                        <th scope="col">Matakuliah</th>
                                        <th scope="col">Nama Dosen</th>
                                        <th scope="col">Jam Masuk</th>
                                        <th scope="col">Gambar Absensi</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql2 = "SELECT * FROM tb_jadwal";
                                    if ($searchKeyword !== '') {
                                        $sql2 .= " WHERE nim LIKE '%$searchKeyword%' OR nama_mahasiswa LIKE '%$searchKeyword%' OR kode_kelas LIKE '%$searchKeyword%' OR Ruang LIKE '%$searchKeyword%' OR Matakkuliah LIKE '%$searchKeyword%' OR nama_dosen LIKE '%$searchKeyword%'";
                                    }
                                    $sql2 .= " ORDER BY nim DESC";
                                    $q2 = mysqli_query($koneksi, $sql2);
                                    if (mysqli_num_rows($q2) > 0) {
                                        while ($r2 = mysqli_fetch_assoc($q2)) {
                                            $nim = $r2['nim'] ?? '';
                                            $nama_mahasiswa = $r2['nama_mahasiswa'] ?? '';
                                            $kode_kelas = $r2['kode_kelas'] ?? '';
                                            $Ruang = $r2['Ruang'] ?? '';
                                            $Matakuliah = $r2['Matakkuliah'] ?? '';
                                            $nama_dosen = $r2['nama_dosen'] ?? '';
                                            $jam_masuk = $r2['jam_masuk'] ?? '';
                                            $Gambar_Absensi = $r2['Gambar_Absensi'] ?? '';
                                    ?>
                                        <tr>
                                            <td scope="row"><?php echo htmlspecialchars($nim) ?></td>
                                            <td scope="row"><?php echo htmlspecialchars($nama_mahasiswa) ?></td>
                                            <td scope="row"><?php echo htmlspecialchars($kode_kelas) ?></td>
                                            <td scope="row"><?php echo htmlspecialchars($Ruang) ?></td>
                                            <td scope="row"><?php echo htmlspecialchars($Matakuliah) ?></td>
                                            <td scope="row"><?php echo htmlspecialchars($nama_dosen) ?></td>
                                            <td scope="row"><?php echo htmlspecialchars($jam_masuk) ?></td>
                                            <td scope="row">
                                                <?php if ($Gambar_Absensi && file_exists($Gambar_Absensi)) { ?>
                                                    <img src="<?= htmlspecialchars($Gambar_Absensi) ?>" alt="Absensi" style="max-width: 120px; max-height: 80px; object-fit: cover;" />
                                                <?php } elseif ($Gambar_Absensi) { ?>
                                                    <a href="<?= htmlspecialchars($Gambar_Absensi) ?>" target="_blank">Lihat gambar</a>
                                                <?php } else { ?>
                                                    Tidak ada gambar
                                                <?php } ?>
                                            </td>
                                            <td scope="row">
                                                <a href="index.php?op=edit&id=<?php echo htmlspecialchars($r2['nim'] ?? '') ?>"><button class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-pen"></i> Edit</button></a>
                                                <a href="index.php?op=delete&id=<?php echo htmlspecialchars($r2['nim'] ?? '') ?>"><button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i> Delete</button></a>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">Tidak ada data yang sesuai dengan pencarian.</td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <a href="report.php" class="btn btn-dark">Lihat Laporan</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
