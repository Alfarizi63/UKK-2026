
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Ambil data guru beserta email akun user
$sql = "SELECT
            t_guru.*,
            t_users.email AS email_user
        FROM t_guru
        LEFT JOIN t_users ON t_guru.user_id = t_users.id
        ORDER BY t_guru.id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query data guru gagal: " . mysqli_error($koneksi));
}

// Ambil akun yang role-nya guru
$sql_user = "SELECT id, name, email
             FROM t_users
             WHERE role = 'guru'
             ORDER BY name ASC";

$query_user = mysqli_query($koneksi, $sql_user);

if (!$query_user) {
    die("Query akun guru gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Guru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
        }

        .main-content {
            margin-left: 250px;
            padding: 25px;
            min-width: 0;
        }

        .form-tambah {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.06);
        }

        .tabel-guru-wrapper {
            width: 100%;
            overflow-x: auto;
            background: white;
            border-radius: 6px;
        }

        #tabelGuru {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        #tabelGuru th,
        #tabelGuru td {
            padding: 10px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }

        #tabelGuru th {
            background: #212529;
            color: white;
            white-space: nowrap;
            cursor: pointer;
        }

        .kontrol-tabel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .kontrol-tabel input {
            max-width: 300px;
        }

        .paginasi {
            display: flex;
            gap: 5px;
            margin-top: 12px;
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

    <h1 class="mb-4">Kelola Guru</h1>

    <?php if (isset($_SESSION['pesan'])) { ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($_SESSION['pesan']); ?>
        </div>
        <?php unset($_SESSION['pesan']); ?>
    <?php } ?>

    <hr>

    <h2>Tambah Data Guru</h2>

    <form action="proses_guru.php" method="POST"
          class="form-tambah mb-4" id="formGuru">

        <input type="hidden" name="aksi" value="tambah">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">NIP</label>
                <input type="text"
                       name="nip"
                       id="nip"
                       class="form-control"
                       maxlength="20"
                       pattern="[0-9]{1,18}"
                       inputmode="numeric"
                       required>

                <small id="peringatanNIP" class="text-danger">
                    NIP maksimal 18 digit dan hanya boleh menggunakan angka.
                </small>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Guru</label>
                <input type="text"
                       name="nama"
                       class="form-control"
                       required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Akun Guru</label>

                <select name="user_id"
                        class="form-control"
                        required>

                    <option value="">
                        -- Pilih Akun Guru --
                    </option>

                    <?php while ($user = mysqli_fetch_assoc($query_user)) { ?>
                        <option value="<?= (int)$user['id']; ?>">
                            <?= htmlspecialchars($user['name']); ?>
                            -
                            <?= htmlspecialchars($user['email']); ?>
                        </option>
                    <?php } ?>

                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Status Aktif</label>

                <select name="status_aktif"
                        class="form-control"
                        required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

        </div>

        <button type="submit" class="btn btn-success">
            Tambah Guru
        </button>

    </form>

    <a href="dashboard.php" class="btn btn-secondary mb-3">
        Kembali ke Dashboard
    </a>

    <hr>

    <h2>Data Guru</h2>

    <div class="kontrol-tabel">

        <input type="search"
               id="cariGuru"
               class="form-control"
               placeholder="Cari NIP, nama, atau email...">

        <label class="mb-0">
            Tampilkan

            <select id="jumlahGuru" class="form-control"
                    style="display:inline-block;width:auto;">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="all">Semua</option>
            </select>

            data
        </label>

    </div>

    <div class="tabel-guru-wrapper">

        <table class="table table-striped table-hover"
               id="tabelGuru">

            <thead>
                <tr>
                    <th data-kolom="0">ID</th>
                    <th data-kolom="1">NIP</th>
                    <th data-kolom="2">Nama</th>
                    <th data-kolom="3">Email</th>
                    <th data-kolom="4">Status</th>
                    <th data-kolom="5">User ID</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (mysqli_num_rows($query) > 0) { ?>

                    <?php while ($guru = mysqli_fetch_assoc($query)) { ?>

                        <tr>
                            <td><?= (int)$guru['id']; ?></td>

                            <td>
                                <?= htmlspecialchars($guru['nip'] ?? ''); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($guru['nama'] ?? ''); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $guru['email_user'] ?? $guru['email'] ?? '-'
                                ); ?>
                            </td>

                            <td>
                                <?php if (($guru['status_aktif'] ?? 0) == 1) { ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php } else { ?>
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                <?php } ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($guru['user_id'] ?? '-'); ?>
                            </td>

                            <td class="text-nowrap">

                                <a href="edit_guru.php?id=<?= (int)$guru['id']; ?>"
                                   class="btn btn-primary btn-sm">
                                    Edit
                                </a>

                                <form action="proses_guru.php"
                                      method="POST"
                                      style="display:inline"
                                      onsubmit="return confirm('Yakin ingin menghapus data guru ini?');">

                                    <input type="hidden" name="aksi" value="hapus">

                                    <input type="hidden"
                                           name="id"
                                           value="<?= (int)$guru['id']; ?>">

                                    <button type="submit"
                                            class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>

                                </form>

                            </td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="7" class="text-center">
                            Belum ada data guru.
                        </td>
                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">

        <small id="infoGuru" class="text-muted"></small>

        <div class="paginasi" id="paginasiGuru"></div>

    </div>

</main>

<script>
// Validasi NIP
const inputNIP = document.getElementById('nip');
const peringatanNIP = document.getElementById('peringatanNIP');
const formGuru = document.getElementById('formGuru');

function validasiNIP() {
    const nilai = inputNIP.value;

    if (nilai.length > 18) {
        peringatanNIP.textContent =
            'Peringatan: NIP tidak boleh lebih dari 18 digit!';

        inputNIP.classList.add('is-invalid');
        inputNIP.setCustomValidity('NIP maksimal 18 digit.');

        return false;
    }

    if (nilai !== '' && !/^[0-9]+$/.test(nilai)) {
        peringatanNIP.textContent =
            'NIP hanya boleh berisi angka!';

        inputNIP.classList.add('is-invalid');
        inputNIP.setCustomValidity('NIP hanya boleh berisi angka.');

        return false;
    }

    peringatanNIP.textContent =
        'NIP maksimal 18 digit dan hanya boleh menggunakan angka.';

    inputNIP.classList.remove('is-invalid');
    inputNIP.setCustomValidity('');

    return true;
}

inputNIP.addEventListener('input', validasiNIP);

formGuru.addEventListener('submit', function(event) {
    if (!validasiNIP()) {
        event.preventDefault();
        inputNIP.focus();
    }
});

// Tabel guru
document.addEventListener('DOMContentLoaded', function () {

    const tabel = document.getElementById('tabelGuru');
    const tbody = tabel.querySelector('tbody');
    const pencarian = document.getElementById('cariGuru');
    const pilihanJumlah = document.getElementById('jumlahGuru');
    const info = document.getElementById('infoGuru');
    const paginasi = document.getElementById('paginasiGuru');

    const barisAsli = Array.from(tbody.querySelectorAll('tr'))
        .filter(function (tr) {
            return tr.cells.length === 7;
        });

    let hasil = barisAsli.slice();
    let halaman = 1;
    let kolomUrut = -1;
    let arah = 1;

    function render() {

        const kata = pencarian.value.toLowerCase().trim();

        hasil = barisAsli.filter(function (tr) {
            return tr.innerText.toLowerCase().includes(kata);
        });

        if (kolomUrut >= 0) {

            hasil.sort(function (a, b) {

                const x = a.cells[kolomUrut].innerText.trim();
                const y = b.cells[kolomUrut].innerText.trim();

                return x.localeCompare(y, 'id', {
                    numeric: true,
                    sensitivity: 'base'
                }) * arah;

            });

        }

        const jumlah = pilihanJumlah.value === 'all'
            ? Math.max(hasil.length, 1)
            : Number(pilihanJumlah.value);

        const totalHalaman = Math.max(
            1,
            Math.ceil(hasil.length / jumlah)
        );

        halaman = Math.min(halaman, totalHalaman);

        const awal = (halaman - 1) * jumlah;
        const dataTampil = hasil.slice(awal, awal + jumlah);

        tbody.replaceChildren();

        if (dataTampil.length > 0) {

            dataTampil.forEach(function (tr) {
                tbody.appendChild(tr);
            });

        } else {

            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 7;
            td.className = 'text-center';
            td.textContent = 'Data guru tidak ditemukan.';

            tr.appendChild(td);
            tbody.appendChild(tr);

        }

        if (hasil.length > 0) {

            info.textContent =
                'Menampilkan ' + (awal + 1) + '–' +
                Math.min(awal + dataTampil.length, hasil.length) +
                ' dari ' + hasil.length + ' data guru';

        } else {

            info.textContent = 'Tidak ada data guru yang cocok.';

        }

        renderPaginasi(totalHalaman);

    }

    function renderPaginasi(totalHalaman) {

        paginasi.replaceChildren();

        function buatTombol(teks, tujuan, nonaktif, aktif) {

            const tombol = document.createElement('button');

            tombol.type = 'button';
            tombol.textContent = teks;
            tombol.disabled = nonaktif;

            tombol.className = aktif
                ? 'btn btn-primary btn-sm'
                : 'btn btn-outline-primary btn-sm';

            tombol.addEventListener('click', function () {
                halaman = tujuan;
                render();
            });

            paginasi.appendChild(tombol);

        }

        buatTombol('‹', halaman - 1, halaman <= 1, false);

        for (let i = 1; i <= totalHalaman; i++) {
            buatTombol(String(i), i, false, i === halaman);
        }

        buatTombol('›', halaman + 1, halaman >= totalHalaman, false);

    }

    pencarian.addEventListener('input', function () {
        halaman = 1;
        render();
    });

    pilihanJumlah.addEventListener('change', function () {
        halaman = 1;
        render();
    });

    tabel.querySelectorAll('thead th[data-kolom]').forEach(function (th) {

        th.addEventListener('click', function () {

            const kolom = Number(th.dataset.kolom);

            if (kolomUrut === kolom) {
                arah *= -1;
            } else {
                kolomUrut = kolom;
                arah = 1;
            }

            halaman = 1;
            render();

        });

    });

    render();

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
