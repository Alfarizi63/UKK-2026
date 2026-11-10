
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Harus POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kelola_guru.php');
    exit;
}

$id       = (int)($_POST['id'] ?? 0);
$nip      = strtoupper(trim($_POST['nip'] ?? ''));
$nama     = trim($_POST['nama'] ?? '');
$status   = $_POST['status_aktif'] ?? '';
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

function kembaliEdit($id, $pesan) {
    $_SESSION['pesan'] = $pesan;
    header('Location: edit_guru.php?id=' . $id);
    exit;
}

// Validasi ID
if ($id <= 0) {
    $_SESSION['pesan'] = 'ID guru tidak valid.';
    header('Location: kelola_guru.php');
    exit;
}

// Validasi NIP
if (!preg_match('/^NIP[0-9]{1,15}$/', $nip)) {
    kembaliEdit($id, 'NIP harus diawali NIP dan diikuti 1 sampai 15 angka.');
}

// Validasi nama
if ($nama === '' || $name === '') {
    kembaliEdit($id, 'Nama guru dan nama akun wajib diisi.');
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    kembaliEdit($id, 'Format email tidak valid.');
}

// Validasi status
if (!in_array((string)$status, ['0', '1'], true)) {
    kembaliEdit($id, 'Status guru tidak valid.');
}

$status = (int)$status;

mysqli_begin_transaction($koneksi);

try {

    // Ambil data guru
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT user_id FROM t_guru WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $dataGuru = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$dataGuru) {
        throw new Exception('Data guru tidak ditemukan.');
    }

    $userId = (int)$dataGuru['user_id'];

    // Cek NIP duplikat selain guru yang sedang diedit
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_guru WHERE nip = ? AND id != ?"
    );

    mysqli_stmt_bind_param($stmt, "si", $nip, $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        mysqli_stmt_close($stmt);
        throw new Exception('NIP sudah digunakan oleh guru lain.');
    }

    mysqli_stmt_close($stmt);

    // Cek email duplikat selain akun milik guru ini
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_users WHERE email = ? AND id != ?"
    );

    mysqli_stmt_bind_param($stmt, "si", $email, $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        mysqli_stmt_close($stmt);
        throw new Exception('Email sudah digunakan oleh akun lain.');
    }

    mysqli_stmt_close($stmt);

    // Update akun tanpa mengganti password
    if ($password === '') {

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_users
             SET name = ?, email = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssi",
            $name,
            $email,
            $userId
        );

    } else {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_users
             SET name = ?, email = ?, password = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $name,
            $email,
            $passwordHash,
            $userId
        );
    }

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Gagal memperbarui akun pengguna.');
    }

    mysqli_stmt_close($stmt);

    // Update data guru
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE t_guru
         SET nip = ?,
             nama = ?,
             email = ?,
             status_aktif = ?,
             updated_at = NOW()
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssii",
        $nip,
        $nama,
        $email,
        $status,
        $id
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Gagal memperbarui data guru.');
    }

    mysqli_stmt_close($stmt);

    mysqli_commit($koneksi);

    $_SESSION['pesan'] = 'Data guru berhasil diperbarui.';
    header('Location: kelola_guru.php');
    exit;

} catch (Throwable $e) {

    mysqli_rollback($koneksi);

    kembaliEdit(
        $id,
        'Gagal memperbarui data guru: ' . $e->getMessage()
    );
}
?>
