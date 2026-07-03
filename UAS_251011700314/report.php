<?php
include 'koneksi.php';

$sql = "SELECT * FROM tb_jadwal";
$data = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Jadwal Kuliah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <style>
    </style>

<body>

    <div class="container mt-5">

        <h2 class="text-center">
            Laporan Data Jadwal Kuliah
        </h2>

        <div class="mb-3">
            <a href="index.php" class="btn btn-primary">Kembali ke Dashboard</a>
            <button
                onclick="window.print()"
                class="btn btn-success mb-3 ms-2">
                Cetak Report
            </button>
        </div>

        <table class="table table-bordered">

            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Kode Kelas</th>
                <th>Ruang</th>
                <th>Matakuliah</th>
                <th>Nama Dosen</th>
                <th>Jam Masuk</th>
                <th>Gambar Absensi</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($data)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['nim']) ?></td>
                    <td><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                    <td><?= htmlspecialchars($row['kode_kelas']) ?></td>
                    <td><?= htmlspecialchars($row['Ruang']) ?></td>
                    <td><?= htmlspecialchars($row['Matakkuliah']) ?></td>
                    <td><?= htmlspecialchars($row['nama_dosen']) ?></td>
                    <td><?= htmlspecialchars($row['jam_masuk']) ?></td>
                    <td>
                        <?php if (!empty($row['Gambar_Absensi']) && file_exists($row['Gambar_Absensi'])) { ?>
                            <img src="<?= htmlspecialchars($row['Gambar_Absensi']) ?>" alt="Absensi" style="max-width: 150px; max-height: 120px; object-fit: cover;" />
                        <?php } elseif (!empty($row['Gambar_Absensi'])) { ?>
                            <a href="<?= htmlspecialchars($row['Gambar_Absensi']) ?>" target="_blank">Lihat gambar</a>
                        <?php } else { ?>
                            Tidak ada gambar
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>

        </table>

        <?php include 'Fille_biasa/file_tampilan/foother.php'; ?>
    </div>

</body>

</html>