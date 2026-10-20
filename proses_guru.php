```php
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// CEK LOGIN DAN HAK AKSES
// ==========================================
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kelola_guru.php');
    exit;
}

$aksi = $_POST['aksi'] ?? '';

/*
|--------------------------------------------------------------------------
| PROSES HAPUS GURU
|--------------------------------------------------------------------------
*/
if ($aksi === 'hapus') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {
        $_SESSION['pesan'] = 'ID guru tidak valid!';
        header('Location: kelola_guru.php');
        exit;
    }

    try {
        $stmt = $koneksi->prepare(
            "DELETE FROM t_guru WHERE id = ?"
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['pesan'] = 'Data guru berhasil dihapus!';
        } else {
            $_SESSION['pesan'] = 'Data guru tidak ditemukan!';
        }

        $stmt->close();

    } catch (mysqli_sql_exception $e) {
        error_log('Hapus guru: ' . $e->getMessage());

        $_SESSION['pesan'] =
            'Gagal menghapus guru. Periksa relasi database.';
    }

    header('Location: kelola_guru.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| PROSES TAMBAH GURU
|--------------------------------------------------------------------------
*/
if ($aksi === 'tambah') {
    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $user_id = (int) ($_POST['user_id'] ?? 0);
    $status_aktif = (int) ($_POST['status_aktif'] ?? 1);

    // Validasi input
    if ($nip === '' || $nama === '' || $email === '') {
        $_SESSION['pesan'] =
            'NIP, nama, dan email wajib diisi!';
        header('Location: kelola_guru.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['pesan'] = 'Format email tidak valid!';
        header('Location: kelola_guru.php');
        exit;
    }

    if ($user_id <= 0) {
        $_SESSION['pesan'] = 'Silakan pilih akun guru!';
        header('Location: kelola_guru.php');
        exit;
    }

    if (!in_array($status_aktif, [0, 1], true)) {
        $_SESSION['pesan'] = 'Status aktif tidak valid!';
        header('Location: kelola_guru.php');
        exit;
    }

    try {
        // Pastikan akun yang dipilih memang akun guru.
        $cek = $koneksi->prepare(
            "SELECT id
             FROM t_users
             WHERE id = ? AND role = 'guru'"
        );

        $cek->bind_param('i', $user_id);
        $cek->execute();

        $akunAda = $cek->get_result()->num_rows > 0;
        $cek->close();

        if (!$akunAda) {
            $_SESSION['pesan'] =
                'Akun guru tidak ditemukan di tabel t_users!';
            header('Location: kelola_guru.php');
            exit;
        }

        // Pastikan akun belum dipakai data guru lain.
        $cek = $koneksi->prepare(
            "SELECT id FROM t_guru WHERE user_id = ?"
        );

        $cek->bind_param('i', $user_id);
        $cek->execute();

        $akunTerpakai = $cek->get_result()->num_rows > 0;
        $cek->close();

        if ($akunTerpakai) {
            $_SESSION['pesan'] =
                'Akun tersebut sudah terhubung dengan data guru!';
            header('Location: kelola_guru.php');
            exit;
        }

        // Simpan data ke tabel t_guru.
        $stmt = $koneksi->prepare(
            "INSERT INTO t_guru
                (nip, nama, email, status_aktif, user_id,
                 created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, NOW(), NOW())"
        );

        $stmt->bind_param(
            'sssii',
            $nip,
            $nama,
            $email,
            $status_aktif,
            $user_id
        );

        $stmt->execute();
        $stmt->close();

        $_SESSION['pesan'] =
            'Data guru berhasil ditambahkan!';

    } catch (mysqli_sql_exception $e) {
        error_log('Tambah guru: ' . $e->getMessage());

        if ($e->getCode() === 1062) {
            $_SESSION['pesan'] =
                'NIP, email, atau akun guru sudah terdaftar!';
        } else {
            $_SESSION['pesan'] =
                'Gagal menambahkan guru. Periksa log error PHP.';
        }
    }

    header('Location: kelola_guru.php');
    exit;
}

// ==========================================
// AKSI TIDAK DIKENALI
// ==========================================
$_SESSION['pesan'] = 'Aksi tidak dikenali!';
header('Location: kelola_guru.php');
exit;
?>
```