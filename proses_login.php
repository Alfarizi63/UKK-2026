
<?php
session_start();

include 'config/koneksi.php';

if (!isset($_POST['login'])) {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['pesan_error'] = 'Email dan password wajib diisi!';
    header('Location: login.php');
    exit;
}

// ========================================
// CARI AKUN USER
// ========================================
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, name, email, password, role
     FROM t_users
     WHERE email = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$user) {
    $_SESSION['pesan_error'] = 'Email tidak ditemukan!';
    header('Location: login.php');
    exit;
}

// ========================================
// VERIFIKASI PASSWORD
// ========================================
if (!password_verify($password, $user['password'])) {
    $_SESSION['pesan_error'] = 'Password salah!';
    header('Location: login.php');
    exit;
}

$user_id = (int) $user['id'];
$role = $user['role'];

$guru_id = null;
$nama_guru = null;

// ========================================
// HUBUNGKAN AKUN DENGAN DATA GURU
// ========================================
if (in_array($role, ['guru', 'wali_kelas'], true)) {

    $stmt_guru = mysqli_prepare(
        $koneksi,
        "SELECT id, nama
         FROM t_guru
         WHERE user_id = ?
         AND status_aktif = 1
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $stmt_guru,
        "i",
        $user_id
    );

    mysqli_stmt_execute($stmt_guru);

    $result_guru = mysqli_stmt_get_result($stmt_guru);
    $guru = mysqli_fetch_assoc($result_guru);

    mysqli_stmt_close($stmt_guru);

    if (!$guru) {
        $_SESSION['pesan_error'] =
            'Akun belum terhubung dengan data guru aktif. Silakan hubungi admin.';

        header('Location: login.php');
        exit;
    }

    $guru_id = (int) $guru['id'];
    $nama_guru = $guru['nama'];
}

// ========================================
// SIMPAN SESSION
// ========================================
session_regenerate_id(true);

$_SESSION['login'] = true;

$_SESSION['id'] = $user_id;
$_SESSION['user_id'] = $user_id;

$_SESSION['name'] = $user['name'];
$_SESSION['email'] = $user['email'];
$_SESSION['role'] = $role;

$_SESSION['guru_id'] = $guru_id;
$_SESSION['nama_guru'] = $nama_guru;

// ========================================
// ARAHKAN KE DASHBOARD
// ========================================
header('Location: dashboard.php');
exit;
?>
