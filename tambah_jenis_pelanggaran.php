
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Khusus admin
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Ambil kategori pelanggaran aktif
$sql_kategori = "SELECT id, nama
                 FROM t_pelanggaran_kategori
                 WHERE status_aktif = 1
                 ORDER BY nama ASC";

$query_kategori = mysqli_query($koneksi, $sql_kategori);

if (!$query_kategori) {
    die("Gagal mengambil kategori: " . mysqli_error($koneksi));
}

$pesan = $_SESSION['pesan'] ?? '';
unset($_SESSION['pesan']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Jenis Pelanggaran</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f5f6fa;
            font-family: Arial, sans-serif;
        }

        .konten {
            margin-left: 273px;
            padding: 28px;
        }

        .judul {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .subjudul {
            color: #777;
            margin-bottom: 25px;
        }

        .card-form {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .form-group label {
            font-weight: 600;
            color: #444;
        }

        .form-control {
            border-radius: 6px;
            height: 42px;
        }

        textarea.form-control {
            height: auto;
        }

        .btn {
            border-radius: 6px;
            padding: 9px 18px;
        }

        #hasil_kategori {
            max-height: 200px;
            overflow-y: auto;
            border-radius: 6px;
        }

        .pilihan-kategori {
            cursor: pointer;
            text-align: left;
        }

        .pilihan-kategori:hover {
            background: #f1f5ff;
        }

        .kategori-terpilih {
            color: #28a745;
            font-size: 13px;
            margin-top: 8px;
        }

        .tidak-ditemukan {
            display: none;
            padding: 10px;
            color: #777;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .konten {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="konten">

        <h4 class="judul">
            <i class="fas fa-plus-circle"></i>
            Tambah Jenis Pelanggaran
        </h4>

        <p class="subjudul">
            Tambahkan jenis pelanggaran baru ke dalam sistem.
        </p>

        <?php if (!empty($pesan)) : ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($pesan); ?>
            </div>
        <?php endif; ?>

        <div class="card-form">

            <form action="proses_jenis_pelanggaran.php" method="POST">

                <input type="hidden" name="aksi" value="tambah">

                <!-- KATEGORI PELANGGARAN -->
                <div class="form-group">

                    <label for="cari_kategori">
                        Kategori Pelanggaran
                    </label>

                    <input
                        type="search"
                        id="cari_kategori"
                        class="form-control"
                        placeholder="Cari kategori pelanggaran..."
                        autocomplete="off"
                    >

                    <input
                        type="hidden"
                        name="pelanggaran_kategori_id"
                        id="pelanggaran_kategori_id"
                        required
                    >

                    <div id="hasil_kategori" class="list-group mt-2">

                        <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) : ?>

                            <button
                                type="button"
                                class="list-group-item list-group-item-action pilihan-kategori"
                                data-id="<?= (int) $kategori['id']; ?>"
                                data-nama="<?= htmlspecialchars($kategori['nama'], ENT_QUOTES, 'UTF-8'); ?>"
                            >
                                <?= htmlspecialchars($kategori['nama']); ?>
                            </button>

                        <?php endwhile; ?>

                        <div id="tidak_ditemukan" class="tidak-ditemukan">
                            Kategori tidak ditemukan.
                        </div>

                    </div>

                    <small id="kategori_terpilih" class="kategori-terpilih d-block"></small>

                </div>

                <!-- NAMA PELANGGARAN -->
                <div class="form-group">

                    <label for="nama_pelanggaran">
                        Nama Pelanggaran
                    </label>

                    <input
                        type="text"
                        name="nama_pelanggaran"
                        id="nama_pelanggaran"
                        class="form-control"
                        maxlength="150"
                        placeholder="Masukkan nama pelanggaran"
                        required
                    >

                </div>

                <!-- POIN -->
                <div class="form-group">

                    <label for="poin">
                        Poin Pelanggaran
                    </label>

                    <input
                        type="number"
                        name="poin"
                        id="poin"
                        class="form-control"
                        min="1"
                        placeholder="Masukkan jumlah poin"
                        required
                    >

                </div>

                <!-- KETERANGAN -->
                <div class="form-group">

                    <label for="keterangan">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        id="keterangan"
                        class="form-control"
                        rows="4"
                        placeholder="Masukkan keterangan pelanggaran"
                    ></textarea>

                </div>

                <hr>

                <a href="kelola_jenis_pelanggaran.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>

            </form>

        </div>

    </div>

    <script>
        const pencarian = document.getElementById('cari_kategori');
        const inputKategori = document.getElementById('pelanggaran_kategori_id');
        const hasilKategori = document.getElementById('hasil_kategori');
        const kategoriTerpilih = document.getElementById('kategori_terpilih');
        const tidakDitemukan = document.getElementById('tidak_ditemukan');

        const semuaPilihan = Array.from(
            hasilKategori.querySelectorAll('.pilihan-kategori')
        );

        // Pencarian kategori
        pencarian.addEventListener('input', function () {

            const kata = this.value.toLowerCase().trim();

            // Batalkan pilihan sebelumnya jika pencarian berubah
            inputKategori.value = '';
            kategoriTerpilih.textContent = '';

            let jumlahHasil = 0;

            semuaPilihan.forEach(function (item) {

                const nama = item.dataset.nama.toLowerCase();

                if (nama.includes(kata)) {
                    item.style.display = 'block';
                    jumlahHasil++;
                } else {
                    item.style.display = 'none';
                }

            });

            tidakDitemukan.style.display =
                jumlahHasil === 0 ? 'block' : 'none';

        });

        // Pilih kategori dari hasil pencarian
        hasilKategori.addEventListener('click', function (e) {

            const tombol = e.target.closest('.pilihan-kategori');

            if (!tombol) {
                return;
            }

            inputKategori.value = tombol.dataset.id;
            pencarian.value = tombol.dataset.nama;

            kategoriTerpilih.textContent =
                'Kategori dipilih: ' + tombol.dataset.nama;

            // Sembunyikan daftar setelah kategori dipilih
            semuaPilihan.forEach(function (item) {
                item.style.display = 'none';
            });

            tidakDitemukan.style.display = 'none';

        });

        // Tampilkan kategori ketika kolom pencarian diklik
        pencarian.addEventListener('focus', function () {

            if (inputKategori.value === '') {
                pencarian.dispatchEvent(new Event('input'));
            }

        });

        // Validasi sebelum dikirim
        document.querySelector('form').addEventListener('submit', function (e) {

            if (inputKategori.value === '') {
                e.preventDefault();

                alert('Silakan cari dan pilih kategori pelanggaran terlebih dahulu.');

                pencarian.focus();
            }

        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

</body>
</html>
