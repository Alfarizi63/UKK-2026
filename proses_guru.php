
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

    $nip = strtoupper(trim($_POST['nip'] ?? ''));
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';
    $status = (int)($_POST['status_aktif'] ?? 1);

    // Validasi
    if ($nip === '' || $nama === '' || $email === '' || $password === '') {
        $_SESSION['pesan'] = 'Semua data wajib diisi.';
        header('Location: kelola_guru.php');
        exit;
    }

    if (!preg_match('/^NIP[0-9]{1,15}$/', $nip)) {
        $_SESSION['pesan'] = 'Format NIP tidak valid.';
        header('Location: kelola_guru.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['pesan'] = 'Format email tidak valid.';
        header('Location: kelola_guru.php');
        exit;
    }

    if (strlen($password) < 8) {
        $_SESSION['pesan'] = 'Password minimal 8 karakter.';
        header('Location: kelola_guru.php');
        exit;
    }

    if ($password !== $konfirmasi) {
        $_SESSION['pesan'] = 'Konfirmasi password tidak sama.';
        header('Location: kelola_guru.php');
        exit;
    }

    if (!in_array($status, [0, 1], true)) {
        $status = 1;
    }

    try {

        $koneksi->begin_transaction();

        // Periksa NIP
        $cekNip = $koneksi->prepare(
            "SELECT id FROM t_guru WHERE nip = ? LIMIT 1"
        );

        $cekNip->bind_param('s', $nip);
        $cekNip->execute();

        if ($cekNip->get_result()->num_rows > 0) {
            throw new Exception('NIP sudah terdaftar.');
        }

        $cekNip->close();

        // Periksa email
        $cekEmail = $koneksi->prepare(
            "SELECT id FROM t_users WHERE email = ? LIMIT 1"
        );

        $cekEmail->bind_param('s', $email);
        $cekEmail->execute();

        if ($cekEmail->get_result()->num_rows > 0) {
            throw new Exception('Email sudah digunakan akun lain.');
        }

        $cekEmail->close();

        // Buat akun user
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $rememberToken = bin2hex(random_bytes(32));
        $role = 'guru';

        $stmtUser = $koneksi->prepare(
            "INSERT INTO t_users
                (name, email, password, remember_token, role)
             VALUES (?, ?, ?, ?, ?)"
        );

        if (!$stmtUser) {
            throw new Exception('Gagal menyiapkan akun: ' . $koneksi->error);
        }

        $stmtUser->bind_param(
            'sssss',
            $nama,
            $email,
            $passwordHash,
            $rememberToken,
            $role
        );

        if (!$stmtUser->execute()) {
            throw new Exception('Gagal membuat akun: ' . $stmtUser->error);
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
            throw new Exception('Gagal menyiapkan data guru: ' . $koneksi->error);
        }

        $stmtGuru->bind_param(
            'sssii',
            $nip,
            $nama,
            $email,
            $status,
            $userId
        );

        if (!$stmtGuru->execute()) {
            throw new Exception('Gagal menyimpan guru: ' . $stmtGuru->error);
        }

        $stmtGuru->close();

        $koneksi->commit();

        $_SESSION['pesan'] = 'Data guru dan akun login berhasil ditambahkan.';

    } catch (Throwable $e) {

        $koneksi->rollback();

        $_SESSION['pesan'] = 'Gagal menambahkan guru: ' . $e->getMessage();
    }

    header('Location: kelola_guru.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| UBAH STATUS GURU
|--------------------------------------------------------------------------
| Tidak menghapus data guru dan tidak mengubah riwayat wali kelas.
*/

if ($aksi === 'ubah_status') {

    $id = (int)($_POST['id'] ?? 0);
    $status = (int)($_POST['status_aktif'] ?? -1);

    if ($id <= 0 || !in_array($status, [0, 1], true)) {
        $_SESSION['pesan'] = 'ID guru atau status tidak valid.';
        header('Location: kelola_guru.php');
        exit;
    }

    $stmt = $koneksi->prepare(
        "UPDATE t_guru
         SET status_aktif = ?, updated_at = NOW()
         WHERE id = ?"
    );

    if (!$stmt) {
        $_SESSION['pesan'] = 'Gagal menyiapkan perubahan status: ' . $koneksi->error;
        header('Location: kelola_guru.php');
        exit;
    }

    $stmt->bind_param('ii', $status, $id);

    if ($stmt->execute() && $stmt->affected_rows >= 0) {

        $_SESSION['pesan'] = $status === 1
            ? 'Guru berhasil diaktifkan.'
            : 'Guru berhasil dinonaktifkan.';

    } else {

        $_SESSION['pesan'] = 'Gagal mengubah status guru: ' . $stmt->error;
    }

    $stmt->close();

    header('Location: kelola_guru.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS GURU
|--------------------------------------------------------------------------
| Guru yang memiliki riwayat wali kelas tidak boleh dihapus.
*/

if ($aksi === 'hapus') {

    $id = (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
        $_SESSION['pesan'] = 'ID guru tidak valid.';
        header('Location: kelola_guru.php');
        exit;
    }

    try {

        $koneksi->begin_transaction();

        // Pastikan guru tersedia dan kunci baris selama transaksi
        $cekGuru = $koneksi->prepare(
            "SELECT id, nama
             FROM t_guru
             WHERE id = ?
             FOR UPDATE"
        );

        $cekGuru->bind_param('i', $id);
        $cekGuru->execute();

        $dataGuru = $cekGuru->get_result()->fetch_assoc();
        $cekGuru->close();

        if (!$dataGuru) {
            throw new Exception('Data guru tidak ditemukan.');
        }

        // Periksa riwayat wali kelas
        $cekRiwayat = $koneksi->prepare(
            "SELECT COUNT(*) AS total
             FROM t_wali_kelas
             WHERE guru_id = ?"
        );

        $cekRiwayat->bind_param('i', $id);
        $cekRiwayat->execute();

        $riwayat = $cekRiwayat->get_result()->fetch_assoc();
        $cekRiwayat->close();

        if ((int)$riwayat['total'] > 0) {

            throw new Exception(
                'Guru ' . $dataGuru['nama'] .
                ' masih memiliki riwayat penugasan wali kelas. ' .
                'Guru tidak dapat dihapus. Silakan nonaktifkan guru.'
            );
        }

        // Hapus guru hanya jika tidak memiliki riwayat wali kelas
        $hapus = $koneksi->prepare(
            "DELETE FROM t_guru WHERE id = ?"
        );

        $hapus->bind_param('i', $id);

        if (!$hapus->execute()) {
            throw new Exception('Gagal menghapus guru: ' . $hapus->error);
        }

        $hapus->close();

        $koneksi->commit();

        $_SESSION['pesan'] = 'Data guru berhasil dihapus.';

    } catch (Throwable $e) {

        $koneksi->rollback();

        $_SESSION['pesan'] = 'Penghapusan gagal: ' . $e->getMessage();
    }

    header('Location: kelola_guru.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| AKSI TIDAK DIKENAL
|--------------------------------------------------------------------------
*/

$_SESSION['pesan'] = 'Aksi tidak dikenali.';
header('Location: kelola_guru.php');
exit;
?>
