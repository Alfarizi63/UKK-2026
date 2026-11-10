
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

if (empty($_SESSION['csrf_import_guru'])) {
    $_SESSION['csrf_import_guru'] = bin2hex(random_bytes(32));
}

$sql = "
    SELECT
        g.id,
        g.nip,
        g.nama,
        g.email,
        g.user_id,
        u.id AS akun_id,
        u.role AS akun_role,
        u.email AS email_akun
    FROM t_guru g
    LEFT JOIN t_users u ON u.id = g.user_id
    ORDER BY g.nama ASC
";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die('Gagal mengambil data guru: ' . mysqli_error($koneksi));
}

$dataGuru = [];
while ($row = mysqli_fetch_assoc($query)) {
    $dataGuru[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Import Akun Guru</title>

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

        .table-wrapper {
            background: white;
            border-radius: 8px;
            overflow-x: auto;
        }

        .table th,
        .table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
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

    <h2 class="mb-2">Import Akun Guru</h2>
    <p class="text-muted">
        Data diambil langsung dari tabel t_guru.
    </p>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_SESSION['pesan']); ?>
        </div>
        <?php unset($_SESSION['pesan']); ?>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5>Informasi Import</h5>
            <p class="mb-1">Email login menggunakan email yang tersimpan di data guru.</p>
            <p class="mb-1">Password awal akun baru: <strong>12345678</strong></p>
            <p class="mb-0">Akun yang sudah terhubung tidak akan dibuat ulang.</p>
        </div>
    </div>

    <form action="proses_import_guru.php" method="POST"
          onsubmit="return konfirmasiImport();">

        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_import_guru']); ?>">

        <div class="toolbar mb-3">
            <input type="search" id="cariGuru"
                   class="form-control" style="max-width:350px"
                   placeholder="Cari NIP, nama, atau email...">

            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-outline-primary"
                        id="pilihSemua">
                    Pilih Semua
                </button>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-person-plus"></i>
                    Import Akun Terpilih
                </button>
            </div>
        </div>

        <div class="table-wrapper shadow-sm">
            <table class="table table-striped table-hover mb-0" id="tabelGuru">
                <thead class="table-dark">
                    <tr>
                        <th>
                            <input type="checkbox" id="checkHeader">
                        </th>
                        <th>No.</th>
                        <th>NIP</th>
                        <th>Nama Guru</th>
                        <th>Email</th>
                        <th>Status Akun</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (count($dataGuru) > 0): ?>
                    <?php foreach ($dataGuru as $index => $guru): ?>
                        <?php
                            $sudahTerhubung = !empty($guru['akun_id']);
                            $emailValid = filter_var(
                                trim($guru['email'] ?? ''),
                                FILTER_VALIDATE_EMAIL
                            );

                            $bisaImport = !$sudahTerhubung && $emailValid;
                            $status = '';

                            if ($sudahTerhubung) {
                                if ($guru['akun_role'] === 'guru') {
                                    $status = 'Sudah memiliki akun';
                                } else {
                                    $status = 'Terhubung ke role lain';
                                }
                            } elseif (!$emailValid) {
                                $status = 'Email tidak valid/kosong';
                            } else {
                                $status = 'Belum memiliki akun';
                            }
                        ?>

                        <tr>
                            <td>
                                <input type="checkbox"
                                       class="checkGuru"
                                       name="guru_id[]"
                                       value="<?= (int)$guru['id']; ?>"
                                       <?= $bisaImport ? '' : 'disabled'; ?>>
                            </td>

                            <td><?= $index + 1; ?></td>

                            <td><?= htmlspecialchars($guru['nip'] ?? '-'); ?></td>

                            <td><?= htmlspecialchars($guru['nama'] ?? '-'); ?></td>

                            <td><?= htmlspecialchars($guru['email'] ?? '-'); ?></td>

                            <td>
                                <?php if ($sudahTerhubung && $guru['akun_role'] === 'guru'): ?>
                                    <span class="badge bg-success">
                                        <?= htmlspecialchars($status); ?>
                                    </span>
                                <?php elseif ($bisaImport): ?>
                                    <span class="badge bg-warning text-dark">
                                        <?= htmlspecialchars($status); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <?= htmlspecialchars($status); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">
                            Belum ada data guru.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-between flex-wrap gap-2">
            <a href="kelola_guru.php" class="btn btn-secondary">
                Kembali ke Kelola Guru
            </a>

            <button type="submit" class="btn btn-success">
                Import Akun Terpilih
            </button>
        </div>

    </form>

</main>

<script>
const cariGuru = document.getElementById('cariGuru');
const checkHeader = document.getElementById('checkHeader');
const pilihSemua = document.getElementById('pilihSemua');

function semuaCheckbox() {
    return Array.from(document.querySelectorAll('.checkGuru'))
        .filter(cb => !cb.disabled);
}

function perbaruiHeader() {
    const semua = semuaCheckbox();
    checkHeader.checked = semua.length > 0 &&
        semua.every(cb => cb.checked);
}

checkHeader.addEventListener('change', function () {
    semuaCheckbox().forEach(cb => {
        if (cb.closest('tr').style.display !== 'none') {
            cb.checked = checkHeader.checked;
        }
    });
});

pilihSemua.addEventListener('click', function () {
    const semua = semuaCheckbox();
    const terlihat = semua.filter(cb =>
        cb.closest('tr').style.display !== 'none'
    );

    const pilih = terlihat.some(cb => !cb.checked);

    terlihat.forEach(cb => cb.checked = pilih);
    perbaruiHeader();
});

document.querySelectorAll('.checkGuru').forEach(cb => {
    cb.addEventListener('change', perbaruiHeader);
});

cariGuru.addEventListener('input', function () {
    const kata = this.value.toLowerCase();

    document.querySelectorAll('#tabelGuru tbody tr').forEach(tr => {
        tr.style.display = tr.innerText.toLowerCase().includes(kata)
            ? ''
            : 'none';
    });
});

function konfirmasiImport() {
    const dipilih = semuaCheckbox().filter(cb => cb.checked);

    if (dipilih.length === 0) {
        alert('Pilih minimal satu guru yang akan diimport.');
        return false;
    }

    return confirm(
        'Import akun untuk ' + dipilih.length +
        ' guru terpilih? Password awal akun baru adalah 12345678.'
    );
}
</script>

</body>
</html>
