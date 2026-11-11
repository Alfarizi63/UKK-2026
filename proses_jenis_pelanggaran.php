
<?php
session_start();

include 'config/koneksi.php';

// Fungsi kembali ke halaman kelola jenis pelanggaran
function kembali($pesan)
{
    $_SESSION['pesan'] = $pesan;
    header('Location: kelola_jenis_pelanggaran.php');
    exit;
}

// ==========================
// PROSES TAMBAH PELANGGARAN
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $aksi = $_POST['aksi'] ?? '';

    if ($aksi === 'tambah') {

        $kategori_id = filter_input(
            INPUT_POST,
            'pelanggaran_kategori_id',
            FILTER_VALIDATE_INT
        );

        $nama = trim($_POST['nama_pelanggaran'] ?? '');
        $poin = filter_input(INPUT_POST, 'poin', FILTER_VALIDATE_INT);
        $deskripsi = trim($_POST['keterangan'] ?? '');

        // Validasi data
        if (
            !$kategori_id ||
            $kategori_id < 1 ||
            $nama === '' ||
            $poin === false ||
            $poin === null ||
            $poin < 1
        ) {
            kembali('Kategori, nama pelanggaran, dan poin wajib diisi dengan benar.');
        }

        // Pastikan kategori tersedia di database
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_pelanggaran_kategori WHERE id = ?"
        );

        if (!$cek) {
            kembali('Gagal memeriksa kategori pelanggaran.');
        }

        mysqli_stmt_bind_param($cek, 'i', $kategori_id);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);

        $kategori_ada = mysqli_stmt_num_rows($cek) > 0;

        mysqli_stmt_close($cek);

        if (!$kategori_ada) {
            kembali('Kategori pelanggaran tidak ditemukan. Silakan pilih kategori yang tersedia.');
        }

        // Simpan data ke tabel t_pelanggaran
        $sql = "INSERT INTO t_pelanggaran
                (pelanggaran_kategori_id, nama, poin, deskripsi, status_aktif)
                VALUES (?, ?, ?, ?, 1)";

        $stmt = mysqli_prepare($koneksi, $sql);

        if (!$stmt) {
            kembali('Gagal menyiapkan penyimpanan data.');
        }

        mysqli_stmt_bind_param(
            $stmt,
            'isis',
            $kategori_id,
            $nama,
            $poin,
            $deskripsi
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            kembali('Jenis pelanggaran berhasil ditambahkan.');
        }

        mysqli_stmt_close($stmt);
        kembali('Gagal menyimpan jenis pelanggaran: ' . mysqli_error($koneksi));
    }

    kembali('Aksi tidak dikenali.');
}

// ==========================
// PROSES HAPUS PELANGGARAN
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $aksi = $_GET['aksi'] ?? '';

    if ($aksi === 'hapus') {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            kembali('ID pelanggaran tidak valid.');
        }

        $sql = "DELETE FROM t_pelanggaran WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $sql);

        if (!$stmt) {
            kembali('Gagal menyiapkan penghapusan data.');
        }

        mysqli_stmt_bind_param($stmt, 'i', $id);

        if (mysqli_stmt_execute($stmt)) {
            $jumlah = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);

            if ($jumlah > 0) {
                kembali('Jenis pelanggaran berhasil dihapus.');
            }

            kembali('Data pelanggaran tidak ditemukan.');
        }

        mysqli_stmt_close($stmt);
        kembali('Data gagal dihapus. Periksa apakah data digunakan oleh catatan pelanggaran siswa.');
    }
}

kembali('Permintaan tidak valid.');
?>
