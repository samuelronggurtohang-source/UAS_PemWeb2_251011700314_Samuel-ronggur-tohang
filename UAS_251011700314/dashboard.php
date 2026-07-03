<?php

include 'login_file/auth.php';
include 'koneksi.php';

$data =
    mysqli_query(
        $koneksi,

        "
SELECT
COUNT(*) AS total,
COUNT(DISTINCT kode_kelas) AS total_kelas,
COUNT(DISTINCT Ruang) AS total_ruang,
COUNT(DISTINCT nama_dosen) AS total_dosen
FROM tb_jadwal
"
    );

$row =
    mysqli_fetch_assoc(
        $data
    );

$reportSql = "SELECT
    COUNT(*) AS total,
    SUM(Gambar_Absensi != '') AS total_absensi,
    SUM(Gambar_Absensi = '') AS total_belum_absensi,
    SUM(kode_kelas != '') AS total_kelas,
    SUM(Ruang != '') AS total_ruang
FROM tb_jadwal";
$reportRes = mysqli_query($koneksi, $reportSql);
$report = mysqli_fetch_assoc($reportRes);

$imageListSql = "SELECT nim, nama_mahasiswa, Matakkuliah, Gambar_Absensi FROM tb_jadwal WHERE Gambar_Absensi != '' ORDER BY nim DESC";
$imageListResult = mysqli_query($koneksi, $imageListSql);

?>

<!DOCTYPE html>

<html>

<head>

    <title>
        Dashboard
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            color: #212529;
        }

        .wrapper {
            display: flex;
        }

        .content {
            padding: 30px;
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

        @media (max-width: 767.98px) {
            .sidebar {
                display: none;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>

</head>

<body>
    <?php include 'file_tampilan/header.php'; ?>
    <div class="wrapper">
        <?php include 'file_tampilan/sidebar.php'; ?>
        <div class="content">
            <div class="text-center mb-4">
                <h2 class="m-0">Dashboard</h2>
            </div>

        <div
            class="row">

            <div class="col">

                <div class="card bg-primary text-white summary-card">

                    <div class="card-body">

                        Jadwal Kuliah

                        <h1>

                            <?= $row['total'] ?>

                        </h1>

                    </div>

                </div>

            </div>

            <div class="col">

                <div class="card bg-success text-white">

                    <div class="card-body">

                        Kelas

                        <h1>

                            <?= $row['total_kelas'] ?? 0 ?>

                        </h1>

                    </div>

                </div>

            </div>

            <div class="col">

                <div class="card bg-danger text-white">

                    <div class="card-body">

                        Ruang

                        <h1>

                            <?= $row['total_ruang'] ?? 0 ?>

                        </h1>

                    </div>

                </div>

            </div>

            <div class="col">

                <div class="card bg-warning">

                    <div class="card-body">

                        Dosen

                        <h1>

                            <?= $row['total_dosen'] ?? 0 ?>

                        </h1>

                    </div>

                </div>

            </div>

        </div>

        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                Laporan Data Jadwal Kuliah
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h5>Total Jadwal</h5>
                            <p class="h3"><?php echo $report['total'] ?? 0 ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h5>Absensi Terisi</h5>
                            <p class="h3"><?php echo $report['total_absensi'] ?? 0 ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h5>Belum Absensi</h5>
                            <p class="h3"><?php echo $report['total_belum_absensi'] ?? 0 ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h5>Total Kelas</h5>
                            <p class="h3"><?php echo $report['total_kelas'] ?? 0 ?></p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <h5>Total Ruang</h5>
                            <p class="h3"><?php echo $report['total_ruang'] ?? 0 ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                Daftar Gambar Absensi
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($imageListResult) > 0) { ?>
                    <div class="row g-3">
                        <?php while ($imageRow = mysqli_fetch_assoc($imageListResult)) { ?>
                            <div class="col-md-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <?php if (!empty($imageRow['Gambar_Absensi']) && file_exists($imageRow['Gambar_Absensi'])) { ?>
                                        <img src="<?= htmlspecialchars($imageRow['Gambar_Absensi']) ?>" class="card-img-top img-fluid" alt="Absensi" style="width: 100%; height: 220px; object-fit: cover;">
                                    <?php } else { ?>
                                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light text-muted" style="height: 180px;">
                                            Gambar tidak tersedia
                                        </div>
                                    <?php } ?>
                                    <div class="card-body">
                                        <h6 class="card-title mb-1"><?= htmlspecialchars($imageRow['nama_mahasiswa'] ?? '-') ?></h6>
                                        <p class="card-text mb-1"><strong>NIM:</strong> <?= htmlspecialchars($imageRow['nim'] ?? '-') ?></p>
                                        <p class="card-text mb-0"><strong>Mata Kuliah:</strong> <?= htmlspecialchars($imageRow['Matakkuliah'] ?? '-') ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-secondary mb-0">Belum ada gambar absensi yang diunggah.</div>
                <?php } ?>
            </div>
        </div>

        <?php include 'file_tampilan/foother.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>