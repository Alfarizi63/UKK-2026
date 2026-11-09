
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID kelas tidak valid.');
}

$id = (int) $_GET['id'];
$pesan = '';

// Ambil data kelas
$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_kelas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die('Data kelas tidak ditemukan.');
}

$nama = $data['nama'];
$tingkat = $data['tingkat'];
$jurusan = $data['jurusan'];
$status_aktif = (int) $data['status_aktif'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $status_aktif = (int) ($_POST['status_aktif'] ?? 0);

    $daftar_tingkat = ['X', 'XI', 'XII'];
    $daftar_jurusan = ['RPL', 'TKJ', 'TKR', 'BD', 'TSM'];

    if (
        $nama === '' ||
        !in_array($tingkat, $daftar_tingkat, true) ||
        !in_array($jurusan, $daftar_jurusan, true) ||
        !in_array($status_aktif, [0, 1], true)
    ) {

        $pesan = 'Periksa kembali seluruh data kelas.';

    } else {

        // Cek apakah kombinasi kelas sudah digunakan kelas lain
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_kelas
             WHERE nama = ?
             AND tingkat = ?
             AND jurusan = ?
             AND id != ?
             LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $cek,
            "sssi",
            $nama,
            $tingkat,
            $jurusan,
            $id
        );

        mysqli_stmt_execute($cek);
        $hasil_cek = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil_cek) > 0) {

            $pesan = 'Kelas tersebut sudah terdaftar. Silakan gunakan nama kelas, tingkat, atau jurusan yang berbeda.';

        } else {

            // Jika tidak duplikat, lakukan update
            $stmt = mysqli_prepare(
                $koneksi,
                "UPDATE t_kelas
                 SET nama = ?, tingkat = ?, jurusan = ?, status_aktif = ?
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "sssii",
                $nama,
                $tingkat,
                $jurusan,
                $status_aktif,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: kelola_kelas.php");
                exit;
            } else {
                $pesan = 'Gagal mengubah kelas: ' . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-width: 0;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.06);
            max-width: 850px;
        }

        .page-title {
            font-weight: 700;
            color: #343a40;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-control {
            padding: 10px 12px;
            border-radius: 7px;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.12);
        }

        .btn {
            padding: 9px 18px;
            border-radius: 7px;
        }

        @media(max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .form-card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

    <div class="mb-4">
        <h3 class="page-title">Edit Data Kelas</h3>
        <p class="text-muted mb-0">
            Perbarui informasi kelas di bawah ini.
        </p>
    </div>

    <div class="form-card">

        <?php if ($pesan !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($pesan); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nama Kelas</label>
                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    value="<?= htmlspecialchars($nama); ?>"
                    placeholder="Masukkan nama kelas"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Tingkat</label>
                <select name="tingkat" class="form-control" required>
                    <option value="">Pilih Tingkat</option>
                    <?php foreach (['X', 'XI', 'XII'] as $item): ?>
                        <option value="<?= $item; ?>"
                            <?= $tingkat === $item ? 'selected' : ''; ?>>
                            <?= $item; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jurusan</label>
                <select name="jurusan" class="form-control" required>
                    <option value="">Pilih Jurusan</option>
                    <?php foreach (['RPL', 'TKJ', 'TKR', 'BD', 'TSM'] as $item): ?>
                        <option value="<?= $item; ?>"
                            <?= $jurusan === $item ? 'selected' : ''; ?>>
                            <?= $item; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="status_aktif" class="form-control" required>
                    <option value="1" <?= $status_aktif === 1 ? 'selected' : ''; ?>>
                        Aktif
                    </option>
                    <option value="0" <?= $status_aktif === 0 ? 'selected' : ''; ?>>
                        Tidak Aktif
                    </option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">

                <a href="kelola_kelas.php" class="btn btn-secondary">
                    Kembali
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>
