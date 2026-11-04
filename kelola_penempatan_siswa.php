
<?php
include 'includes/cek_session.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

include 'config/koneksi.php';

$sql = "
    SELECT
        ks.id,
        s.nis,
        s.nama AS nama_siswa,
        k.nama AS nama_kelas,
        ta.nama AS tahun_ajaran
    FROM t_kelas_siswa ks
    LEFT JOIN t_siswa s ON ks.siswa_id = s.id
    LEFT JOIN t_kelas k ON ks.kelas_id = k.id
    LEFT JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
    ORDER BY ks.id DESC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Gagal mengambil data: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penempatan Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            margin: 0;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-width: 0;
        }

        .table-responsive {
            background: white;
            padding: 15px;
            border-radius: 6px;
        }

        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">

    <h2>Penempatan Siswa</h2>
    <p>Kelola penempatan siswa berdasarkan kelas dan tahun ajaran.</p>

    <div class="mb-3">
        <a href="dashboard.php" class="btn btn-secondary btn-sm">
            Kembali ke Dashboard
        </a>

        <a href="tambah_penempatan_siswa.php" class="btn btn-primary btn-sm">
            Tambah Penempatan Siswa
        </a>
    </div>

    <div class="form-group mb-3">
        <input type="text" id="cariTabel" class="form-control"
               placeholder="Cari data penempatan siswa...">
    </div>

    <div class="table-responsive">
        <table id="tabelPenempatan" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php
            $no = 1;

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nis'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['nama_siswa'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?></td>

                    <td>
                        <a class="btn btn-warning btn-sm"
                           href="edit_penempatan_siswa.php?id=<?= (int) $row['id'] ?>">
                            Edit
                        </a>

                        <a class="btn btn-danger btn-sm"
                           href="hapus_penempatan_siswa.php?id=<?= (int) $row['id'] ?>"
                           onclick="return confirm('Yakin ingin menghapus penempatan siswa ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr>
                    <td colspan="6" class="text-center">
                        Belum ada data penempatan siswa.
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <small id="infoTabel"></small>

        <div>
            <button type="button" id="sebelumnya" class="btn btn-secondary btn-sm">
                Sebelumnya
            </button>

            <button type="button" id="berikutnya" class="btn btn-primary btn-sm">
                Berikutnya
            </button>
        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabel = document.getElementById('tabelPenempatan');
    const pencarian = document.getElementById('cariTabel');
    const tombolSebelumnya = document.getElementById('sebelumnya');
    const tombolBerikutnya = document.getElementById('berikutnya');
    const info = document.getElementById('infoTabel');

    const semuaBaris = Array.from(tabel.querySelectorAll('tbody tr'));
    const jumlahPerHalaman = 10;
    let halaman = 1;

    function tampilkanTabel() {
        const kata = pencarian.value.toLowerCase();

        const barisCocok = semuaBaris.filter(function (baris) {
            return baris.innerText.toLowerCase().includes(kata);
        });

        const totalHalaman = Math.max(
            1,
            Math.ceil(barisCocok.length / jumlahPerHalaman)
        );

        if (halaman > totalHalaman) {
            halaman = totalHalaman;
        }

        semuaBaris.forEach(function (baris) {
            baris.style.display = 'none';
        });

        const mulai = (halaman - 1) * jumlahPerHalaman;
        const akhir = mulai + jumlahPerHalaman;

        barisCocok.slice(mulai, akhir).forEach(function (baris) {
            baris.style.display = '';
        });

        if (barisCocok.length === 0) {
            info.textContent = 'Tidak ada data yang cocok.';
        } else {
            info.textContent =
                'Menampilkan ' + (mulai + 1) + '-' +
                Math.min(akhir, barisCocok.length) +
                ' dari ' + barisCocok.length + ' data';
        }

        tombolSebelumnya.disabled = halaman <= 1;
        tombolBerikutnya.disabled = halaman >= totalHalaman;
    }

    pencarian.addEventListener('input', function () {
        halaman = 1;
        tampilkanTabel();
    });

    tombolSebelumnya.addEventListener('click', function () {
        if (halaman > 1) {
            halaman--;
            tampilkanTabel();
        }
    });

    tombolBerikutnya.addEventListener('click', function () {
        halaman++;
        tampilkanTabel();
    });

    tampilkanTabel();
});
</script>

</body>
</html>
