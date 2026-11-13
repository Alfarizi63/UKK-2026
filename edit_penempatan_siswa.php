
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// ========================================
// VALIDASI ID PENEMPATAN
// ========================================
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: kelola_penempatan_siswa.php');
    exit;
}

// ========================================
// AMBIL DATA PENEMPATAN
// ========================================
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, siswa_id, kelas_id, tahun_ajaran_id
     FROM t_kelas_siswa
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    header('Location: kelola_penempatan_siswa.php');
    exit;
}

$siswa_id = (int) $data['siswa_id'];
$kelas_id = (int) $data['kelas_id'];

// ========================================
// AMBIL TAHUN AJARAN AKTIF
// ========================================
$query_tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     LIMIT 1"
);

$tahun_aktif = mysqli_fetch_assoc($query_tahun);

// ========================================
// PROSES UPDATE
// ========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $kelas_baru = filter_input(
        INPUT_POST,
        'kelas_id',
        FILTER_VALIDATE_INT
    );

    if (!$kelas_baru || $kelas_baru < 1) {
        $error = 'Silakan pilih kelas terlebih dahulu.';
    } elseif (!$tahun_aktif) {
        $error = 'Belum ada tahun ajaran yang aktif.';
    } else {

        $tahun_aktif_id = (int) $tahun_aktif['id'];

        // Cek kelas tersedia
        $cek_kelas = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_kelas WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $cek_kelas,
            "i",
            $kelas_baru
        );

        mysqli_stmt_execute($cek_kelas);

        $hasil_kelas = mysqli_stmt_get_result($cek_kelas);
        $kelas_valid = mysqli_fetch_assoc($hasil_kelas);

        mysqli_stmt_close($cek_kelas);

        if (!$kelas_valid) {
            $error = 'Kelas yang dipilih tidak ditemukan.';
        } else {

            // Cek duplikasi siswa pada tahun ajaran aktif
            $cek = mysqli_prepare(
                $koneksi,
                "SELECT id
                 FROM t_kelas_siswa
                 WHERE siswa_id = ?
                 AND tahun_ajaran_id = ?
                 AND id != ?
                 LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $cek,
                "iii",
                $siswa_id,
                $tahun_aktif_id,
                $id
            );

            mysqli_stmt_execute($cek);

            $hasil_cek = mysqli_stmt_get_result($cek);
            $duplikat = mysqli_fetch_assoc($hasil_cek);

            mysqli_stmt_close($cek);

            if ($duplikat) {
                $error = 'Siswa sudah memiliki penempatan pada tahun ajaran aktif.';
            } else {

                // Update penempatan
                $update = mysqli_prepare(
                    $koneksi,
                    "UPDATE t_kelas_siswa
                     SET kelas_id = ?, tahun_ajaran_id = ?
                     WHERE id = ?"
                );

                mysqli_stmt_bind_param(
                    $update,
                    "iii",
                    $kelas_baru,
                    $tahun_aktif_id,
                    $id
                );

                if (mysqli_stmt_execute($update)) {
                    mysqli_stmt_close($update);

                    $_SESSION['success_penempatan'] =
                        'Data penempatan siswa berhasil diperbarui.';

                    header('Location: kelola_penempatan_siswa.php');
                    exit;
                } else {
                    $error = 'Gagal memperbarui data penempatan.';
                    mysqli_stmt_close($update);
                }
            }
        }
    }

    $kelas_id = (int) $kelas_baru;
}

// ========================================
// AMBIL DATA SISWA YANG SUDAH DIPILIH
// ========================================
$stmt_siswa = mysqli_prepare(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     WHERE id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt_siswa,
    "i",
    $siswa_id
);

mysqli_stmt_execute($stmt_siswa);

$result_siswa = mysqli_stmt_get_result($stmt_siswa);
$siswa = mysqli_fetch_assoc($result_siswa);

mysqli_stmt_close($stmt_siswa);

// ========================================
// AMBIL SEMUA KELAS
// ========================================
$query_kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     ORDER BY tingkat ASC, nama ASC"
);

if (!$query_kelas) {
    die('Gagal mengambil data kelas: ' . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Penempatan Siswa</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Select2 -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <style>
        body {
            background: #f5f6fa;
            font-family: Arial, sans-serif;
        }

        .konten {
            margin-left: 273px;
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
        }

        .judul {
            font-weight: 600;
            color: #343a40;
        }

        .form-control {
            height: 43px;
            border-radius: 6px;
        }

        .btn {
            border-radius: 6px;
        }

        .info-siswa {
            background: #f1f5f9;
            border-radius: 7px;
            padding: 13px;
        }

        /* Tampilan Select2 */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 43px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 6px 10px;
        }

        .select2-container--default
        .select2-selection--single
        .select2-selection__rendered {
            line-height: 29px;
            padding-left: 2px;
            color: #495057;
        }

        .select2-container--default
        .select2-selection--single
        .select2-selection__arrow {
            height: 41px;
            right: 8px;
        }

        .select2-container--default.select2-container--focus
        .select2-selection--single {
            border-color: #80bdff;
        }

        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 6px;
        }

        .select2-search--dropdown .select2-search__field {
            padding: 8px;
            border-radius: 5px;
        }

        .select2-results__option {
            padding: 9px 12px;
        }

        @media (max-width: 768px) {
            .konten {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="konten">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="judul mb-1">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Penempatan Siswa
                </h4>

                <small class="text-muted">
                    Perbarui kelas penempatan siswa.
                </small>
            </div>

            <a href="kelola_penempatan_siswa.php"
               class="btn btn-secondary btn-sm">

                <i class="fas fa-arrow-left mr-1"></i>
                Kembali

            </a>

        </div>

        <!-- FORM -->
        <div class="card">
            <div class="card-body">

                <?php if (!empty($error)): ?>

                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= htmlspecialchars($error); ?>
                    </div>

                <?php endif; ?>

                <?php if (!$tahun_aktif): ?>

                    <div class="alert alert-warning">
                        Belum ada tahun ajaran aktif.
                        Silakan aktifkan tahun ajaran terlebih dahulu.
                    </div>

                <?php endif; ?>

                <?php if (!$siswa): ?>

                    <div class="alert alert-danger">
                        Data siswa tidak ditemukan.
                    </div>

                <?php else: ?>

                    <form method="POST">

                        <!-- SISWA -->
                        <div class="form-group">

                            <label>Nama Siswa</label>

                            <div class="info-siswa">

                                <strong>
                                    <?= htmlspecialchars($siswa['nama']); ?>
                                </strong>

                                <br>

                                <small class="text-muted">
                                    NIS:
                                    <?= htmlspecialchars($siswa['nis']); ?>
                                </small>

                            </div>

                            <small class="text-muted">
                                Data siswa mengikuti penempatan sebelumnya.
                            </small>

                        </div>

                        <!-- TAHUN AJARAN -->
                        <div class="form-group">

                            <label>Tahun Ajaran Aktif</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($tahun_aktif['nama'] ?? 'Belum tersedia'); ?>"
                                   readonly>

                        </div>

                        <!-- PILIH KELAS -->
                        <div class="form-group">

                            <label for="kelas_id">
                                Pilih Kelas
                            </label>

                            <select name="kelas_id"
                                    id="kelas_id"
                                    class="form-control"
                                    required>

                                <option value=""></option>

                                <?php while ($kelas = mysqli_fetch_assoc($query_kelas)): ?>

                                    <?php
                                    $nama_kelas = $kelas['nama'];

                                    if (!empty($kelas['tingkat'])) {
                                        $nama_kelas .= ' - ' . $kelas['tingkat'];
                                    }

                                    if (!empty($kelas['jurusan'])) {
                                        $nama_kelas .= ' - ' . $kelas['jurusan'];
                                    }
                                    ?>

                                    <option
                                        value="<?= (int) $kelas['id']; ?>"
                                        <?= $kelas_id === (int) $kelas['id'] ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($nama_kelas); ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                            <small class="text-muted">
                                Klik kolom di atas lalu ketik nama kelas untuk mencari.
                            </small>

                        </div>

                        <hr>

                        <!-- TOMBOL -->
                        <div class="d-flex justify-content-end">

                            <a href="kelola_penempatan_siswa.php"
                               class="btn btn-light mr-2">

                                Batal

                            </a>

                            <button type="submit"
                                    class="btn btn-primary"
                                    <?= !$tahun_aktif ? 'disabled' : ''; ?>>

                                <i class="fas fa-save mr-1"></i>
                                Simpan Perubahan

                            </button>

                        </div>

                    </form>

                <?php endif; ?>

            </div>
        </div>

    </div>

    <!-- JQuery Full (Diperlukan Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Popper dan Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {

            $('#kelas_id').select2({
                width: '100%',
                placeholder: 'Cari atau pilih kelas...',
                allowClear: true,
                language: {
                    noResults: function () {
                        return 'Kelas tidak ditemukan';
                    },
                    searching: function () {
                        return 'Sedang mencari...';
                    }
                }
            });

        });
    </script>

</body>
</html>
