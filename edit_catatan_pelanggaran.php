
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$role = $_SESSION['role'] ?? '';
$user_id = (int)($_SESSION['id'] ?? 0);

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    header('Location: dashboard.php');
    exit;
}

$guru_id = 0;

if ($role !== 'admin') {
    $stmt = mysqli_prepare($koneksi, "
        SELECT id
        FROM t_guru
        WHERE user_id = ? AND status_aktif = 1
        LIMIT 1
    ");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if (!$guru) {
        die('Data guru tidak ditemukan.');
    }

    $guru_id = (int)$guru['id'];
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die('ID catatan tidak valid.');
}

$sql = "SELECT ps.*, s.nis
        FROM t_pelanggaran_siswa ps
        LEFT JOIN t_siswa s ON s.id = ps.siswa_id
        WHERE ps.id = ?";

if ($role !== 'admin') {
    $sql .= " AND ps.guru_id = ?";
}

$sql .= " LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);

if ($role === 'admin') {
    mysqli_stmt_bind_param($stmt, 'i', $id);
} else {
    mysqli_stmt_bind_param($stmt, 'ii', $id, $guru_id);
}

mysqli_stmt_execute($stmt);
$catatan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$catatan) {
    die('Catatan tidak ditemukan atau tidak memiliki akses.');
}

if (strtolower(trim($catatan['status'] ?? '')) !== 'belum ditindaklanjuti') {
    die('Catatan yang sudah ditindaklanjuti tidak bisa diedit.');
}

$data_pelanggaran = [];

$q = mysqli_query($koneksi, "
    SELECT id, kode, nama, poin
    FROM t_pelanggaran
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

while ($row = mysqli_fetch_assoc($q)) {
    $data_pelanggaran[] = $row;
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$menu = $role === 'admin'
    ? ($menu_admin ?? [])
    : ($role === 'guru' ? ($menu_guru ?? []) : ($menu_wali_kelas ?? []));
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Catatan Pelanggaran</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<style>
body {
    background: #f5f6fa;
    font-family: Arial, sans-serif;
}
.main-content {
    margin-left: 250px;
    padding: 25px;
    min-height: 100vh;
}
.card {
    border: none;
    border-radius: 13px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.card-header {
    background: #212529;
    color: white;
    padding: 17px 20px;
}
.form-control, .form-select {
    padding: 11px;
    border-radius: 8px;
}
.btn {
    border-radius: 8px;
}
.select2-container {
    width: 100% !important;
}
.select2-container .select2-selection--single {
    height: 46px !important;
    border: 1px solid #ced4da !important;
    border-radius: 8px !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 44px !important;
    padding-left: 12px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 44px !important;
}
@media(max-width:768px) {
    .main-content {
        margin-left: 0;
        padding: 15px;
    }
}
</style>
</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="mb-4">
        <h3 class="fw-bold">Edit Catatan Pelanggaran</h3>
        <p class="text-muted">Perbarui data pelanggaran siswa.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square me-1"></i>
                Form Edit Pelanggaran
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="proses_edit_catatan_pelanggaran.php" method="POST">

                <input type="hidden" name="id" value="<?= (int)$catatan['id']; ?>">
                <input type="hidden" name="csrf_token"
                       value="<?= e($_SESSION['csrf_token'] ?? ''); ?>">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Siswa</label>
                    <input type="text" class="form-control"
                           value="<?= e($catatan['nama_siswa']); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">NIS</label>
                    <input type="text" class="form-control"
                           value="<?= e($catatan['nis'] ?? '-'); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Jenis Pelanggaran</label>
                    <select name="pelanggaran_id" id="pelanggaran_id"
                            class="form-select select-search" required>
                        <option value="">-- Pilih Pelanggaran --</option>

                        <?php foreach ($data_pelanggaran as $p): ?>
                            <option value="<?= (int)$p['id']; ?>"
                                    data-poin="<?= (int)$p['poin']; ?>"
                                <?= (int)$catatan['pelanggaran_id'] === (int)$p['id'] ? 'selected' : ''; ?>>
                                <?= e($p['kode']); ?> -
                                <?= e($p['nama']); ?>
                                (<?= (int)$p['poin']; ?> Poin)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Poin Pelanggaran</label>
                    <input type="number" id="poin_tampil" class="form-control"
                           value="<?= (int)$catatan['poin']; ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Pelanggaran</label>
                    <input type="date" name="tanggal" class="form-control"
                           value="<?= e($catatan['tanggal']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Keterangan</label>
                    <textarea name="keterangan" class="form-control"
                              rows="4" required><?= e($catatan['keterangan']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>

                <a href="catatan_pelanggaran.php" class="btn btn-secondary">
                    Batal
                </a>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    $('.select-search').select2({
        width: '100%',
        placeholder: 'Ketik untuk mencari...',
        allowClear: true
    });

    $('#pelanggaran_id').on('change', function () {
        let poin = $(this).find(':selected').data('poin');
        $('#poin_tampil').val(poin !== undefined ? poin : 0);
    });
});
</script>

</body>
</html>
