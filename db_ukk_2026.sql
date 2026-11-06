-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Nov 2026 pada 03.28
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
  `nama` varchar(150) DEFAULT NULL,
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
(101, '19850101001', 'Ahmad Fauzi - RPL', 'ahmad.fauzi@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(102, '19850202002', 'Dedi Kurniawan - RPL', 'dedi.kurniawan@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(103, '19850303003', 'Rizky Maulana - RPL', 'rizky.maulana@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(104, '19850404004', 'Fajar Nugraha - RPL', 'fajar.nugraha@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(105, '19850505005', 'Andi Setiawan - RPL', 'andi.setiawan@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(106, '19850606006', 'Siti Nurhaliza - RPL', 'siti.nurhaliza@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(107, '19850707007', 'Yudi Hermawan - RPL', 'yudi.hermawan@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(108, '19850808008', 'Dimas Saputra - RPL', 'dimas.saputra@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(109, '19850909009', 'Rina Marlina - RPL', 'rina.marlina@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(110, '19851010010', 'Agus Firmansyah - RPL', 'agus.firmansyah@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(111, '19851111011', 'Budi Santoso - TSM', 'budi.santoso@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(112, '19851212012', 'Eko Prasetyo - TSM', 'eko.prasetyo@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(113, '19860101013', 'Wawan Setiawan - TSM', 'wawan.setiawan@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(114, '19860202014', 'Hendra Gunawan - TSM', 'hendra.gunawan@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(115, '19860303015', 'Rudi Hartono - TSM', 'rudi.hartono@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(116, '19860404016', 'Nanda Permana - TSM', 'nanda.permana@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(117, '19860505017', 'Dian Purnama - TSM', 'dian.purnama@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(118, '19860606018', 'Taufik Hidayat - TSM', 'taufik.hidayat@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(119, '19860707019', 'Samsul Arifin - TSM', 'samsul.arifin@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(120, '19860808020', 'Rian Ramadhan - TSM', 'rian.ramadhan@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(121, '19860909021', 'Asep Suherman - TKR', 'asep.suherman@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(122, '19861010022', 'Jajang Mulyana - TKR', 'jajang.mulyana@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(123, '19861111023', 'Dedi Supriyadi - TKR', 'dedi.supriyadi@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(124, '19861212024', 'Rahmat Hidayat - TKR', 'rahmat.hidayat@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(125, '19870101025', 'Yusuf Maulana - TKR', 'yusuf.maulana@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(126, '19870202026', 'Imam Syafii - TKR', 'imam.syafii@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(127, '19870303027', 'Fikri Ramadhan - TKR', 'fikri.ramadhan@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(128, '19870404028', 'Dadan Kurnia - TKR', 'dadan.kurnia@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(129, '19870505029', 'Roni Saputra - TKR', 'roni.saputra@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(130, '19870606030', 'Bayu Firmansyah - TKR', 'bayu.firmansyah@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(131, '19870707031', 'Maya Sari - BDPM', 'maya.sari@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(132, '19870808032', 'Dewi Anggraini - BDPM', 'dewi.anggraini@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(133, '19870909033', 'Nia Kurniasih - BDPM', 'nia.kurniasih@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(134, '19871010034', 'Rina Amelia - BDPM', 'rina.amelia@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(135, '19871111035', 'Lina Marlina - BDPM', 'lina.marlina@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(136, '19871212036', 'Fitri Handayani - BDPM', 'fitri.handayani@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(137, '19880101037', 'Siska Wulandari - BDPM', 'siska.wulandari@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(138, '19880202038', 'Putri Lestari - BDPM', 'putri.lestari@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(139, '19880303039', 'Ratna Puspita - BDPM', 'ratna.puspita@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(140, '19880404040', 'Intan Permata - BDPM', 'intan.permata@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(141, '19880505041', 'Arif Rahman - TKJ', 'arif.rahman@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(142, '19880606042', 'Ilham Fauzi - TKJ', 'ilham.fauzi@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(143, '19880707043', 'Gilang Pratama - TKJ', 'gilang.pratama@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(144, '19880808044', 'Rendi Saputra - TKJ', 'rendi.saputra@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(145, '19880909045', 'Fauzan Akbar - TKJ', 'fauzan.akbar@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(146, '19881010046', 'Nur Aisyah - Matematika', 'nur.aisyah@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(147, '19881111047', 'Siti Rahma - Bahasa Indonesia', 'siti.rahma@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(148, '19881212048', 'Andri Wijaya - Bahasa Inggris', 'andri.wijaya@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(149, '19890101049', 'Ustazah Khadijah - PAI', 'khadijah@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(150, '19890202050', 'Deni Saputra - PJOK', 'deni.saputra@sekolah.sch.id', 1, 0, '2026-10-02 03:26:03', '2026-10-02 03:26:03'),
(152, '1543652', 'yana sudrajat', 'mcok30324@gmail.com', 1, 106, '2026-10-02 03:42:41', '2026-10-02 03:51:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas`
--

CREATE TABLE `t_kelas` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
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
(1, 'BD', 'XI', 'BD', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(2, 'TSM 1', 'XI', 'TSM', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(3, 'TSM 2', 'XI', 'TSM', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(4, 'TSM 3', 'XI', 'TSM', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(5, 'RPL 1', 'XI', 'RPL', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(6, 'RPL 2', 'XI', 'RPL', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(7, 'TKJ 1', 'XI', 'TKJ', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, 'TKJ 2', 'XI', 'TKJ', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(9, 'TKJ 3', 'XI', 'TKJ', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(10, 'TKJ 4', 'XI', 'TKJ', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(11, 'TKR 1', 'XI', 'TKR', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(12, 'TKR 2', 'XI', 'TKR', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(13, 'TKR 3', 'XI', 'TKR', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(14, 'TKR 4', 'XI', 'TKR', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(15, 'BD', 'XI', 'BD', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(16, 'TSM 1', 'XI', 'TSM', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(17, 'TSM 2', 'XI', 'TSM', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(18, 'TSM 3', 'XI', 'TSM', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(19, 'RPL 1', 'XI', 'RPL', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, 'RPL 2', 'XI', 'RPL', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(21, 'TKJ 1', 'XI', 'TKJ', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(22, 'TKJ 2', 'XI', 'TKJ', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(23, 'TKJ 3', 'XI', 'TKJ', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(24, 'TKJ 4', 'XI', 'TKJ', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(25, 'TKR 1', 'XI', 'TKR', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(26, 'TKR 2', 'XI', 'TKR', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(27, 'TKR 3', 'XI', 'TKR', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(28, 'TKR 4', 'XI', 'TKR', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(29, 'BD', 'XI', 'BD', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(30, 'TSM 1', 'XI', 'TSM', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(31, 'TSM 2', 'XI', 'TSM', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(32, 'TSM 3', 'XI', 'TSM', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(33, 'RPL 1', 'XI', 'RPL', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(34, 'RPL 2', 'XI', 'RPL', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(35, 'TKJ 1', 'XI', 'TKJ', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(36, 'TKJ 2', 'XI', 'TKJ', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(37, 'TKJ 3', 'XI', 'TKJ', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(38, 'TKJ 4', 'XI', 'TKJ', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(39, 'TKR 1', 'XI', 'TKR', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(40, 'TKR 2', 'XI', 'TKR', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(41, 'TKR 3', 'XI', 'TKR', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(42, 'TKR 4', 'XI', 'TKR', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(43, 'BD', 'XI', 'BD', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(44, 'TSM 1', 'XI', 'TSM', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(45, 'TSM 2', 'XI', 'TSM', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(46, 'TSM 3', 'XI', 'TSM', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(47, 'RPL 1', 'XI', 'RPL', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(48, 'RPL 2', 'XI', 'RPL', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(49, 'TKJ 1', 'XI', 'TKJ', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(50, 'TKJ 2', 'XI', 'TKJ', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(51, 'TKJ 3', 'XI', 'TKJ', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(52, 'TKJ 4', 'XI', 'TKJ', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(53, 'TKR 1', 'XI', 'TKR', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(54, 'TKR 2', 'XI', 'TKR', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(55, 'TKR 3', 'XI', 'TKR', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(56, 'TKR 4', 'XI', 'TKR', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(57, 'BD', 'XI', 'BD', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(58, 'TSM 1', 'XI', 'TSM', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(59, 'TSM 2', 'XI', 'TSM', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(60, 'TSM 3', 'XI', 'TSM', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(61, 'RPL 1', 'XI', 'RPL', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(62, 'RPL 2', 'XI', 'RPL', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(63, 'TKJ 1', 'XI', 'TKJ', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(64, 'TKJ 2', 'XI', 'TKJ', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(65, 'TKJ 3', 'XI', 'TKJ', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(66, 'TKJ 4', 'XI', 'TKJ', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(67, 'TKR 1', 'XI', 'TKR', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(68, 'TKR 2', 'XI', 'TKR', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(69, 'TKR 3', 'XI', 'TKR', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(70, 'TKR 4', 'XI', 'TKR', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(71, 'BD', 'XI', 'BD', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(72, 'TSM 1', 'XI', 'TSM', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(73, 'TSM 2', 'XI', 'TSM', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(74, 'TSM 3', 'XI', 'TSM', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(75, 'RPL 1', 'XI', 'RPL', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(76, 'RPL 2', 'XI', 'RPL', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(77, 'TKJ 1', 'XI', 'TKJ', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(78, 'TKJ 2', 'XI', 'TKJ', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(79, 'TKJ 3', 'XI', 'TKJ', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(80, 'TKJ 4', 'XI', 'TKJ', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(81, 'TKR 1', 'XI', 'TKR', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(82, 'TKR 2', 'XI', 'TKR', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(83, 'TKR 3', 'XI', 'TKR', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(84, 'TKR 4', 'XI', 'TKR', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(85, 'BD', 'XI', 'BD', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(86, 'TSM 1', 'XI', 'TSM', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(87, 'TSM 2', 'XI', 'TSM', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(88, 'TSM 3', 'XI', 'TSM', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(89, 'RPL 1', 'XI', 'RPL', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(90, 'RPL 2', 'XI', 'RPL', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(91, 'TKJ 1', 'XI', 'TKJ', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(92, 'TKJ 2', 'XI', 'TKJ', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(93, 'TKJ 3', 'XI', 'TKJ', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(94, 'TKJ 4', 'XI', 'TKJ', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(95, 'TKR 1', 'XI', 'TKR', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(96, 'TKR 2', 'XI', 'TKR', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(97, 'TKR 3', 'XI', 'TKR', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(98, 'TKR 4', 'XI', 'TKR', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(99, 'BD', 'XI', 'BD', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(100, 'TSM 1', 'XI', 'TSM', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_kelas_siswa`
--

CREATE TABLE `t_kelas_siswa` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran`
--

CREATE TABLE `t_pelanggaran` (
  `id` int(11) NOT NULL,
  `pelanggaran_kategori_id` int(11) DEFAULT NULL,
  `kode` varchar(30) DEFAULT NULL,
  `nama` varchar(150) DEFAULT NULL,
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
(1, 1, 'PG-001', 'Terlambat datang ke sekolah #001', 2, 'Deskripsi pelanggaran nomor 1.', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(2, 2, 'PG-002', 'Tidak memakai atribut lengkap #002', 3, 'Deskripsi pelanggaran nomor 2.', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(3, 3, 'PG-003', 'Tidak memakai seragam sesuai ketentuan #003', 4, 'Deskripsi pelanggaran nomor 3.', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(4, 4, 'PG-004', 'Meninggalkan kelas tanpa izin #004', 5, 'Deskripsi pelanggaran nomor 4.', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(5, 5, 'PG-005', 'Tidak mengerjakan tugas #005', 6, 'Deskripsi pelanggaran nomor 5.', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(6, 6, 'PG-006', 'Rambut tidak sesuai aturan #006', 7, 'Deskripsi pelanggaran nomor 6.', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(7, 7, 'PG-007', 'Membawa barang terlarang #007', 8, 'Deskripsi pelanggaran nomor 7.', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, 8, 'PG-008', 'Berkata tidak sopan #008', 9, 'Deskripsi pelanggaran nomor 8.', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(9, 9, 'PG-009', 'Membuang sampah sembarangan #009', 10, 'Deskripsi pelanggaran nomor 9.', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(10, 10, 'PG-010', 'Tidak mengikuti upacara #010', 1, 'Deskripsi pelanggaran nomor 10.', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(11, 11, 'PG-011', 'Terlambat datang ke sekolah #011', 2, 'Deskripsi pelanggaran nomor 11.', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(12, 12, 'PG-012', 'Tidak memakai atribut lengkap #012', 3, 'Deskripsi pelanggaran nomor 12.', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(13, 13, 'PG-013', 'Tidak memakai seragam sesuai ketentuan #013', 4, 'Deskripsi pelanggaran nomor 13.', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(14, 14, 'PG-014', 'Meninggalkan kelas tanpa izin #014', 5, 'Deskripsi pelanggaran nomor 14.', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(15, 15, 'PG-015', 'Tidak mengerjakan tugas #015', 6, 'Deskripsi pelanggaran nomor 15.', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(16, 16, 'PG-016', 'Rambut tidak sesuai aturan #016', 7, 'Deskripsi pelanggaran nomor 16.', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(17, 17, 'PG-017', 'Membawa barang terlarang #017', 8, 'Deskripsi pelanggaran nomor 17.', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(18, 18, 'PG-018', 'Berkata tidak sopan #018', 9, 'Deskripsi pelanggaran nomor 18.', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(19, 19, 'PG-019', 'Membuang sampah sembarangan #019', 10, 'Deskripsi pelanggaran nomor 19.', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, 20, 'PG-020', 'Tidak mengikuti upacara #020', 1, 'Deskripsi pelanggaran nomor 20.', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(21, 21, 'PG-021', 'Terlambat datang ke sekolah #021', 2, 'Deskripsi pelanggaran nomor 21.', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(22, 22, 'PG-022', 'Tidak memakai atribut lengkap #022', 3, 'Deskripsi pelanggaran nomor 22.', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(23, 23, 'PG-023', 'Tidak memakai seragam sesuai ketentuan #023', 4, 'Deskripsi pelanggaran nomor 23.', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(24, 24, 'PG-024', 'Meninggalkan kelas tanpa izin #024', 5, 'Deskripsi pelanggaran nomor 24.', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(25, 25, 'PG-025', 'Tidak mengerjakan tugas #025', 6, 'Deskripsi pelanggaran nomor 25.', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(26, 26, 'PG-026', 'Rambut tidak sesuai aturan #026', 7, 'Deskripsi pelanggaran nomor 26.', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(27, 27, 'PG-027', 'Membawa barang terlarang #027', 8, 'Deskripsi pelanggaran nomor 27.', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(28, 28, 'PG-028', 'Berkata tidak sopan #028', 9, 'Deskripsi pelanggaran nomor 28.', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(29, 29, 'PG-029', 'Membuang sampah sembarangan #029', 10, 'Deskripsi pelanggaran nomor 29.', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(30, 30, 'PG-030', 'Tidak mengikuti upacara #030', 1, 'Deskripsi pelanggaran nomor 30.', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(31, 31, 'PG-031', 'Terlambat datang ke sekolah #031', 2, 'Deskripsi pelanggaran nomor 31.', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(32, 32, 'PG-032', 'Tidak memakai atribut lengkap #032', 3, 'Deskripsi pelanggaran nomor 32.', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(33, 33, 'PG-033', 'Tidak memakai seragam sesuai ketentuan #033', 4, 'Deskripsi pelanggaran nomor 33.', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(34, 34, 'PG-034', 'Meninggalkan kelas tanpa izin #034', 5, 'Deskripsi pelanggaran nomor 34.', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(35, 35, 'PG-035', 'Tidak mengerjakan tugas #035', 6, 'Deskripsi pelanggaran nomor 35.', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(36, 36, 'PG-036', 'Rambut tidak sesuai aturan #036', 7, 'Deskripsi pelanggaran nomor 36.', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(37, 37, 'PG-037', 'Membawa barang terlarang #037', 8, 'Deskripsi pelanggaran nomor 37.', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(38, 38, 'PG-038', 'Berkata tidak sopan #038', 9, 'Deskripsi pelanggaran nomor 38.', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(39, 39, 'PG-039', 'Membuang sampah sembarangan #039', 10, 'Deskripsi pelanggaran nomor 39.', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(40, 40, 'PG-040', 'Tidak mengikuti upacara #040', 1, 'Deskripsi pelanggaran nomor 40.', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(41, 41, 'PG-041', 'Terlambat datang ke sekolah #041', 2, 'Deskripsi pelanggaran nomor 41.', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(42, 42, 'PG-042', 'Tidak memakai atribut lengkap #042', 3, 'Deskripsi pelanggaran nomor 42.', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(43, 43, 'PG-043', 'Tidak memakai seragam sesuai ketentuan #043', 4, 'Deskripsi pelanggaran nomor 43.', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(44, 44, 'PG-044', 'Meninggalkan kelas tanpa izin #044', 5, 'Deskripsi pelanggaran nomor 44.', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(45, 45, 'PG-045', 'Tidak mengerjakan tugas #045', 6, 'Deskripsi pelanggaran nomor 45.', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(46, 46, 'PG-046', 'Rambut tidak sesuai aturan #046', 7, 'Deskripsi pelanggaran nomor 46.', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(47, 47, 'PG-047', 'Membawa barang terlarang #047', 8, 'Deskripsi pelanggaran nomor 47.', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(48, 48, 'PG-048', 'Berkata tidak sopan #048', 9, 'Deskripsi pelanggaran nomor 48.', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(49, 49, 'PG-049', 'Membuang sampah sembarangan #049', 10, 'Deskripsi pelanggaran nomor 49.', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(50, 50, 'PG-050', 'Tidak mengikuti upacara #050', 1, 'Deskripsi pelanggaran nomor 50.', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(51, 51, 'PG-051', 'Terlambat datang ke sekolah #051', 2, 'Deskripsi pelanggaran nomor 51.', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(52, 52, 'PG-052', 'Tidak memakai atribut lengkap #052', 3, 'Deskripsi pelanggaran nomor 52.', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(53, 53, 'PG-053', 'Tidak memakai seragam sesuai ketentuan #053', 4, 'Deskripsi pelanggaran nomor 53.', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(54, 54, 'PG-054', 'Meninggalkan kelas tanpa izin #054', 5, 'Deskripsi pelanggaran nomor 54.', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(55, 55, 'PG-055', 'Tidak mengerjakan tugas #055', 6, 'Deskripsi pelanggaran nomor 55.', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(56, 56, 'PG-056', 'Rambut tidak sesuai aturan #056', 7, 'Deskripsi pelanggaran nomor 56.', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(57, 57, 'PG-057', 'Membawa barang terlarang #057', 8, 'Deskripsi pelanggaran nomor 57.', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(58, 58, 'PG-058', 'Berkata tidak sopan #058', 9, 'Deskripsi pelanggaran nomor 58.', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(59, 59, 'PG-059', 'Membuang sampah sembarangan #059', 10, 'Deskripsi pelanggaran nomor 59.', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(60, 60, 'PG-060', 'Tidak mengikuti upacara #060', 1, 'Deskripsi pelanggaran nomor 60.', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(61, 61, 'PG-061', 'Terlambat datang ke sekolah #061', 2, 'Deskripsi pelanggaran nomor 61.', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(62, 62, 'PG-062', 'Tidak memakai atribut lengkap #062', 3, 'Deskripsi pelanggaran nomor 62.', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(63, 63, 'PG-063', 'Tidak memakai seragam sesuai ketentuan #063', 4, 'Deskripsi pelanggaran nomor 63.', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(64, 64, 'PG-064', 'Meninggalkan kelas tanpa izin #064', 5, 'Deskripsi pelanggaran nomor 64.', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(65, 65, 'PG-065', 'Tidak mengerjakan tugas #065', 6, 'Deskripsi pelanggaran nomor 65.', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(66, 66, 'PG-066', 'Rambut tidak sesuai aturan #066', 7, 'Deskripsi pelanggaran nomor 66.', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(67, 67, 'PG-067', 'Membawa barang terlarang #067', 8, 'Deskripsi pelanggaran nomor 67.', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(68, 68, 'PG-068', 'Berkata tidak sopan #068', 9, 'Deskripsi pelanggaran nomor 68.', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(69, 69, 'PG-069', 'Membuang sampah sembarangan #069', 10, 'Deskripsi pelanggaran nomor 69.', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(70, 70, 'PG-070', 'Tidak mengikuti upacara #070', 1, 'Deskripsi pelanggaran nomor 70.', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(71, 71, 'PG-071', 'Terlambat datang ke sekolah #071', 2, 'Deskripsi pelanggaran nomor 71.', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(72, 72, 'PG-072', 'Tidak memakai atribut lengkap #072', 3, 'Deskripsi pelanggaran nomor 72.', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(73, 73, 'PG-073', 'Tidak memakai seragam sesuai ketentuan #073', 4, 'Deskripsi pelanggaran nomor 73.', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(74, 74, 'PG-074', 'Meninggalkan kelas tanpa izin #074', 5, 'Deskripsi pelanggaran nomor 74.', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(75, 75, 'PG-075', 'Tidak mengerjakan tugas #075', 6, 'Deskripsi pelanggaran nomor 75.', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(76, 76, 'PG-076', 'Rambut tidak sesuai aturan #076', 7, 'Deskripsi pelanggaran nomor 76.', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(77, 77, 'PG-077', 'Membawa barang terlarang #077', 8, 'Deskripsi pelanggaran nomor 77.', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(78, 78, 'PG-078', 'Berkata tidak sopan #078', 9, 'Deskripsi pelanggaran nomor 78.', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(79, 79, 'PG-079', 'Membuang sampah sembarangan #079', 10, 'Deskripsi pelanggaran nomor 79.', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(80, 80, 'PG-080', 'Tidak mengikuti upacara #080', 1, 'Deskripsi pelanggaran nomor 80.', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(81, 81, 'PG-081', 'Terlambat datang ke sekolah #081', 2, 'Deskripsi pelanggaran nomor 81.', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(82, 82, 'PG-082', 'Tidak memakai atribut lengkap #082', 3, 'Deskripsi pelanggaran nomor 82.', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(83, 83, 'PG-083', 'Tidak memakai seragam sesuai ketentuan #083', 4, 'Deskripsi pelanggaran nomor 83.', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(84, 84, 'PG-084', 'Meninggalkan kelas tanpa izin #084', 5, 'Deskripsi pelanggaran nomor 84.', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(85, 85, 'PG-085', 'Tidak mengerjakan tugas #085', 6, 'Deskripsi pelanggaran nomor 85.', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(86, 86, 'PG-086', 'Rambut tidak sesuai aturan #086', 7, 'Deskripsi pelanggaran nomor 86.', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(87, 87, 'PG-087', 'Membawa barang terlarang #087', 8, 'Deskripsi pelanggaran nomor 87.', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(88, 88, 'PG-088', 'Berkata tidak sopan #088', 9, 'Deskripsi pelanggaran nomor 88.', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(89, 89, 'PG-089', 'Membuang sampah sembarangan #089', 10, 'Deskripsi pelanggaran nomor 89.', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(90, 90, 'PG-090', 'Tidak mengikuti upacara #090', 1, 'Deskripsi pelanggaran nomor 90.', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(91, 91, 'PG-091', 'Terlambat datang ke sekolah #091', 2, 'Deskripsi pelanggaran nomor 91.', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(92, 92, 'PG-092', 'Tidak memakai atribut lengkap #092', 3, 'Deskripsi pelanggaran nomor 92.', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(93, 93, 'PG-093', 'Tidak memakai seragam sesuai ketentuan #093', 4, 'Deskripsi pelanggaran nomor 93.', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(94, 94, 'PG-094', 'Meninggalkan kelas tanpa izin #094', 5, 'Deskripsi pelanggaran nomor 94.', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(95, 95, 'PG-095', 'Tidak mengerjakan tugas #095', 6, 'Deskripsi pelanggaran nomor 95.', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(96, 96, 'PG-096', 'Rambut tidak sesuai aturan #096', 7, 'Deskripsi pelanggaran nomor 96.', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(97, 97, 'PG-097', 'Membawa barang terlarang #097', 8, 'Deskripsi pelanggaran nomor 97.', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(98, 98, 'PG-098', 'Berkata tidak sopan #098', 9, 'Deskripsi pelanggaran nomor 98.', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(99, 99, 'PG-099', 'Membuang sampah sembarangan #099', 10, 'Deskripsi pelanggaran nomor 99.', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(100, 100, 'PG-100', 'Tidak mengikuti upacara #100', 1, 'Deskripsi pelanggaran nomor 100.', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_kategori`
--

CREATE TABLE `t_pelanggaran_kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pelanggaran_kategori`
--

INSERT INTO `t_pelanggaran_kategori` (`id`, `nama`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Kedisiplinan 1', 'Kategori pelanggaran nomor 1', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(2, 'Kerapian 1', 'Kategori pelanggaran nomor 2', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(3, 'Kehadiran 1', 'Kategori pelanggaran nomor 3', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(4, 'Ketertiban 1', 'Kategori pelanggaran nomor 4', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(5, 'Perlengkapan 1', 'Kategori pelanggaran nomor 5', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(6, 'Sikap 1', 'Kategori pelanggaran nomor 6', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(7, 'Kebersihan 1', 'Kategori pelanggaran nomor 7', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, 'Keamanan 1', 'Kategori pelanggaran nomor 8', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(9, 'Akademik 1', 'Kategori pelanggaran nomor 9', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(10, 'Lingkungan 1', 'Kategori pelanggaran nomor 10', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(11, 'Kedisiplinan 2', 'Kategori pelanggaran nomor 11', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(12, 'Kerapian 2', 'Kategori pelanggaran nomor 12', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(13, 'Kehadiran 2', 'Kategori pelanggaran nomor 13', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(14, 'Ketertiban 2', 'Kategori pelanggaran nomor 14', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(15, 'Perlengkapan 2', 'Kategori pelanggaran nomor 15', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(16, 'Sikap 2', 'Kategori pelanggaran nomor 16', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(17, 'Kebersihan 2', 'Kategori pelanggaran nomor 17', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(18, 'Keamanan 2', 'Kategori pelanggaran nomor 18', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(19, 'Akademik 2', 'Kategori pelanggaran nomor 19', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, 'Lingkungan 2', 'Kategori pelanggaran nomor 20', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(21, 'Kedisiplinan 3', 'Kategori pelanggaran nomor 21', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(22, 'Kerapian 3', 'Kategori pelanggaran nomor 22', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(23, 'Kehadiran 3', 'Kategori pelanggaran nomor 23', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(24, 'Ketertiban 3', 'Kategori pelanggaran nomor 24', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(25, 'Perlengkapan 3', 'Kategori pelanggaran nomor 25', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(26, 'Sikap 3', 'Kategori pelanggaran nomor 26', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(27, 'Kebersihan 3', 'Kategori pelanggaran nomor 27', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(28, 'Keamanan 3', 'Kategori pelanggaran nomor 28', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(29, 'Akademik 3', 'Kategori pelanggaran nomor 29', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(30, 'Lingkungan 3', 'Kategori pelanggaran nomor 30', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(31, 'Kedisiplinan 4', 'Kategori pelanggaran nomor 31', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(32, 'Kerapian 4', 'Kategori pelanggaran nomor 32', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(33, 'Kehadiran 4', 'Kategori pelanggaran nomor 33', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(34, 'Ketertiban 4', 'Kategori pelanggaran nomor 34', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(35, 'Perlengkapan 4', 'Kategori pelanggaran nomor 35', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(36, 'Sikap 4', 'Kategori pelanggaran nomor 36', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(37, 'Kebersihan 4', 'Kategori pelanggaran nomor 37', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(38, 'Keamanan 4', 'Kategori pelanggaran nomor 38', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(39, 'Akademik 4', 'Kategori pelanggaran nomor 39', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(40, 'Lingkungan 4', 'Kategori pelanggaran nomor 40', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(41, 'Kedisiplinan 5', 'Kategori pelanggaran nomor 41', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(42, 'Kerapian 5', 'Kategori pelanggaran nomor 42', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(43, 'Kehadiran 5', 'Kategori pelanggaran nomor 43', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(44, 'Ketertiban 5', 'Kategori pelanggaran nomor 44', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(45, 'Perlengkapan 5', 'Kategori pelanggaran nomor 45', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(46, 'Sikap 5', 'Kategori pelanggaran nomor 46', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(47, 'Kebersihan 5', 'Kategori pelanggaran nomor 47', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(48, 'Keamanan 5', 'Kategori pelanggaran nomor 48', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(49, 'Akademik 5', 'Kategori pelanggaran nomor 49', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(50, 'Lingkungan 5', 'Kategori pelanggaran nomor 50', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(51, 'Kedisiplinan 6', 'Kategori pelanggaran nomor 51', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(52, 'Kerapian 6', 'Kategori pelanggaran nomor 52', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(53, 'Kehadiran 6', 'Kategori pelanggaran nomor 53', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(54, 'Ketertiban 6', 'Kategori pelanggaran nomor 54', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(55, 'Perlengkapan 6', 'Kategori pelanggaran nomor 55', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(56, 'Sikap 6', 'Kategori pelanggaran nomor 56', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(57, 'Kebersihan 6', 'Kategori pelanggaran nomor 57', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(58, 'Keamanan 6', 'Kategori pelanggaran nomor 58', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(59, 'Akademik 6', 'Kategori pelanggaran nomor 59', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(60, 'Lingkungan 6', 'Kategori pelanggaran nomor 60', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(61, 'Kedisiplinan 7', 'Kategori pelanggaran nomor 61', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(62, 'Kerapian 7', 'Kategori pelanggaran nomor 62', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(63, 'Kehadiran 7', 'Kategori pelanggaran nomor 63', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(64, 'Ketertiban 7', 'Kategori pelanggaran nomor 64', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(65, 'Perlengkapan 7', 'Kategori pelanggaran nomor 65', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(66, 'Sikap 7', 'Kategori pelanggaran nomor 66', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(67, 'Kebersihan 7', 'Kategori pelanggaran nomor 67', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(68, 'Keamanan 7', 'Kategori pelanggaran nomor 68', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(69, 'Akademik 7', 'Kategori pelanggaran nomor 69', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(70, 'Lingkungan 7', 'Kategori pelanggaran nomor 70', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(71, 'Kedisiplinan 8', 'Kategori pelanggaran nomor 71', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(72, 'Kerapian 8', 'Kategori pelanggaran nomor 72', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(73, 'Kehadiran 8', 'Kategori pelanggaran nomor 73', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(74, 'Ketertiban 8', 'Kategori pelanggaran nomor 74', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(75, 'Perlengkapan 8', 'Kategori pelanggaran nomor 75', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(76, 'Sikap 8', 'Kategori pelanggaran nomor 76', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(77, 'Kebersihan 8', 'Kategori pelanggaran nomor 77', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(78, 'Keamanan 8', 'Kategori pelanggaran nomor 78', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(79, 'Akademik 8', 'Kategori pelanggaran nomor 79', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(80, 'Lingkungan 8', 'Kategori pelanggaran nomor 80', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(81, 'Kedisiplinan 9', 'Kategori pelanggaran nomor 81', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(82, 'Kerapian 9', 'Kategori pelanggaran nomor 82', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(83, 'Kehadiran 9', 'Kategori pelanggaran nomor 83', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(84, 'Ketertiban 9', 'Kategori pelanggaran nomor 84', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(85, 'Perlengkapan 9', 'Kategori pelanggaran nomor 85', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(86, 'Sikap 9', 'Kategori pelanggaran nomor 86', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(87, 'Kebersihan 9', 'Kategori pelanggaran nomor 87', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(88, 'Keamanan 9', 'Kategori pelanggaran nomor 88', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(89, 'Akademik 9', 'Kategori pelanggaran nomor 89', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(90, 'Lingkungan 9', 'Kategori pelanggaran nomor 90', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(91, 'Kedisiplinan 10', 'Kategori pelanggaran nomor 91', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(92, 'Kerapian 10', 'Kategori pelanggaran nomor 92', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(93, 'Kehadiran 10', 'Kategori pelanggaran nomor 93', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(94, 'Ketertiban 10', 'Kategori pelanggaran nomor 94', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(95, 'Perlengkapan 10', 'Kategori pelanggaran nomor 95', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(96, 'Sikap 10', 'Kategori pelanggaran nomor 96', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(97, 'Kebersihan 10', 'Kategori pelanggaran nomor 97', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(98, 'Keamanan 10', 'Kategori pelanggaran nomor 98', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(99, 'Akademik 10', 'Kategori pelanggaran nomor 99', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(100, 'Lingkungan 10', 'Kategori pelanggaran nomor 100', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pelanggaran_siswa`
--

CREATE TABLE `t_pelanggaran_siswa` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `siswa_id` int(11) DEFAULT NULL,
  `nama_siswa` varchar(150) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `pelanggaran_id` int(11) DEFAULT NULL,
  `nama_pelanggaran` varchar(150) NOT NULL,
  `pelanggaran_kategori_id` int(11) DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `nama_guru` varchar(150) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text NOT NULL,
  `poin` int(11) NOT NULL,
  `tindakan` text NOT NULL,
  `status` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(30) DEFAULT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama` varchar(150) DEFAULT NULL,
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
(105, '26060001', '0087123401', 'Alvin Rahadian', 'L', '2008-08-03', 'Jl. Sukamaju', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(106, '26060002', '0087123402', 'Alisa Ramadhani', 'P', '2008-09-28', 'Jl. Cempaka', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(107, '26060003', '0087123403', 'Alma Aura Livia', 'P', '2008-08-26', 'Jl. Melati', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(108, '26060004', '0087123404', 'Anis Khoerunnisa N.R', 'P', '2008-09-25', 'Jl. Sukaraja', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(109, '26060005', '0098123405', 'Azmil Amira', 'P', '2009-10-30', 'Jl. Mawar', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(110, '26060006', '0087123406', 'Azril Akbar', 'L', '2008-08-21', 'Jl. Cikurubuk', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(111, '26060007', '0087123407', 'Erlangga Saputra', 'L', '2008-06-06', 'Jl. Panyingkiran', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(112, '26060008', '0098123408', 'Hilma Himmatunnajah', 'P', '2009-03-25', 'Jl. Cibeureum', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(113, '26060009', '0098123409', 'ilma nazya', 'P', '2009-02-16', 'Jl. Sindanggalih', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(114, '26060010', '0087123410', 'Julianda Dharma Pangestu', 'L', '2008-07-02', 'Jl. Nagarawangi', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(115, '26060011', '0098123411', 'Kayila Nur Fadilah', 'P', '2009-07-15', 'Jl. Cihideung', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(116, '26060012', '0087123412', 'Moza Dina Nabillah', 'P', '2008-11-21', 'Jl. Tentara Pelajar', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(117, '26060013', '0087123413', 'Muhammad Al Farizi', 'L', '2008-11-13', 'Jl. Mitra Batik', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(118, '26060014', '0087123414', 'fahrin', 'L', '2008-12-20', 'Jl. Paseh', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(119, '26060015', '0067123415', 'Muhammad Zidan', 'L', '2006-03-30', 'Jl. Perintis Kemerdekaan', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(120, '26060016', '0098123416', 'muhammad zulfan', 'L', '2009-01-27', 'Jl. Sukalaya', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(121, '26060017', '0098123417', 'Raditya Jeti Prasetya', 'L', '2009-09-01', 'Jl. Letjen Mashudi', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(122, '26060018', '0087123418', 'Rangga Ramadiyanto Nurhakim', 'L', '2008-05-21', 'Jl. Ir. H. Juanda', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(123, '26060019', '0087123419', 'Natiya nur Azkiya', 'P', '2008-12-26', 'Jl. Cisalak', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(124, '26060020', '0087123420', 'Novita Puspitasari', 'P', '2008-11-02', 'Jl. Siliwangi', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(125, '26060021', '0098123421', 'Risqi Muhamad Ihwani', 'L', '2009-07-22', 'Jl. Leuwidahu', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(126, '26060022', '0087123422', 'Nucky Andreas febrian', 'L', '2008-02-16', 'Jl. Gunung Sabeulah', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(127, '26060023', '0087123423', 'Salwa Sabilatul Barkah', 'P', '2008-09-24', 'Jl. Cikanyere', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04'),
(128, '26060024', '0098123424', 'Tazkia Zahra', 'P', '2009-01-09', 'Jl. Babakan', 0, '2026-11-06 02:26:04', '2026-11-06 02:26:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_tahun_ajaran`
--

CREATE TABLE `t_tahun_ajaran` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) DEFAULT NULL,
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
(1, '2020/2021', '2020-07-01', '2021-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(2, '2020/2021', '2020-07-01', '2021-06-30', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(3, '2020/2021', '2020-07-01', '2021-06-30', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(4, '2020/2021', '2020-07-01', '2021-06-30', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(5, '2021/2022', '2021-07-01', '2022-06-30', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(6, '2021/2022', '2021-07-01', '2022-06-30', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(7, '2021/2022', '2021-07-01', '2022-06-30', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(8, '2021/2022', '2021-07-01', '2022-06-30', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(9, '2022/2023', '2022-07-01', '2023-06-30', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(10, '2022/2023', '2022-07-01', '2023-06-30', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(11, '2022/2023', '2022-07-01', '2023-06-30', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(12, '2022/2023', '2022-07-01', '2023-06-30', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(13, '2023/2024', '2023-07-01', '2024-06-30', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(14, '2023/2024', '2023-07-01', '2024-06-30', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(15, '2023/2024', '2023-07-01', '2024-06-30', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(16, '2023/2024', '2023-07-01', '2024-06-30', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(17, '2024/2025', '2024-07-01', '2025-06-30', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(18, '2024/2025', '2024-07-01', '2025-06-30', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(19, '2024/2025', '2024-07-01', '2025-06-30', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(20, '2024/2025', '2024-07-01', '2025-06-30', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(21, '2025/2026', '2025-07-01', '2026-06-30', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(22, '2025/2026', '2025-07-01', '2026-06-30', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(23, '2025/2026', '2025-07-01', '2026-06-30', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(24, '2025/2026', '2025-07-01', '2026-06-30', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(25, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(26, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(27, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(28, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(29, '2027/2028', '2027-07-01', '2028-06-30', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(30, '2027/2028', '2027-07-01', '2028-06-30', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(31, '2027/2028', '2027-07-01', '2028-06-30', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(32, '2027/2028', '2027-07-01', '2028-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(33, '2028/2029', '2028-07-01', '2029-06-30', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(34, '2028/2029', '2028-07-01', '2029-06-30', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(35, '2028/2029', '2028-07-01', '2029-06-30', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(36, '2028/2029', '2028-07-01', '2029-06-30', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(37, '2029/2030', '2029-07-01', '2030-06-30', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(38, '2029/2030', '2029-07-01', '2030-06-30', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(39, '2029/2030', '2029-07-01', '2030-06-30', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(40, '2029/2030', '2029-07-01', '2030-06-30', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(41, '2030/2031', '2030-07-01', '2031-06-30', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(42, '2030/2031', '2030-07-01', '2031-06-30', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(43, '2030/2031', '2030-07-01', '2031-06-30', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(44, '2030/2031', '2030-07-01', '2031-06-30', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(45, '2031/2032', '2031-07-01', '2032-06-30', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(46, '2031/2032', '2031-07-01', '2032-06-30', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(47, '2031/2032', '2031-07-01', '2032-06-30', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(48, '2031/2032', '2031-07-01', '2032-06-30', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(49, '2032/2033', '2032-07-01', '2033-06-30', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(50, '2032/2033', '2032-07-01', '2033-06-30', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(51, '2032/2033', '2032-07-01', '2033-06-30', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(52, '2032/2033', '2032-07-01', '2033-06-30', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(53, '2033/2034', '2033-07-01', '2034-06-30', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(54, '2033/2034', '2033-07-01', '2034-06-30', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(55, '2033/2034', '2033-07-01', '2034-06-30', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(56, '2033/2034', '2033-07-01', '2034-06-30', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(57, '2034/2035', '2034-07-01', '2035-06-30', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(58, '2034/2035', '2034-07-01', '2035-06-30', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(59, '2034/2035', '2034-07-01', '2035-06-30', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(60, '2034/2035', '2034-07-01', '2035-06-30', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(61, '2035/2036', '2035-07-01', '2036-06-30', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(62, '2035/2036', '2035-07-01', '2036-06-30', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(63, '2035/2036', '2035-07-01', '2036-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(64, '2035/2036', '2035-07-01', '2036-06-30', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(65, '2036/2037', '2036-07-01', '2037-06-30', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(66, '2036/2037', '2036-07-01', '2037-06-30', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(67, '2036/2037', '2036-07-01', '2037-06-30', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(68, '2036/2037', '2036-07-01', '2037-06-30', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(69, '2037/2038', '2037-07-01', '2038-06-30', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00'),
(70, '2037/2038', '2037-07-01', '2038-06-30', 1, '2026-07-08 01:00:00', '2026-07-08 01:00:00'),
(71, '2037/2038', '2037-07-01', '2038-06-30', 1, '2026-07-09 01:00:00', '2026-07-09 01:00:00'),
(72, '2037/2038', '2037-07-01', '2038-06-30', 1, '2026-07-10 01:00:00', '2026-07-10 01:00:00'),
(73, '2038/2039', '2038-07-01', '2039-06-30', 1, '2026-07-11 01:00:00', '2026-07-11 01:00:00'),
(74, '2038/2039', '2038-07-01', '2039-06-30', 1, '2026-07-12 01:00:00', '2026-07-12 01:00:00'),
(75, '2038/2039', '2038-07-01', '2039-06-30', 1, '2026-07-13 01:00:00', '2026-07-13 01:00:00'),
(76, '2038/2039', '2038-07-01', '2039-06-30', 1, '2026-07-14 01:00:00', '2026-07-14 01:00:00'),
(77, '2039/2040', '2039-07-01', '2040-06-30', 1, '2026-07-15 01:00:00', '2026-07-15 01:00:00'),
(78, '2039/2040', '2039-07-01', '2040-06-30', 1, '2026-07-16 01:00:00', '2026-07-16 01:00:00'),
(79, '2039/2040', '2039-07-01', '2040-06-30', 1, '2026-07-17 01:00:00', '2026-07-17 01:00:00'),
(80, '2039/2040', '2039-07-01', '2040-06-30', 1, '2026-07-18 01:00:00', '2026-07-18 01:00:00'),
(81, '2040/2041', '2040-07-01', '2041-06-30', 1, '2026-07-19 01:00:00', '2026-07-19 01:00:00'),
(82, '2040/2041', '2040-07-01', '2041-06-30', 1, '2026-07-20 01:00:00', '2026-07-20 01:00:00'),
(83, '2040/2041', '2040-07-01', '2041-06-30', 1, '2026-07-21 01:00:00', '2026-07-21 01:00:00'),
(84, '2040/2041', '2040-07-01', '2041-06-30', 1, '2026-07-22 01:00:00', '2026-07-22 01:00:00'),
(85, '2041/2042', '2041-07-01', '2042-06-30', 1, '2026-07-23 01:00:00', '2026-07-23 01:00:00'),
(86, '2041/2042', '2041-07-01', '2042-06-30', 1, '2026-07-24 01:00:00', '2026-07-24 01:00:00'),
(87, '2041/2042', '2041-07-01', '2042-06-30', 1, '2026-07-25 01:00:00', '2026-07-25 01:00:00'),
(88, '2041/2042', '2041-07-01', '2042-06-30', 1, '2026-07-26 01:00:00', '2026-07-26 01:00:00'),
(89, '2042/2043', '2042-07-01', '2043-06-30', 1, '2026-07-27 01:00:00', '2026-07-27 01:00:00'),
(90, '2042/2043', '2042-07-01', '2043-06-30', 1, '2026-07-28 01:00:00', '2026-07-28 01:00:00'),
(91, '2042/2043', '2042-07-01', '2043-06-30', 1, '2026-07-29 01:00:00', '2026-07-29 01:00:00'),
(92, '2042/2043', '2042-07-01', '2043-06-30', 1, '2026-07-30 01:00:00', '2026-07-30 01:00:00'),
(93, '2043/2044', '2043-07-01', '2044-06-30', 1, '2026-07-31 01:00:00', '2026-07-31 01:00:00'),
(94, '2043/2044', '2043-07-01', '2044-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(95, '2043/2044', '2043-07-01', '2044-06-30', 1, '2026-07-02 01:00:00', '2026-07-02 01:00:00'),
(96, '2043/2044', '2043-07-01', '2044-06-30', 1, '2026-07-03 01:00:00', '2026-07-03 01:00:00'),
(97, '2044/2045', '2044-07-01', '2045-06-30', 1, '2026-07-04 01:00:00', '2026-07-04 01:00:00'),
(98, '2044/2045', '2044-07-01', '2045-06-30', 1, '2026-07-05 01:00:00', '2026-07-05 01:00:00'),
(99, '2044/2045', '2044-07-01', '2045-06-30', 1, '2026-07-06 01:00:00', '2026-07-06 01:00:00'),
(100, '2044/2045', '2044-07-01', '2045-06-30', 1, '2026-07-07 01:00:00', '2026-07-07 01:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_wali_kelas`
--

CREATE TABLE `t_wali_kelas` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indeks untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT untuk tabel `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT untuk tabel `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD CONSTRAINT `t_kelas_siswa_ibfk_1` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_kelas_siswa_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_kelas_siswa_ibfk_3` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD CONSTRAINT `t_pelanggaran_ibfk_1` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_1` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`),
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_2` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_4` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_5` FOREIGN KEY (`pelanggaran_id`) REFERENCES `t_pelanggaran` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_6` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD CONSTRAINT `t_wali_kelas_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_wali_kelas_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `t_wali_kelas_ibfk_3` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
