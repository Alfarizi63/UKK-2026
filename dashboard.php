
<?php

include 'includes/cek_session.php';
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Dashboard</title>
</head>

<body>
<?php if ($_SESSION['role'] == 'admin' ) { ?>
     

<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR -->
       <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 position-fixed top-0 start-0 overflow-auto">
            <h4 class="text-white mb-4">
                My Website
            </h4>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link active">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link text-white">
                        Data Siswa
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link text-white">
                        Data Guru
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link text-white">
                        Kelas
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kategori_pelanggaran.php" class="nav-link text-white">
                        kelola kategori pelanggaran
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link text-white">
                        Kelola tahun ajaran
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="laporan.php" class="nav-link text-white">
                        Laporan
                    </a>
                     <li class="nav-item mb-2">
                    <a href="kelola_wali_kelas.php" class="nav-link text-white">
                        kelola wali kelas
                    </a>
                </li>
                    
                      <li class="nav-item mb-2">
                    <a href="kelola_penempatan_siswa.php" class="nav-link text-white">
                        kelola penempatan siswa
                    </a>
                </li>
                
                 <li class="nav-item mb-2">
                    <a href="rekap_point.php" class="nav-link text-white">
                        rekap poin
                    </a>
                </li>
                 <li class="nav-item mb-2">
                    <a href="riwayat.php" class="nav-link text-white">
                        riwayat
                    </a>
                </li>
                 <li class="nav-item mb-2">
                    <a href="kelola_jenis_pelanggaran.php" class="nav-link text-white">
                        kelola jenis pelanggaran
                    </a>
                </li>
                 <li class="nav-item mb-2">
                    <a href="export_pelanggaran.php" class="nav-link text-white">
                        export pelanggaran
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="tentang_saya.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>
                <hr class="text-secondary">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        Logout
                    </a>
                </li>
                
            </ul>
        </div>
        <!-- KONTEN -->
         <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">
            <h1>selamat datang, <?php echo $_SESSION['name'];?></h1>
    <p>anda login sebagai: <?php echo $_SESSION['role']?></p>
            <div class="row">
                <!-- CARD DATA SISWA -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                Data Siswa
                            </h5>
                            <h2>
                                3000
                            </h2>
                        </div>
                    </div>
                </div>
                <!-- CARD DATA GURU -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                Data Guru
                            </h5>
                            <h2>
                                25
                            </h2>
                        </div>
                    </div>
                </div>
                <!-- CARD KELAS -->
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                Kelas
                            </h5>
                            <h2>
                             12
                          </h2>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


    <ul>

<?php if ($_SESSION['role'] == 'admin'  )  {?>
<li><a href="dashboard.php">dashboard</a></li>
      <li><a href="kelola_siswa.php">kelola siswa</a></li>
      <li><a href="kelola_guru.php">kelola guru</a></li>
      <li><a href="catatan_pelanggaran.php">catatan pelanggaran</a></li>
           <li><a href="kelola_tahun_ajaran.php">kelola tahun ajaran</a></li>
            <li><a href="kelola_kelas.php">kelola kelas</a></li>
              <li><a href="kelola_wali_kelas.php">kelola wali kelas</a></li>
                <li><a href="kelola_kategori_pelanggaran.php">kelola kategori pelanggaran</a></li>
              <li><a href="kelola_penempatan_siswa.php">kelola penempatan siswa</a></li>
             <li><a href="laporan.php">Laporan</a></li>
              <li><a href="rekap_point.php">rekap point</a></li>
            
         <?php } ?>

         <?php if ($_SESSION['role'] == 'guru' ) { ?>

<style>
    body {
        background: #f8f9fa;
    }

    .sidebar-guru {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        width: 273px;
        background: #343a40;
        padding: 14px;
        overflow-y: auto;
        z-index: 1000;
    }

    .sidebar-guru h4 {
        color: white;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .sidebar-guru a {
        color: white;
        display: block;
        padding: 10px 14px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
    }

    .sidebar-guru a:hover {
        background: #495057;
    }

    .sidebar-guru a.active {
        background: #007bff;
        color: white;
    }

    .konten-guru {
        margin-left: 273px;
        padding: 25px;
        min-width: 0;
    }

    .kartu-menu-guru {
        background: white;
        border-radius: 8px;
        padding: 22px;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .kartu-menu-guru h5 {
        font-weight: bold;
        margin-bottom: 12px;
    }

    .kartu-menu-guru a {
        text-decoration: none;
    }

    .tombol-menu-guru {
        display: inline-block;
        background: #007bff;
        color: white !important;
        padding: 7px 13px;
        border-radius: 4px;
        font-size: 14px;
    }

    .tombol-menu-guru:hover {
        background: #0069d9;
    }

    @media (max-width: 767px) {
        .sidebar-guru {
            position: relative;
            width: 100%;
            height: auto;
        }

        .konten-guru {
            margin-left: 0;
            padding: 15px;
        }
    }
</style>

<div class="sidebar-guru">
    <h4>My Website</h4>

    <ul class="nav nav-pills flex-column">
        <li class="nav-item mb-2">
            <a href="dashboard.php" class="nav-link active">
                Dashboard
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="catatan_pelanggaran.php" class="nav-link">
                catatan
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="tindakan.php" class="nav-link">
                tindakan
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="laporan.php" class="nav-link">
                laporan
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="riwayat.php" class="nav-link">
                riwayat
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="rekap_point.php" class="nav-link">
                rekap point
            </a>
        </li>
        <a href="login.php" class="text-danger">Logout</a>
    </ul>
</div>

<main class="konten-guru">

    <div class="bg-white p-4 rounded shadow-sm mb-4">
        <h1>selamat datang, <?php echo $_SESSION['name'];?></h1>
        <p class="text-secondary mb-0">
            anda login sebagai: <?php echo $_SESSION['role']?>
        </p>
    </div>

    <h2 class="mb-3">Dashboard</h2>

    <div class="row g-3">

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="kartu-menu-guru">
                <h5>catatan</h5>
                <p class="text-secondary">
                    Kelola catatan pelanggaran siswa.
                </p>
                <a href="catatan_pelanggaran.php" class="tombol-menu-guru">
                    Buka Menu
                </a>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="kartu-menu-guru">
                <h5>tindakan</h5>
                <p class="text-secondary">
                    Kelola tindakan pelanggaran siswa.
                </p>
                <a href="tindakan.php" class="tombol-menu-guru">
                    Buka Menu
                </a>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="kartu-menu-guru">
                <h5>laporan</h5>
                <p class="text-secondary">
                    Lihat laporan pelanggaran siswa.
                </p>
                <a href="laporan.php" class="tombol-menu-guru">
                    Buka Menu
                </a>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="kartu-menu-guru">
                <h5>riwayat</h5>
                <p class="text-secondary">
                    Lihat riwayat pelanggaran siswa.
                </p>
                <a href="riwayat.php" class="tombol-menu-guru">
                    Buka Menu
                </a>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="kartu-menu-guru">
                <h5>rekap point</h5>
                <p class="text-secondary">
                    Lihat rekap poin pelanggaran siswa.
                </p>
                <a href="rekap_point.php" class="tombol-menu-guru">
                    Buka Menu
                </a>
            </div>
        </div>

    </div>

    <a href="login.php" class="btn btn-danger mt-3">logout</a>

</main>

<?php } ?>

    <table class="table">
      <th>
        <td>:</td>
      </th>
        </main>
    </div>
</div>
</body>
</html>