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
 * TAMBAH GURU
 * Akun t_users dibuat otomatis, lalu user_id disimpan ke t_guru.
 */
if ($aksi === 'tambah') {
    $nip       = trim($_POST['nip'] ?? '');
    $nama      = trim($_POST['nama'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';
    $status    = trim($_POST['status_aktif'] ?? 'aktif');

    // Validasi
    if ($nip === '' || $nama === '' || $email === '' || $password === '') {
        $_SESSION['pesan_error'] = 'Semua data wajib diisi.';
        header('Location: kelola_guru.php');
        exit;
    }

    if (!preg_match('/^[0-9]{1,18}$/', $nip)) {
        $_SESSION['pesan_error'] = 'NIP harus berupa angka dengan maksimal 18 digit.';
        header('Location: kelola_guru.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['pesan_error'] = 'Format email tidak valid.';
        header('Location: kelola_guru.php');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['pesan_error'] = 'Password minimal 8 karakter.';
        header('Location: kelola_guru.php');
        exit;
    }

    if ($password !== $konfirmasi_password) {
        $_SESSION['pesan_error'] = 'Konfirmasi password tidak sama.';
        header('Location: kelola_guru.php');
        exit;
    }

    if (!in_array($status, ['aktif', 'nonaktif'], true)) {
        $status = 'aktif';
    }

    // Pastikan NIP belum digunakan
    $cekNip = $koneksi->prepare("SELECT id FROM t_guru WHERE nip = ? LIMIT 1");
    if (!$cekNip) {
        $_SESSION['pesan_error'] = 'Gagal memeriksa NIP: ' . $koneksi->error;
        header('Location: kelola_guru.php');
        exit;
    }

    $cekNip->bind_param('s', $nip);
    $cekNip->execute();
    $hasilNip = $cekNip->get_result();

    if ($hasilNip->num_rows > 0) {
        $cekNip->close();
        $_SESSION['pesan_error'] = 'NIP sudah terdaftar.';
        header('Location: kelola_guru.php');
        exit;
    }
    $cekNip->close();

    // Pastikan email belum digunakan akun lain
    $cekEmail = $koneksi->prepare("SELECT id FROM t_users WHERE email = ? LIMIT 1");
    if (!$cekEmail) {
        $_SESSION['pesan_error'] = 'Gagal memeriksa email: ' . $koneksi->error;
        header('Location: kelola_guru.php');
        exit;
    }

    $cekEmail->bind_param('s', $email);
    $cekEmail->execute();
    $hasilEmail = $cekEmail->get_result();

    if ($hasilEmail->num_rows > 0) {
        $cekEmail->close();
        $_SESSION['pesan_error'] = 'Email sudah digunakan oleh akun lain.';
        header('Location: kelola_guru.php');
        exit;
    }
    $cekEmail->close();

    try {
        $koneksi->begin_transaction();

        // Buat akun guru otomatis
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'guru';

        $stmtUser = $koneksi->prepare(
            "INSERT INTO t_users (name, email, password, role) VALUES (?, ?, ?, ?)"
        );

        if (!$stmtUser) {
            throw new Exception('Gagal menyiapkan akun guru: ' . $koneksi->error);
        }

        $stmtUser->bind_param('ssss', $nama, $email, $passwordHash, $role);

        if (!$stmtUser->execute()) {
            throw new Exception('Gagal membuat akun guru: ' . $stmtUser->error);
        }

        $userId = $koneksi->insert_id;
        $stmtUser->close();

        // Simpan data guru dan hubungkan dengan akun yang baru dibuat
        $stmtGuru = $koneksi->prepare(
            "INSERT INTO t_guru
                (nip, nama, email, status_aktif, user_id, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, NOW(), NOW())"
        );

        if (!$stmtGuru) {
            throw new Exception('Gagal menyiapkan data guru: ' . $koneksi->error);
        }

        $stmtGuru->bind_param('ssssi', $nip, $nama, $email, $status, $userId);

        if (!$stmtGuru->execute()) {
            throw new Exception('Gagal menyimpan data guru: ' . $stmtGuru->error);
        }

        $stmtGuru->close();
        $koneksi->commit();

        $_SESSION['pesan_sukses'] = 'Data guru dan akun login berhasil ditambahkan.';
        header('Location: kelola_guru.php');
        exit;

    } catch (Throwable $e) {
        $koneksi->rollback();
        $_SESSION['pesan_error'] = 'Gagal menambahkan guru: ' . $e->getMessage();
        header('Location: kelola_guru.php');
        exit;
    }
}

/*
 * HAPUS GURU
 * Hapus data guru saja agar akun t_users tidak terhapus tanpa konfirmasi.
 */
if ($aksi === 'hapus') {
    $id = (int) ($_POST['id'] ?? 0);

    if ($id <= 0) {
        $_SESSION['pesan_error'] = 'ID guru tidak valid.';
        header('Location: kelola_guru.php');
        exit;
    }

    $stmt = $koneksi->prepare("DELETE FROM t_guru WHERE id = ?");
    if (!$stmt) {
        $_SESSION['pesan_error'] = 'Gagal menyiapkan penghapusan: ' . $koneksi->error;
        header('Location: kelola_guru.php');
        exit;
    }

    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $_SESSION['pesan_sukses'] = 'Data guru berhasil dihapus.';
    } else {
        $_SESSION['pesan_error'] = 'Gagal menghapus data guru: ' . $stmt->error;
    }

    $stmt->close();
    header('Location: kelola_guru.php');
    exit;
}

// Aksi tidak dikenal
$_SESSION['pesan_error'] = 'Aksi tidak dikenali.';
header('Location: kelola_guru.php');
exit;
?>
