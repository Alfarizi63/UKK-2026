
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Hak akses admin dan guru
if (!in_array($_SESSION['role'] ?? '', ['admin', 'guru'], true)) {
    header('Location: dashboard.php');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    die("ID pelanggaran tidak valid.");
}

$stmt = $koneksi->prepare(
    "SELECT * FROM t_pelanggaran WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    die("Data pelanggaran tidak ditemukan.");
}

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama'] ?? '');
    $poin = (int) ($_POST['poin'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($nama === '') {
        $pesan = "Nama pelanggaran wajib diisi.";
    } elseif ($poin < 1) {
        $pesan = "Poin minimal 1.";
    } else {

        $stmt = $koneksi->prepare(
            "UPDATE t_pelanggaran
             SET nama = ?, poin = ?, deskripsi = ?
             WHERE id = ?"
        );

        $stmt->bind_param("sisi", $nama, $poin, $deskripsi, $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: catatan_pelanggaran.php");
            exit;
        }

        $pesan = "Gagal menyimpan perubahan: " . $stmt->error;
        $stmt->close();
    }

    // Pertahankan input jika terjadi kesalahan
    $data['nama'] = $nama;
    $data['poin'] = $poin;
    $data['deskripsi'] = $deskripsi;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggaran</title>

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
        <h3 class="page-title">Edit Pelanggaran</h3>
        <p class="text-muted mb-0">
            Perbarui informasi jenis pelanggaran.
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
                <label class="form-label">Nama Pelanggaran</label>

                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    value="<?= htmlspecialchars($data['nama'] ?? '') ?>"
                    placeholder="Masukkan nama pelanggaran"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Poin</label>

                <input
                    type="number"
                    name="poin"
                    class="form-control"
                    min="1"
                    value="<?= (int) $data['poin'] ?>"
                    placeholder="Masukkan jumlah poin"
                    required
                >
            </div>

            <div class="mb-4">
                <label class="form-label">Deskripsi</label>

                <textarea
                    name="deskripsi"
                    class="form-control"
                    rows="4"
                    placeholder="Masukkan deskripsi pelanggaran"
                ><?= htmlspecialchars($data['deskripsi'] ?? '') ?></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">

                <a href="catatan_pelanggaran.php" class="btn btn-secondary">
                    Batal
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
