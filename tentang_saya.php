
<?php
include 'includes/cek_session.php';

$nama = "Muhammad Alfarizi";
$kelas = "XII RPL 1";
$jurusan = "Rekayasa Perangkat Lunak";
$hobi = "Belajar coding";
$cita_cita = "Menjadi Programmer";
$deskripsi = "Saya adalah siswa yang sedang belajar pemrograman dan mengembangkan kemampuan di bidang teknologi.";

// Lokasi foto sesuai folder pada screenshot
$foto = "assets/img/profil.jpg";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Saya</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 20px;
            color: #1e293b;
        }

        .profil {
            max-width: 550px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .foto-profil {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #2563eb;
            display: block;
            margin: 0 auto 20px;
        }

        h1 {
            color: #2563eb;
        }

        .deskripsi {
            color: #64748b;
            line-height: 1.7;
        }

        .informasi {
            background: #f8fafc;
            text-align: left;
            padding: 15px 20px;
            border-radius: 10px;
            margin: 25px 0;
        }

        .informasi p {
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            line-height: 1.6;
        }

        .informasi p:last-child {
            border-bottom: none;
        }

        .tombol {
            display: inline-block;
            padding: 12px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 7px;
        }

        .tombol:hover {
            background: #1d4ed8;
        }

        @media (max-width: 600px) {
            .profil {
                padding: 20px;
                margin: 10px auto;
            }

            .foto-profil {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>

<body>

<div class="profil">

    <?php if (file_exists(__DIR__ . '/' . $foto)) { ?>
 <img src="img/alfarizi.jpg" alt="">
    <?php } else { ?>
        <div style="padding: 40px 0; color: #64748b;">
            Foto belum ditemukan.
        </div>
    <?php } ?>

    <h1>Tentang Saya</h1>

    <p class="deskripsi">
        <?php echo htmlspecialchars($deskripsi); ?>
    </p>

    <div class="informasi">
        <p><strong>Nama:</strong>
            <?php echo htmlspecialchars($nama); ?></p>

        <p><strong>Kelas:</strong>
            <?php echo htmlspecialchars($kelas); ?></p>

        <p><strong>Jurusan:</strong>
            <?php echo htmlspecialchars($jurusan); ?></p>

        <p><strong>Hobi:</strong>
            <?php echo htmlspecialchars($hobi); ?></p>

        <p><strong>Cita-cita:</strong>
            <?php echo htmlspecialchars($cita_cita); ?></p>
    </div>

    <a href="dashboard.php" class="tombol">
        Kembali ke Dashboard
    </a>

</div>

</body>
</html>