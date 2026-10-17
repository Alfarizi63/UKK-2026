
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_pelanggaran ORDER BY id DESC";
$result = mysqli_query($koneksi, $sql);

if (!$result) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jenis Pelanggaran</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 25px;
            color: #222;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 22px;
            margin-bottom: 22px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        h2 {
            margin-top: 0;
        }

        label {
            display: block;
            margin: 14px 0 6px;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button, .btn {
            display: inline-block;
            border: none;
            padding: 9px 13px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            color: white;
            font-size: 14px;
        }

        .btn-simpan {
            background: #198754;
            margin-top: 16px;
        }

        .btn-edit {
            background: #0d6efd;
        }

        .btn-hapus {
            background: #dc3545;
        }

        .btn-kembali {
            background: #6c757d;
            margin-bottom: 18px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #e9ecef;
        }

        .pesan {
            padding: 12px;
            background: #d1e7dd;
            color: #0f5132;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .kosong {
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">

    <a href="dashboard.php" class="btn btn-kembali">
        Kembali ke Dashboard
    </a>

    <div class="card">
        <h2>Tambah Jenis Pelanggaran</h2>

        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="pesan">
                <?= htmlspecialchars($_SESSION['pesan']) ?>
            </div>
            <?php unset($_SESSION['pesan']); ?>
        <?php endif; ?>

        <form action="proses_jenis_pelanggaran.php" method="POST">

            <input type="hidden" name="aksi" value="tambah">

            <label for="nama_pelanggaran">
                Nama Pelanggaran
            </label>
            <input
                type="text"
                id="nama_pelanggaran"
                name="nama_pelanggaran"
                maxlength="150"
                required
            >

            <label for="poin">Poin Pelanggaran</label>
            <input
                type="number"
                id="poin"
                name="poin"
                min="1"
                required
            >

            <label for="keterangan">Keterangan</label>
            <textarea
                id="keterangan"
                name="keterangan"
                rows="3"
            ></textarea>

            <button type="submit" class="btn-simpan">
                Simpan Pelanggaran
            </button>

        </form>
    </div>

    <div class="card">
        <h2>Daftar Jenis Pelanggaran</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelanggaran</th>
                        <th>Poin</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <?= (int) $row['id'] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama'] ?? '-') ?>
                            </td>

                            <td>
                                <?= (int) $row['poin'] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['deskripsi'] ?? '-') ?>
                            </td>

                            <td>
                                <a
                                    href="edit_pelanggaran.php?id=<?= (int) $row['id'] ?>"
                                    class="btn btn-edit"
                                >Edit</a>

                                <a
                                    href="proses_jenis_pelanggaran.php?aksi=hapus&id=<?= (int) $row['id'] ?>"
                                    class="btn btn-hapus"
                                    onclick="return confirm('Yakin ingin menghapus pelanggaran ini?')"
                                >Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="kosong">
                            Belum ada data jenis pelanggaran.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
