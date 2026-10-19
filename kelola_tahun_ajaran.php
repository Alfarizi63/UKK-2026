
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$query = mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran ORDER BY id DESC");
$data_tahun = [];

while ($row = mysqli_fetch_assoc($query)) {
    $data_tahun[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Tahun Ajaran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <style>
        body {
            background: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 10px 14px;
            border-radius: 4px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .sidebar a.active {
            background: #007bff;
            color: white;
        }

        .sidebar h4 {
            color: white;
            font-weight: bold;
            margin-bottom: 20px;
        }

        main {
            min-width: 0;
        }

        @media (max-width: 767px) {
            .sidebar {
                position: static !important;
                min-height: auto;
            }

            main {
                margin-left: 0 !important;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 sidebar p-3">
            <h4>My Website</h4>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_siswa.php" class="nav-link">Data Siswa</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_guru.php" class="nav-link">Data Guru</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_kelas.php" class="nav-link">Kelas</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link">
                        kelola Kategori Pelanggaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="kelola_tahun_ajaran.php" class="nav-link active">
                        Tahun Ajaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="laporan.php" class="nav-link">Laporan</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_wali_kelas.php" class="nav-link">Wali Kelas</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_penempatan_siswa.php" class="nav-link">
                        Penempatan Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a href="rekap_point.php" class="nav-link">Rekap Poin</a>
                </li>
                <li class="nav-item">
                    <a href="riwayat.php" class="nav-link">Riwayat</a>
                </li>
                <li class="nav-item">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link">
                        Jenis Pelanggaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="export_pelanggaran.php" class="nav-link">
                        Export Pelanggaran
                    </a>
                </li>
                <li class="nav-item">
                    <a href="tentang_saya.php" class="nav-link">About Me</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>
            </ul>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <div class="card">
                <div class="card-header">
                    <h4>Kelola Tahun Ajaran</h4>
                    <p class="mb-0">Halaman pengelolaan data tahun ajaran sekolah.</p>
                </div>

                <div class="card-body">

                    <a href="dashboard.php" class="btn btn-secondary mb-3">Kembali</a>
                    <a href="tambah_tahun_ajaran.php" class="btn btn-primary mb-3">+ Tambah Tahun Ajaran</a>

                    <div class="form-group">
                        <input type="text" id="searchInput" class="form-control"
                               placeholder="Cari tahun ajaran atau tanggal...">
                    </div>

                    <div class="form-group">
                        <label for="rowsPerPage">Tampilkan:</label>
                        <select id="rowsPerPage" class="form-control" style="width: auto;">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="all">Semua</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th onclick="sortTable(0)">ID</th>
                                    <th onclick="sortTable(1)">Tahun Ajaran</th>
                                    <th onclick="sortTable(2)">Tanggal Mulai</th>
                                    <th onclick="sortTable(3)">Tanggal Selesai</th>
                                    <th onclick="sortTable(4)">Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php foreach ($data_tahun as $tahun) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($tahun['id']) ?></td>
                                        <td><?= htmlspecialchars($tahun['nama']) ?></td>
                                        <td><?= htmlspecialchars($tahun['tanggal_mulai']) ?></td>
                                        <td><?= htmlspecialchars($tahun['tanggal_selesai']) ?></td>
                                        <td>
                                            <?php if ($tahun['status_aktif'] == 1) { ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php } else { ?>
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="edit_tahun_ajaran.php?id=<?= $tahun['id'] ?>"
                                               class="btn btn-warning btn-sm">Edit</a>

                                            <a href="hapus_tahun_ajaran.php?id=<?= $tahun['id'] ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus data tahun ajaran ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php if (count($data_tahun) == 0) { ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data tahun ajaran.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="pagination" class="mt-3"></div>

                </div>
            </div>

        </main>
    </div>
</div>

<script>
const table = document.getElementById('dataTable');
const tbody = document.getElementById('tableBody');
const searchInput = document.getElementById('searchInput');
const rowsPerPage = document.getElementById('rowsPerPage');
const pagination = document.getElementById('pagination');

let currentPage = 1;
let sortColumn = -1;
let sortDirection = 1;

function getRows() {
    return Array.from(tbody.querySelectorAll('tr')).filter(row =>
        row.cells.length > 1
    );
}

function filterRows() {
    const keyword = searchInput.value.toLowerCase();

    return getRows().filter(row =>
        row.innerText.toLowerCase().includes(keyword)
    );
}

function renderTable() {
    const allRows = getRows();
    const filteredRows = filterRows();
    const limit = rowsPerPage.value === 'all'
        ? filteredRows.length
        : parseInt(rowsPerPage.value);

    allRows.forEach(row => row.style.display = 'none');

    const totalPages = Math.max(1, Math.ceil(filteredRows.length / limit));

    if (currentPage > totalPages) {
        currentPage = totalPages;
    }

    const start = (currentPage - 1) * limit;
    const end = rowsPerPage.value === 'all'
        ? filteredRows.length
        : start + limit;

    filteredRows.slice(start, end).forEach(row => {
        row.style.display = '';
    });

    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    pagination.innerHTML = '';

    if (rowsPerPage.value === 'all' || totalPages <= 1) {
        return;
    }

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'btn btn-sm mr-1 ' +
            (i === currentPage ? 'btn-primary' : 'btn-secondary');
        button.textContent = i;

        button.onclick = function () {
            currentPage = i;
            renderTable();
        };

        pagination.appendChild(button);
    }
}

function sortTable(column) {
    if (column === 5) return;

    const rows = getRows();

    if (sortColumn === column) {
        sortDirection *= -1;
    } else {
        sortColumn = column;
        sortDirection = 1;
    }

    rows.sort((a, b) => {
        const valueA = a.cells[column].innerText.trim();
        const valueB = b.cells[column].innerText.trim();

        return valueA.localeCompare(valueB, 'id', {
            numeric: true,
            sensitivity: 'base'
        }) * sortDirection;
    });

    rows.forEach(row => tbody.appendChild(row));

    currentPage = 1;
    renderTable();
}

searchInput.addEventListener('input', function () {
    currentPage = 1;
    renderTable();
});

rowsPerPage.addEventListener('change', function () {
    currentPage = 1;
    renderTable();
});

renderTable();
</script>

</body>
</html>
