
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: kelola_guru.php");
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$status = (int) ($_POST['status_aktif'] ?? 0);

if (
    $id <= 0 || $nip === '' || $nama === '' ||
    !in_array($status, [0, 1], true) ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    die("Data tidak valid. Periksa kembali formulir.");
}

// Pastikan data guru tersedia
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT user_id FROM t_guru WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);

if (!$guru) {
    die("Data guru tidak ditemukan.");
}

// Perbarui data guru
$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE t_guru
     SET nip = ?, nama = ?, status_aktif = ?
     WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "ssii", $nip, $nama, $status, $id);

if (!mysqli_stmt_execute($stmt)) {
    die("Gagal memperbarui guru: " .
        htmlspecialchars(mysqli_stmt_error($stmt)));
}

// Perbarui akun jika user_id valid
$user_id = (int) ($guru['user_id'] ?? 0);

if ($user_id > 0) {
    if ($name === '') {
        die("Nama akun tidak boleh kosong.");
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE t_users SET name = ?, email = ? WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $user_id);

    if (!mysqli_stmt_execute($stmt)) {
        die("Gagal memperbarui akun: " .
            htmlspecialchars(mysqli_stmt_error($stmt)));
    }

    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_users SET password = ? WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "si", $hash, $user_id);

        if (!mysqli_stmt_execute($stmt)) {
            die("Gagal memperbarui password: " .
                htmlspecialchars(mysqli_stmt_error($stmt)));
        }
    }
}

$_SESSION['pesan'] = "Data guru berhasil diperbarui.";
header("Location: kelola_guru.php");
exit;
?>
