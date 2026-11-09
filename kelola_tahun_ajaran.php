
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data tahun ajaran
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM t_tahun_ajaran
     ORDER BY tanggal_mulai DESC, id DESC"
);

if (!$query) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tahun Ajaran</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .konten {
            margin-left: 273px;
            padding: 30px;
            min-height: 100vh;
        }

        .judul {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subjudul {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .card-custom {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }

        .card-header-custom {
            padding: 16px 20px;
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            margin-bottom: 0;
        }

        .table th,
        .table td {
            vertical-align: middle;
            font-size: 13px;
        }

        .table thead th {
            white-space: nowrap;
        }

        .badge {
            padding: 7px 10px;
            font-size: 11px;
        }

        /* KOLOM AKSI */
        .kolom-aksi {
            width: 150px;
            min-width: 150px;
        }

        .aksi-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
        }

        .btn-aksi {
            width: 62px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .btn-aksi i {
            margin-right: 4px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 13px;
        }

        @media (max-width: 767px) {
            .konten {
                margin-left: 0;
                padding: 18px;
            }

            .judul {
                font-size: 23px;
            }

            .card-body {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="konten">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

            <div>
                <h2 class="judul">Kelola Tahun Ajaran</h2>
                <p class="subjudul">
                    Kelola data tahun ajaran sekolah.
                </p>
            </div>

            <div class="mb-3">
                <a href="tambah_tahun_ajaran.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Tambah Tahun Ajaran
                </a>
            </div>

        </div>

        <!-- NOTIFIKASI -->
        <?php if (isset($_GET['pesan'])) { ?>

            <?php if ($_GET['pesan'] === 'hapus') { ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i>
                    Tahun ajaran berhasil dihapus.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>

            <?php } elseif ($_GET['pesan'] === 'gagal') { ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i>
                    Gagal memproses data tahun ajaran.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php } ?>

        <?php } ?>

        <!-- TABEL -->
        <div class="card-custom">

            <div class="card-header-custom">
                <i class="fas fa-calendar-alt text-primary mr-2"></i>
                Daftar Tahun Ajaran
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead class="thead-dark">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th>Nama Tahun Ajaran</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th width="130" class="text-center">Status</th>
                                <th class="kolom-aksi text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $no = 1;

                            if (mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                            ?>

                                <tr>

                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= date('d-m-Y', strtotime($data['tanggal_mulai'])); ?>
                                    </td>

                                    <td>
                                        <?= date('d-m-Y', strtotime($data['tanggal_selesai'])); ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ((int)$data['status_aktif'] === 1) { ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i>
                                                Aktif
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-times-circle"></i>
                                                Tidak Aktif
                                            </span>
                                        <?php } ?>
                                    </td>

                                    <!-- AKSI RAPI -->
                                    <td class="kolom-aksi">

                                        <div class="aksi-wrapper">

                                            <a href="edit_tahun_ajaran.php?id=<?= (int)$data['id']; ?>"
                                               class="btn btn-warning btn-aksi"
                                               title="Edit Tahun Ajaran">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>

                                            <button type="button"
                                                    class="btn btn-danger btn-aksi"
                                                    title="Hapus Tahun Ajaran"
                                                    onclick="konfirmasiHapus(<?= (int)$data['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                                Hapus
                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            <?php
                                }
                            } else {
                            ?>

                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-folder-open"></i>
                                        Belum ada data tahun ajaran.
                                    </td>
                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

        <div class="footer">
            © <?= date('Y'); ?> Sistem Informasi Pelanggaran Siswa
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

    <script>
        function konfirmasiHapus(id) {
            const konfirmasi = confirm(
                'Yakin ingin menghapus tahun ajaran ini?'
            );

            if (konfirmasi) {
                window.location.href =
                    'hapus_tahun_ajaran.php?id=' + encodeURIComponent(id);
            }
        }

        $(document).ready(function () {
            setTimeout(function () {
                $('.alert').alert('close');
            }, 4000);
        });
    </script>

</body>
</html>
