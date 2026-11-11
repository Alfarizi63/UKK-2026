
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$pesan = '';
$error = '';

// =====================================
// AMBIL TAHUN AJARAN AKTIF
// =====================================
$sql_tahun = "SELECT id, nama
              FROM t_tahun_ajaran
              WHERE status_aktif = 1
              LIMIT 1";

$query_tahun = mysqli_query($koneksi, $sql_tahun);
$tahun_aktif = mysqli_fetch_assoc($query_tahun);

if (!$tahun_aktif) {
    die("Belum ada tahun ajaran yang aktif. Silakan aktifkan tahun ajaran terlebih dahulu.");
}

$tahun_ajaran_id = (int) $tahun_aktif['id'];

// =====================================
// PROSES SIMPAN PENEMPATAN
// =====================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pastikan tahun ajaran masih aktif
    $cek_tahun = mysqli_query(
        $koneksi,
        "SELECT id FROM t_tahun_ajaran
         WHERE id = $tahun_ajaran_id
         AND status_aktif = 1
         LIMIT 1"
    );

    if (!$cek_tahun || mysqli_num_rows($cek_tahun) === 0) {
        $error = "Tahun ajaran aktif tidak ditemukan.";
    } else {

        $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
        $siswa_ids = $_POST['siswa_ids'] ?? [];

        if (!is_array($siswa_ids)) {
            $siswa_ids = [];
        }

        $siswa_ids = array_unique(
            array_filter(
                array_map('intval', $siswa_ids),
                function ($id) {
                    return $id > 0;
                }
            )
        );

        if ($kelas_id <= 0) {
            $error = "Silakan pilih kelas terlebih dahulu.";
        } elseif (empty($siswa_ids)) {
            $error = "Silakan pilih minimal satu siswa.";
        } else {

            // Pastikan kelas aktif
            $stmt_kelas = mysqli_prepare(
                $koneksi,
                "SELECT id FROM t_kelas
                 WHERE id = ?
                 AND status_aktif = 1
                 LIMIT 1"
            );

            mysqli_stmt_bind_param($stmt_kelas, "i", $kelas_id);
            mysqli_stmt_execute($stmt_kelas);
            $hasil_kelas = mysqli_stmt_get_result($stmt_kelas);

            if (mysqli_num_rows($hasil_kelas) === 0) {
                $error = "Kelas tidak ditemukan atau tidak aktif.";
            } else {

                mysqli_begin_transaction($koneksi);

                try {
                    $berhasil = 0;
                    $dilewati = 0;

                    foreach ($siswa_ids as $siswa_id) {

                        // Cek siswa aktif dan belum ditempatkan
                        $stmt_cek = mysqli_prepare(
                            $koneksi,
                            "SELECT s.id
                             FROM t_siswa s
                             WHERE s.id = ?
                             AND s.status_aktif = 1
                             AND NOT EXISTS (
                                 SELECT 1
                                 FROM t_kelas_siswa ks
                                 WHERE ks.siswa_id = s.id
                                 AND ks.tahun_ajaran_id = ?
                             )
                             LIMIT 1"
                        );

                        mysqli_stmt_bind_param(
                            $stmt_cek,
                            "ii",
                            $siswa_id,
                            $tahun_ajaran_id
                        );

                        mysqli_stmt_execute($stmt_cek);
                        $hasil_cek = mysqli_stmt_get_result($stmt_cek);

                        if (mysqli_num_rows($hasil_cek) === 0) {
                            $dilewati++;
                            continue;
                        }

                        // Simpan penempatan siswa
                        $stmt_insert = mysqli_prepare(
                            $koneksi,
                            "INSERT INTO t_kelas_siswa
                             (siswa_id, kelas_id, tahun_ajaran_id)
                             VALUES (?, ?, ?)"
                        );

                        mysqli_stmt_bind_param(
                            $stmt_insert,
                            "iii",
                            $siswa_id,
                            $kelas_id,
                            $tahun_ajaran_id
                        );

                        if (!mysqli_stmt_execute($stmt_insert)) {
                            throw new Exception(mysqli_stmt_error($stmt_insert));
                        }

                        $berhasil++;
                    }

                    mysqli_commit($koneksi);

                    if ($berhasil > 0) {
                        $pesan = "$berhasil siswa berhasil ditempatkan.";
                    }

                    if ($dilewati > 0) {
                        $pesan .= " $dilewati siswa dilewati karena tidak aktif atau sudah memiliki penempatan pada tahun ajaran ini.";
                    }

                    if ($berhasil === 0 && $dilewati === 0) {
                        $pesan = "Tidak ada siswa yang berhasil ditempatkan.";
                    }

                } catch (Exception $e) {
                    mysqli_rollback($koneksi);
                    $error = "Gagal menyimpan penempatan: " . $e->getMessage();
                }
            }
        }
    }
}

// =====================================
// AMBIL KELAS AKTIF
// =====================================
$sql_kelas = "SELECT id, nama
              FROM t_kelas
              WHERE status_aktif = 1
              ORDER BY nama ASC";

$query_kelas = mysqli_query($koneksi, $sql_kelas);

// =====================================
// AMBIL SISWA YANG BELUM DITEMPATKAN
// =====================================
$sql_siswa = "SELECT s.id, s.nis, s.nama
              FROM t_siswa s
              WHERE s.status_aktif = 1
              AND NOT EXISTS (
                  SELECT 1
                  FROM t_kelas_siswa ks
                  WHERE ks.siswa_id = s.id
                  AND ks.tahun_ajaran_id = $tahun_ajaran_id
              )
              ORDER BY s.nama ASC";

$query_siswa = mysqli_query($koneksi, $sql_siswa);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penempatan Siswa</title>

    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <style>
        body {
            background: #f5f6fa;
        }

        .konten {
            margin-left: 273px;
            padding: 25px;
        }

        .card {
            border: none;
            border-radius: 10px;
        }

        .judul {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .daftar-siswa {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            max-height: 350px;
            overflow-y: auto;
        }

        .item-siswa {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }

        .item-siswa:last-child {
            border-bottom: none;
        }

        .item-siswa:hover {
            background: #f8f9fa;
        }

        .jumlah-pilihan {
            font-size: 13px;
            color: #555;
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

    <h4 class="judul">Tambah Penempatan Siswa</h4>

    <?php if ($pesan !== ''): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($pesan); ?>
        </div>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST">

                <!-- TAHUN AJARAN OTOMATIS -->
                <div class="form-group">
                    <label>Tahun Ajaran Aktif</label>

                    <input type="text"
                           class="form-control"
                           value="<?= htmlspecialchars($tahun_aktif['nama']); ?>"
                           readonly>

                    <small class="form-text text-muted">
                        Tahun ajaran mengikuti pengaturan tahun ajaran aktif.
                    </small>
                </div>

                <!-- PILIH KELAS -->
                <div class="form-group">
                    <label>Pilih Kelas</label>

                    <input type="text"
                           id="cariKelas"
                           class="form-control mb-2"
                           placeholder="Cari nama kelas...">

                    <select name="kelas_id"
                            id="kelas_id"
                            class="form-control"
                            required>

                        <option value="">-- Pilih Kelas --</option>

                        <?php while ($kelas = mysqli_fetch_assoc($query_kelas)): ?>

                            <option value="<?= (int) $kelas['id']; ?>"
                                <?= (isset($_POST['kelas_id']) &&
                                     (int) $_POST['kelas_id'] === (int) $kelas['id'])
                                     ? 'selected' : ''; ?>>

                                <?= htmlspecialchars($kelas['nama']); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>
                </div>

                <!-- PILIH SISWA -->
                <div class="form-group">

                    <label>Pilih Siswa</label>

                    <input type="text"
                           id="cariSiswa"
                           class="form-control mb-2"
                           placeholder="Cari berdasarkan nama atau NIS...">

                    <div class="d-flex justify-content-between mb-2">

                        <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                id="pilihSemua">
                            Pilih Semua
                        </button>

                        <button type="button"
                                class="btn btn-sm btn-outline-secondary"
                                id="hapusPilihan">
                            Hapus Pilihan
                        </button>

                    </div>

                    <div class="daftar-siswa">

                        <?php if (mysqli_num_rows($query_siswa) > 0): ?>

                            <?php while ($siswa = mysqli_fetch_assoc($query_siswa)): ?>

                                <div class="item-siswa">

                                    <div class="custom-control custom-checkbox">

                                        <input type="checkbox"
                                               class="custom-control-input checkbox-siswa"
                                               id="siswa<?= (int) $siswa['id']; ?>"
                                               name="siswa_ids[]"
                                               value="<?= (int) $siswa['id']; ?>"
                                               <?= (isset($_POST['siswa_ids']) &&
                                                    in_array(
                                                        (int) $siswa['id'],
                                                        array_map('intval', (array) $_POST['siswa_ids'])
                                                    ))
                                                    ? 'checked' : ''; ?>>

                                        <label class="custom-control-label"
                                               for="siswa<?= (int) $siswa['id']; ?>">

                                            <?= htmlspecialchars($siswa['nis']); ?>
                                            -
                                            <?= htmlspecialchars($siswa['nama']); ?>

                                        </label>

                                    </div>

                                </div>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <div class="p-3 text-center text-muted">
                                Tidak ada siswa yang tersedia untuk ditempatkan.
                            </div>

                        <?php endif; ?>

                    </div>

                    <div class="jumlah-pilihan mt-2">
                        Jumlah siswa dipilih:
                        <strong id="jumlahDipilih">0</strong>
                    </div>

                </div>

                <hr>

                <button type="submit" class="btn btn-primary">
                    Simpan Penempatan
                </button>

                <a href="kelola_penempatan_siswa.php"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const cariKelas = document.getElementById('cariKelas');
    const selectKelas = document.getElementById('kelas_id');

    const cariSiswa = document.getElementById('cariSiswa');
    const pilihSemua = document.getElementById('pilihSemua');
    const hapusPilihan = document.getElementById('hapusPilihan');
    const jumlahDipilih = document.getElementById('jumlahDipilih');

    const itemSiswa = document.querySelectorAll('.item-siswa');
    const checkboxSiswa = document.querySelectorAll('.checkbox-siswa');

    // =================================
    // PENCARIAN KELAS
    // =================================
    cariKelas.addEventListener('input', function () {

        const kata = this.value.toLowerCase();

        Array.from(selectKelas.options).forEach(function (option, index) {

            if (index === 0) return;

            option.hidden = !option.text.toLowerCase().includes(kata);

        });

        if (
            selectKelas.selectedIndex > 0 &&
            !selectKelas.options[selectKelas.selectedIndex]
                .text.toLowerCase().includes(kata)
        ) {
            selectKelas.selectedIndex = 0;
        }

    });

    // =================================
    // PENCARIAN SISWA
    // =================================
    cariSiswa.addEventListener('input', function () {

        const kata = this.value.toLowerCase();

        itemSiswa.forEach(function (item) {

            const teks = item.innerText.toLowerCase();

            item.style.display = teks.includes(kata) ? '' : 'none';

        });

    });

    // =================================
    // HITUNG SISWA DIPILIH
    // =================================
    function hitungPilihan() {

        let jumlah = 0;

        checkboxSiswa.forEach(function (checkbox) {

            if (checkbox.checked) {
                jumlah++;
            }

        });

        jumlahDipilih.textContent = jumlah;

    }

    // =================================
    // PILIH SEMUA SISWA YANG TERLIHAT
    // =================================
    pilihSemua.addEventListener('click', function () {

        itemSiswa.forEach(function (item) {

            if (item.style.display !== 'none') {

                const checkbox = item.querySelector('.checkbox-siswa');

                if (checkbox) {
                    checkbox.checked = true;
                }

            }

        });

        hitungPilihan();

    });

    // =================================
    // HAPUS SEMUA PILIHAN
    // =================================
    hapusPilihan.addEventListener('click', function () {

        checkboxSiswa.forEach(function (checkbox) {
            checkbox.checked = false;
        });

        hitungPilihan();

    });

    // =================================
    // UPDATE JUMLAH CHECKBOX
    // =================================
    checkboxSiswa.forEach(function (checkbox) {

        checkbox.addEventListener('change', hitungPilihan);

    });

    hitungPilihan();

});
</script>

</body>
</html>
