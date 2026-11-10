
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
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
| TAMBAH GURU
|--------------------------------------------------------------------------
*/

if ($aksi === 'tambah') {

    $nip       = strtoupper(trim($_POST['nip'] ?? ''));
    $nama      = trim($_POST['nama'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';
    $status    = (int) ($_POST['status_aktif'] ?? 1);

    // Validasi data wajib
    if (
        $nip === '' ||
        $nama === '' ||
        $email === '' ||
        $password === ''
    ) {
        $_SESSION['pesan'] = 'Semua data wajib diisi.';
        header('Location: kelola_guru.php');
        exit;
    }

    // NIP harus diawali NIP dan diikuti 1-15 angka
    if (!preg_match('/^NIP[0-9]{1,15}$/', $nip)) {
        $_SESSION['pesan'] = 'NIP harus diawali NIP dan diikuti 1-15 angka.';
        header('Location: kelola_guru.php');
        exit;
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['pesan'] = 'Format email tidak valid.';
        header('Location: kelola_guru.php');
        exit;
    }

    // Validasi password
    if (strlen($password) < 8) {
        $_SESSION['pesan'] = 'Password minimal 8 karakter.';
        header('Location: kelola_guru.php');
        exit;
    }

    if ($password !== $konfirmasi_password) {
        $_SESSION['pesan'] = 'Konfirmasi password tidak sama.';
        header('Location: kelola_guru.php');
        exit;
    }

    // Validasi status
    if (!in_array($status, [0, 1], true)) {
        $status = 1;
    }

    // Cek NIP
    $cekNip = $koneksi->prepare(
        "SELECT id FROM t_guru WHERE nip = ? LIMIT 1"
    );

    if (!$cekNip) {
        $_SESSION['pesan'] = 'Gagal memeriksa NIP: ' . $koneksi->error;
        header('Location: kelola_guru.php');
        exit;
    }

    $cekNip->bind_param('s', $nip);
    $cekNip->execute();

    if ($cekNip->get_result()->num_rows > 0) {
        $cekNip->close();

        $_SESSION['pesan'] = 'NIP sudah terdaftar.';
        header('Location: kelola_guru.php');
        exit;
    }

    $cekNip->close();

    // Cek email
    $cekEmail = $koneksi->prepare(
        "SELECT id FROM t_users WHERE email = ? LIMIT 1"
    );

    if (!$cekEmail) {
        $_SESSION['pesan'] = 'Gagal memeriksa email: ' . $koneksi->error;
        header('Location: kelola_guru.php');
        exit;
    }

    $cekEmail->bind_param('s', $email);
    $cekEmail->execute();

    if ($cekEmail->get_result()->num_rows > 0) {
        $cekEmail->close();

        $_SESSION['pesan'] = 'Email sudah digunakan akun lain.';
        header('Location: kelola_guru.php');
        exit;
    }

    $cekEmail->close();

    // Simpan akun dan data guru dalam satu transaksi
    try {

        $koneksi->begin_transaction();

        // Buat akun login
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $role = 'guru';

        $stmtUser = $koneksi->prepare(
            "INSERT INTO t_users
                (name, email, password, role)
             VALUES (?, ?, ?, ?)"
        );

        if (!$stmtUser) {
            throw new Exception(
                'Gagal menyiapkan akun: ' . $koneksi->error
            );
        }

        $stmtUser->bind_param(
            'ssss',
            $nama,
            $email,
            $passwordHash,
            $role
        );

        if (!$stmtUser->execute()) {
            throw new Exception(
                'Gagal membuat akun: ' . $stmtUser->error
            );
        }

        $userId = $koneksi->insert_id;

        $stmtUser->close();

        // Simpan data guru
        $stmtGuru = $koneksi->prepare(
            "INSERT INTO t_guru
                (nip, nama, email, status_aktif, user_id, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, NOW(), NOW())"
        );

        if (!$stmtGuru) {
            throw new Exception(
                'Gagal menyiapkan data guru: ' . $koneksi->error
            );
        }

        // status_aktif dan user_id menggunakan integer
        $stmtGuru->bind_param(
            'sssii',
            $nip,
            $nama,
            $email,
            $status,
            $userId
        );

        if (!$stmtGuru->execute()) {
            throw new Exception(
                'Gagal menyimpan data guru: ' . $stmtGuru->error
            );
        }

        $stmtGuru->close();

        // Berhasil
        $koneksi->commit();

        $_SESSION['pesan'] =
            'Data guru dan akun login berhasil ditambahkan!';

        header('Location: kelola_guru.php');
        exit;

    } catch (Throwable $e) {

        $koneksi->rollback();

        $_SESSION['pesan'] =
            'Gagal menambahkan guru: ' . $e->getMessage();

        header('Location: kelola_guru.php');
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| HAPUS GURU
|--------------------------------------------------------------------------
*/

if ($aksi === 'hapus') {

    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {
        $_SESSION['pesan'] = 'ID guru tidak valid.';
        header('Location: kelola_guru.php');
        exit;
    }

    $stmt = $koneksi->prepare(
        "DELETE FROM t_guru WHERE id = ?"
    );

    if (!$stmt) {
        $_SESSION['pesan'] =
            'Gagal menyiapkan penghapusan: ' . $koneksi->error;

        header('Location: kelola_guru.php');
        exit;
    }

    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {

        $_SESSION['pesan'] = 'Data guru berhasil dihapus.';

    } else {

        $_SESSION['pesan'] =
            'Gagal menghapus data guru: ' . $stmt->error;
    }

    $stmt->close();

    header('Location: kelola_guru.php');
    exit;
}

// Aksi tidak dikenal
$_SESSION['pesan'] = 'Aksi tidak dikenali.';

header('Location: kelola_guru.php');
exit;
?>
