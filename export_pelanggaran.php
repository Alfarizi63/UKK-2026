
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT id, kode, nama, poin, deskripsi, status_aktif, created_at
        FROM t_pelanggaran
        ORDER BY id ASC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data laporan: " . mysqli_error($koneksi));
}

$nama_file = 'Laporan_Pelanggaran_' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $nama_file . '"');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// Agar karakter terbaca dengan benar di Excel
fwrite($output, "\xEF\xBB\xBF");

// Baris judul laporan
fputcsv($output, ['LAPORAN DATA JENIS PELANGGARAN'], ';');
fputcsv($output, ['Tanggal Ekspor', date('d-m-Y')], ';');
fputcsv($output, [], ';');

// Header tabel
fputcsv($output, [
    'No',
    'Kode Pelanggaran',
    'Nama Pelanggaran',
    'Poin',
    'Deskripsi',
    'Status',
    'Tanggal Dibuat'
], ';');

$no = 1;

while ($row = mysqli_fetch_assoc($query)) {
    $tanggal = '-';

    if (!empty($row['created_at'])) {
        $timestamp = strtotime($row['created_at']);
        if ($timestamp !== false) {
            $tanggal = date('d-m-Y H:i', $timestamp);
        }
    }

    fputcsv($output, [
        $no++,
        $row['kode'] ?? '-',
        $row['nama'] ?? '-',
        (int) $row['poin'],
        $row['deskripsi'] ?? '-',
        (int) $row['status_aktif'] === 1 ? 'Aktif' : 'Nonaktif',
        $tanggal
    ], ';');
}

fclose($output);
exit;
?>
