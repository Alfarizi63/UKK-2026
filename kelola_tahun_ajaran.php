
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        main {
            min-width: 0;
        }

        .table th {
            cursor: pointer;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h4 class="mb-1">Kelola Tahun Ajaran</h4>
            <p class="mb-0 text-muted">
                Halaman pengelolaan data tahun ajaran sekolah.
            </p>
        </div>

        <div class="card-body">

            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali
            </a>

            <a href="tambah_tahun_ajaran.php" class="btn btn-primary mb-3">
                + Tambah Tahun Ajaran
            </a>

            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control"
                       placeholder="Cari tahun ajaran atau tanggal...">
            </div>

            <div class="mb-3">
                <label for="rowsPerPage" class="form-label">Tampilkan:</label>
                <select id="rowsPerPage" class="form-select" style="width: auto;">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="all">Semua</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable">
                    <thead class="table-dark">
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
                                        <span class="badge bg-success">Aktif</span>
                                    <?php } else { ?>
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <a href="edit_tahun_ajaran.php?id=<?= urlencode($tahun['id']) ?>"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <a href="hapus_tahun_ajaran.php?id=<?= urlencode($tahun['id']) ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus data tahun ajaran ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if (count($data_tahun) == 0) { ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    Belum ada data tahun ajaran.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div id="pagination" class="mt-3"></div>

        </div>
    </div>

</main>

<script>
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

    const totalPages = Math.max(
        1,
        Math.ceil(filteredRows.length / Math.max(limit, 1))
    );

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
        button.className = 'btn btn-sm me-1 ' +
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
