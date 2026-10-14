
<?php
include 'includes/cek_session.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tentang Saya | My Personal Space</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    scroll-behavior: smooth;
}

:root {
    --bg: #080d16;
    --card: #101a29;
    --border: #22334b;
    --blue: #55aaff;
    --text: #edf4ff;
    --muted: #a4b2c8;
}

body {
    font-family: Arial, sans-serif;
    background: var(--bg);
    color: var(--text);
    line-height: 1.7;
}

a {
    color: inherit;
    text-decoration: none;
}

.layout {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 245px;
    flex-shrink: 0;
    background: #0b111d;
    border-right: 1px solid var(--border);
    padding: 28px 18px;
    display: flex;
    flex-direction: column;
    position: fixed;
    inset: 0 auto 0 0;
    z-index: 10;
}

.logo {
    font-size: 23px;
    font-weight: bold;
    margin-bottom: 4px;
}

.logo span {
    color: var(--blue);
}

.tagline {
    color: var(--muted);
    font-size: 12px;
    margin-bottom: 36px;
}

.nav a {
    display: block;
    padding: 12px 14px;
    border-radius: 10px;
    color: var(--muted);
    margin-bottom: 7px;
    transition: .25s;
}

.nav a:hover,
.nav a.active {
    color: white;
    background: #183251;
}

.sidebar-footer {
    margin-top: auto;
    color: var(--muted);
    font-size: 12px;
    border-top: 1px solid var(--border);
    padding-top: 18px;
}

main {
    margin-left: 245px;
    width: calc(100% - 245px);
}

.hero {
    min-height: 390px;
    padding: 70px 6%;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(ellipse at 85% 30%, #204f79 0%, transparent 35%),
        linear-gradient(115deg, #101c2d, #080d16 70%);
}

.hero::after {
    content: "ABOUT ME";
    position: absolute;
    right: -20px;
    bottom: -45px;
    font-size: clamp(65px, 12vw, 155px);
    font-weight: 900;
    color: rgba(255,255,255,.035);
    pointer-events: none;
}

.hero-content {
    max-width: 650px;
    position: relative;
    z-index: 1;
}

.eyebrow {
    color: var(--blue);
    letter-spacing: 3px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.hero h1 {
    font-size: clamp(36px, 5vw, 58px);
    line-height: 1.2;
    margin: 12px 0;
}

.hero h1 span {
    color: var(--blue);
}

.hero p {
    max-width: 540px;
    color: #c2cee0;
    margin: 16px 0 24px;
}

.button {
    display: inline-block;
    padding: 11px 19px;
    border-radius: 8px;
    background: var(--blue);
    color: #07111d;
    font-weight: bold;
    margin-right: 8px;
    margin-bottom: 8px;
}

.button.secondary {
    background: transparent;
    border: 1px solid #56708e;
    color: white;
}

.container {
    padding: 36px 4%;
}

.section-title {
    font-size: 25px;
    margin-bottom: 8px;
}

.section-subtitle {
    color: var(--muted);
    margin-bottom: 23px;
    font-size: 14px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.card {
    background: linear-gradient(145deg, #111c2c, #0c1420);
    border: 1px solid var(--border);
    border-radius: 15px;
    padding: 24px;
    min-width: 0;
}

.card h3 {
    font-size: 17px;
    margin-bottom: 14px;
}

.card p,
.card li {
    color: #b7c5d9;
    font-size: 14px;
}

.profile-card {
    display: flex;
    gap: 22px;
    align-items: center;
}

.profile-photo {
    width: 135px;
    height: 165px;
    flex-shrink: 0;
    border-radius: 12px;
    border: 1px solid #355b80;
    object-fit: cover;
    background: #18283b;
}

.profile-info p {
    margin-bottom: 8px;
}

.label {
    color: var(--blue);
    font-weight: bold;
}

.list {
    list-style: none;
}

.list li {
    margin: 9px 0;
}

.list li::before {
    content: "✦ ";
    color: var(--blue);
    margin-right: 7px;
}

.timeline {
    border-left: 2px solid #315c85;
    padding-left: 18px;
}

.timeline-item {
    margin-bottom: 18px;
    position: relative;
}

.timeline-item::before {
    content: "";
    position: absolute;
    width: 9px;
    height: 9px;
    background: var(--blue);
    border-radius: 50%;
    left: -24px;
    top: 8px;
}

.timeline-item h4 {
    margin-bottom: 3px;
}

.timeline-item p {
    font-size: 13px;
}

.skill {
    margin: 18px 0;
}

.skill-label {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    margin-bottom: 7px;
}

.bar {
    height: 7px;
    background: #253247;
    border-radius: 10px;
    overflow: hidden;
}

.bar span {
    display: block;
    height: 100%;
    background: linear-gradient(90deg, #3788ff, #6bc8ff);
    border-radius: 10px;
}

.quote {
    font-size: 19px !important;
    color: #dceaff !important;
    font-style: italic;
}

.gallery {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
}

.gallery-item {
    height: 155px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: linear-gradient(145deg, #203d5b, #101725);
    display: flex;
    align-items: end;
    padding: 15px;
    color: white;
    font-size: 14px;
    overflow: hidden;
}

.contact-links {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.contact-links a {
    border: 1px solid var(--border);
    background: #101a29;
    padding: 12px 16px;
    border-radius: 9px;
}

.contact-links a:hover {
    border-color: var(--blue);
}

footer {
    border-top: 1px solid var(--border);
    text-align: center;
    color: var(--muted);
    padding: 25px;
    font-size: 12px;
}

@media (max-width: 850px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        padding: 16px;
    }

    .tagline,
    .sidebar-footer {
        display: none;
    }

    .nav {
        display: flex;
        overflow-x: auto;
        gap: 5px;
        margin-top: 12px;
    }

    .nav a {
        white-space: nowrap;
        margin: 0;
        padding: 9px 12px;
        font-size: 12px;
    }

    .layout {
        display: block;
    }

    main {
        margin-left: 0;
        width: 100%;
    }

    .hero {
        min-height: 350px;
        padding: 45px 6%;
    }
}

@media (max-width: 600px) {
    .grid,
    .gallery {
        grid-template-columns: 1fr;
    }

    .profile-card {
        flex-direction: column;
        align-items: flex-start;
    }

    .profile-photo {
        width: 100%;
        height: 230px;
    }

    .container {
        padding: 25px 5%;
    }

    .card {
        padding: 19px;
    }
}
</style>
</head>

<body>
<div class="layout">

<aside class="sidebar">
    <div class="logo">MY<span>SPACE.</span></div>
    <div class="tagline">Mengenal diri, membangun masa depan.</div>

    <nav class="nav">
        <a class="active" href="#beranda">Beranda</a>
        <a href="#tentang">Tentang Saya</a>
        <a href="#hobi">Hobi</a>
        <a href="#pendidikan">Pendidikan</a>
        <a href="#tujuan">Tujuan</a>
        <a href="#galeri">Galeri</a>
        <a href="#kontak">Kontak</a>
    </nav>

    <div class="sidebar-footer">
        Terima kasih sudah berkunjung.<br><br>
        © 2026 My Personal Space
    </div>
</aside>

<main>

<section class="hero" id="beranda">
    <div class="hero-content">
        <div class="eyebrow">SELAMAT DATANG DI DUNIA SAYA</div>
        <h1>Halo, saya <span>Nama Kamu.</span></h1>

        <p>
            Saya seorang pelajar yang terus belajar, mencoba hal baru,
            dan berusaha menjadi versi terbaik dari diri sendiri.
            Ini adalah cerita singkat tentang siapa saya.
        </p>

        <a href="#tentang" class="button">Kenali Saya</a>
        <a href="#tujuan" class="button secondary">Lihat Tujuan Saya</a>
    </div>
</section>

<div class="container">

<section id="tentang">
    <h2 class="section-title">Di Balik Nama Saya</h2>
    <p class="section-subtitle">Sedikit cerita tentang siapa saya sebenarnya.</p>

    <div class="card profile-card">
        <!-- Ganti images/foto-saya.jpg dengan foto milikmu -->
        <img class="profile-photo"
             src="images/foto-saya.jpg"
             alt="Foto profil saya"
             onerror="this.style.display='none'">

        <div class="profile-info">
            <h3>Nama Lengkap Kamu</h3>
            <p><span class="label">Panggilan:</span> Nama panggilan</p>
            <p><span class="label">Status:</span> Pelajar</p>
            <p><span class="label">Asal:</span> Kota asal kamu</p>
            <p>
                Saya adalah seseorang yang memiliki rasa ingin tahu,
                senang mempelajari hal baru, dan ingin terus berkembang.
                Saya tahu masih banyak yang harus dipelajari, dan saya
                ingin menikmati prosesnya.
            </p>
        </div>
    </div>
</section>

<section id="hobi" style="margin-top:35px">
    <h2 class="section-title">Hal yang Saya Sukai</h2>
    <p class="section-subtitle">Aktivitas yang membuat hari saya lebih bermakna.</p>

    <div class="grid">
        <div class="card">
            <h3>01 / Hobi & Minat</h3>
            <ul class="list">
                <li>Membaca buku dan mencari pengetahuan baru</li>
                <li>Belajar teknologi dan pemrograman</li>
                <li>Olahraga dan menjaga kebugaran</li>
                <li>Mendengarkan musik</li>
                <li>Tulis hobi lain di sini</li>
            </ul>
        </div>

        <div class="card">
            <h3>02 / Kepribadian Saya</h3>
            <p>
                Saya percaya setiap orang mempunyai kelebihan dan
                kekurangannya masing-masing. Saya ingin terus belajar
                memahami diri sendiri, menghargai orang lain, dan
                memperbaiki kesalahan yang pernah saya lakukan.
            </p>
            <br>
            <p class="quote">
                "Tidak harus sempurna untuk mulai berkembang."
            </p>
        </div>
    </div>
</section>

<section id="pendidikan" style="margin-top:35px">
    <h2 class="section-title">Perjalanan Pendidikan</h2>
    <p class="section-subtitle">Tempat saya belajar dan mengembangkan kemampuan.</p>

    <div class="card">
        <div class="timeline">
            <div class="timeline-item">
                <h4>SMK — Nama Sekolah Kamu</h4>
                <p>2023–sekarang (ubah sesuai data sebenarnya)</p>
                <p>Jurusan: Rekayasa Perangkat Lunak</p>
            </div>

            <div class="timeline-item">
                <h4>SMP — Nama Sekolah Kamu</h4>
                <p>Tahun masuk – tahun lulus</p>
                <p>Tulis pengalaman atau hal yang kamu pelajari.</p>
            </div>

            <div class="timeline-item">
                <h4>Pengalaman Belajar Lain</h4>
                <p>Kursus, proyek pribadi, organisasi, atau pencapaian.</p>
            </div>
        </div>
    </div>
</section>

<section style="margin-top:35px">
    <h2 class="section-title">Kemampuan yang Saya Kembangkan</h2>
    <p class="section-subtitle">Contoh kemampuan. Ubah sesuai kemampuanmu.</p>

    <div class="card">
        <div class="skill">
            <div class="skill-label">
                <span>HTML & CSS</span><span>60%</span>
            </div>
            <div class="bar"><span style="width:60%"></span></div>
        </div>

        <div class="skill">
            <div class="skill-label">
                <span>PHP & MySQL</span><span>45%</span>
            </div>
            <div class="bar"><span style="width:45%"></span></div>
        </div>

        <div class="skill">
            <div class="skill-label">
                <span>Komunikasi</span><span>55%</span>
            </div>
            <div class="bar"><span style="width:55%"></span></div>
        </div>
    </div>
</section>

<section id="tujuan" style="margin-top:35px">
    <h2 class="section-title">Masa Depan yang Saya Tuju</h2>
    <p class="section-subtitle">Hal-hal yang ingin saya usahakan secara bertahap.</p>

    <div class="grid">
        <div class="card">
            <h3>Tujuan Jangka Pendek</h3>
            <ul class="list">
                <li>Menyelesaikan pendidikan dengan baik</li>
                <li>Meningkatkan kemampuan pemrograman</li>
                <li>Belajar bahasa Inggris</li>
                <li>Membangun kebiasaan yang positif</li>
            </ul>
        </div>

        <div class="card">
            <h3>Impian Jangka Panjang</h3>
            <ul class="list">
                <li>Memiliki keahlian yang bermanfaat</li>
                <li>Membangun usaha sendiri</li>
                <li>Membantu keluarga</li>
                <li>Menjadi pribadi yang bermanfaat</li>
            </ul>
        </div>
    </div>
</section>

<section id="galeri" style="margin-top:35px">
    <h2 class="section-title">Potongan Cerita Saya</h2>
    <p class="section-subtitle">Tempat untuk foto kegiatan, karya, atau kenangan.</p>

    <div class="gallery">
        <div class="gallery-item">01 — Kegiatan Saya</div>
        <div class="gallery-item">02 — Karya Saya</div>
        <div class="gallery-item">03 — Kenangan Saya</div>
    </div>
</section>

<section id="kontak" style="margin-top:35px">
    <h2 class="section-title">Mari Terhubung</h2>
    <p class="section-subtitle">Ganti tautan berikut dengan akun milikmu.</p>

    <div class="contact-links">
        <a href="https://instagram.com/" target="_blank" rel="noopener">Instagram ↗</a>
        <a href="https://github.com/" target="_blank" rel="noopener">GitHub ↗</a>
        <a href="mailto:emailkamu@example.com">Email ↗</a>
    </div>
</section>

</div>

<footer>
    Terus belajar, terus berkembang, dan jangan lupa bersyukur.<br>
    © 2026 — Nama Kamu. Dibuat dengan semangat untuk terus berkembang.
</footer>

</main>
</div>

<script>
const navLinks = document.querySelectorAll('.nav a');

navLinks.forEach(link => {
    link.addEventListener('click', function () {
        navLinks.forEach(item => item.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>
</body>
</html>
