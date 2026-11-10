
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

function kembali($pesan) {
    $_SESSION['pesan'] = $pesan;
    header('Location: import_akun_guru.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: import_akun_guru.php');
    exit;
}

$token = $_POST['csrf_token'] ?? '';

if (
    empty($_SESSION['csrf_import_guru']) ||
    !hash_equals($_SESSION['csrf_import_guru'], $token)
) {
    kembali('Permintaan tidak valid.');
}

$guruIds = $_POST['guru_id'] ?? [];

if (!is_array($guruIds) || empty($guruIds)) {
    kembali('Pilih minimal satu guru.');
}

$guruIds = array_unique(array_filter(array_map('intval', $guruIds)));

if (empty($guruIds)) {
    kembali('Data guru tidak valid.');
}

mysqli_begin_transaction($koneksi);

try {
    $passwordHash = password_hash('12345678', PASSWORD_DEFAULT);

    $baru = 0;
    $terhubung = 0;
    $dilewati = 0;

    $stmtGuru = mysqli_prepare(
        $koneksi,
        "SELECT id, nama, email, user_id
         FROM t_guru WHERE id = ? FOR UPDATE"
    );

    $stmtUser = mysqli_prepare(
        $koneksi,
        "SELECT id, role FROM t_users
         WHERE LOWER(TRIM(email)) = LOWER(TRIM(?))
         LIMIT 1"
    );

    $stmtInsert = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_users
        (name, email, password, remember_token, role, created_at, updated_at)
        VALUES (?, ?, ?, ?, 'guru', NOW(), NOW())"
    );

    $stmtUpdate = mysqli_prepare(
        $koneksi,
        "UPDATE t_guru SET user_id = ?, updated_at = NOW()
         WHERE id = ?"
    );

    $stmtPemilik = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_guru
         WHERE user_id = ? AND id <> ?
         LIMIT 1"
    );

    foreach ($guruIds as $guruId) {

        mysqli_stmt_bind_param($stmtGuru, 'i', $guruId);
        mysqli_stmt_execute($stmtGuru);

        $guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtGuru));

        if (!$guru) {
            throw new Exception("Guru ID $guruId tidak ditemukan.");
        }

        $email = trim($guru['email'] ?? '');
        $nama = trim($guru['nama'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email $nama tidak valid.");
        }

        // Cari akun berdasarkan email
        mysqli_stmt_bind_param($stmtUser, 's', $email);
        mysqli_stmt_execute($stmtUser);

        $akun = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtUser));

        if ($akun) {

            if ($akun['role'] !== 'guru') {
                throw new Exception(
                    "Email $email digunakan akun non-guru."
                );
            }

            $userId = (int)$akun['id'];

            // Pastikan akun tidak terhubung ke guru lain
            mysqli_stmt_bind_param(
                $stmtPemilik,
                'ii',
                $userId,
                $guruId
            );

            mysqli_stmt_execute($stmtPemilik);

            if (mysqli_fetch_assoc(mysqli_stmt_get_result($stmtPemilik))) {
                throw new Exception(
                    "Akun $email sudah terhubung ke guru lain."
                );
            }

            if ((int)$guru['user_id'] === $userId) {
                $dilewati++;
                continue;
            }

            // Hubungkan user_id lama yang salah ke akun yang benar
            mysqli_stmt_bind_param(
                $stmtUpdate,
                'ii',
                $userId,
                $guruId
            );

            mysqli_stmt_execute($stmtUpdate);
            $terhubung++;

        } else {

            // Buat akun baru
            $rememberToken = bin2hex(random_bytes(32));

            mysqli_stmt_bind_param(
                $stmtInsert,
                'ssss',
                $nama,
                $email,
                $passwordHash,
                $rememberToken
            );

            if (!mysqli_stmt_execute($stmtInsert)) {
                throw new Exception(
                    "Gagal membuat akun $email: " .
                    mysqli_stmt_error($stmtInsert)
                );
            }

            $userId = mysqli_insert_id($koneksi);

            mysqli_stmt_bind_param(
                $stmtUpdate,
                'ii',
                $userId,
                $guruId
            );

            mysqli_stmt_execute($stmtUpdate);
            $baru++;
        }
    }

    mysqli_commit($koneksi);

    kembali(
        "Import berhasil! $baru akun baru dibuat, " .
        "$terhubung akun berhasil dihubungkan, " .
        "$dilewati akun sudah benar."
    );

} catch (Throwable $e) {

    mysqli_rollback($koneksi);

    kembali(
        'Import dibatalkan. ' . $e->getMessage()
    );
}
