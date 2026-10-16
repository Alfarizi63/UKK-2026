
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data laporan pelanggaran
$sql = "SELECT
            id,
            kode,
            nama,
            poin,
            deskripsi,
            status_aktif,
            created_at
        FROM t_pelanggaran
        ORDER BY id ASC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data laporan: " . mysqli_error($koneksi));
}

// Nama file hasil ekspor
$nama_file = 'laporan_pelanggaran_' . date('Y-m-d') . '.csv';

// Header agar browser mengunduh file CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $nama_file . '"');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// BOM agar karakter terbaca dengan benar di Excel
fwrite($output, "\xEF\xBB\xBF");

// Header kolom laporan
fputcsv($output, [
    'ID',
    'Kode Pelanggaran',
    'Nama Pelanggaran',
    'Poin',
    'Deskripsi',
    'Status',
    'Tanggal Dibuat'
], ';');

// Isi laporan
while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $row['id'],
        $row['kode'] ?? '',
        $row['nama'] ?? '',
        $row['poin'],
        $row['deskripsi'],
        (int)$row['status_aktif'] === 1 ? 'Aktif' : 'Nonaktif',
        $row['created_at'] ?? ''
    ], ';');
}

fclose($output);
exit;
?>
