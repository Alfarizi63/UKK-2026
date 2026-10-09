<?php

include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>dashboard-warung abc</title>
</head>
<body>
    <h1>selamat datang, <?php echo $_SESSION['name'];?></h1>
    <p>anda login sebagai: <?php echo $_SESSION['role']?></p>

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
      <li><a href="menu3.php">menu 3</a></li>
           <li><a href="menu4.php">menu 4</a></li>
<?php } ?>
    </ul>
    <a href="login.php">logout</a>
</body>
</html>