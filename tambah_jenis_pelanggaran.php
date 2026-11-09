
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// KHUSUS ADMIN
$role = $_SESSION['role'] ?? '';

if ($role !== 'admin') {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Jenis Pelanggaran</title>

    <!-- BOOTSTRAP 4.1.3 -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8f9fa;
            font-family: Arial, sans-serif;
            color: #343a40;
        }

        .konten {
            margin-left: 273px;
            padding: 28px;
            min-height: 100vh;
        }

        .judul {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subjudul {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .card-form {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .card-header-custom {
            background: #fff;
            color: #343a40;
            padding: 17px 20px;
            border-bottom: 1px solid #dee2e6;
            font-size: 16px;
            font-weight: bold;
        }

        .card-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            height: 42px;
            font-size: 14px;
            border: 1px solid #ced4da;
        }

        textarea.form-control {
            height: auto;
            resize: vertical;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        }

        .btn {
            padding: 9px 18px;
            font-size: 13px;
            border-radius: 4px;
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
                font-size: 24px;
            }

            .card-body {
                padding: 18px;
            }
        }
    </style>

</head>

<body>

    <!-- SIDEBAR -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- KONTEN -->
    <main class="konten">

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

            <div>
                <h2 class="judul">Tambah Jenis Pelanggaran</h2>

                <p class="subjudul">
                    Tambahkan jenis pelanggaran baru beserta poinnya.
                </p>
            </div>

            <div class="mb-3">

                <a href="kelola_jenis_pelanggaran.php"
                   class="btn btn-secondary">

                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali

                </a>

            </div>

        </div>

        <!-- PESAN -->
        <?php if (isset($_SESSION['pesan'])) { ?>

            <div class="alert alert-info alert-dismissible fade show">

                <?= htmlspecialchars(
                    $_SESSION['pesan'],
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>

            </div>

            <?php unset($_SESSION['pesan']); ?>

        <?php } ?>

        <!-- FORM -->
        <div class="card-form">

            <div class="card-header-custom">
                <i class="fas fa-plus-circle text-primary mr-2"></i>
                Form Tambah Jenis Pelanggaran
            </div>

            <div class="card-body">

                <form action="proses_jenis_pelanggaran.php" method="POST">

                    <input type="hidden" name="aksi" value="tambah">

                    <!-- NAMA PELANGGARAN -->
                    <div class="form-group">

                        <label for="nama_pelanggaran">
                            Nama Pelanggaran
                        </label>

                        <input
                            type="text"
                            id="nama_pelanggaran"
                            name="nama_pelanggaran"
                            class="form-control"
                            maxlength="150"
                            placeholder="Masukkan nama pelanggaran"
                            required
                        >

                    </div>

                    <!-- POIN -->
                    <div class="form-group">

                        <label for="poin">
                            Poin Pelanggaran
                        </label>

                        <input
                            type="number"
                            id="poin"
                            name="poin"
                            class="form-control"
                            min="1"
                            placeholder="Masukkan jumlah poin"
                            required
                        >

                    </div>

                    <!-- KETERANGAN -->
                    <div class="form-group">

                        <label for="keterangan">
                            Keterangan
                        </label>

                        <textarea
                            id="keterangan"
                            name="keterangan"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan keterangan pelanggaran..."
                        ></textarea>

                    </div>

                    <hr>

                    <!-- TOMBOL -->
                    <div class="d-flex justify-content-between flex-wrap">

                        <a href="kelola_jenis_pelanggaran.php"
                           class="btn btn-secondary mb-2">

                            <i class="fas fa-times mr-1"></i>
                            Batal

                        </a>

                        <button type="submit" class="btn btn-primary mb-2">

                            <i class="fas fa-save mr-1"></i>
                            Simpan Pelanggaran

                        </button>

                    </div>

                </form>

            </div>

        </div>

        <div class="footer">
            © <?= date('Y'); ?> Sistem Informasi Pelanggaran Siswa
        </div>

    </main>

    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>
</html>
