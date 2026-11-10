
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

// Validasi CSRF
$token = $_POST['csrf_token'] ?? '';

if (
    empty($_SESSION['csrf_import_guru']) ||
    !hash_equals($_SESSION['csrf_import_guru'], $token)
) {
    kembali('Permintaan tidak valid. Silakan muat ulang halaman.');
}

$guruIds = $_POST['guru_id'] ?? [];

if (!is_array($guruIds) || count($guruIds) === 0) {
    kembali('Pilih minimal satu guru.');
}

// Validasi ID
$guruIds = array_unique(array_filter(
    array_map('intval', $guruIds),
    function ($id) {
        return $id > 0;
    }
));

if (count($guruIds) === 0) {
    kembali('Data guru yang dipilih tidak valid.');
}

mysqli_begin_transaction($koneksi);

try {
    $passwordHash = password_hash('12345678', PASSWORD_DEFAULT);

    if ($passwordHash === false) {
        throw new Exception('Gagal membuat hash password.');
    }

    $berhasil = 0;
    $dilewati = 0;

    $stmtGuru = mysqli_prepare(
        $koneksi,
        "SELECT id, nip, nama, email, user_id
         FROM t_guru
         WHERE id = ?
         FOR UPDATE"
    );

    $stmtUser = mysqli_prepare(
        $koneksi,
        "SELECT id, role
         FROM t_users
         WHERE LOWER(TRIM(email)) = LOWER(TRIM(?))
         LIMIT 1
         FOR UPDATE"
    );

    $stmtCekPemilik = mysqli_prepare(
        $koneksi,
        "SELECT id
         FROM t_guru
         WHERE user_id = ? AND id <> ?
         LIMIT 1"
    );

    $stmtInsert = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_users
         (name, email, password, remember_token, role, created_at, updated_at)
         VALUES (?, ?, ?, ?, 'guru', NOW(), NOW())"
    );

    $stmtUpdateGuru = mysqli_prepare(
        $koneksi,
        "UPDATE t_guru
         SET user_id = ?, updated_at = NOW()
         WHERE id = ?"
    );

    foreach ($guruIds as $guruId) {

        // Ambil data guru
        mysqli_stmt_bind_param($stmtGuru, 'i', $guruId);
        mysqli_stmt_execute($stmtGuru);

        $guru = mysqli_fetch_assoc(
            mysqli_stmt_get_result($stmtGuru)
        );

        if (!$guru) {
            throw new Exception(
                "Data guru dengan ID $guruId tidak ditemukan."
            );
        }

        // Jika sudah memiliki akun yang terhubung
        if ((int)$guru['user_id'] > 0) {

            $userIdLama = (int)$guru['user_id'];

            mysqli_stmt_bind_param(
                $stmtCekPemilik,
                'ii',
                $userIdLama,
                $guruId
            );

            mysqli_stmt_execute($stmtCekPemilik);

            $pemilikLain = mysqli_fetch_assoc(
                mysqli_stmt_get_result($stmtCekPemilik)
            );

            if ($pemilikLain) {
                throw new Exception(
                    "Akun guru ID $guruId sudah terhubung dengan data guru lain."
                );
            }

            mysqli_stmt_bind_param(
                $stmtUser,
                's',
                $guru['email']
            );

            mysqli_stmt_execute($stmtUser);

            $akunLama = mysqli_fetch_assoc(
                mysqli_stmt_get_result($stmtUser)
            );

            if ($akunLama && (int)$akunLama['id'] === $userIdLama
                && $akunLama['role'] === 'guru') {
                $dilewati++;
                continue;
            }

            throw new Exception(
                "Guru " . $guru['nama'] .
                " memiliki user_id yang tidak cocok dengan akun guru."
            );
        }

        // Validasi email
        $email = trim($guru['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception(
                "Email guru " . $guru['nama'] . " tidak valid atau kosong."
            );
        }

        // Cari akun berdasarkan email
        mysqli_stmt_bind_param($stmtUser, 's', $email);
        mysqli_stmt_execute($stmtUser);

        $akun = mysqli_fetch_assoc(
            mysqli_stmt_get_result($stmtUser)
        );

        if ($akun) {

            // Jangan mengambil alih akun admin/wali kelas
            if ($akun['role'] !== 'guru') {
                throw new Exception(
                    "Email $email sudah digunakan akun dengan role " .
                    $akun['role'] . ". Tidak ada perubahan pada akun tersebut."
                );
            }

            $userId = (int)$akun['id'];

            // Pastikan akun tidak dimiliki guru lain
            mysqli_stmt_bind_param(
                $stmtCekPemilik,
                'ii',
                $userId,
                $guruId
            );

            mysqli_stmt_execute($stmtCekPemilik);

            $pemilikLain = mysqli_fetch_assoc(
                mysqli_stmt_get_result($stmtCekPemilik)
            );

            if ($pemilikLain) {
                throw new Exception(
                    "Email $email sudah terhubung dengan data guru lain."
                );
            }

            // Akun guru sudah ada: gunakan tanpa mengubah password.
        } else {

            // Buat akun baru
            $nama = $guru['nama'];
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
                    "Gagal membuat akun untuk " . $guru['nama'] .
                    ": " . mysqli_stmt_error($stmtInsert)
                );
            }

            $userId = mysqli_insert_id($koneksi);
        }

        // Hubungkan akun ke data guru
        mysqli_stmt_bind_param(
            $stmtUpdateGuru,
            'ii',
            $userId,
            $guruId
        );

        if (!mysqli_stmt_execute($stmtUpdateGuru)) {
            throw new Exception(
                "Gagal menghubungkan akun guru " . $guru['nama']
            );
        }

        $berhasil++;
    }

    mysqli_commit($koneksi);

    kembali(
        "Import selesai! $berhasil akun baru berhasil dibuat/dihubungkan. " .
        "$dilewati guru dilewati karena sudah memiliki akun."
    );

} catch (Throwable $e) {

    mysqli_rollback($koneksi);

    kembali(
        'Import dibatalkan. Tidak ada perubahan yang disimpan. ' .
        $e->getMessage()
    );
}
