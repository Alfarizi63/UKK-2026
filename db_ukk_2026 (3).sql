-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Nov 2026 pada 01.09
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ukk_2026`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_guru`
--

CREATE TABLE `t_guru` (
  `id` int(11) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_guru`
--

INSERT INTO `t_guru` (`id`, `nip`, `nama`, `email`, `status_aktif`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'NIP20260001', 'Wawan Kurniawan, S.Pd.I', 'WawanKurniawan@gmail.com', 1, 55, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, 'NIP20260002', 'Ai Nuryaidah, S.Pd', 'AiNuryaidah@gmail.com', 1, 56, '2026-02-02 01:00:00', '2026-02-02 01:00:00'),
(3, 'NIP20260003', 'Maya Permata Endah, S.Pd', 'MayaPermataEndah@gmail.com', 1, 57, '2026-03-03 01:00:00', '2026-03-03 01:00:00'),
(4, 'NIP20260004', 'Dody Jaenudin, S.Pd', 'DodyJaenudin@gmail.com', 1, 58, '2026-04-04 01:00:00', '2026-04-04 01:00:00'),
(5, 'NIP20260005', 'Dedi Kardiman, S.Pd', 'DediKardiman@gmail.com', 1, 59, '2026-05-05 01:00:00', '2026-05-05 01:00:00'),
(6, 'NIP20260006', 'Uca Musa Yusenda, S.Pd', 'UcaMusaYusenda@gmail.com', 1, 60, '2026-06-06 01:00:00', '2026-06-06 01:00:00'),
(7, 'NIP20260007', 'Drs. Ahmad Nur Mukhlis', 'AhmadNurMukhlis@gmail.com', 1, 61, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, 'NIP20260008', 'Drs. Heri', 'Heri@gmail.com', 1, 62, '2026-08-08 01:00:00', '2026-08-08 01:00:00'),
(9, 'NIP20260009', 'Asep Bastaman', 'AsepBastaman@gmail.com', 1, 63, '2026-09-09 01:00:00', '2026-09-09 01:00:00'),
(10, 'NIP20260010', 'H. Encep Maemun, S.T., M.Pd', 'EncepMaemun@gmail.com', 1, 64, '2026-10-10 01:00:00', '2026-10-10 01:00:00'),
(11, 'NIP20260011', 'Opik', 'Opik@gmail.com', 1, 65, '2026-11-11 01:00:00', '2026-11-11 01:00:00'),
(12, 'NIP20260012', 'Ida Farida, S.Pd', 'IdaFarida@gmail.com', 1, 66, '2026-12-12 01:00:00', '2026-12-12 01:00:00'),
(13, 'NIP20260013', 'Idah Mahmudah, S.Pd', 'IdahMahmudah@gmail.com', 1, 67, '2026-01-13 01:00:00', '2026-01-13 01:00:00'),
(14, 'NIP20260014', 'Drs. Muhamad Ali', 'MuhamadAli@gmail.com', 1, 68, '2026-02-14 01:00:00', '2026-02-14 01:00:00'),
(15, 'NIP20260015', 'Helmi Ali Akbar, S.Pd.I', 'HelmiAliAkbar@gmail.com', 1, 69, '2026-03-15 01:00:00', '2026-03-15 01:00:00'),
(16, 'NIP20260016', 'Syeni Suswarini, S.Pd', 'SyeniSuswarini@gmail.com', 1, 70, '2026-04-16 01:00:00', '2026-04-16 01:00:00'),
(17, 'NIP20260017', 'Wiwid Yulianti, S.Pd', 'WiwidYulianti@gmail.com', 1, 71, '2026-05-17 01:00:00', '2026-05-17 01:00:00'),
(18, 'NIP20260018', 'Ansor Muhajir, S.T', 'AnsorMuhajir@gmail.com', 1, 72, '2026-06-18 01:00:00', '2026-06-18 01:00:00'),
(19, 'NIP20260019', 'Ogi Gaosun Gais, S.Pd.I', 'OgiGaosunGais@gmail.com', 1, 73, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, 'NIP20260020', 'Sandi Mulyana, S.Pd', 'SandiMulyana@gmail.com', 1, 74, '2026-08-20 01:00:00', '2026-08-20 01:00:00'),
(21, 'NIP20260021', 'Erna Suryani, S.Pd', 'ErnaSuryani@gmail.com', 1, 75, '2026-09-21 01:00:00', '2026-09-21 01:00:00'),
(22, 'NIP20260022', 'Ai Wahidah, S.Ag', 'AiWahidah@gmail.com', 1, 76, '2026-10-22 01:00:00', '2026-10-22 01:00:00'),
(23, 'NIP20260023', 'Chandra Aditya Yusup, S.Pd', 'ChandraAdityaYusup@gmail.com', 1, 77, '2026-11-23 01:00:00', '2026-11-23 01:00:00'),
(24, 'NIP20260024', 'Arif Kusmawan, S.Pd', 'ArifKusmawan@gmail.com', 1, 78, '2026-12-24 01:00:00', '2026-12-24 01:00:00'),
(25, 'NIP20260025', 'Asep Taufiq Hidayat, S.Kom', 'AsepTaufiqHidayat@gmail.com', 1, 79, '2026-01-25 01:00:00', '2026-01-25 01:00:00'),
(26, 'NIP20260026', 'Rendy Ichsan Kusumah, S.T', 'RendyIchsanKusumah@gmail.com', 1, 80, '2026-02-26 01:00:00', '2026-02-26 01:00:00'),
(27, 'NIP20260027', 'Feri Rahmawan, S.Pd', 'FeriRahmawan@gmail.com', 1, 81, '2026-03-27 01:00:00', '2026-03-27 01:00:00'),
(28, 'NIP20260028', 'Ari Sandriana, S.Kom', 'AriSandriana@gmail.com', 1, 82, '2026-04-28 01:00:00', '2026-04-28 01:00:00'),
(29, 'NIP20260029', 'Yaneu Nurdian, S.Pd, M.Pd', 'YaneuNurdian@gmail.com', 1, 83, '2026-05-01 01:00:00', '2026-05-01 01:00:00'),
(30, 'NIP20260030', 'Ucu Muhammad Khoer N, S.Pd', 'UcuMuhammadKhoerN@gmail.com', 1, 84, '2026-06-02 01:00:00', '2026-06-02 01:00:00'),
(31, 'NIP20260031', 'Mira Dwi Rahmawati, S.Ag', 'MiraDwiRahmawati@gmail.com', 1, 85, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(32, 'NIP20260032', 'Aldi Maulana, S.Pd', 'AldiMaulana@gmail.com', 1, 3, '2026-08-04 01:00:00', '2026-08-04 01:00:00'),
(33, 'NIP20260033', 'Eli Rosliah, S.Pd', 'EliRosliah@gmail.com', 1, 86, '2026-09-05 01:00:00', '2026-09-05 01:00:00'),
(34, 'NIP20260034', 'Isni Nur Fitriani, S.Pd', 'IsniNurFitriani@gmail.com', 1, 87, '2026-10-06 01:00:00', '2026-10-06 01:00:00'),
(35, 'NIP20260035', 'Aanisah Putri Nur Aini, S.Pd', 'AanisahPutriNurAini@gmail.com', 1, 88, '2026-11-07 01:00:00', '2026-11-07 01:00:00'),
(36, 'NIP20260036', 'Yusi Luthfiani, S.Sos.I', 'YusiLuthfiani@gmail.com', 1, 89, '2026-12-08 01:00:00', '2026-12-08 01:00:00'),
(37, 'NIP20260037', 'Lilis Rustandy Mawardi, S.Pd.,', 'LilisRustandyMawardi@gmail.com', 1, 90, '2026-01-09 01:00:00', '2026-01-09 01:00:00'),
(38, 'NIP20260038', 'Yusrin Noer Sabrina, S.Pd', 'YusrinNoerSabrina@gmail.com', 1, 91, '2026-02-10 01:00:00', '2026-02-10 01:00:00'),
(39, 'NIP20260039', 'Senny Indayani, S.Pd', 'SennyIndayani@gmail.com', 1, 39, '2026-03-11 01:00:00', '2026-03-11 01:00:00'),
(40, 'NIP20260040', 'Ilma Amalia, S.Pd', 'IlmaAmalia@gmail.com', 1, 40, '2026-04-12 01:00:00', '2026-04-12 01:00:00'),
(41, 'NIP20260041', 'Alvira Sofwatunnisa, S.Pd', 'AlviraSofwatunnisa@gmail.com', 1, 41, '2026-05-13 01:00:00', '2026-05-13 01:00:00'),
(42, 'NIP20260042', 'Risna Andriyani, S.Pd', 'RisnaAndriyani@gmail.com', 1, 42, '2026-06-14 01:00:00', '2026-06-14 01:00:00'),
(43, 'NIP20260043', 'Dra. Tintin Noerhayati', 'TintinNoerhayati@gmail.com', 1, 43, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(44, 'NIP20260044', 'Rizkia Fahira, S.Pd', 'RizkiaFahira@gmail.com', 1, 44, '2026-08-16 01:00:00', '2026-08-16 01:00:00'),
(45, 'NIP20260045', 'Frasetio', 'Frasetio@gmail.com', 1, 45, '2026-09-17 01:00:00', '2026-09-17 01:00:00'),
(46, 'NIP20260046', 'Ratnaningsih Hatil Aeni, S.Pd', 'RatnaningsihHatilAeni@gmail.com', 1, 46, '2026-10-18 01:00:00', '2026-10-18 01:00:00'),
(47, 'NIP20260047', 'Dheny Darajatul Hasani, S.Pd', 'DhenyDarajatulHasani@gmail.com', 1, 47, '2026-11-19 01:00:00', '2026-11-19 01:00:00'),
(48, 'NIP20260048', 'Fahrullah, S.Pd', 'Fahrullah@gmail.com', 1, 48, '2026-12-20 01:00:00', '2026-12-20 01:00:00'),
(49, 'NIP20260049', 'Aan Andriana, S.T', 'AanAndriana@gmail.com', 1, 49, '2026-01-21 01:00:00', '2026-01-21 01:00:00'),
(50, 'NIP20260050', 'Fitri Fauziah, S.T', 'FitriFauziah@gmail.com', 1, 50, '2026-02-22 01:00:00', '2026-02-22 01:00:00'),
(51, 'NIP20260051', 'Nida Aulia, S.Pd', 'NidaAulia@gmail.com', 1, 51, '2026-10-22 00:45:15', '2026-10-22 00:45:15'),
(52, 'NIP20260052', 'Sri Nurrizki Putri Krisnadi, S', 'SriNurrizkiPutriKrisnad@gmail.com', 1, 52, '2026-10-22 01:00:17', '2026-10-22 01:00:17'),
(53, 'NIP20260053', 'Riesy Putri Navia, S.Kom', 'RiesyPutriNavia@gmail.com', 1, 0, '2026-11-05 02:15:47', '2026-11-05 02:15:47'),
(54, 'NIP20260054', 'Rurry Febriani Maryadi, S.Pd', 'RurryFebrianiMaryadi@gmail.com', 1, 0, '2026-11-05 02:15:47', '2026-11-05 02:15:47'),
(55, 'NIP20260055', 'Irvan Rizal, S.Pd', 'IrvanRizal@gmail.com', 1, 54, '2026-11-05 02:19:21', '2026-11-05 02:19:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas`
--

CREATE TABLE `t_kelas` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tingkat` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_kelas`
--

INSERT INTO `t_kelas` (`id`, `nama`, `tingkat`, `jurusan`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'X TKJ 1', 'X', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 09:37:21'),
(2, 'X BD', 'X', 'BD', 1, '2026-02-02 01:00:00', '2026-11-05 09:37:21'),
(3, 'XI BD', 'XI', 'BD', 1, '2026-03-03 01:00:00', '2026-11-05 09:37:21'),
(4, 'XI RPL 1', 'XI', 'RPL', 1, '2026-04-04 01:00:00', '2026-11-05 09:37:21'),
(5, 'X RPL 1', 'X', 'RPL', 1, '2026-05-05 01:00:00', '2026-11-05 09:37:21'),
(6, 'XII RPL 1', 'XII', 'RPL', 1, '2026-06-06 01:00:00', '2026-11-05 09:37:21'),
(7, 'XII TKJ 4', 'XII', 'TKJ', 1, '2026-07-07 01:00:00', '2026-11-05 09:37:21'),
(8, 'X RPL 2', 'X', 'RPL', 1, '2026-08-08 01:00:00', '2026-11-05 09:37:21'),
(9, 'X TKJ 2', 'X', 'TKJ', 1, '2026-09-09 01:00:00', '2026-11-05 09:37:21'),
(10, 'XII TKJ 1', 'XII', 'TKJ', 1, '2026-10-10 01:00:00', '2026-11-05 09:37:21'),
(11, 'XI TKR 2', 'XI', 'TKR', 1, '2026-11-11 01:00:00', '2026-11-05 09:37:21'),
(12, 'XII TKJ 3', 'XII', 'TKJ', 1, '2026-12-12 01:00:00', '2026-11-05 09:37:21'),
(13, 'XII RPL 2', 'XII', 'RPL', 1, '2026-01-13 01:00:00', '2026-11-05 09:37:21'),
(14, 'X TKJ 3', 'X', 'TKJ', 1, '2026-02-14 01:00:00', '2026-11-05 09:37:21'),
(15, 'X TKR 3', 'X', 'TKR', 1, '2026-03-15 01:00:00', '2026-11-05 09:37:21'),
(16, 'XI TKJ 1', 'XI', 'TKJ', 1, '2026-04-16 01:00:00', '2026-11-05 09:37:21'),
(17, 'X TSM 1', 'X', 'TSM', 1, '2026-05-17 01:00:00', '2026-11-05 09:37:21'),
(18, 'XII TKJ 2', 'XII', 'TKJ', 1, '2026-06-18 01:00:00', '2026-11-05 09:37:21'),
(19, 'XII BD', 'XII', 'BD', 1, '2026-07-19 01:00:00', '2026-11-05 09:37:21'),
(20, 'X TKR 4', 'X', 'TKR', 1, '2026-08-20 01:00:00', '2026-11-05 09:37:21'),
(21, 'XII TKR 4', 'XII', 'TKR', 1, '2026-09-21 01:00:00', '2026-11-05 09:37:21'),
(22, 'X TKR 1', 'X', 'TKR', 1, '2026-10-22 01:00:00', '2026-11-05 09:37:21'),
(23, 'X TSM 2', 'X', 'TSM', 1, '2026-11-23 01:00:00', '2026-11-05 09:37:21'),
(24, 'XI TKJ 2', 'XI', 'TKJ', 1, '2026-12-24 01:00:00', '2026-11-05 09:37:21'),
(25, 'XII TKR 5', 'XII', 'TKR', 1, '2026-01-25 01:00:00', '2026-11-05 09:37:21'),
(26, 'XII TSM 1', 'XII', 'TSM', 1, '2026-02-26 01:00:00', '2026-11-05 09:37:21'),
(27, 'XI TSM 2', 'XI', 'TSM', 1, '2026-03-27 01:00:00', '2026-11-05 09:37:21'),
(28, 'X TKR 2', 'X', 'TKR', 1, '2026-04-28 01:00:00', '2026-11-05 09:37:21'),
(29, 'X TSM 3', 'X', 'TSM', 1, '2026-05-01 01:00:00', '2026-11-05 09:37:21'),
(30, 'XII TSM 2', 'XII', 'TSM', 1, '2026-06-02 01:00:00', '2026-11-05 09:37:21'),
(31, 'XII TSM 3', 'XII', 'TSM', 1, '2026-07-03 01:00:00', '2026-11-05 09:37:21'),
(32, 'XII TKR 2', 'XII', 'TKR', 1, '2026-08-04 01:00:00', '2026-11-05 09:37:21'),
(33, 'X TKR 5', 'X', 'TKR', 1, '2026-09-05 01:00:00', '2026-11-05 09:37:21'),
(34, 'XI TKR 1', 'XI', 'TKR', 1, '2026-10-06 01:00:00', '2026-11-05 09:37:21'),
(35, 'XII TKR 1', 'XII', 'TKR', 1, '2026-11-07 01:00:00', '2026-11-05 09:37:21'),
(36, 'XI TKR 3', 'XI', 'TKR', 1, '2026-12-08 01:00:00', '2026-11-05 09:37:21'),
(37, 'XII TKR 3', 'XII', 'TKR', 1, '2026-01-09 01:00:00', '2026-11-05 09:37:21'),
(38, 'XI TSM 1', 'XI', 'TSM', 1, '2026-02-10 01:00:00', '2026-11-05 09:37:21'),
(39, 'XII AKL 3', 'XII', 'AKL', 0, '2026-03-11 01:00:00', '2026-11-05 09:37:21'),
(40, 'X MPLB 3', 'X', 'MPLB', 0, '2026-04-12 01:00:00', '2026-11-05 09:37:21'),
(41, 'XI RPL 3', 'XI', 'RPL', 0, '2026-05-13 01:00:00', '2026-11-05 09:37:21'),
(42, 'XII TKJ 3', 'XII', 'TKJ', 0, '2026-06-14 01:00:00', '2026-11-05 09:37:21'),
(43, 'X DKV 3', 'X', 'DKV', 0, '2026-07-15 01:00:00', '2026-11-05 09:37:21'),
(44, 'XI AKL 3', 'XI', 'AKL', 0, '2026-08-16 01:00:00', '2026-11-05 09:37:21'),
(45, 'XII MPLB 3', 'XII', 'MPLB', 0, '2026-09-17 01:00:00', '2026-11-05 09:37:21'),
(46, 'X RPL 4', 'X', 'RPL', 0, '2026-10-18 01:00:00', '2026-11-05 09:37:21'),
(47, 'XI TKJ 4', 'XI', 'TKJ', 0, '2026-11-19 01:00:00', '2026-11-05 09:37:21'),
(48, 'XII DKV 4', 'XII', 'DKV', 0, '2026-12-20 01:00:00', '2026-11-05 09:37:21'),
(49, 'X AKL 4', 'X', 'AKL', 0, '2026-01-21 01:00:00', '2026-11-05 09:37:21'),
(50, 'XI MPLB 4', 'XI', 'MPLB', 0, '2026-02-22 01:00:00', '2026-11-05 09:37:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas_siswa`
--

CREATE TABLE `t_kelas_siswa` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_kelas_siswa`
--

INSERT INTO `t_kelas_siswa` (`id`, `siswa_id`, `tahun_ajaran_id`, `kelas_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 50, 13, '1977-07-01', '1978-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, 2, 50, 13, '1977-07-01', '1978-06-30', 1, '2026-01-02 01:00:00', '2026-01-02 01:00:00'),
(3, 3, 50, 13, '1977-07-01', '1978-06-30', 1, '2026-01-03 01:00:00', '2026-01-03 01:00:00'),
(4, 4, 50, 13, '1977-07-01', '1978-06-30', 1, '2026-01-04 01:00:00', '2026-01-04 01:00:00'),
(5, 5, 50, 13, '1977-07-01', '1978-06-30', 1, '2026-01-05 01:00:00', '2026-01-05 01:00:00'),
(6, 6, 50, 13, '1977-07-01', '1978-06-30', 1, '2026-01-06 01:00:00', '2026-01-06 01:00:00'),
(7, 7, 50, 13, '1977-07-01', '1978-06-30', 1, '2026-01-07 01:00:00', '2026-01-07 01:00:00'),
(8, 8, 50, 13, '1977-07-01', '1978-06-30', 1, '2026-01-08 01:00:00', '2026-01-08 01:00:00'),
(9, 9, 50, 13, '1977-07-01', '1978-06-30', 1, '2026-01-09 01:00:00', '2026-01-09 01:00:00'),
(10, 10, 50, 13, '1977-07-01', '1978-06-30', 1, '2026-01-10 01:00:00', '2026-01-10 01:00:00'),
(11, 11, 50, 13, '1978-07-01', '1979-06-30', 1, '2026-01-11 01:00:00', '2026-01-11 01:00:00'),
(12, 12, 50, 13, '1978-07-01', '1979-06-30', 1, '2026-01-12 01:00:00', '2026-01-12 01:00:00'),
(13, 13, 50, 13, '1978-07-01', '1979-06-30', 1, '2026-01-13 01:00:00', '2026-01-13 01:00:00'),
(14, 14, 50, 13, '1978-07-01', '1979-06-30', 1, '2026-01-14 01:00:00', '2026-01-14 01:00:00'),
(15, 15, 50, 13, '1978-07-01', '1979-06-30', 1, '2026-01-15 01:00:00', '2026-01-15 01:00:00'),
(16, 16, 50, 13, '1978-07-01', '1979-06-30', 1, '2026-01-16 01:00:00', '2026-01-16 01:00:00'),
(17, 17, 50, 13, '1978-07-01', '1979-06-30', 1, '2026-01-17 01:00:00', '2026-01-17 01:00:00'),
(18, 18, 50, 13, '1978-07-01', '1979-06-30', 1, '2026-01-18 01:00:00', '2026-01-18 01:00:00'),
(19, 19, 2, 13, '1978-07-01', '1979-06-30', 1, '2026-01-19 01:00:00', '2026-01-19 01:00:00'),
(20, 20, 50, 13, '1978-07-01', '1979-06-30', 1, '2026-01-20 01:00:00', '2026-01-20 01:00:00'),
(21, 21, 50, 13, '1979-07-01', '1980-06-30', 1, '2026-01-21 01:00:00', '2026-01-21 01:00:00'),
(22, 22, 50, 13, '1979-07-01', '1980-06-30', 1, '2026-01-22 01:00:00', '0000-00-00 00:00:00'),
(23, 23, 50, 13, '1979-07-01', '1980-06-30', 1, '2026-01-23 01:00:00', '2026-01-23 01:00:00'),
(24, 24, 50, 13, '1979-07-01', '1980-06-30', 1, '2026-01-24 01:00:00', '2026-01-24 01:00:00'),
(25, 25, 50, 13, '1979-07-01', '1980-06-30', 1, '2026-01-25 01:00:00', '2026-01-25 01:00:00'),
(26, 26, 50, 13, '1979-07-01', '1980-06-30', 1, '2026-01-26 01:00:00', '2026-01-26 01:00:00'),
(82, 83, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(83, 84, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(84, 85, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(85, 86, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(86, 87, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(87, 88, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(88, 89, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(89, 90, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(90, 91, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(91, 92, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(92, 93, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(93, 94, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(94, 95, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(95, 96, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(96, 97, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(97, 98, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(98, 99, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(99, 100, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(100, 101, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(101, 102, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(102, 103, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(103, 104, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(104, 105, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(105, 106, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(106, 107, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(107, 108, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(108, 109, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(109, 110, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(110, 111, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(111, 112, 50, 20, '2026-07-01', '2027-06-30', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran`
--

CREATE TABLE `t_pelanggaran` (
  `id` int(11) NOT NULL,
  `pelanggaran_kategori_id` int(11) NOT NULL,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `poin` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran`
--

INSERT INTO `t_pelanggaran` (`id`, `pelanggaran_kategori_id`, `kode`, `nama`, `poin`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 'PLG001', 'Terlambat Masuk Sekolah', 1, 'Siswa datang melewati waktu masuk sekolah yang telah ditentukan.', 1, '2026-01-01 01:00:00', '2026-10-21 02:22:21'),
(2, 2, 'PLG002', 'Tidak Memakai Seragam Sesuai Aturan', 2, 'Siswa mengenakan seragam yang tidak sesuai dengan ketentuan sekolah.', 1, '2026-02-02 01:00:00', '2026-10-21 02:22:21'),
(3, 3, 'PLG003', 'Tidak Hadir Tanpa Keterangan', 3, 'Siswa tidak hadir di sekolah tanpa keterangan yang sah.', 1, '2026-03-03 01:00:00', '2026-10-21 02:22:21'),
(4, 4, 'PLG004', 'Rambut Tidak Sesuai Aturan', 4, 'Penampilan rambut siswa tidak sesuai dengan tata tertib sekolah.', 1, '2026-04-04 01:00:00', '2026-10-21 02:22:21'),
(5, 5, 'PLG005', 'Tidak Mengerjakan Tugas', 5, 'Siswa tidak mengerjakan tugas yang diberikan oleh guru.', 1, '2026-05-05 01:00:00', '2026-10-21 02:22:21'),
(6, 6, 'PLG006', 'Keluar Kelas Tanpa Izin', 1, 'Siswa meninggalkan ruang kelas tanpa izin dari guru.', 1, '2026-06-06 01:00:00', '2026-10-21 02:22:21'),
(7, 7, 'PLG007', 'Tidak Memakai Atribut Sekolah', 2, 'Siswa tidak menggunakan atribut sekolah yang diwajibkan.', 1, '2026-07-07 01:00:00', '2026-10-21 02:22:21'),
(8, 8, 'PLG008', 'Terlambat Masuk Kelas', 3, 'Siswa terlambat memasuki ruang kelas setelah pelajaran dimulai.', 1, '2026-08-08 01:00:00', '2026-10-21 02:22:21'),
(9, 9, 'PLG009', 'Memakai Sepatu Tidak Sesuai Aturan', 4, 'Siswa memakai sepatu yang tidak sesuai dengan ketentuan sekolah.', 1, '2026-09-09 01:00:00', '2026-10-21 02:22:21'),
(10, 10, 'PLG010', 'Menyontek Saat Ujian', 5, 'Siswa melakukan tindakan menyontek selama pelaksanaan ujian.', 1, '2026-10-10 01:00:00', '2026-10-21 02:22:21'),
(11, 11, 'PLG011', 'Membolos Saat Jam Pelajaran', 1, 'Siswa meninggalkan pelajaran tanpa izin atau alasan yang dapat diterima.', 1, '2026-11-11 01:00:00', '2026-10-21 02:22:21'),
(12, 12, 'PLG012', 'Tidak Mengikuti Upacara', 2, 'Siswa tidak mengikuti upacara tanpa keterangan yang jelas.', 1, '2026-12-12 01:00:00', '2026-10-21 02:22:21'),
(13, 13, 'PLG013', 'Tidak Membawa Perlengkapan Belajar', 3, 'Siswa tidak membawa alat atau perlengkapan belajar yang diperlukan.', 1, '2026-01-13 01:00:00', '2026-10-21 02:22:21'),
(14, 14, 'PLG014', 'Membuang Sampah Sembarangan', 4, 'Siswa membuang sampah tidak pada tempat yang disediakan.', 1, '2026-02-14 01:00:00', '2026-10-21 02:22:21'),
(15, 15, 'PLG015', 'Berkelahi dengan Teman', 5, 'Siswa terlibat perkelahian dengan siswa lain.', 1, '2026-03-15 01:00:00', '2026-10-21 02:22:21'),
(16, 16, 'PLG016', 'Berkata Tidak Sopan', 1, 'Siswa menggunakan perkataan yang tidak sopan di lingkungan sekolah.', 1, '2026-04-16 01:00:00', '2026-10-21 02:22:21'),
(17, 17, 'PLG017', 'Mengganggu Teman Saat Belajar', 2, 'Siswa mengganggu teman sehingga kegiatan belajar terganggu.', 1, '2026-05-17 01:00:00', '2026-10-21 02:22:21'),
(18, 18, 'PLG018', 'Tidak Melaksanakan Piket Kelas', 3, 'Siswa tidak menjalankan tugas piket kelas sesuai jadwal.', 1, '2026-06-18 01:00:00', '2026-10-21 02:22:21'),
(19, 19, 'PLG019', 'Merusak Fasilitas Sekolah', 4, 'Siswa merusak atau menggunakan fasilitas sekolah secara tidak semestinya.', 1, '2026-07-19 01:00:00', '2026-10-21 02:22:21'),
(20, 20, 'PLG020', 'Tidak Mengumpulkan Tugas', 5, 'Siswa tidak menyerahkan tugas sesuai batas waktu yang ditentukan.', 1, '2026-08-20 01:00:00', '2026-10-21 02:22:21'),
(21, 21, 'PLG021', 'Menggunakan HP Saat Pelajaran', 1, 'Siswa menggunakan telepon genggam tanpa izin saat pembelajaran.', 1, '2026-09-21 01:00:00', '2026-10-21 02:22:21'),
(22, 22, 'PLG022', 'Tidur Saat Pelajaran', 2, 'Siswa tidur saat kegiatan belajar mengajar berlangsung.', 1, '2026-10-22 01:00:00', '2026-10-21 02:22:21'),
(23, 23, 'PLG023', 'Tidak Mengikuti Kegiatan Sekolah', 3, 'Siswa tidak mengikuti kegiatan resmi sekolah tanpa alasan yang sah.', 1, '2026-11-23 01:00:00', '2026-10-21 02:22:21'),
(24, 24, 'PLG024', 'Memakai Seragam Tidak Rapi', 4, 'Siswa mengenakan seragam yang tidak rapi atau tidak sesuai ketentuan.', 1, '2026-12-24 01:00:00', '2026-10-21 02:22:21'),
(25, 25, 'PLG025', 'Meninggalkan Sekolah Tanpa Izin', 5, 'Siswa meninggalkan lingkungan sekolah tanpa izin dari pihak berwenang.', 1, '2026-01-25 01:00:00', '2026-10-21 02:22:21'),
(26, 26, 'PLG026', 'Tidak Membawa Buku Pelajaran', 1, 'Siswa tidak membawa buku yang diperlukan untuk mengikuti pelajaran.', 1, '2026-02-26 01:00:00', '2026-10-21 02:22:21'),
(27, 27, 'PLG027', 'Mencontek Pekerjaan Teman', 2, 'Siswa menyalin jawaban atau pekerjaan milik siswa lain.', 1, '2026-03-27 01:00:00', '2026-10-21 02:22:21'),
(28, 28, 'PLG028', 'Tidak Mematuhi Tata Tertib', 3, 'Siswa melakukan tindakan yang bertentangan dengan tata tertib sekolah.', 1, '2026-04-28 01:00:00', '2026-10-21 02:22:21'),
(29, 29, 'PLG029', 'Membuat Keributan di Kelas', 4, 'Siswa membuat keributan yang mengganggu proses pembelajaran.', 1, '2026-05-01 01:00:00', '2026-10-21 02:22:21'),
(30, 30, 'PLG030', 'Tidak Menghormati Guru', 5, 'Siswa menunjukkan perilaku yang tidak menghormati guru.', 1, '2026-06-02 01:00:00', '2026-10-21 02:22:21'),
(31, 31, 'PLG031', 'Terlambat Kembali dari Istirahat', 1, 'Siswa terlambat kembali ke kelas setelah waktu istirahat berakhir.', 1, '2026-07-03 01:00:00', '2026-10-21 02:22:21'),
(32, 32, 'PLG032', 'Tidak Memakai Ikat Pinggang', 2, 'Siswa tidak menggunakan ikat pinggang sesuai ketentuan seragam.', 1, '2026-08-04 01:00:00', '2026-10-21 02:22:21'),
(33, 33, 'PLG033', 'Memakai Aksesori Terlarang', 3, 'Siswa memakai aksesori yang tidak diperbolehkan oleh sekolah.', 1, '2026-09-05 01:00:00', '2026-10-21 02:22:21'),
(34, 34, 'PLG034', 'Mencoret Meja atau Dinding', 4, 'Siswa mencoret meja, kursi, dinding, atau fasilitas sekolah lainnya.', 1, '2026-10-06 01:00:00', '2026-10-21 02:22:21'),
(35, 35, 'PLG035', 'Tidak Menjaga Kebersihan Kelas', 5, 'Siswa tidak menjaga kebersihan ruang kelas dan lingkungan sekitarnya.', 1, '2026-11-07 01:00:00', '2026-10-21 02:22:21'),
(36, 36, 'PLG036', 'Tidak Membawa Kartu Pelajar', 1, 'Siswa tidak membawa kartu pelajar saat diwajibkan.', 1, '2026-12-08 01:00:00', '2026-10-21 02:22:21'),
(37, 37, 'PLG037', 'Tidak Mengikuti Pelajaran Praktik', 2, 'Siswa tidak mengikuti kegiatan praktik tanpa alasan yang sah.', 1, '2026-01-09 01:00:00', '2026-10-21 02:22:21'),
(38, 38, 'PLG038', 'Tidak Memakai Perlengkapan Praktik', 3, 'Siswa tidak menggunakan perlengkapan keselamatan atau praktik yang diwajibkan.', 1, '2026-02-10 01:00:00', '2026-10-21 02:22:21'),
(39, 39, 'PLG039', 'Menyalahgunakan Fasilitas Laboratorium', 4, 'Siswa menggunakan fasilitas laboratorium tidak sesuai prosedur.', 1, '2026-03-11 01:00:00', '2026-10-21 02:22:21'),
(40, 40, 'PLG040', 'Tidak Mematuhi Aturan Perpustakaan', 5, 'Siswa tidak mematuhi ketentuan penggunaan perpustakaan.', 1, '2026-04-12 01:00:00', '2026-10-21 02:22:21'),
(41, 41, 'PLG041', 'Berisik Saat Kegiatan Belajar', 1, 'Siswa berbicara atau membuat suara gaduh saat pembelajaran berlangsung.', 1, '2026-05-13 01:00:00', '2026-10-21 02:22:21'),
(42, 42, 'PLG042', 'Tidak Mengikuti Kerja Bakti', 2, 'Siswa tidak mengikuti kegiatan kerja bakti yang telah dijadwalkan.', 1, '2026-06-14 01:00:00', '2026-10-21 02:22:21'),
(43, 43, 'PLG043', 'Memalsukan Tanda Tangan', 3, 'Siswa memalsukan tanda tangan pada dokumen sekolah.', 1, '2026-07-15 01:00:00', '2026-10-21 02:22:21'),
(44, 44, 'PLG044', 'Membawa Barang yang Dilarang', 4, 'Siswa membawa barang yang dilarang oleh peraturan sekolah.', 1, '2026-08-16 01:00:00', '2026-10-21 02:22:21'),
(45, 45, 'PLG045', 'Mengambil Barang Milik Teman', 5, 'Siswa mengambil barang milik orang lain tanpa izin.', 1, '2026-09-17 01:00:00', '2026-10-21 02:22:21'),
(46, 46, 'PLG046', 'Tidak Mengikuti Ujian Tanpa Alasan', 1, 'Siswa tidak mengikuti ujian tanpa keterangan yang dapat diterima.', 1, '2026-10-18 01:00:00', '2026-10-21 02:22:21'),
(47, 47, 'PLG047', 'Tidak Menyelesaikan Tugas Kelompok', 2, 'Siswa tidak menjalankan bagian tugas kelompok yang menjadi tanggung jawabnya.', 1, '2026-11-19 01:00:00', '2026-10-21 02:22:21'),
(48, 48, 'PLG048', 'Menggunakan Seragam Olahraga Tidak Sesuai', 3, 'Siswa memakai seragam olahraga yang tidak sesuai ketentuan.', 1, '2026-12-20 01:00:00', '2026-10-21 02:22:21'),
(49, 49, 'PLG049', 'Tidak Menjaga Ketertiban Sekolah', 4, 'Siswa melakukan tindakan yang mengganggu ketertiban lingkungan sekolah.', 1, '2026-01-21 01:00:00', '2026-10-21 02:22:21'),
(50, 50, 'PLG050', 'Mengulangi Pelanggaran yang Sama', 5, 'Siswa kembali melakukan pelanggaran yang sebelumnya telah dicatat.', 1, '2026-02-22 01:00:00', '2026-10-21 02:22:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_kategori`
--

CREATE TABLE `t_pelanggaran_kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran_kategori`
--

INSERT INTO `t_pelanggaran_kategori` (`id`, `nama`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Kedisiplinan Waktu', 'Kategori untuk pelanggaran terkait ketepatan waktu datang dan mengikuti kegiatan sekolah.', 1, '2026-01-01 01:00:00', '2026-10-21 02:25:07'),
(2, 'Seragam dan Atribut', 'Kategori untuk ketidakpatuhan terhadap ketentuan seragam dan atribut sekolah.', 1, '2026-02-02 01:00:00', '2026-10-21 02:25:07'),
(3, 'Kehadiran Siswa', 'Kategori untuk ketidakhadiran atau ketidakpatuhan terhadap aturan kehadiran.', 1, '2026-03-03 01:00:00', '2026-10-21 02:25:07'),
(4, 'Etika dan Sikap', 'Kategori untuk perilaku dan sikap yang tidak sesuai dengan tata tertib.', 1, '2026-04-04 01:00:00', '2026-10-21 02:25:07'),
(5, 'Akademik', 'Kategori untuk pelanggaran kewajiban dan aturan akademik.', 1, '2026-05-05 01:00:00', '2026-10-21 02:25:07'),
(6, 'Ketertiban Kelas', 'Kategori untuk tindakan yang mengganggu ketertiban selama pembelajaran.', 1, '2026-06-06 01:00:00', '2026-10-21 02:25:07'),
(7, 'Kerapian Diri', 'Kategori untuk penampilan diri yang tidak sesuai aturan sekolah.', 1, '2026-07-07 01:00:00', '2026-10-21 02:25:07'),
(8, 'Kebersihan Lingkungan', 'Kategori untuk tindakan yang mengganggu kebersihan lingkungan sekolah.', 1, '2026-08-08 01:00:00', '2026-10-21 02:25:07'),
(9, 'Tanggung Jawab', 'Kategori untuk kelalaian dalam menjalankan kewajiban sebagai siswa.', 1, '2026-09-09 01:00:00', '2026-10-21 02:25:07'),
(10, 'Kejujuran', 'Kategori untuk tindakan tidak jujur dalam kegiatan sekolah.', 1, '2026-10-10 01:00:00', '2026-10-21 02:25:07'),
(11, 'Penggunaan Teknologi', 'Kategori untuk penggunaan perangkat teknologi yang melanggar aturan.', 1, '2026-11-11 01:00:00', '2026-10-21 02:25:07'),
(12, 'Perizinan Siswa', 'Kategori untuk pelanggaran prosedur izin keluar atau meninggalkan sekolah.', 1, '2026-12-12 01:00:00', '2026-10-21 02:25:07'),
(13, 'Kegiatan Sekolah', 'Kategori untuk ketidakpatuhan terhadap aturan kegiatan sekolah.', 1, '2026-01-13 01:00:00', '2026-10-21 02:25:07'),
(14, 'Perilaku Sosial', 'Kategori untuk perilaku yang merugikan atau mengganggu orang lain.', 1, '2026-02-14 01:00:00', '2026-10-21 02:25:07'),
(15, 'Penghormatan Guru', 'Kategori untuk perilaku yang tidak menghormati guru dan tenaga kependidikan.', 1, '2026-03-15 01:00:00', '2026-10-21 02:25:07'),
(16, 'Kedisiplinan Belajar', 'Kategori untuk ketidakdisiplinan selama proses belajar.', 1, '2026-04-16 01:00:00', '2026-10-21 02:25:07'),
(17, 'Kelengkapan Belajar', 'Kategori untuk tidak membawa perlengkapan belajar yang diwajibkan.', 1, '2026-05-17 01:00:00', '2026-10-21 02:25:07'),
(18, 'Tata Tertib Sekolah', 'Kategori untuk pelanggaran terhadap peraturan sekolah.', 1, '2026-06-18 01:00:00', '2026-10-21 02:25:07'),
(19, 'Keamanan Sekolah', 'Kategori untuk tindakan yang membahayakan keamanan lingkungan sekolah.', 1, '2026-07-19 01:00:00', '2026-10-21 02:25:07'),
(20, 'Pemeliharaan Fasilitas', 'Kategori untuk tindakan yang merusak atau tidak menjaga fasilitas sekolah.', 1, '2026-08-20 01:00:00', '2026-10-21 02:25:07'),
(21, 'Ketertiban Ujian', 'Kategori untuk pelanggaran tata tertib selama pelaksanaan ujian.', 1, '2026-09-21 01:00:00', '2026-10-21 02:25:07'),
(22, 'Kejujuran Akademik', 'Kategori untuk tindakan menyontek atau melakukan kecurangan akademik.', 1, '2026-10-22 01:00:00', '2026-10-21 02:25:07'),
(23, 'Kegiatan Upacara', 'Kategori untuk ketidakpatuhan saat upacara dan kegiatan resmi sekolah.', 1, '2026-11-23 01:00:00', '2026-10-21 02:25:07'),
(24, 'Kegiatan Piket', 'Kategori untuk tidak menjalankan tugas piket sesuai jadwal.', 1, '2026-12-24 01:00:00', '2026-10-21 02:25:07'),
(25, 'Kegiatan Kerja Bakti', 'Kategori untuk ketidakpatuhan dalam kegiatan kebersihan bersama.', 1, '2026-01-25 01:00:00', '2026-10-21 02:25:07'),
(26, 'Kedisiplinan Praktik', 'Kategori untuk pelanggaran aturan pembelajaran praktik kejuruan.', 1, '2026-02-26 01:00:00', '2026-10-21 02:25:07'),
(27, 'Keselamatan Praktik', 'Kategori untuk tidak mematuhi prosedur keselamatan saat praktik.', 1, '2026-03-27 01:00:00', '2026-10-21 02:25:07'),
(28, 'Penggunaan Laboratorium', 'Kategori untuk penggunaan laboratorium yang tidak sesuai prosedur.', 1, '2026-04-28 01:00:00', '2026-10-21 02:25:07'),
(29, 'Penggunaan Perpustakaan', 'Kategori untuk pelanggaran aturan penggunaan perpustakaan.', 1, '2026-05-01 01:00:00', '2026-10-21 02:25:07'),
(30, 'Kerapian Seragam', 'Kategori untuk penggunaan seragam yang tidak rapi atau tidak sesuai ketentuan.', 1, '2026-06-02 01:00:00', '2026-10-21 02:25:07'),
(31, 'Atribut Sekolah', 'Kategori untuk ketidaklengkapan atribut yang diwajibkan sekolah.', 1, '2026-07-03 01:00:00', '2026-10-21 02:25:07'),
(32, 'Komunikasi Sopan', 'Kategori untuk penggunaan bahasa atau komunikasi yang tidak sopan.', 1, '2026-08-04 01:00:00', '2026-10-21 02:25:07'),
(33, 'Pergaulan Siswa', 'Kategori untuk perilaku pergaulan yang melanggar tata tertib.', 1, '2026-09-05 01:00:00', '2026-10-21 02:25:07'),
(34, 'Pencegahan Perundungan', 'Kategori untuk perilaku perundungan atau tindakan merendahkan orang lain.', 1, '2026-10-06 01:00:00', '2026-10-21 02:25:07'),
(35, 'Pengelolaan Sampah', 'Kategori untuk tindakan membuang sampah sembarangan atau mengotori lingkungan.', 1, '2026-11-07 01:00:00', '2026-10-21 02:25:07'),
(36, 'Ketertiban Istirahat', 'Kategori untuk ketidakdisiplinan saat waktu istirahat.', 1, '2026-12-08 01:00:00', '2026-10-21 02:25:07'),
(37, 'Ketepatan Mengumpulkan Tugas', 'Kategori untuk kelalaian mengumpulkan tugas sesuai tenggat waktu.', 1, '2026-01-09 01:00:00', '2026-10-21 02:25:07'),
(38, 'Partisipasi Pembelajaran', 'Kategori untuk tidak mengikuti pembelajaran tanpa alasan yang sah.', 1, '2026-02-10 01:00:00', '2026-10-21 02:25:07'),
(39, 'Tanggung Jawab Kelompok', 'Kategori untuk tidak menjalankan tanggung jawab dalam tugas kelompok.', 1, '2026-03-11 01:00:00', '2026-10-21 02:25:07'),
(40, 'Kegiatan Ekstrakurikuler', 'Kategori untuk pelanggaran aturan kegiatan ekstrakurikuler.', 1, '2026-04-12 01:00:00', '2026-10-21 02:25:07'),
(41, 'Penggunaan Barang Pribadi', 'Kategori untuk penggunaan barang pribadi yang mengganggu pembelajaran.', 1, '2026-05-13 01:00:00', '2026-10-21 02:25:07'),
(42, 'Larangan Membawa Barang', 'Kategori untuk membawa barang yang dilarang oleh sekolah.', 1, '2026-06-14 01:00:00', '2026-10-21 02:25:07'),
(43, 'Keamanan Barang', 'Kategori untuk tindakan mengambil atau mengganggu barang milik orang lain.', 1, '2026-07-15 01:00:00', '2026-10-21 02:25:07'),
(44, 'Kejujuran Administrasi', 'Kategori untuk pemalsuan atau ketidakjujuran dalam dokumen administrasi.', 1, '2026-08-16 01:00:00', '2026-10-21 02:25:07'),
(45, 'Kepatuhan Perizinan', 'Kategori untuk ketidakpatuhan terhadap prosedur perizinan.', 1, '2026-09-17 01:00:00', '2026-10-21 02:25:07'),
(46, 'Ketertiban Lingkungan', 'Kategori untuk tindakan yang mengganggu ketertiban lingkungan sekolah.', 1, '2026-10-18 01:00:00', '2026-10-21 02:25:07'),
(47, 'Kedisiplinan Administrasi', 'Kategori untuk kelalaian dalam memenuhi ketentuan administrasi sekolah.', 1, '2026-11-19 01:00:00', '2026-10-21 02:25:07'),
(48, 'Etika Digital', 'Kategori untuk penggunaan teknologi digital yang tidak sesuai aturan.', 1, '2026-12-20 01:00:00', '2026-10-21 02:25:07'),
(49, 'Kepedulian Sosial', 'Kategori untuk perilaku yang tidak menunjukkan kepedulian terhadap warga sekolah.', 1, '2026-01-21 01:00:00', '2026-10-21 02:25:07'),
(50, 'Pelanggaran Berulang', 'Kategori untuk pelanggaran yang dilakukan kembali setelah pelanggaran sebelumnya dicatat.', 1, '2026-02-22 01:00:00', '2026-10-21 02:25:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_siswa`
--

CREATE TABLE `t_pelanggaran_siswa` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nama_siswa` varchar(150) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `pelanggaran_id` int(11) NOT NULL,
  `nama_pelanggaran` varchar(150) NOT NULL,
  `pelanggaran_kategori_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `nama_guru` varchar(150) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text NOT NULL,
  `poin` int(11) NOT NULL,
  `tindakan` text NOT NULL,
  `status` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran_siswa`
--

INSERT INTO `t_pelanggaran_siswa` (`id`, `tahun_ajaran_id`, `siswa_id`, `nama_siswa`, `kelas_id`, `nama_kelas`, `pelanggaran_id`, `nama_pelanggaran`, `pelanggaran_kategori_id`, `guru_id`, `nama_guru`, `tanggal`, `keterangan`, `poin`, `tindakan`, `status`, `created_at`, `updated_at`) VALUES
(55, 50, 1, 'Adittia Nur Lantip', 13, 'XII RPL 2', 16, 'Berkata Tidak Sopan', 16, 2, 'Ai Nuryaidah, S.Pd', '2026-11-06', '', 1, '', 'Pembinaan', '2026-11-06 05:02:23', '2026-11-06 05:02:23'),
(56, 50, 2, 'Agnia Rosya Noerillah', 13, 'XII RPL 2', 34, 'Mencoret Meja atau Dinding', 34, 2, 'Ai Nuryaidah, S.Pd', '2026-11-07', '', 4, '', 'Ditindaklanjuti', '2026-11-06 05:07:29', '2026-11-06 05:07:29'),
(57, 2, 19, 'Rizki Permata Sugianto', 13, 'XII RPL 2', 47, 'Tidak Menyelesaikan Tugas Kelompok', 47, 2, 'Ai Nuryaidah, S.Pd', '2026-11-27', '', 2, '', 'Selesai', '2026-11-06 05:11:45', '2026-11-06 05:11:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(30) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `jenis_kelamin` char(1) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nis`, `nisn`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '102410544', '0000000001', 'Adittia Nur Lantip', 'L', '2010-01-15', 'Jl. Pendidikan No. 1, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, '102410545', '0000000002', 'Agnia Rosya Noerillah', 'P', '2010-02-20', 'Jl. Pendidikan No. 2, Tasikmalaya', 1, '2026-02-02 01:00:00', '2026-02-02 01:00:00'),
(3, '102410546', '0000000003', 'Anisa Amirulla', 'P', '2010-03-12', 'Jl. Pendidikan No. 3, Tasikmalaya', 1, '2026-03-03 01:00:00', '2026-03-03 01:00:00'),
(4, '102410547', '0000000004', 'Aradea Kandi', 'L', '2010-04-25', 'Jl. Pendidikan No. 4, Tasikmalaya', 1, '2026-04-04 01:00:00', '2026-04-04 01:00:00'),
(5, '102410548', '0000000005', 'Arfa Diesa Okmaleliza', 'P', '2010-05-18', 'Jl. Pendidikan No. 5, Tasikmalaya', 1, '2026-05-05 01:00:00', '2026-05-05 01:00:00'),
(6, '102410549', '0000000006', 'Fitri Nur Syami', 'P', '2010-06-07', 'Jl. Pendidikan No. 6, Tasikmalaya', 1, '2026-06-06 01:00:00', '2026-06-06 01:00:00'),
(7, '102410550', '0000000007', 'Junita Putri Nur Rahman', 'P', '2010-07-22', 'Jl. Pendidikan No. 7, Tasikmalaya', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, '102410551', '0000000008', 'Mohammad Atalarik Ali Syahbana', 'L', '2010-08-14', 'Jl. Pendidikan No. 8, Tasikmalaya', 1, '2026-08-08 01:00:00', '2026-08-08 01:00:00'),
(9, '102410552', '0000000009', 'Muhammad Fahril Fahrurozi', 'L', '2010-09-30', 'Jl. Pendidikan No. 9, Tasikmalaya', 1, '2026-09-09 01:00:00', '2026-09-09 01:00:00'),
(10, '102410750', '0000000010', 'Muhammad Adnan Syaeful Islami', 'L', '2010-10-11', 'Jl. Pendidikan No. 10, Tasikmalaya', 1, '2026-10-10 01:00:00', '2026-10-10 01:00:00'),
(11, '102410553', '0000000011', 'Muhammad Zahru Aqila Fikri', 'L', '2010-11-05', 'Jl. Pendidikan No. 11, Tasikmalaya', 1, '2026-11-11 01:00:00', '2026-11-11 01:00:00'),
(12, '102410554', '0000000012', 'Nadia Zulfa Soraya', 'P', '2010-12-19', 'Jl. Pendidikan No. 12, Tasikmalaya', 1, '2026-12-12 01:00:00', '2026-12-12 01:00:00'),
(13, '102410555', '0000000013', 'Nazril Ilham', 'L', '2011-01-08', 'Jl. Pendidikan No. 13, Tasikmalaya', 1, '2026-01-13 01:00:00', '2026-01-13 01:00:00'),
(14, '102410556', '0000000014', 'Puspita Dewi', 'P', '2011-02-17', 'Jl. Pendidikan No. 14, Tasikmalaya', 1, '2026-02-14 01:00:00', '2026-02-14 01:00:00'),
(15, '102410558', '0000000015', 'Rakha Muhammad Naykal', 'L', '2011-03-29', 'Jl. Pendidikan No. 15, Tasikmalaya', 1, '2026-03-15 01:00:00', '2026-03-15 01:00:00'),
(16, '102410559', '0000000016', 'Rani Melani', 'P', '2011-04-13', 'Jl. Pendidikan No. 16, Tasikmalaya', 1, '2026-04-16 01:00:00', '2026-04-16 01:00:00'),
(17, '102410560', '0000000017', 'Reihan Muzaki', 'L', '2011-05-21', 'Jl. Pendidikan No. 17, Tasikmalaya', 1, '2026-05-17 01:00:00', '2026-05-17 01:00:00'),
(18, '102410561', '0000000018', 'Resa Juliansah', 'L', '2011-06-09', 'Jl. Pendidikan No. 18, Tasikmalaya', 1, '2026-06-18 01:00:00', '2026-06-18 01:00:00'),
(19, '102410562', '0000000019', 'Rizki Permata Sugianto', 'L', '2011-07-27', 'Jl. Pendidikan No. 19, Tasikmalaya', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, '102410563', '0000000020', 'Sahira Firaj', 'P', '2011-08-16', 'Jl. Pendidikan No. 20, Tasikmalaya', 1, '2026-08-20 01:00:00', '2026-08-20 01:00:00'),
(21, '102410564', '0000000021', 'Sendi Mardian', 'L', '2011-09-04', 'Jl. Pendidikan No. 21, Tasikmalaya', 1, '2026-09-21 01:00:00', '0000-00-00 00:00:00'),
(22, '102410755', '0000000022', 'Shahaidan Nizzar', 'L', '2011-10-23', 'Jl. Pendidikan No. 22, Tasikmalaya', 1, '2026-10-22 01:00:00', '2026-10-22 01:00:00'),
(23, '102410565', '0000000023', 'Sherly Satriani', 'P', '2011-11-14', 'Jl. Pendidikan No. 23, Tasikmalaya', 1, '2026-11-23 01:00:00', '2026-11-23 01:00:00'),
(24, '102410566', '0000000024', 'Sofinurjanah', 'P', '2011-12-02', 'Jl. Pendidikan No. 24, Tasikmalaya', 1, '2026-12-24 01:00:00', '2026-12-24 01:00:00'),
(25, '102410567', '0000000025', 'Suraiya Salsabila Azzahira', 'P', '2010-01-28', 'Jl. Pendidikan No. 25, Tasikmalaya', 1, '2026-01-25 01:00:00', '2026-01-25 01:00:00'),
(26, '102410568', '0000000026', 'Toni Antonio', 'L', '2010-02-11', 'Jl. Pendidikan No. 26, Tasikmalaya', 1, '2026-02-26 01:00:00', '2026-02-26 01:00:00'),
(83, '102611149', '0000000027', 'Abdul Malik Fajar', 'L', '2009-01-12', 'Jl. Contoh Melati No. 1, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(84, '102611150', '0000000028', 'Agin Ginanjar Saputra', 'L', '2009-03-21', 'Jl. Contoh Mawar No. 2, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(85, '102611151', '0000000029', 'Algi Athari', 'L', '2009-05-14', 'Jl. Contoh Anggrek No. 3, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(86, '102611152', '0000000030', 'Arystya Apriliandi Franki', 'L', '2009-07-09', 'Jl. Contoh Kenanga No. 4, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(87, '102611153', '0000000031', 'Billah Abdillah', 'L', '2009-02-18', 'Jl. Contoh Dahlia No. 5, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(88, '102611154', '0000000032', 'Dhimas Dwi Ramadhan', 'L', '2009-04-25', 'Jl. Contoh Teratai No. 6, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(89, '102611155', '0000000033', 'Fadil Ramdhani', 'L', '2009-06-11', 'Jl. Contoh Flamboyan No. 7, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(90, '102611478', '0000000034', 'Faraz Fachri Muharram', 'L', '2009-08-17', 'Jl. Contoh Cempaka No. 8, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(91, '102611157', '0000000035', 'Haikal Ilham Suhanda', 'L', '2009-09-23', 'Jl. Contoh Kamboja No. 9, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(92, '102611158', '0000000036', 'Laodra Roba Ahfiana', 'L', '2009-10-05', 'Jl. Contoh Cemara No. 10, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(93, '102611159', '0000000037', 'Lintang Febrian Maulana', 'L', '2009-11-19', 'Jl. Contoh Pinus No. 11, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(94, '102611160', '0000000038', 'Lisan Dzakir Ramadhan', 'L', '2009-12-08', 'Jl. Contoh Merpati No. 12, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(95, '102611161', '0000000039', 'Muhamad Abylah Azam', 'L', '2010-01-16', 'Jl. Contoh Rajawali No. 13, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(96, '102611162', '0000000040', 'Muhamad Algiansyah', 'L', '2010-02-22', 'Jl. Contoh Garuda No. 14, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(97, '102611163', '0000000041', 'Muhamad Azril Yasin', 'L', '2010-03-13', 'Jl. Contoh Merak No. 15, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(98, '102611164', '0000000042', 'Muhamad Itshar', 'L', '2010-04-27', 'Jl. Contoh Elang No. 16, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(99, '102611165', '0000000043', 'Muhamad Nasabandy', 'L', '2010-05-09', 'Jl. Contoh Rajin No. 17, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(100, '102611166', '0000000044', 'Muhammad Adam Efathurrohman', 'L', '2010-06-15', 'Jl. Contoh Sukamaju No. 18, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(101, '102611167', '0000000045', 'Muhammad Dzikri Abdul Jalil', 'L', '2010-07-20', 'Jl. Contoh Sukaraja No. 19, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(102, '102611476', '0000000046', 'Muhammad Rafa Firmansyah', 'L', '2010-08-11', 'Jl. Contoh Sukasari No. 20, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(103, '102611169', '0000000047', 'Muhammad Rizky Hidayatullah', 'L', '2010-09-24', 'Jl. Contoh Cibereum No. 21, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(104, '102611170', '0000000048', 'Nasta Pratama Supriatna', 'L', '2010-10-06', 'Jl. Contoh Cihideung No. 22, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(105, '102611171', '0000000049', 'Raditia Purnama', 'L', '2010-11-18', 'Jl. Contoh Indihiang No. 23, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(106, '102611172', '0000000050', 'Rahmat Suparman', 'L', '2010-12-12', 'Jl. Contoh Kawalu No. 24, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(107, '102611173', '0000000051', 'Rama Manramsyah Gustian Pratama', 'L', '2009-02-05', 'Jl. Contoh Mangkubumi No. 25, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(108, '102611174', '0000000052', 'Rehan Nadi Saputra', 'L', '2009-04-19', 'Jl. Contoh Tamansari No. 26, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(109, '102611175', '0000000053', 'Rof Rof Al Arof Subul Amin', 'L', '2009-06-23', 'Jl. Contoh Singaparna No. 27, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(110, '102611176', '0000000054', 'Sandi Permana', 'L', '2009-08-08', 'Jl. Contoh Manonjaya No. 28, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(111, '102611177', '0000000055', 'Tubagus Rizqi', 'L', '2009-10-14', 'Jl. Contoh Rajapolah No. 29, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(112, '102611178', '0000000056', 'Wildan Nurjaman', 'L', '2009-12-20', 'Jl. Contoh Cisayong No. 30, Tasikmalaya', 1, '2026-11-06 08:18:37', '2026-11-06 08:18:37'),
(113, '102410341', '0000000057', 'Adi Bayu Maulana Putra', 'L', '2008-01-12', 'Jl. Melati, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(114, '102410343', '0000000058', 'Agan Basyri Sa\'ady', 'L', '2008-02-15', 'Jl. Mawar, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(115, '102410344', '0000000059', 'Ali Rahmat Mutaqin', 'L', '2008-03-21', 'Jl. Anggrek, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(116, '102410345', '0000000060', 'Alzi Kusnendi', 'L', '2008-04-09', 'Jl. Kenanga, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(117, '102410346', '0000000061', 'Andika Nugraha', 'L', '2008-05-17', 'Jl. Dahlia, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(118, '102410347', '0000000062', 'Andri Mabrur', 'L', '2008-06-11', 'Jl. Teratai, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(119, '102410349', '0000000063', 'Dafa Ezar Ramadan', 'L', '2008-07-23', 'Jl. Cempaka, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(120, '102410350', '0000000064', 'Defri Muhamad Ramadhan', 'L', '2008-08-14', 'Jl. Cemara, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(121, '102410351', '0000000065', 'Deva Al Fauzan', 'L', '2008-09-05', 'Jl. Merpati, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(122, '102410352', '0000000066', 'Dika Ardiansyah Putra', 'L', '2008-10-19', 'Jl. Rajawali, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(123, '102410353', '0000000067', 'Fadli Muhammad Ramdhan', 'L', '2008-11-08', 'Jl. Garuda, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(124, '102410354', '0000000068', 'Febi Danil Gunawan', 'L', '2008-12-16', 'Jl. Merak, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(125, '102410355', '0000000069', 'Gabriel Alhazmi Adiyasa', 'L', '2008-01-25', 'Jl. Elang, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(126, '102410356', '0000000070', 'Gisna Sufi Ginanjar', 'L', '2008-02-27', 'Jl. Pinus, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(127, '102410358', '0000000071', 'Lutfi Indriawan', 'L', '2008-03-13', 'Jl. Flamboyan, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(128, '102410359', '0000000072', 'Moch Alif Sayyandra Abipraya', 'L', '2008-04-22', 'Jl. Sukamaju, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(129, '102410360', '0000000073', 'Muhamad Al Mu\'min Akbar', 'L', '2008-05-06', 'Jl. Sukaraja, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(130, '102410361', '0000000074', 'Muhamad Rafi Ramdani', 'L', '2008-06-18', 'Jl. Sukasari, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(131, '102410362', '0000000075', 'Muhamad Zulpi Zulkipli', 'L', '2008-07-29', 'Jl. Cibeureum, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(132, '102410364', '0000000076', 'Muhammad Faisal Arrizal', 'L', '2008-08-12', 'Jl. Cihideung, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(133, '102410366', '0000000077', 'Muhammad Rafliyatu Rizqi Nur Rohman', 'L', '2008-09-24', 'Jl. Indihiang, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(134, '102410367', '0000000078', 'Muhammad Ridho Qushoiri', 'L', '2008-10-07', 'Jl. Kawalu, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(135, '102410368', '0000000079', 'Raditia Nugraha', 'L', '2008-11-15', 'Jl. Tamansari, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(136, '102410369', '0000000080', 'Raeval Legya Rachman', 'L', '2008-12-03', 'Jl. Mangkubumi, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(137, '102410370', '0000000081', 'Rafa Azkia', 'L', '2008-01-19', 'Jl. Singaparna, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(138, '102410371', '0000000082', 'Refan Sultansah', 'L', '2008-02-08', 'Jl. Rajapolah, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(139, '102410372', '0000000083', 'Reihan Al Azhar', 'L', '2008-03-26', 'Jl. Cisayong, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(140, '112510757', '0000000084', 'Sani Achmad Fikri', 'L', '2008-04-16', 'Jl. Leuwisari, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(141, '102410373', '0000000085', 'Sendy Ramdhani', 'L', '2008-05-28', 'Jl. Manonjaya, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(142, '102410374', '0000000086', 'Surahman Wahdan Andika', 'L', '2008-06-09', 'Jl. Salawu, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(143, '102410375', '0000000087', 'Tahmid Hidayatulloh', 'L', '2008-07-17', 'Jl. Sukaratu, Tasikmalaya', 1, '2026-11-06 08:30:14', '2026-11-06 08:30:14'),
(144, '102410520', '0091000001', 'Alfin Rahadian', 'L', '2008-01-12', 'Jl. Pendidikan No. 1', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(145, '102410521', '0091000002', 'Alisa Ramadhani', 'P', '2008-02-15', 'Jl. Pendidikan No. 2', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(146, '102410522', '0091000003', 'Alma Aura Livia', 'P', '2008-03-20', 'Jl. Pendidikan No. 3', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(147, '102410523', '0091000004', 'Anis Khoerunnisa Nur Ramadani', 'P', '2008-04-18', 'Jl. Pendidikan No. 4', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(148, '102410524', '0091000005', 'Azmil Amira', 'P', '2008-05-22', 'Jl. Pendidikan No. 5', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(149, '102410525', '0091000006', 'Azril Akbar Fawji Sedillah', 'L', '2008-06-10', 'Jl. Pendidikan No. 6', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(150, '102410527', '0091000007', 'Erlangga Saputra Hidayat', 'L', '2008-07-14', 'Jl. Pendidikan No. 7', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(151, '102410528', '0091000008', 'Hilma Humma Tunnajah', 'P', '2008-08-19', 'Jl. Pendidikan No. 8', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(152, '102410529', '0091000009', 'Ilma Najya', 'P', '2008-09-12', 'Jl. Pendidikan No. 9', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(153, '102410531', '0091000010', 'Julianda Dharma Pangestu', 'L', '2008-10-16', 'Jl. Pendidikan No. 10', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(154, '102410532', '0091000011', 'Kayla Nur Fadilah', 'P', '2008-11-21', 'Jl. Pendidikan No. 11', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(155, '102410533', '0091000012', 'Moza Dina Nabillah', 'P', '2008-12-09', 'Jl. Pendidikan No. 12', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(156, '102410534', '0091000013', 'Muhamad Alfarizi', 'L', '2008-01-25', 'Jl. Pendidikan No. 13', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(157, '102410535', '0091000014', 'Muhamad Fahrin Adam', 'L', '2008-02-27', 'Jl. Pendidikan No. 14', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(158, '102410536', '0091000015', 'Muhamad Zidane Maulidan', 'L', '2008-03-16', 'Jl. Pendidikan No. 15', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(159, '102410537', '0091000016', 'Muhamad Zulfan Nuralfansyah', 'L', '2008-04-24', 'Jl. Pendidikan No. 16', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(160, '102410538', '0091000017', 'Natiya Nur Azkiya', 'P', '2008-05-13', 'Jl. Pendidikan No. 17', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(161, '102410539', '0091000018', 'Novita Puspitasari', 'P', '2008-06-29', 'Jl. Pendidikan No. 18', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(162, '102410540', '0091000019', 'Nucky Andreas Febrian', 'L', '2008-07-08', 'Jl. Pendidikan No. 19', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(163, '102410543', '0091000020', 'Raditya Jeti Prasetya', 'L', '2008-08-26', 'Jl. Pendidikan No. 20', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(164, '102410741', '0091000021', 'Rangga Ramadiyanto Nurhakim', 'L', '2008-09-17', 'Jl. Pendidikan No. 21', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(165, '102410541', '0091000022', 'Risqi Muhamad Ihwani', 'L', '2008-10-23', 'Jl. Pendidikan No. 22', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(166, '102410542', '0091000023', 'Salwa Sabilatul Barakah', 'P', '2008-11-11', 'Jl. Pendidikan No. 23', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08'),
(167, '102410753', '0091000024', 'Tazkia Zahra', 'P', '2008-12-20', 'Jl. Pendidikan No. 24', 1, '2026-11-06 08:57:08', '2026-11-06 08:57:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_tahun_ajaran`
--

CREATE TABLE `t_tahun_ajaran` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_tahun_ajaran`
--

INSERT INTO `t_tahun_ajaran` (`id`, `nama`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, '1977/1978', '1977-07-01', '1978-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, '1978/1979', '1978-07-01', '1979-06-30', 0, '2026-02-02 01:00:00', '2026-02-02 01:00:00'),
(3, '1979/1980', '1979-07-01', '1980-06-30', 0, '2026-03-03 01:00:00', '2026-03-03 01:00:00'),
(4, '1980/1981', '1980-07-01', '1981-06-30', 0, '2026-04-04 01:00:00', '2026-04-04 01:00:00'),
(5, '1981/1982', '1981-07-01', '1982-06-30', 0, '2026-05-05 01:00:00', '2026-05-05 01:00:00'),
(6, '1982/1983', '1982-07-01', '1983-06-30', 0, '2026-06-06 01:00:00', '2026-06-06 01:00:00'),
(7, '1983/1984', '1983-07-01', '1984-06-30', 0, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, '1984/1985', '1984-07-01', '1985-06-30', 0, '2026-08-08 01:00:00', '2026-08-08 01:00:00'),
(9, '1985/1986', '1985-07-01', '1986-06-30', 0, '2026-09-09 01:00:00', '2026-09-09 01:00:00'),
(10, '1986/1987', '1986-07-01', '1987-06-30', 0, '2026-10-10 01:00:00', '2026-10-10 01:00:00'),
(11, '1987/1988', '1987-07-01', '1988-06-30', 0, '2026-11-11 01:00:00', '2026-11-11 01:00:00'),
(12, '1988/1989', '1988-07-01', '1989-06-30', 0, '2026-12-12 01:00:00', '2026-12-12 01:00:00'),
(13, '1989/1990', '1989-07-01', '1990-06-30', 0, '2026-01-13 01:00:00', '2026-01-13 01:00:00'),
(14, '1990/1991', '1990-07-01', '1991-06-30', 0, '2026-02-14 01:00:00', '2026-02-14 01:00:00'),
(15, '1991/1992', '1991-07-01', '1992-06-30', 0, '2026-03-15 01:00:00', '2026-03-15 01:00:00'),
(16, '1992/1993', '1992-07-01', '1993-06-30', 0, '2026-04-16 01:00:00', '2026-04-16 01:00:00'),
(17, '1993/1994', '1993-07-01', '1994-06-30', 0, '2026-05-17 01:00:00', '2026-05-17 01:00:00'),
(18, '1994/1995', '1994-07-01', '1995-06-30', 0, '2026-06-18 01:00:00', '2026-06-18 01:00:00'),
(19, '1995/1996', '1995-07-01', '1996-06-30', 0, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, '1996/1997', '1996-07-01', '1997-06-30', 0, '2026-08-20 01:00:00', '2026-08-20 01:00:00'),
(21, '1997/1998', '1997-07-01', '1998-06-30', 0, '2026-09-21 01:00:00', '2026-09-21 01:00:00'),
(22, '1998/1999', '1998-07-01', '1999-06-30', 0, '2026-10-22 01:00:00', '2026-10-22 01:00:00'),
(23, '1999/2000', '1999-07-01', '2000-06-30', 0, '2026-11-23 01:00:00', '2026-11-23 01:00:00'),
(24, '2000/2001', '2000-07-01', '2001-06-30', 0, '2026-12-24 01:00:00', '2026-12-24 01:00:00'),
(25, '2001/2002', '2001-07-01', '2002-06-30', 0, '2026-01-25 01:00:00', '2026-01-25 01:00:00'),
(26, '2002/2003', '2002-07-01', '2003-06-30', 0, '2026-02-26 01:00:00', '2026-02-26 01:00:00'),
(27, '2003/2004', '2003-07-01', '2004-06-30', 0, '2026-03-27 01:00:00', '2026-03-27 01:00:00'),
(28, '2004/2005', '2004-07-01', '2005-06-30', 0, '2026-04-28 01:00:00', '2026-04-28 01:00:00'),
(29, '2005/2006', '2005-07-01', '2006-06-30', 0, '2026-05-01 01:00:00', '2026-05-01 01:00:00'),
(30, '2006/2007', '2006-07-01', '2007-06-30', 0, '2026-06-02 01:00:00', '2026-06-02 01:00:00'),
(31, '2007/2008', '2007-07-01', '2008-06-30', 0, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(32, '2008/2009', '2008-07-01', '2009-06-30', 0, '2026-08-04 01:00:00', '2026-08-04 01:00:00'),
(33, '2009/2010', '2009-07-01', '2010-06-30', 0, '2026-09-05 01:00:00', '2026-09-05 01:00:00'),
(34, '2010/2011', '2010-07-01', '2011-06-30', 0, '2026-10-06 01:00:00', '2026-10-06 01:00:00'),
(35, '2011/2012', '2011-07-01', '2012-06-30', 0, '2026-11-07 01:00:00', '2026-11-07 01:00:00'),
(36, '2012/2013', '2012-07-01', '2013-06-30', 0, '2026-12-08 01:00:00', '2026-12-08 01:00:00'),
(37, '2013/2014', '2013-07-01', '2014-06-30', 0, '2026-01-09 01:00:00', '2026-01-09 01:00:00'),
(38, '2014/2015', '2014-07-01', '2015-06-30', 0, '2026-02-10 01:00:00', '2026-02-10 01:00:00'),
(39, '2015/2016', '2015-07-01', '2016-06-30', 0, '2026-03-11 01:00:00', '2026-03-11 01:00:00'),
(40, '2016/2017', '2016-07-01', '2017-06-30', 0, '2026-04-12 01:00:00', '2026-04-12 01:00:00'),
(41, '2017/2018', '2017-07-01', '2018-06-30', 0, '2026-05-13 01:00:00', '2026-05-13 01:00:00'),
(42, '2018/2019', '2018-07-01', '2019-06-30', 0, '2026-06-14 01:00:00', '2026-06-14 01:00:00'),
(43, '2019/2020', '2019-07-01', '2020-06-30', 0, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(44, '2020/2021', '2020-07-01', '2021-06-30', 0, '2026-08-16 01:00:00', '2026-08-16 01:00:00'),
(45, '2021/2022', '2021-07-01', '2022-06-30', 0, '2026-09-17 01:00:00', '2026-09-17 01:00:00'),
(46, '2022/2023', '2022-07-01', '2023-06-30', 0, '2026-10-18 01:00:00', '2026-10-18 01:00:00'),
(47, '2023/2024', '2023-07-01', '2024-06-30', 0, '2026-11-19 01:00:00', '2026-11-19 01:00:00'),
(48, '2024/2025', '2024-07-01', '2025-06-30', 0, '2026-12-20 01:00:00', '2026-12-20 01:00:00'),
(49, '2025/2026', '2025-07-01', '2026-06-30', 0, '2026-01-21 01:00:00', '2026-01-21 01:00:00'),
(50, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-02-22 01:00:00', '2026-02-22 01:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_users`
--

CREATE TABLE `t_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) NOT NULL,
  `role` varchar(225) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_users`
--

INSERT INTO `t_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '2026-09-23 02:30:15', '$2y$10$lc3Nt6spSsPoYakhUFrM4.ebaZPH6vbjLBXOcstE8JoRJpAw0LjBS', '', 'admin', '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, 'Guru', 'guru@gmail.com', '2026-09-23 02:30:15', '$2y$10$ySulB0pG5h0YsJXaK/rHvunRn5EgiE8PO4JVqtfrxiaKJKTEluaAC', '\r\n', 'guru', '2026-02-02 01:00:00', '2026-02-02 01:00:00'),
(55, 'Wawan Kurniawan, S.Pd.I', 'WawanKurniawan@gmail.com', '2026-11-06 07:09:06', '$2y$12$r5F3IvywiFhShFYAvG.HJOmkSTTH7AkArBZYuaMwvYduwyk909NrW', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(56, 'Ai Nuryaidah, S.Pd', 'AiNuryaidah@gmail.com', '2026-11-06 07:09:06', '$2y$12$1/R4mYSkw1msduuIq7wj2e0.6VYkdbSjz7a0JU.2C9Lbc24rqLpDu', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(57, 'Maya Permata Endah, S.Pd', 'MayaPermataEndah@gmail.com', '2026-11-06 07:09:06', '$2y$12$tP0G7Xr/bC6F9qX/F8clVevgUylmNEFvANfxC9m82g.SBCfFgv2Ei', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(58, 'Dody Jaenudin, S.Pd', 'DodyJaenudin@gmail.com', '2026-11-06 07:09:06', '$2y$12$AZSE/CuwCrkUsVgOBtc.wOLH2t./gflooLnEAnU.aWqC87wPSMjXa', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(59, 'Dedi Kardiman, S.Pd', 'DediKardiman@gmail.com', '2026-11-06 07:09:06', '$2y$12$fEILYdwj6nxLGgHYKOVC6evdN9Sw4PqE5zLeo0VTzrziHoL1ZDN7a', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(60, 'Uca Musa Yusenda, S.Pd', 'UcaMusaYusenda@gmail.com', '2026-11-06 07:09:06', '$2y$12$oLbxU5EiFtEJpVjHzVbfhuJpV8i.8vcKYT.s1kzV4jfWBZ9HjZUEm', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(61, 'Drs. Ahmad Nur Mukhlis', 'AhmadNurMukhlis@gmail.com', '2026-11-06 07:09:06', '$2y$12$n9EIx37PSb/aZGwLWXujZOjLOBeHIMY01lfMNTdjUU9Yfqgj4pm0O', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(62, 'Drs. Heri', 'Heri@gmail.com', '2026-11-06 07:09:06', '$2y$12$lq0dyAChkwMaTv4cBS5kz.PSJyhBHvlTJRO9n7X9tQvjjTESwiMLy', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(63, 'Asep Bastaman', 'AsepBastaman@gmail.com', '2026-11-06 07:09:06', '$2y$12$VE4iW4hoVocef/61nbB9UORUgrUXxkFnSy9KW86kcvMDUYlxPSoLG', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(64, 'H. Encep Maemun, S.T., M.Pd', 'EncepMaemun@gmail.com', '2026-11-06 07:09:06', '$2y$12$vs8Y.lyF3LrLyHz0Z48im.t5qK5xEm7AGmo0KjnOdPF.q60qZTczC', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(65, 'Opik', 'Opik@gmail.com', '2026-11-06 07:09:06', '$2y$12$4ZLB2yAqNOqxTu683fCMNeGA1PShVpBkTbZJU2NlDdujnkoJDqMFC', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(66, 'Ida Farida, S.Pd', 'IdaFarida@gmail.com', '2026-11-06 07:09:06', '$2y$12$LFppPu/y2RbXv5FahaTxbODeW9jb5XpxBjm6pHOmba4tmQ4QV/ehy', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(67, 'Idah Mahmudah, S.Pd', 'IdahMahmudah@gmail.com', '2026-11-06 07:09:06', '$2y$12$NZ2ixvJds.PU8gR2EizTh.VPpCZGm7B8wdN7I4CngeqKO7oq5B3ne', '', 'wali kelas', '2026-11-06 07:09:06', '2026-11-06 07:09:06'),
(68, 'Drs. Muhamad Ali', 'MuhamadAli@gmail.com', '2026-11-06 07:09:07', '$2y$12$CiV2TjVEgO2UF.ftSy6PY.dWdwur9HsdAURJeviN5/.4yyYXhUmBO', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(69, 'Helmi Ali Akbar, S.Pd.I', 'HelmiAliAkbar@gmail.com', '2026-11-06 07:09:07', '$2y$12$4jB40S0jHg8qHg0KXfghXef4/3rFJ0EVRhgMk5koxcablIRIagIL6', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(70, 'Syeni Suswarini, S.Pd', 'SyeniSuswarini@gmail.com', '2026-11-06 07:09:07', '$2y$12$F.m2uqm77SAJwRrym1dToOaLaxKwl3YJ3YIzkmHzOe8c0qvclTI4e', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(71, 'Wiwid Yulianti, S.Pd', 'WiwidYulianti@gmail.com', '2026-11-06 07:09:07', '$2y$12$GkrXnHBdNgjRU5eqqsk1Ee3ixDk9hXv.2xbmV5Ts5GjEm71brPnk6', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(72, 'Ansor Muhajir, S.T', 'AnsorMuhajir@gmail.com', '2026-11-06 07:09:07', '$2y$12$Y74kJO/2f4W./wuWJLQBf.HR0oV5Swj/KHshJB05AtQgqsNsozbla', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(73, 'Ogi Gaosun Gais, S.Pd.I', 'OgiGaosunGais@gmail.com', '2026-11-06 07:09:07', '$2y$12$rgmvIdpzYDNrgXjCGyFz5OKNIkV8c5NmmXYAJU6K.1sq1QsTXZT66', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(74, 'Sandi Mulyana, S.Pd', 'SandiMulyana@gmail.com', '2026-11-06 07:09:07', '$2y$12$j9TtYDVPrZVGzCVp6upPFuOhqQfP.X7UoRe15doHhMe8e/Rcqkjvu', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(75, 'Erna Suryani, S.Pd', 'ErnaSuryani@gmail.com', '2026-11-06 07:09:07', '$2y$12$hAbwQmc8zIRG.1AGbGCsN..822p2arCAd7fehhRoFa6x6hhOM.Bye', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(76, 'Ai Wahidah, S.Ag', 'AiWahidah@gmail.com', '2026-11-06 07:09:07', '$2y$12$auwu/CuOSvlHBoAd9TDUG.gz9laYhg5GU9B9/SIfg3nletaGy1dwS', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(77, 'Chandra Aditya Yusup, S.Pd', 'ChandraAdityaYusup@gmail.com', '2026-11-06 07:09:07', '$2y$12$5XHSc9oimZy4k1Pop4Gy7O7pxW1dFPCOJlH5H7JtIs..RAMqzk4FO', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(78, 'Arif Kusmawan, S.Pd', 'ArifKusmawan@gmail.com', '2026-11-06 07:09:07', '$2y$12$KEGXBzUHx.zAt9JpEYODQuUAnsoP/yfIqD/bHeertbWiTGetDJQ9C', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(79, 'Asep Taufiq Hidayat, S.Kom', 'AsepTaufiqHidayat@gmail.com', '2026-11-06 07:09:07', '$2y$12$C9r7e.zVdOUPmGExbhKNc.Otgd2JYgA8bko6pohQYfi/c1UfcDfgS', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(80, 'Rendy Ichsan Kusumah, S.T', 'RendyIchsanKusumah@gmail.com', '2026-11-06 07:09:07', '$2y$12$091XtRt5cWvnxofEfPm.1e68P.4VPRqo3QYsiN0NIRpV9VM9mSSzy', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(81, 'Feri Rahmawan, S.Pd', 'FeriRahmawan@gmail.com', '2026-11-06 07:09:07', '$2y$12$vER.TV66FImGMXl3c5LDcuzz9bGP7ZCbC.wM7dsyDAB6OUDcGRTym', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(82, 'Ari Sandriana, S.Kom', 'AriSandriana@gmail.com', '2026-11-06 07:09:07', '$2y$12$yrY7jZulLPeDeY4.pi1DV.bAT4hekueQ/GnXifgJKOJ9Dm1ydvsyu', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(83, 'Yaneu Nurdian, S.Pd, M.Pd', 'YaneuNurdian@gmail.com', '2026-11-06 07:09:07', '$2y$12$1UwSdgbGqofkmn0V2dgeIeAf1P78p.e8seXvpGUZV6kOIl2gGprka', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(84, 'Ucu Muhammad Khoer N, S.Pd', 'UcuMuhammadKhoerN@gmail.com', '2026-11-06 07:09:07', '$2y$12$vy8ygP5dlyQgeJeln1hr5.hMJzWNtLp16lmz.zSasglxl8Y4whgO2', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(85, 'Mira Dwi Rahmawati, S.Ag', 'MiraDwiRahmawati@gmail.com', '2026-11-06 07:09:07', '$2y$12$LPPRT1EU2u1fGA2pcOFU1erjlItrYWsn4bMTpdd/bK2vCVroeCkiW', '', 'wali kelas', '2026-11-06 07:09:07', '2026-11-06 07:09:07'),
(86, 'Eli Rosliah, S.Pd', 'EliRosliah@gmail.com', '2026-11-06 07:09:08', '$2y$12$2t4aKTzgK1mos9iYEvYRZ.NG3g9byyoeQAn4uBw62HlefDnVf90jC', '', 'wali kelas', '2026-11-06 07:09:08', '2026-11-06 07:09:08'),
(87, 'Isni Nur Fitriani, S.Pd', 'IsniNurFitriani@gmail.com', '2026-11-06 07:09:08', '$2y$12$5KGL7dw7t379JPMD6OAzTuJinjklAJHFC9cNiGpjOXk/gYRN6P8Ri', '', 'wali kelas', '2026-11-06 07:09:08', '2026-11-06 07:09:08'),
(88, 'Aanisah Putri Nur Aini, S.Pd', 'AanisahPutriNurAini@gmail.com', '2026-11-06 07:09:08', '$2y$12$TXonJnKTakkbD/8/r59BDeqVvsn9noO8wZqvarMdZUmjgnoeQGzvG', '', 'wali kelas', '2026-11-06 07:09:08', '2026-11-06 07:09:08'),
(89, 'Yusi Luthfiani, S.Sos.I', 'YusiLuthfiani@gmail.com', '2026-11-06 07:09:08', '$2y$12$S92WfOqT0B72a24MV9lyHe6mFEBWZ/D6uryqbgYOO4w06yYX2AwSC', '', 'wali kelas', '2026-11-06 07:09:08', '2026-11-06 07:09:08'),
(90, 'Lilis Rustandy Mawardi, S.Pd.,', 'LilisRustandyMawardi@gmail.com', '2026-11-06 07:09:08', '$2y$12$TWWOZCFB89fP7MZmklEsPO1An.rMVmd1RVwMxl8O5fL0WkmEAsd06', '', 'wali kelas', '2026-11-06 07:09:08', '2026-11-06 07:09:08'),
(91, 'Yusrin Noer Sabrina, S.Pd', 'YusrinNoerSabrina@gmail.com', '2026-11-06 07:09:08', '$2y$12$WYjgALFxSqTFTJF1K9UbF.3kDVKecOhczfyA/QZO89/WDORpmIpxW', '', 'wali kelas', '2026-11-06 07:09:08', '2026-11-06 07:09:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_wali_kelas`
--

CREATE TABLE `t_wali_kelas` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_wali_kelas`
--

INSERT INTO `t_wali_kelas` (`id`, `tahun_ajaran_id`, `kelas_id`, `guru_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 50, 1, 1, '1977-07-01', '1978-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, 50, 2, 2, '1977-07-01', '1978-06-30', 1, '2026-02-02 01:00:00', '2026-02-02 01:00:00'),
(3, 50, 3, 3, '1977-07-01', '1978-06-30', 1, '2026-03-03 01:00:00', '2026-03-03 01:00:00'),
(4, 50, 4, 4, '1977-07-01', '1978-06-30', 1, '2026-04-04 01:00:00', '2026-04-04 01:00:00'),
(5, 50, 5, 5, '1977-07-01', '1978-06-30', 1, '2026-05-05 01:00:00', '2026-05-05 01:00:00'),
(6, 50, 6, 6, '1977-07-01', '1978-06-30', 1, '2026-06-06 01:00:00', '2026-06-06 01:00:00'),
(7, 50, 7, 7, '1977-07-01', '1978-06-30', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, 50, 8, 8, '1977-07-01', '1978-06-30', 1, '2026-08-08 01:00:00', '2026-08-08 01:00:00'),
(9, 50, 9, 9, '1977-07-01', '1978-06-30', 1, '2026-09-09 01:00:00', '2026-09-09 01:00:00'),
(10, 50, 10, 10, '1977-07-01', '1978-06-30', 1, '2026-10-10 01:00:00', '2026-10-10 01:00:00'),
(11, 50, 1, 11, '1978-07-01', '1979-06-30', 1, '2026-11-11 01:00:00', '2026-11-11 01:00:00'),
(12, 50, 2, 12, '1978-07-01', '1979-06-30', 1, '2026-12-12 01:00:00', '2026-12-12 01:00:00'),
(13, 50, 3, 13, '1978-07-01', '1979-06-30', 1, '2026-01-13 01:00:00', '2026-01-13 01:00:00'),
(14, 50, 4, 14, '1978-07-01', '1979-06-30', 1, '2026-02-14 01:00:00', '2026-02-14 01:00:00'),
(15, 50, 5, 15, '1978-07-01', '1979-06-30', 1, '2026-03-15 01:00:00', '2026-03-15 01:00:00'),
(16, 50, 6, 16, '1978-07-01', '1979-06-30', 1, '2026-04-16 01:00:00', '2026-04-16 01:00:00'),
(17, 50, 7, 17, '1978-07-01', '1979-06-30', 1, '2026-05-17 01:00:00', '2026-05-17 01:00:00'),
(18, 50, 8, 18, '1978-07-01', '1979-06-30', 1, '2026-06-18 01:00:00', '2026-06-18 01:00:00'),
(19, 50, 9, 19, '1978-07-01', '1979-06-30', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, 50, 10, 20, '1978-07-01', '1979-06-30', 1, '2026-08-20 01:00:00', '2026-08-20 01:00:00'),
(21, 50, 1, 21, '1979-07-01', '1980-06-30', 1, '2026-09-21 01:00:00', '2026-09-21 01:00:00'),
(22, 50, 2, 22, '1979-07-01', '1980-06-30', 1, '2026-10-22 01:00:00', '2026-10-22 01:00:00'),
(23, 50, 3, 23, '1979-07-01', '1980-06-30', 1, '2026-11-23 01:00:00', '2026-11-23 01:00:00'),
(24, 50, 4, 24, '1979-07-01', '1980-06-30', 1, '2026-12-24 01:00:00', '2026-12-24 01:00:00'),
(25, 50, 5, 25, '1979-07-01', '1980-06-30', 1, '2026-01-25 01:00:00', '2026-01-25 01:00:00'),
(26, 50, 6, 26, '1979-07-01', '1980-06-30', 1, '2026-02-26 01:00:00', '2026-02-26 01:00:00'),
(27, 50, 7, 27, '1979-07-01', '1980-06-30', 1, '2026-03-27 01:00:00', '2026-03-27 01:00:00'),
(28, 50, 8, 28, '1979-07-01', '1980-06-30', 1, '2026-04-28 01:00:00', '2026-04-28 01:00:00'),
(29, 50, 9, 29, '1979-07-01', '1980-06-30', 1, '2026-05-01 01:00:00', '2026-05-01 01:00:00'),
(30, 50, 10, 30, '1979-07-01', '1980-06-30', 1, '2026-06-02 01:00:00', '2026-06-02 01:00:00'),
(31, 50, 1, 31, '1980-07-01', '1981-06-30', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(32, 50, 2, 32, '1980-07-01', '1981-06-30', 1, '2026-08-04 01:00:00', '2026-08-04 01:00:00'),
(33, 50, 3, 33, '1980-07-01', '1981-06-30', 1, '2026-09-05 01:00:00', '2026-09-05 01:00:00'),
(34, 50, 4, 34, '1980-07-01', '1981-06-30', 1, '2026-10-06 01:00:00', '2026-10-06 01:00:00'),
(35, 50, 5, 35, '1980-07-01', '1981-06-30', 1, '2026-11-07 01:00:00', '2026-11-07 01:00:00'),
(36, 50, 6, 36, '1980-07-01', '1981-06-30', 1, '2026-12-08 01:00:00', '2026-12-08 01:00:00'),
(37, 50, 7, 37, '1980-07-01', '1981-06-30', 1, '2026-01-09 01:00:00', '2026-01-09 01:00:00'),
(38, 50, 8, 38, '1980-07-01', '1981-06-30', 1, '2026-02-10 01:00:00', '2026-02-10 01:00:00');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggaran_kategori_id` (`pelanggaran_kategori_id`);

--
-- Indeks untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `pelanggaran_id` (`pelanggaran_id`),
  ADD KEY `pelanggaran_kategori_id` (`pelanggaran_kategori_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indeks untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `db_ukk_2026` (`tahun_ajaran_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT untuk tabel `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD CONSTRAINT `t_kelas_siswa_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_kelas_siswa_ibfk_2` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_kelas_siswa_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  ADD CONSTRAINT `t_pelanggaran_kategori_ibfk_1` FOREIGN KEY (`id`) REFERENCES `t_pelanggaran` (`pelanggaran_kategori_id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_1` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_4` FOREIGN KEY (`pelanggaran_id`) REFERENCES `t_pelanggaran` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_5` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_6` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD CONSTRAINT `db_ukk_2026` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`),
  ADD CONSTRAINT `t_wali_kelas_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`),
  ADD CONSTRAINT `t_wali_kelas_ibfk_2` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
