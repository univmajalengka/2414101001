-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Des 2025 pada 09.00
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
-- Database: `tugaspabw_2414101001`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ai_knowledge`
--

CREATE TABLE `ai_knowledge` (
  `id` int(11) NOT NULL,
  `question_pattern` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `username`, `message`, `avatar_url`, `created_at`) VALUES
(1, 'Developer', 'test', 'uploads/profiles/pf_691ecd904f101.jpg', '2025-11-21 07:56:26'),
(2, 'Developer', 'siapa itu Virlan Hakuto?', 'uploads/profiles/pf_691ecd904f101.jpg', '2025-11-21 07:56:36'),
(3, 'Admin', 'Virlan Hakuto adalah player MLBB', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 07:57:08'),
(4, 'Admin', 'Vivi, siapa itu Virlan Hakuto', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 07:57:17'),
(5, 'Vivi', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 'binigua.jpg', '2025-11-21 07:57:17'),
(6, 'Admin', 'Vivi, siapa itu Virlan Hakuto?', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 07:57:50'),
(7, 'Vivi', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 'binigua.jpg', '2025-11-21 07:57:50'),
(8, 'Admin', 'Vivi, siapa kamu', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:05:44'),
(9, 'Vivi', 'Saya Vivi, asisten virtual yang dirancang untuk membantu Anda dengan berbagai pertanyaan. Saya memiliki pengetahuan tentang banyak topik termasuk sains, sejarah, teknologi, anime, dan tentu saja informasi tentang DF Scenery. Saya terus belajar setiap hari untuk memberikan jawaban yang lebih baik!', 'binigua.jpg', '2025-11-21 08:05:44'),
(10, 'Admin', 'Vivi, siapa itu Virlan Hakuto', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:05:53'),
(11, 'Vivi', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 'binigua.jpg', '2025-11-21 08:05:53'),
(12, 'Developer', 'siapa itu Virlan Hakuto?', 'uploads/profiles/pf_691ecd904f101.jpg', '2025-11-21 08:06:24'),
(13, 'Admin', 'Virlan Hakuto adalah player MLBB', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:06:36'),
(14, 'Developer', 'Vivi, siapa Virlan Hakuto', 'uploads/profiles/pf_691ecd904f101.jpg', '2025-11-21 08:06:45'),
(15, 'Vivi', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 'binigua.jpg', '2025-11-21 08:06:45'),
(16, 'Developer', 'siapa itu Virlan Hakuto', 'uploads/profiles/pf_691ecd904f101.jpg', '2025-11-21 08:08:12'),
(17, 'Admin', 'Virlan Hakuto adalah player MLBB', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:08:43'),
(18, 'Admin', 'Vivi, siapa itu Virlan Hakuto', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:08:53'),
(19, 'Vivi', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 'binigua.jpg', '2025-11-21 08:08:53'),
(20, 'Admin', 'Vivi, siapa Jess No Limit', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:11:50'),
(21, 'Vivi', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 'binigua.jpg', '2025-11-21 08:11:50'),
(22, 'Admin', 'Vivi, siapa presiden indonesia saat ini', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:12:02'),
(23, 'Vivi', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 'binigua.jpg', '2025-11-21 08:12:02'),
(24, 'Admin', 'Vivi, siapa presiden indonesia saat ini', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:12:16'),
(25, 'Vivi', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 'binigua.jpg', '2025-11-21 08:12:16'),
(26, 'Admin', 'Vivi, apa itu kecerdasan buatan', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:12:28'),
(27, 'Vivi', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 'binigua.jpg', '2025-11-21 08:12:28'),
(28, 'Admin', 'Vivi, apa itu ai', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:28:28'),
(29, 'Vivi', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 'binigua.jpg', '2025-11-21 08:28:28'),
(30, 'Admin', 'Vivi, ai', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:28:35'),
(31, 'Vivi', 'Hmm, saya belum bisa memberikan jawaban yang spesifik untuk itu. Tapi saya terus belajar setiap hari! Ada hal lain tentang DF Scenery atau topik lain yang ingin Anda tanyakan?', 'binigua.jpg', '2025-11-21 08:28:35'),
(32, 'Admin', 'Vivi, apa itu kecerdasan buatan', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:28:46'),
(33, 'Vivi', 'Kecerdasan Buatan (AI) adalah teknologi yang memungkinkan mesin dan komputer untuk meniru kemampuan kognitif manusia seperti belajar, memecahkan masalah, dan mengambil keputusan secara otonom. AI bekerja dengan menganalisis sejumlah besar data untuk mengenali pola, membuat prediksi, memahami bahasa, dan melakukan tugas-tugas kompleks. Contohnya termasuk asisten virtual seperti Siri dan Google Assistant, kendaraan otonom, dan sistem rekomendasi di platform seperti Netflix dan Amazon. ', 'binigua.jpg', '2025-11-21 08:28:46'),
(34, 'Admin', 'halo vivi', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:31:54'),
(35, 'Admin', 'Vivi', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:31:58'),
(36, 'Admin', 'Vivi, siapa kamu', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:32:06'),
(37, 'Vivi', 'Saya Vivi, asisten virtual yang dirancang untuk membantu Anda dengan berbagai pertanyaan. Saya memiliki pengetahuan tentang banyak topik termasuk sains, sejarah, teknologi, anime, dan tentu saja informasi tentang DF Scenery. Saya terus belajar setiap hari untuk memberikan jawaban yang lebih baik!', 'binigua.jpg', '2025-11-21 08:32:06'),
(38, 'Admin', 'Vivi, apa itu kecerdasan buatan', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:32:41'),
(39, 'Vivi', 'Kecerdasan Buatan (AI) adalah teknologi yang memungkinkan mesin dan komputer untuk meniru kemampuan kognitif manusia seperti belajar, memecahkan masalah, dan mengambil keputusan secara otonom. AI bekerja dengan menganalisis sejumlah besar data untuk mengenali pola, membuat prediksi, memahami bahasa, dan melakukan tugas-tugas kompleks. Contohnya termasuk asisten virtual seperti Siri dan Google Assistant, kendaraan otonom, dan sistem rekomendasi di platform seperti Netflix dan Amazon. ', 'binigua.jpg', '2025-11-21 08:32:41'),
(40, 'Admin', 'Vivi, sejarah indonesia', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:37:46'),
(41, 'Vivi', 'Presiden Indonesia saat ini adalah Prabowo Subianto', 'binigua.jpg', '2025-11-21 08:37:46'),
(42, 'Admin', 'Vivi, apa itu sains', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:38:15'),
(43, 'Vivi', 'Sains adalah cara sistematis untuk mempelajari dunia alam melalui observasi dan eksperimen. Sains membantu kita memahami alam semesta dari yang terkecil hingga yang terbesar.', 'binigua.jpg', '2025-11-21 08:38:15'),
(44, 'Admin', 'Vivi, sejarah dunia', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:38:50'),
(45, 'Vivi', 'Sejarah dunia mencakup peradaban kuno seperti Mesir, Yunani, dan Romawi, Abad Pertengahan dengan feodalisme, Renaissance yang membangkitkan kembali seni dan ilmu pengetahuan, hingga era modern dengan revolusi industri dan teknologi informasi.', 'binigua.jpg', '2025-11-21 08:38:50'),
(46, 'Admin', 'Vivi, sejarah indonesia', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:38:58'),
(47, 'Vivi', 'Presiden Indonesia saat ini adalah Prabowo Subianto', 'binigua.jpg', '2025-11-21 08:38:58'),
(48, 'Admin', 'Vivi, bahas asia', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:39:15'),
(49, 'Vivi', 'Pertanyaan yang bagus! Sayangnya, saya belum memiliki informasi lengkap tentang itu. Namun, saya bisa membantu Anda dengan informasi tentang DF Scenery atau topik umum lainnya. Ada yang bisa saya bantu?', 'binigua.jpg', '2025-11-21 08:39:15'),
(50, 'Admin', 'Vivi, asia', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:39:20'),
(51, 'Vivi', 'Pertanyaan yang bagus! Sayangnya, saya belum memiliki informasi lengkap tentang itu. Namun, saya bisa membantu Anda dengan informasi tentang DF Scenery atau topik umum lainnya. Ada yang bisa saya bantu?', 'binigua.jpg', '2025-11-21 08:39:20'),
(52, 'Admin', 'Vivi, 230 + 90 berapa', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:39:48'),
(53, 'Vivi', 'Hasil dari 230 + 90 adalah 320.', 'binigua.jpg', '2025-11-21 08:39:48'),
(54, 'Admin', 'Vivi, siapa istri Carlotta Wuthering Waves', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 08:55:24'),
(55, 'Vivi', 'tentu saja Carlotta pada game Wuthering Waves adalah istri dari DF!', 'binigua.jpg', '2025-11-21 08:55:24'),
(56, 'David_Firdaus', 'stress', 'uploads/profiles/pf_69144e361bc03.jpg', '2025-11-21 08:57:26'),
(57, 'Developer', 'wkaokwoakowakwoak', 'uploads/profiles/pf_691ecd904f101.jpg', '2025-11-21 08:58:11'),
(58, 'Admin', 'test', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 09:01:52'),
(59, 'Admin', 'Vivi, bahas game genshin impact', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 09:26:06'),
(60, 'Vivi', 'tentu saja Carlotta pada game Wuthering Waves adalah istri dari DF!', 'binigua.jpg', '2025-11-21 09:26:06'),
(61, 'Admin', 'Vivi, bahas game genshin impact', 'uploads/profiles/pf_69155aefcbeb3.jpg', '2025-11-21 09:27:39'),
(62, 'Vivi', 'Selamat pagi! Senang berbicara dengan Anda. Apa yang ingin Anda ketahui hari ini?', 'binigua.jpg', '2025-11-21 09:27:39'),
(64, 'Vivi', 'Hmm, saya belum bisa memberikan jawaban yang spesifik untuk itu. Tapi saya terus belajar setiap hari! Ada hal lain tentang DF Scenery atau topik lain yang ingin Anda tanyakan?', 'binigua.jpg', '2025-11-21 09:27:49'),
(65, 'David_Firdaus', 'test', 'uploads/profiles/pf_69144e361bc03.jpg', '2025-11-22 05:13:54'),
(66, 'David_Firdaus', 'Vivi, siapa kamu', 'uploads/profiles/pf_69144e361bc03.jpg', '2025-11-22 05:14:01'),
(67, 'Vivi', 'Saya Vivi, asisten virtual yang dirancang untuk membantu Anda dengan berbagai pertanyaan. Saya memiliki pengetahuan tentang banyak topik termasuk sains, sejarah, teknologi, anime, dan tentu saja informasi tentang DF Scenery. Saya terus belajar setiap hari untuk memberikan jawaban yang lebih baik!', 'binigua.jpg', '2025-11-22 05:14:01'),
(68, 'Developer', 'Vivi, siapa president indonesia saat ini', 'uploads/profiles/pf_691ecd904f101.jpg', '2025-12-10 06:43:53'),
(69, 'Vivi', 'President Indonesia saat ini adalah Prabowo Subianto.', 'binigua.jpg', '2025-12-10 06:43:53'),
(70, 'VHakuto', 'test', 'uploads/profiles/pf_693913cabde39.jpg', '2025-12-10 07:35:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `rating` int(1) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `username`, `rating`, `comment`, `created_at`) VALUES
(2, 14, NULL, 5, 'test', '2025-11-18 16:22:26'),
(3, 6, NULL, 3, 'test', '2025-11-18 16:23:13'),
(4, 21, NULL, 5, 'test', '2025-11-18 16:24:08'),
(5, 14, NULL, 5, 'test', '2025-11-18 16:28:24'),
(6, 14, NULL, 5, 'test', '2025-11-18 16:28:29'),
(7, 14, NULL, 5, 'test', '2025-11-18 16:28:34'),
(8, 14, NULL, 5, 'test', '2025-11-18 16:41:00'),
(9, 17, NULL, 5, 'test', '2025-11-20 15:15:28'),
(10, 17, NULL, 5, 'test', '2025-11-22 12:13:06'),
(11, 6, NULL, 5, 'test', '2025-11-22 12:13:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user1` varchar(50) NOT NULL,
  `user2` varchar(50) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dfkomik_about`
--

CREATE TABLE `dfkomik_about` (
  `id` int(11) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dfkomik_about`
--

INSERT INTO `dfkomik_about` (`id`, `description`) VALUES
(17, 'Tempat baca komik Manga, Manhwa, & Manhua'),
(18, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dfkomik_bookmarks`
--

CREATE TABLE `dfkomik_bookmarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comic_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dfkomik_bookmarks`
--

INSERT INTO `dfkomik_bookmarks` (`id`, `user_id`, `comic_id`, `created_at`) VALUES
(3, 6, 6, '2025-11-11 09:28:41'),
(7, 9, 5, '2025-11-11 10:06:08'),
(8, 9, 11, '2025-11-12 04:09:57'),
(9, 6, 5, '2025-11-13 08:10:33'),
(11, 18, 5, '2025-11-13 08:15:46'),
(12, 14, 5, '2025-11-19 05:38:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dfkomik_chapters`
--

CREATE TABLE `dfkomik_chapters` (
  `id` int(11) NOT NULL,
  `comic_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dfkomik_chapters`
--

INSERT INTO `dfkomik_chapters` (`id`, `comic_id`, `title`, `subtitle`, `image`, `created_at`) VALUES
(14, 5, 'Chapter 1', 'Impostor', '60.jpeg', '2025-11-11 09:16:46'),
(16, 6, 'Chapter 1', 'Aw my love', 'job-change-log-job-switch-log-recommendations-for-manhwa-v0-t0vm5wrflgue1.webp', '2025-11-11 09:21:41'),
(17, 5, 'Chapter 2', '', 'Screenshot 2025-11-11 133848.png', '2025-11-11 09:22:32'),
(18, 5, 'Chapter 3', 'yaaa', '10.jpeg', '2025-11-11 09:23:18'),
(19, 7, 'Chapter 1', 'yaa', 'i_became_the_wife_of_the_male_lead_season_3_art.webp', '2025-11-11 09:26:03'),
(23, 5, 'Chapter 4', 'Test', '0b0b48aa-9c44-49e9-80d1-54233c5d9385.jpg', '2025-11-12 09:35:03'),
(25, 5, 'Chapter 5', 'test', '0af87bde132510a6fd5c27e0b43e00b4.jpg', '2025-11-12 09:36:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dfkomik_chapter_images`
--

CREATE TABLE `dfkomik_chapter_images` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dfkomik_chapter_images`
--

INSERT INTO `dfkomik_chapter_images` (`id`, `chapter_id`, `filename`, `created_at`) VALUES
(58, 14, '00.jpeg', '2025-11-11 09:16:46'),
(59, 14, '01.jpeg', '2025-11-11 09:16:46'),
(60, 14, '02.jpeg', '2025-11-11 09:16:46'),
(61, 14, '03.jpeg', '2025-11-11 09:16:46'),
(62, 14, '04.jpeg', '2025-11-11 09:16:46'),
(63, 14, '05.jpeg', '2025-11-11 09:16:46'),
(64, 14, '06.jpeg', '2025-11-11 09:16:46'),
(65, 14, '07.jpeg', '2025-11-11 09:16:46'),
(66, 14, '08.jpeg', '2025-11-11 09:16:46'),
(67, 14, '09.jpeg', '2025-11-11 09:16:46'),
(68, 14, '10.jpeg', '2025-11-11 09:16:46'),
(69, 14, '11.jpeg', '2025-11-11 09:16:46'),
(70, 14, '12.jpeg', '2025-11-11 09:16:46'),
(71, 14, '13.jpeg', '2025-11-11 09:16:46'),
(72, 14, '14.jpeg', '2025-11-11 09:16:46'),
(73, 14, '15.jpeg', '2025-11-11 09:16:46'),
(74, 14, '16.jpeg', '2025-11-11 09:16:46'),
(75, 14, '60.jpeg', '2025-11-11 09:16:46'),
(76, 14, '61.jpeg', '2025-11-11 09:16:46'),
(77, 16, '20251111_161230.jpg', '2025-11-11 09:21:41'),
(78, 17, '20251111_161230.jpg', '2025-11-11 09:22:32'),
(79, 18, '20251111_161230.jpg', '2025-11-11 09:23:18'),
(80, 19, '20251111_161230.jpg', '2025-11-11 09:26:03'),
(81, 0, '0a73c539c7863fd2232297b22964e66a.jpg', '2025-11-12 09:31:00'),
(82, 0, '0a73c539c7863fd2232297b22964e66a.jpg', '2025-11-12 09:31:26'),
(84, 23, '0a73c539c7863fd2232297b22964e66a.jpg', '2025-11-12 09:35:03'),
(85, 23, '0f5f12b00d2cc89727e727223b90898b.jpg', '2025-11-12 09:35:03'),
(87, 25, '0a95a5d1b9d750370c0ca8d7d555e77f.jpg', '2025-11-12 09:36:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dfkomik_comics`
--

CREATE TABLE `dfkomik_comics` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `genre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dfkomik_comics`
--

INSERT INTO `dfkomik_comics` (`id`, `created_at`, `updated_at`, `title`, `cover`, `description`, `genre`) VALUES
(5, '2025-11-12 16:29:52', '2025-11-13 15:10:21', 'The Villain Of Destiny', 'Screenshot 2025-11-11 133848.png', 'mengisahkan tentang salah satu tokoh yang bernama Gu Changge yang mana dia telah menyadari bahwa dirinya telah masuk ke dalam sebuah fantasi.\r\n\r\nDalam kisahnya dia menjadi tokoh protagonis, akan tetapi dia memiliki sumpah bahwa dirinya akan melakukan balas dendam.\r\n\r\nSebenarnya Gu Changge sangat beruntung. Hal ini dikarenakan hampir semua wanita yang ada dalam tokoh manhua ini sangat tergila – gila padanya. Bahkan dimanapun kakinya berpijak semua orang menyukai dan menghormatinya. Hingga banyak sekali orang yang iri dengannya.\r\n\r\nNamun, meski dengan segala keuntungan yang dia miliki tak membuat Gu Changge lengah. Harkat dan martabat harga dirinya lebih tinggi dibandingkan semua itu. Sehingga dia tak mudah jatuh dan terlena hanya karena pujian saja.', 'Action, Fantasy'),
(6, '2025-11-12 16:29:52', '2025-11-12 16:35:38', 'Job Change Log', 'cover_6912ffcbbd16d4.47069157.webp', NULL, NULL),
(7, '2025-11-12 16:29:52', NULL, 'I Became the Wife of the Male Lead', 'cover_69130104324b42.14619524.webp', NULL, NULL),
(8, '2025-11-12 16:29:52', NULL, 'Player Who Returned 10,000 Years Later', 'cover_6913017c16f670.93467755.webp', NULL, NULL),
(9, '2025-11-12 16:29:52', NULL, 'Helmut: The Forsaken Child', 'cover_6913029aaf12d5.43629757.webp', NULL, NULL),
(10, '2025-11-12 16:29:52', NULL, 'Villain to Kill', 'cover_691302d0655280.41944095.webp', NULL, NULL),
(11, '2025-11-12 16:29:52', NULL, 'Omniscient Reader’s Viewpoint', 'cover_6913030b999215.49351864.jpeg', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `dfkomik_comments`
--

CREATE TABLE `dfkomik_comments` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dfkomik_comments`
--

INSERT INTO `dfkomik_comments` (`id`, `chapter_id`, `username`, `comment`, `created_at`) VALUES
(1, 6, 'admin123', 'yaa', '2025-11-11 08:19:22'),
(2, 17, 'admin123', 'ditunggu', '2025-11-11 09:23:29'),
(3, 14, 'David Firdaus', 'yaaaa', '2025-11-11 09:56:55'),
(4, 14, 'David Firdaus', 'ya', '2025-11-12 09:05:57'),
(5, 14, 'admin123', 'test', '2025-11-12 09:06:32'),
(6, 14, 'admin123', 'test', '2025-11-12 09:15:52'),
(7, 14, 'ADM David Firdaus', 'test', '2025-11-12 09:18:21'),
(8, 14, 'ADM David Firdaus', 'test', '2025-11-12 09:18:52'),
(9, 14, 'ADM David Firdaus', 'test', '2025-11-12 09:19:23'),
(10, 14, 'David Firdaus', 'test', '2025-11-12 09:20:57'),
(11, 14, 'David Firdaus ADM', 'v', '2025-11-12 09:22:32'),
(12, 25, 'David Firdaus', 'hai', '2025-11-12 09:38:38'),
(13, 25, 'David Firdaus ADM', 'hai', '2025-11-12 09:39:00'),
(14, 25, 'YDavid Firdaus', 'yaa', '2025-11-12 09:39:36'),
(15, 25, 'YDavid Firdaus', 'Chapter 1 komik beneran ya', '2025-11-12 09:40:11'),
(16, 25, 'YDavid Firdaus', 'y', '2025-11-13 03:50:21'),
(17, 25, 'Admin', 'ya', '2025-11-13 04:12:58'),
(18, 25, 'YDavid Firdaus', 'test', '2025-11-13 04:50:34'),
(19, 25, 'Developer', 'test', '2025-11-13 04:57:11'),
(20, 14, 'YDavid Firdaus', 'test', '2025-11-13 08:10:43'),
(21, 25, 'David_Firdaus', 'test', '2025-11-13 08:26:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dfkomik_gallery`
--

CREATE TABLE `dfkomik_gallery` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dfkomik_gallery`
--

INSERT INTO `dfkomik_gallery` (`id`, `filename`) VALUES
(15, '20251111_163847.jpg'),
(16, '20251111_164048.jpg'),
(17, '20251111_164105.jpg'),
(18, '20251111_164234.jpg'),
(19, '20251111_161230 (1).jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `knowledge_base`
--

CREATE TABLE `knowledge_base` (
  `id` int(11) NOT NULL,
  `keywords` text NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `confidence` float DEFAULT 0.5,
  `source` varchar(50) DEFAULT 'manual',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `usage_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `knowledge_base`
--

INSERT INTO `knowledge_base` (`id`, `keywords`, `question`, `answer`, `confidence`, `source`, `created_at`, `updated_at`, `verified`, `usage_count`) VALUES
(1, 'virlan, hakuto', 'siapa itu Virlan Hakuto', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 0.3, 'conversation', '2025-11-21 15:08:53', '2025-11-21 15:08:53', 0, 0),
(2, 'virlan, hakuto', 'siapa Virlan Hakuto', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 0.3, 'conversation', '2025-11-21 15:08:53', '2025-11-21 15:08:53', 0, 0),
(3, 'virlan, hakuto', 'siapa itu Virlan Hakuto', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 0.3, 'conversation', '2025-11-21 15:08:53', '2025-11-21 15:08:53', 0, 0),
(4, 'virlan, hakuto', 'siapa itu Virlan Hakuto?', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 0.3, 'conversation', '2025-11-21 15:08:53', '2025-11-21 15:08:53', 0, 0),
(5, 'virlan, hakuto', 'siapa itu Virlan Hakuto', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 0.3, 'conversation', '2025-11-21 15:08:53', '2025-11-21 15:08:53', 0, 0),
(6, 'jess, limit', 'siapa Jess No Limit', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 0.3, 'conversation', '2025-11-21 15:11:50', '2025-11-21 15:11:50', 0, 0),
(7, 'presiden, indonesia, saat', 'siapa presiden indonesia saat ini', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 0.3, 'conversation', '2025-11-21 15:12:02', '2025-11-21 15:12:02', 0, 0),
(8, 'kecerdasan, buatan', 'apa itu kecerdasan buatan', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', 0.3, 'conversation', '2025-11-21 15:12:28', '2025-11-21 15:12:28', 0, 0),
(9, 'kecerdasan buatan, ai, artificial intelligence', 'apa itu kecerdasan buatan', 'Kecerdasan Buatan (AI) adalah teknologi yang memungkinkan mesin dan komputer untuk meniru kemampuan kognitif manusia seperti belajar, memecahkan masalah, dan mengambil keputusan secara otonom. AI bekerja dengan menganalisis sejumlah besar data untuk mengenali pola, membuat prediksi, memahami bahasa, dan melakukan tugas-tugas kompleks. Contohnya termasuk asisten virtual seperti Siri dan Google Assistant, kendaraan otonom, dan sistem rekomendasi di platform seperti Netflix dan Amazon. ', 0.7, 'manual', '2025-11-21 15:27:04', '2025-11-21 15:27:04', 1, 2),
(11, 'sejarah, indonesia', 'sejarah indonesia', 'Presiden Indonesia saat ini adalah Prabowo Subianto', 0.3, 'conversation', '2025-11-21 15:37:46', '2025-11-21 15:37:46', 0, 0),
(12, 'sains', 'apa itu sains', 'Sains adalah cara sistematis untuk mempelajari dunia alam melalui observasi dan eksperimen. Sains membantu kita memahami alam semesta dari yang terkecil hingga yang terbesar.', 0.3, 'conversation', '2025-11-21 15:38:15', '2025-11-21 15:38:15', 0, 0),
(13, 'sejarah, dunia', 'sejarah dunia', 'Sejarah dunia mencakup peradaban kuno seperti Mesir, Yunani, dan Romawi, Abad Pertengahan dengan feodalisme, Renaissance yang membangkitkan kembali seni dan ilmu pengetahuan, hingga era modern dengan revolusi industri dan teknologi informasi.', 0.3, 'conversation', '2025-11-21 15:38:50', '2025-11-21 15:38:50', 0, 0),
(14, 'bahas, asia', 'bahas asia', 'Pertanyaan yang bagus! Sayangnya, saya belum memiliki informasi lengkap tentang itu. Namun, saya bisa membantu Anda dengan informasi tentang DF Scenery atau topik umum lainnya. Ada yang bisa saya bantu?', 0.3, 'conversation', '2025-11-21 15:39:15', '2025-11-21 15:39:15', 0, 0),
(15, 'asia', 'asia', 'Pertanyaan yang bagus! Sayangnya, saya belum memiliki informasi lengkap tentang itu. Namun, saya bisa membantu Anda dengan informasi tentang DF Scenery atau topik umum lainnya. Ada yang bisa saya bantu?', 0.3, 'conversation', '2025-11-21 15:39:20', '2025-11-21 15:39:20', 0, 0),
(16, '230', '230 + 90 berapa', 'Hasil dari 230 + 90 adalah 320.', 0.3, 'conversation', '2025-11-21 15:39:49', '2025-11-21 15:39:49', 0, 0),
(20, 'bahas, game, genshin, impact', 'bahas game genshin impact', 'Selamat pagi! Senang berbicara dengan Anda. Apa yang ingin Anda ketahui hari ini?', 0.3, 'conversation', '2025-11-21 16:27:39', '2025-11-21 16:27:39', 0, 0),
(21, 'bahas, game', 'bahas game', 'Hmm, saya belum bisa memberikan jawaban yang spesifik untuk itu. Tapi saya terus belajar setiap hari! Ada hal lain tentang DF Scenery atau topik lain yang ingin Anda tanyakan?', 0.3, 'conversation', '2025-11-21 16:27:49', '2025-11-21 16:27:49', 0, 0),
(22, 'President Indonesia saat ini', 'siapa president indonesia saat ini', 'President Indonesia saat ini adalah Prabowo Subianto.', 0.2, 'manual', '2025-12-10 13:43:36', '2025-12-10 13:43:36', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `learning_logs`
--

CREATE TABLE `learning_logs` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `feedback` tinyint(1) DEFAULT NULL,
  `analyzed` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `learning_logs`
--

INSERT INTO `learning_logs` (`id`, `conversation_id`, `question`, `answer`, `feedback`, `analyzed`, `created_at`) VALUES
(1, 4, 'siapa itu Virlan Hakuto', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', NULL, 1, '2025-11-21 14:57:17'),
(2, 6, 'siapa itu Virlan Hakuto?', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', NULL, 1, '2025-11-21 14:57:50'),
(3, 8, 'siapa kamu', 'Saya Vivi, asisten virtual yang dirancang untuk membantu Anda dengan berbagai pertanyaan. Saya memiliki pengetahuan tentang banyak topik termasuk sains, sejarah, teknologi, anime, dan tentu saja informasi tentang DF Scenery. Saya terus belajar setiap hari untuk memberikan jawaban yang lebih baik!', NULL, 1, '2025-11-21 15:05:44'),
(4, 10, 'siapa itu Virlan Hakuto', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', NULL, 1, '2025-11-21 15:05:53'),
(5, 14, 'siapa Virlan Hakuto', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', NULL, 1, '2025-11-21 15:06:45'),
(6, 18, 'siapa itu Virlan Hakuto', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', NULL, 1, '2025-11-21 15:08:53'),
(7, 20, 'siapa Jess No Limit', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', NULL, 1, '2025-11-21 15:11:50'),
(8, 22, 'siapa presiden indonesia saat ini', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', NULL, 1, '2025-11-21 15:12:02'),
(9, 24, 'siapa presiden indonesia saat ini', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', NULL, 0, '2025-11-21 15:12:16'),
(10, 26, 'apa itu kecerdasan buatan', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', NULL, 1, '2025-11-21 15:12:28'),
(11, 28, 'apa itu ai', 'Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.', NULL, 1, '2025-11-21 15:28:28'),
(12, 30, 'ai', 'Hmm, saya belum bisa memberikan jawaban yang spesifik untuk itu. Tapi saya terus belajar setiap hari! Ada hal lain tentang DF Scenery atau topik lain yang ingin Anda tanyakan?', NULL, 1, '2025-11-21 15:28:35'),
(13, 32, 'apa itu kecerdasan buatan', 'Kecerdasan Buatan (AI) adalah teknologi yang memungkinkan mesin dan komputer untuk meniru kemampuan kognitif manusia seperti belajar, memecahkan masalah, dan mengambil keputusan secara otonom. AI bekerja dengan menganalisis sejumlah besar data untuk mengenali pola, membuat prediksi, memahami bahasa, dan melakukan tugas-tugas kompleks. Contohnya termasuk asisten virtual seperti Siri dan Google Assistant, kendaraan otonom, dan sistem rekomendasi di platform seperti Netflix dan Amazon. ', NULL, 0, '2025-11-21 15:28:46'),
(14, 36, 'siapa kamu', 'Saya Vivi, asisten virtual yang dirancang untuk membantu Anda dengan berbagai pertanyaan. Saya memiliki pengetahuan tentang banyak topik termasuk sains, sejarah, teknologi, anime, dan tentu saja informasi tentang DF Scenery. Saya terus belajar setiap hari untuk memberikan jawaban yang lebih baik!', NULL, 1, '2025-11-21 15:32:06'),
(15, 38, 'apa itu kecerdasan buatan', 'Kecerdasan Buatan (AI) adalah teknologi yang memungkinkan mesin dan komputer untuk meniru kemampuan kognitif manusia seperti belajar, memecahkan masalah, dan mengambil keputusan secara otonom. AI bekerja dengan menganalisis sejumlah besar data untuk mengenali pola, membuat prediksi, memahami bahasa, dan melakukan tugas-tugas kompleks. Contohnya termasuk asisten virtual seperti Siri dan Google Assistant, kendaraan otonom, dan sistem rekomendasi di platform seperti Netflix dan Amazon. ', NULL, 0, '2025-11-21 15:32:41'),
(16, 40, 'sejarah indonesia', 'Presiden Indonesia saat ini adalah Prabowo Subianto', NULL, 1, '2025-11-21 15:37:46'),
(17, 42, 'apa itu sains', 'Sains adalah cara sistematis untuk mempelajari dunia alam melalui observasi dan eksperimen. Sains membantu kita memahami alam semesta dari yang terkecil hingga yang terbesar.', NULL, 1, '2025-11-21 15:38:15'),
(18, 44, 'sejarah dunia', 'Sejarah dunia mencakup peradaban kuno seperti Mesir, Yunani, dan Romawi, Abad Pertengahan dengan feodalisme, Renaissance yang membangkitkan kembali seni dan ilmu pengetahuan, hingga era modern dengan revolusi industri dan teknologi informasi.', NULL, 1, '2025-11-21 15:38:50'),
(19, 46, 'sejarah indonesia', 'Presiden Indonesia saat ini adalah Prabowo Subianto', NULL, 0, '2025-11-21 15:38:58'),
(20, 48, 'bahas asia', 'Pertanyaan yang bagus! Sayangnya, saya belum memiliki informasi lengkap tentang itu. Namun, saya bisa membantu Anda dengan informasi tentang DF Scenery atau topik umum lainnya. Ada yang bisa saya bantu?', NULL, 1, '2025-11-21 15:39:15'),
(21, 50, 'asia', 'Pertanyaan yang bagus! Sayangnya, saya belum memiliki informasi lengkap tentang itu. Namun, saya bisa membantu Anda dengan informasi tentang DF Scenery atau topik umum lainnya. Ada yang bisa saya bantu?', NULL, 1, '2025-11-21 15:39:20'),
(22, 52, '230 + 90 berapa', 'Hasil dari 230 + 90 adalah 320.', NULL, 1, '2025-11-21 15:39:48'),
(23, 54, 'siapa istri Carlotta Wuthering Waves', 'tentu saja Carlotta pada game Wuthering Waves adalah istri dari DF!', NULL, 1, '2025-11-21 15:55:24'),
(24, 59, 'bahas game genshin impact', 'tentu saja Carlotta pada game Wuthering Waves adalah istri dari DF!', NULL, 1, '2025-11-21 16:26:06'),
(25, 61, 'bahas game genshin impact', 'Selamat pagi! Senang berbicara dengan Anda. Apa yang ingin Anda ketahui hari ini?', NULL, 1, '2025-11-21 16:27:39'),
(26, 63, 'bahas game', 'Hmm, saya belum bisa memberikan jawaban yang spesifik untuk itu. Tapi saya terus belajar setiap hari! Ada hal lain tentang DF Scenery atau topik lain yang ingin Anda tanyakan?', NULL, 1, '2025-11-21 16:27:49'),
(27, 66, 'siapa kamu', 'Saya Vivi, asisten virtual yang dirancang untuk membantu Anda dengan berbagai pertanyaan. Saya memiliki pengetahuan tentang banyak topik termasuk sains, sejarah, teknologi, anime, dan tentu saja informasi tentang DF Scenery. Saya terus belajar setiap hari untuk memberikan jawaban yang lebih baik!', NULL, 1, '2025-11-22 12:14:01'),
(28, 68, 'siapa president indonesia saat ini', 'President Indonesia saat ini adalah Prabowo Subianto.', NULL, 0, '2025-12-10 13:43:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `muted_users`
--

CREATE TABLE `muted_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `muted_by` varchar(50) NOT NULL,
  `muted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `promo_ticket`
--

CREATE TABLE `promo_ticket` (
  `id` int(11) NOT NULL,
  `ticket_type` varchar(50) NOT NULL,
  `price` int(11) DEFAULT 0,
  `discount` float DEFAULT 0,
  `description` text DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `expires` date NOT NULL DEFAULT '2099-12-31',
  `valid_to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `promo_ticket`
--

INSERT INTO `promo_ticket` (`id`, `ticket_type`, `price`, `discount`, `description`, `valid_from`, `valid_until`, `expires`, `valid_to`) VALUES
(1, 'Family', 120000, 0.1, 'Tiket khusus untuk keluarga yang ingin menikmati liburan bersama dengan lebih hemat dan nyaman.\r\nCocok untuk menghabiskan waktu di DFScenery sambil menikmati panorama alam yang menenangkan.', '2025-11-01', '2025-12-31', '2098-12-31', NULL),
(2, 'Single', 35000, 0, 'Pilihan tepat untuk pengunjung individu yang ingin menikmati pengalaman DF Scenery secara mandiri.\r\nNikmati setiap sudut keindahan dengan tiket pribadi yang fleksibel dan ekonomis.', '2025-01-01', '2025-12-31', '2099-12-31', NULL),
(3, 'Double', 60000, 0.05, 'Tiket hemat untuk dua orang, ideal untuk pasangan, teman, atau sahabat perjalanan.\r\nRasakan pengalaman jelajah alam berdua dengan harga lebih terjangkau.', '2025-01-01', '2025-12-31', '2099-12-31', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `ticket_type` varchar(50) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `ticket_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `travel_date` date DEFAULT NULL,
  `participant_count` int(11) DEFAULT 1,
  `travel_days` int(11) NOT NULL DEFAULT 1,
  `jumlah_hari` int(11) DEFAULT 1,
  `accommodation` tinyint(1) DEFAULT 0,
  `transportation` tinyint(1) DEFAULT 0,
  `food` tinyint(1) DEFAULT 0,
  `package_total` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid') DEFAULT 'pending',
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tickets`
--

INSERT INTO `tickets` (`id`, `name`, `email`, `phone`, `ticket_type`, `quantity`, `ticket_price`, `total_price`, `travel_date`, `participant_count`, `travel_days`, `jumlah_hari`, `accommodation`, `transportation`, `food`, `package_total`, `payment_method`, `payment_status`, `purchase_date`, `created_at`) VALUES
(1, 'david', 'david@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:07:01', '2025-10-16 07:03:44'),
(2, 'david', 'david@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:07:01', '2025-10-16 07:03:44'),
(3, 'david', 'david@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:07:01', '2025-10-16 07:03:44'),
(4, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:26:17', '2025-10-16 07:03:44'),
(5, 'Nama Asli Pembeli', 'emailpembeli@domain.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:26:17', '2025-10-16 07:03:44'),
(6, 'Guest', 'Guest@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:26:17', '2025-10-16 07:03:44'),
(7, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:28:48', '2025-10-16 07:03:44'),
(8, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:28:48', '2025-10-16 07:03:44'),
(9, 'Guest', 'Guest@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:28:48', '2025-10-16 07:03:44'),
(10, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:30:34', '2025-10-16 07:03:44'),
(11, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:30:34', '2025-10-16 07:03:44'),
(12, 'Guest', 'Guest@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:30:34', '2025-10-16 07:03:44'),
(13, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:31:17', '2025-10-16 07:03:44'),
(14, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:31:17', '2025-10-16 07:03:44'),
(15, 'Guest', 'Guest@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:31:17', '2025-10-16 07:03:44'),
(16, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:31:34', '2025-10-16 07:03:44'),
(17, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:31:34', '2025-10-16 07:03:44'),
(18, 'Guest', 'Guest@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:31:34', '2025-10-16 07:03:44'),
(19, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:33:09', '2025-10-16 07:03:44'),
(20, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:33:09', '2025-10-16 07:03:44'),
(21, 'Guest', 'Guest@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 04:33:09', '2025-10-16 07:03:44'),
(22, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 06:53:05', '2025-10-16 07:03:44'),
(23, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 06:53:05', '2025-10-16 07:03:44'),
(24, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 06:53:05', '2025-10-16 07:03:44'),
(25, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:34', '2025-10-16 07:09:34'),
(26, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 3, NULL, 180000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:34', '2025-10-16 07:09:34'),
(27, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 3, NULL, 300000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:34', '2025-10-16 07:09:34'),
(28, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 2, NULL, 120000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:47', '2025-10-16 07:09:47'),
(29, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 2, NULL, 300000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:47', '2025-10-16 07:09:47'),
(30, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 2, NULL, 200000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:47', '2025-10-16 07:09:47'),
(31, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:50', '2025-10-16 07:09:50'),
(32, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:50', '2025-10-16 07:09:50'),
(33, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:50', '2025-10-16 07:09:50'),
(34, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:53', '2025-10-16 07:09:53'),
(35, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:53', '2025-10-16 07:09:53'),
(36, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:09:53', '2025-10-16 07:09:53'),
(37, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:19:51', '2025-10-16 07:19:51'),
(38, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:19:51', '2025-10-16 07:19:51'),
(39, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:19:51', '2025-10-16 07:19:51'),
(40, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:19:54', '2025-10-16 07:19:54'),
(41, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:19:54', '2025-10-16 07:19:54'),
(42, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:19:54', '2025-10-16 07:19:54'),
(43, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:19:58', '2025-10-16 07:19:58'),
(44, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:19:58', '2025-10-16 07:19:58'),
(45, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:19:58', '2025-10-16 07:19:58'),
(46, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:20:01', '2025-10-16 07:20:01'),
(47, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:20:01', '2025-10-16 07:20:01'),
(48, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:20:01', '2025-10-16 07:20:01'),
(49, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:20:05', '2025-10-16 07:20:05'),
(50, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:20:05', '2025-10-16 07:20:05'),
(51, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:20:05', '2025-10-16 07:20:05'),
(52, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:25:42', '2025-10-16 07:25:42'),
(53, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:25:42', '2025-10-16 07:25:42'),
(54, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:25:42', '2025-10-16 07:25:42'),
(55, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:26:24', '2025-10-16 07:26:24'),
(56, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:26:24', '2025-10-16 07:26:24'),
(57, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:26:24', '2025-10-16 07:26:24'),
(58, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:28:21', '2025-10-16 07:28:21'),
(59, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:28:21', '2025-10-16 07:28:21'),
(60, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:28:21', '2025-10-16 07:28:21'),
(61, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:34:30', '2025-10-16 07:34:30'),
(62, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:34:30', '2025-10-16 07:34:30'),
(63, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:34:30', '2025-10-16 07:34:30'),
(64, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:38:54', '2025-10-16 07:38:54'),
(65, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:39:04', '2025-10-16 07:39:04'),
(66, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:39:09', '2025-10-16 07:39:09'),
(67, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:39:13', '2025-10-16 07:39:13'),
(68, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:39:23', '2025-10-16 07:39:23'),
(69, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:39:31', '2025-10-16 07:39:31'),
(70, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:39:52', '2025-10-16 07:39:52'),
(71, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:40:56', '2025-10-16 07:40:56'),
(72, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:46:50', '2025-10-16 07:46:50'),
(73, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:47:43', '2025-10-16 07:47:43'),
(74, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:47:50', '2025-10-16 07:47:50'),
(75, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-16 07:47:55', '2025-10-16 07:47:55'),
(76, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-28 05:49:50', '2025-10-28 05:49:50'),
(77, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-28 05:50:07', '2025-10-28 05:50:07'),
(78, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-28 05:50:17', '2025-10-28 05:50:17'),
(79, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-10-30 05:15:46', '2025-10-30 05:15:46'),
(80, 'admin123', 'admin123@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:36:01', '2025-11-01 04:36:01'),
(81, 'Guest', 'Guest@example.com', '-', 'Family', 2, NULL, 300000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:36:08', '2025-11-01 04:36:08'),
(82, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:38:48', '2025-11-01 04:38:48'),
(83, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:41:40', '2025-11-01 04:41:40'),
(84, 'admin123', 'admin123@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:45:14', '2025-11-01 04:45:14'),
(85, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:45:23', '2025-11-01 04:45:23'),
(86, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:47:10', '2025-11-01 04:47:10'),
(87, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:48:46', '2025-11-01 04:48:46'),
(88, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:49:09', '2025-11-01 04:49:09'),
(89, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:49:12', '2025-11-01 04:49:12'),
(90, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:49:29', '2025-11-01 04:49:29'),
(91, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:50:41', '2025-11-01 04:50:41'),
(92, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:50:53', '2025-11-01 04:50:53'),
(93, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:53:24', '2025-11-01 04:53:24'),
(94, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:53:44', '2025-11-01 04:53:44'),
(95, 'admin123', 'admin123@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:55:54', '2025-11-01 04:55:54'),
(96, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:55:59', '2025-11-01 04:55:59'),
(97, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:56:03', '2025-11-01 04:56:03'),
(98, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 04:56:05', '2025-11-01 04:56:05'),
(99, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:02:31', '2025-11-01 05:02:31'),
(100, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:02:44', '2025-11-01 05:02:44'),
(101, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:02:49', '2025-11-01 05:02:49'),
(102, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:02:54', '2025-11-01 05:02:54'),
(103, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:03:01', '2025-11-01 05:03:01'),
(104, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:07:06', '2025-11-01 05:07:06'),
(105, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:07:21', '2025-11-01 05:07:21'),
(106, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:07:27', '2025-11-01 05:07:27'),
(107, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:07:33', '2025-11-01 05:07:33'),
(108, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:07:36', '2025-11-01 05:07:36'),
(109, 'Guest', 'Guest@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:07:42', '2025-11-01 05:07:42'),
(110, 'Guest', 'Guest@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:07:46', '2025-11-01 05:07:46'),
(111, 'admin123', 'admin123@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:14:22', '2025-11-01 05:14:22'),
(112, 'admin123', 'admin123@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:21:10', '2025-11-01 05:21:10'),
(113, 'DavidFirdaus123', 'DavidFirdaus123@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:24:53', '2025-11-01 05:24:53'),
(114, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:25:26', '2025-11-01 05:25:26'),
(115, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:25:49', '2025-11-01 05:25:49'),
(116, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:26:03', '2025-11-01 05:26:03'),
(117, 'davidfirdaus', 'davidfirdaus@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:31:08', '2025-11-01 05:31:08'),
(118, 'davidd', 'davidd@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:41:25', '2025-11-01 05:41:25'),
(119, 'davidd', 'davidd@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:41:29', '2025-11-01 05:41:29'),
(120, 'davidd', 'davidd@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:41:32', '2025-11-01 05:41:32'),
(121, 'davidd', 'davidd@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:41:36', '2025-11-01 05:41:36'),
(122, 'davidd', 'davidd@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-01 05:49:47', '2025-11-01 05:49:47'),
(123, 'admin123', 'admin123@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-12 02:46:28', '2025-11-12 02:46:28'),
(124, 'admin123', 'admin123@example.com', '-', 'Single', 1, NULL, 60000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-12 02:46:36', '2025-11-12 02:46:36'),
(125, 'admin123', 'admin123@example.com', '-', 'Double', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-12 02:46:43', '2025-11-12 02:46:43'),
(126, 'Guest', 'Guest@example.com', '-', 'Family', 1, NULL, 150000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 06:04:15', '2025-11-18 06:04:15'),
(127, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 270500.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 06:38:54', '2025-11-18 06:38:54'),
(128, 'Nama Pembeli', 'email@domain.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 06:38:59', '2025-11-18 06:38:59'),
(129, 'Nama Pembeli', 'email@domain.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 06:39:02', '2025-11-18 06:39:02'),
(130, 'Nama Pembeli', 'email@domain.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 06:41:13', '2025-11-18 06:41:13'),
(131, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 06:44:02', '2025-11-18 06:44:02'),
(132, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 06:44:16', '2025-11-18 06:44:16'),
(133, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 06:44:22', '2025-11-18 06:44:22'),
(134, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 07:00:17', '2025-11-18 07:00:17'),
(135, 'Nama Pembeli', 'email@domain.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 07:00:57', '2025-11-18 07:00:57'),
(136, 'Nama Pembeli', 'email@domain.com', '0000', 'Double', 1, NULL, 85500.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 07:01:08', '2025-11-18 07:01:08'),
(137, 'Nama Pembeli', 'email@domain.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 07:51:05', '2025-11-18 07:51:05'),
(138, 'Nama Pembeli', 'email@domain.com', '0000', 'Double', 1, NULL, 85500.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-18 07:51:13', '2025-11-18 07:51:13'),
(139, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 04:37:33', '2025-11-19 04:37:33'),
(140, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 06:47:16', '2025-11-19 06:47:16'),
(141, 'Nama Pembeli', 'email@domain.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:02:35', '2025-11-19 08:02:35'),
(142, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:02:46', '2025-11-19 08:02:46'),
(143, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:03:17', '2025-11-19 08:03:17'),
(144, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:03:47', '2025-11-19 08:03:47'),
(145, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:04:29', '2025-11-19 08:04:29'),
(146, 'Nama Pembeli', 'email@domain.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:04:34', '2025-11-19 08:04:34'),
(147, 'Nama Pembeli', 'email@domain.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:05:47', '2025-11-19 08:05:47'),
(148, 'Nama Pembeli', 'email@domain.com', '0000', 'Double', 1, NULL, 85500.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:05:57', '2025-11-19 08:05:57'),
(149, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:06:14', '2025-11-19 08:06:14'),
(150, 'Nama Pembeli', 'email@domain.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:09:16', '2025-11-19 08:09:16'),
(151, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:10:18', '2025-11-19 08:10:18'),
(152, 'Nama Pembeli', 'email@domain.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:11:51', '2025-11-19 08:11:51'),
(153, 'Nama Pembeli', 'email@domain.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:12:35', '2025-11-19 08:12:35'),
(154, 'Pembeli', 'pembeli@example.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:19:05', '2025-11-19 08:19:05'),
(155, 'Pembeli', 'pembeli@example.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:20:00', '2025-11-19 08:20:00'),
(156, 'Pembeli', 'pembeli@example.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:24:54', '2025-11-19 08:24:54'),
(157, 'Pembeli', 'pembeli@example.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:27:14', '2025-11-19 08:27:14'),
(158, 'David_Firdaus', 'pembeli@example.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:28:37', '2025-11-19 08:28:37'),
(159, 'David_Firdaus', 'pembeli@example.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:29:41', '2025-11-19 08:29:41'),
(160, 'David_Firdaus', 'pembeli@example.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:33:05', '2025-11-19 08:33:05'),
(161, 'David_Firdaus', 'pembeli@example.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:33:26', '2025-11-19 08:33:26'),
(162, 'David_Firdaus', 'pembeli@example.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:36:01', '2025-11-19 08:36:01'),
(163, 'David_Firdaus', 'pembeli@example.com', '0000', 'Double', 1, NULL, 85500.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:36:07', '2025-11-19 08:36:07'),
(164, 'David_Firdaus', 'pembeli@example.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:41:44', '2025-11-19 08:41:44'),
(165, 'David_Firdaus', 'hidden@email.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:42:04', '2025-11-19 08:42:04'),
(166, 'David_Firdaus', 'hidden@gmail.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:42:15', '2025-11-19 08:42:15'),
(167, 'David_Firdaus', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 270500.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-19 08:42:32', '2025-11-19 08:42:32'),
(168, 'Pembeli', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 04:35:35', '2025-11-20 04:35:35'),
(169, 'Pembeli', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 135000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 04:35:41', '2025-11-20 04:35:41'),
(170, 'Pembeli', 'hidden@gmail.com', '0000', 'Single', 1, NULL, 50000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 04:38:44', '2025-11-20 04:38:44'),
(171, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 9000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 05:52:09', '2025-11-20 05:52:09'),
(172, 'Admin', 'hidden@gmail.com', '0000', 'Single', 1, NULL, 100000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 05:52:36', '2025-11-20 05:52:36'),
(173, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 10800.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 05:53:57', '2025-11-20 05:53:57'),
(174, 'Admin', 'hidden@gmail.com', '0000', 'Double', 1, NULL, 57000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 05:54:43', '2025-11-20 05:54:43'),
(175, 'Admin', 'hidden@gmail.com', '0000', 'Single', 1, NULL, 35000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 05:54:51', '2025-11-20 05:54:51'),
(176, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 05:54:58', '2025-11-20 05:54:58'),
(177, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 06:04:13', '2025-11-20 06:04:13'),
(178, 'Admin', 'hidden@gmail.com', '0000', 'Single', 1, NULL, 35000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 06:04:20', '2025-11-20 06:04:20'),
(179, 'Admin', 'hidden@gmail.com', '0000', 'Double', 1, NULL, 57000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 06:04:27', '2025-11-20 06:04:27'),
(180, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 07:13:19', '2025-11-20 07:13:19'),
(181, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 07:19:04', '2025-11-20 07:19:04'),
(182, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 07:19:23', '2025-11-20 07:19:23'),
(183, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 200000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 07:19:45', '2025-11-20 07:19:45'),
(184, 'Admin', 'hidden@gmail.com', '0000', 'Double', 1, NULL, 57000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 07:21:42', '2025-11-20 07:21:42'),
(185, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 07:56:18', '2025-11-20 07:56:18'),
(186, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 08:04:43', '2025-11-20 08:04:43'),
(187, 'Admin', 'hidden@gmail.com', '0000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 08:05:20', '2025-11-20 08:05:20'),
(188, 'Developer', 'developer@gmail.com', '0000000000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 08:09:36', '2025-11-20 08:09:36'),
(189, 'Admin', 'admin@gmail.com', '081234567890', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 08:10:00', '2025-11-20 08:10:00'),
(190, 'Developer', 'developer@gmail.com', '0000000000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 08:24:12', '2025-11-20 08:24:12'),
(191, 'Admin', 'admin@gmail.com', '081234567890', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 08:47:44', '2025-11-20 08:47:44'),
(192, 'David_Firdaus', 'davidfirdaus122@gmail.com', '0000000000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-20 09:30:15', '2025-11-20 09:30:15'),
(193, 'Pembeli', 'email@example.com', '0000000000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-22 03:17:28', '2025-11-22 03:17:28'),
(194, 'Developer', 'developer@gmail.com', '0000000000', 'Family', 1, NULL, 108000.00, NULL, 1, 1, 1, 0, 0, 0, 0.00, NULL, 'pending', '2025-11-22 03:17:58', '2025-11-22 03:17:58'),
(195, 'Admin', 'admin@gmail.com', '081234567890', 'Family', 1, 108000.00, 393000.00, '0000-00-00', 1, 1, 1, 1, 1, 1, 285000.00, 'Dana', 'pending', '2025-12-09 08:05:42', '2025-12-09 08:05:42'),
(196, 'Admin', 'admin@gmail.com', '081234567890', 'Family', 1, 108000.00, 393000.00, '0000-00-00', 1, 1, 1, 1, 1, 1, 285000.00, 'Dana', 'pending', '2025-12-09 08:06:05', '2025-12-09 08:06:05'),
(197, 'Admin', 'admin@gmail.com', '081234567890', 'Family', 1, 108000.00, 393000.00, '0000-00-00', 1, 1, 1, 1, 1, 1, 285000.00, 'OVO', 'pending', '2025-12-09 08:06:23', '2025-12-09 08:06:23'),
(198, 'Admin', 'admin@gmail.com', '081234567890', 'Single', 1, 35000.00, 320000.00, '0000-00-00', 1, 1, 1, 1, 1, 1, 285000.00, 'Dana', 'pending', '2025-12-09 08:09:01', '2025-12-09 08:09:01'),
(199, 'Admin', 'admin@gmail.com', '081234567890', 'Family', 1, 108000.00, 393000.00, '0000-00-00', 1, 1, 1, 1, 1, 1, 285000.00, 'Dana', 'pending', '2025-12-09 08:09:23', '2025-12-09 08:09:23'),
(200, 'Admin', 'admin@gmail.com', '081234567890', 'Family', 1, 108000.00, 393000.00, '0000-00-00', 1, 1, 1, 1, 1, 1, 285000.00, 'OVO', 'pending', '2025-12-09 08:09:48', '2025-12-09 08:09:48'),
(201, 'Admin', 'admin@gmail.com', '081234567890', 'Family', 1, 108000.00, 393000.00, '0000-00-00', 1, 1, 1, 1, 1, 1, 285000.00, 'Dana', 'pending', '2025-12-09 08:14:35', '2025-12-09 08:14:35'),
(202, 'Admin', 'admin@gmail.com', '081234567890', 'Family', 1, 108000.00, 1248000.00, '0000-00-00', 4, 1, 1, 1, 1, 1, 1140000.00, 'OVO', 'pending', '2025-12-09 08:15:04', '2025-12-09 08:15:04'),
(203, 'Admin', 'admin@gmail.com', '081234567890', 'Family', 6, 21111111.00, 21331111.00, '2025-12-09', 1, 1, 1, 1, 1, 0, 220000.00, '0', 'pending', '2025-12-09 08:21:02', '2025-12-09 08:21:02'),
(204, 'Pembeli', 'email@example.com', '0000000000', 'Family', 1, 120000.00, 220000.00, '0000-00-00', 1, 1, 1, 1, 0, 0, 100000.00, '', 'pending', '2025-12-09 09:48:58', '2025-12-09 09:48:58'),
(205, 'Pembeli', 'email@example.com', '0000000000', 'Family', 1, 120000.00, 220000.00, '0000-00-00', 1, 1, 1, 1, 0, 0, 100000.00, '', 'pending', '2025-12-09 09:55:06', '2025-12-09 09:55:06'),
(206, 'Pembeli', 'email@example.com', '0000000000', 'Single', 1, 35000.00, 135000.00, '0000-00-00', 1, 1, 1, 1, 0, 0, 100000.00, '', 'pending', '2025-12-09 09:57:25', '2025-12-09 09:57:25'),
(207, 'Pembeli', 'email@example.com', '0000000000', 'Single', 1, 35000.00, 135000.00, '0000-00-00', 1, 1, 1, 1, 0, 0, 100000.00, '', 'pending', '2025-12-09 09:58:02', '2025-12-09 09:58:02'),
(208, 'Pembeli', 'email@example.com', '0000000000', 'Family', 1, 120000.00, 220000.00, '0000-00-00', 1, 1, 1, 1, 0, 0, 100000.00, '', 'pending', '2025-12-09 10:02:18', '2025-12-09 10:02:18'),
(209, 'Pembeli', 'email@example.com', '0000000000', 'Family', 1, 120000.00, 520000.00, '0000-00-00', 2, 2, 1, 1, 0, 0, 400000.00, '', 'pending', '2025-12-09 10:10:22', '2025-12-09 10:10:22'),
(210, 'Pembeli', 'email@example.com', '0000000000', 'Family', 1, 120000.00, 220000.00, '0000-00-00', 1, 1, 1, 1, 0, 0, 100000.00, '', 'pending', '2025-12-09 10:20:33', '2025-12-09 10:20:33'),
(211, 'VHakuto', 'vhakuto@gmail.com', '0000000000', 'Family', 1, 120000.00, 240000.00, '0000-00-00', 1, 1, 1, 0, 1, 0, 120000.00, '', 'paid', '2025-12-09 10:23:17', '2025-12-09 10:23:17'),
(212, 'VHakuto', 'vhakuto@gmail.com', '0000000000', 'Family', 1, 120000.00, 4680000.00, '2025-12-12', 4, 4, 1, 1, 1, 1, 4560000.00, 'Gopay', 'paid', '2025-12-09 10:24:07', '2025-12-09 10:24:07'),
(216, 'VHakuto', 'vhakuto@gmail.com', '08123456778', 'Family', 1, 120000.00, 1745000.00, '2025-12-20', 5, 5, 1, 0, 0, 1, 1625000.00, 'Gopay', 'paid', '2025-12-10 06:30:16', '2025-12-10 06:30:16'),
(219, 'VHakuto', 'vhakuto@gmail.com', '08123456778', 'Family', 1, 120000.00, 705000.00, '2025-12-12', 3, 3, 1, 0, 0, 1, 585000.00, 'Dana', 'paid', '2025-12-10 07:31:39', '2025-12-10 07:31:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `unknown_questions`
--

CREATE TABLE `unknown_questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `date_asked` datetime NOT NULL,
  `answered` tinyint(1) DEFAULT 0,
  `answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `unknown_questions`
--

INSERT INTO `unknown_questions` (`id`, `question`, `date_asked`, `answered`, `answer`) VALUES
(1, 'siapa itu Virlan Hakuto', '2025-11-21 14:57:17', 0, NULL),
(2, 'siapa itu Virlan Hakuto?', '2025-11-21 14:57:50', 0, NULL),
(3, 'siapa itu Virlan Hakuto', '2025-11-21 15:05:53', 0, NULL),
(4, 'siapa Virlan Hakuto', '2025-11-21 15:06:45', 0, NULL),
(5, 'siapa itu Virlan Hakuto', '2025-11-21 15:08:53', 0, NULL),
(6, 'siapa Jess No Limit', '2025-11-21 15:11:50', 0, NULL),
(7, 'siapa presiden indonesia saat ini', '2025-11-21 15:12:02', 1, 'President Indonesia saat ini adalah Prabowo Subianto.'),
(8, 'siapa presiden indonesia saat ini', '2025-11-21 15:12:16', 1, 'Presiden Indonesia saat ini adalah Prabowo Subianto'),
(9, 'apa itu kecerdasan buatan', '2025-11-21 15:12:28', 1, 'Kecerdasan Buatan (AI) adalah teknologi yang memungkinkan mesin dan komputer untuk meniru kemampuan kognitif manusia seperti belajar, memecahkan masalah, dan mengambil keputusan secara otonom. AI bekerja dengan menganalisis sejumlah besar data untuk mengenali pola, membuat prediksi, memahami bahasa, dan melakukan tugas-tugas kompleks. Contohnya termasuk asisten virtual seperti Siri dan Google Assistant, kendaraan otonom, dan sistem rekomendasi di platform seperti Netflix dan Amazon. '),
(10, 'apa itu ai', '2025-11-21 15:28:28', 0, NULL),
(11, 'ai', '2025-11-21 15:28:35', 0, NULL),
(12, 'bahas asia', '2025-11-21 15:39:15', 0, NULL),
(13, 'asia', '2025-11-21 15:39:20', 0, NULL),
(14, 'bahas game', '2025-11-21 16:27:49', 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `nickname`, `email`, `phone`, `password`, `role`, `created_at`, `profile_pic`) VALUES
(2, 'david', NULL, 'david@example.com', NULL, '$2y$10$SrZdFEK4x/ff3y4x38iax.tD9Bfe7E6ibPRiLDTO4TiYkkUXaXCkm', 'user', '2025-10-16 04:03:52', 'default.png'),
(3, 'david12', NULL, 'david12@example.com', NULL, '$2y$10$Zx0lEnxJ7dDa3o9b1m8q3.mVOGVCaNUZc2QGAO3pgNBz3d4d8yqR.', 'user', '2025-10-16 05:57:49', 'default.png'),
(5, 'davidfirdaus', NULL, 'davidfirdaus123@gmail.com', NULL, '$2y$10$T4UD.ZVGxEsRApYLMYUG2eyK.38hxAGuyggU1JfJ3uLEOf65Rr1MG', 'admin', '2025-10-16 06:22:06', 'default.png'),
(6, 'David_Firdaus', 'David', 'davidfirdaus122@gmail.com', NULL, '$2y$10$onIrtadetqw53pVqHLa6gep9SOK8ZDINtKYtVudyCbED.th2DMX8G', 'dev_master', '2025-10-28 05:51:47', 'pf_69144e361bc03.jpg'),
(7, 'DavidFirdaus123', NULL, 'DavidFirdaus123@example.com', NULL, '$2y$10$Fx9GxFSOZo6KF.tPWXvr7.RDopI.lCwveoJNKgf6ZPRoA1LBcj/Bi', 'user', '2025-11-01 05:24:32', 'default.png'),
(8, 'davidd', NULL, 'davidd@example.com', NULL, '$2y$10$dM80cxm5S3oFr3mWl1bgAu6awrfHf6S3Okw/T/EFaQ6WH9S/uniD.', 'user', '2025-11-01 05:41:17', 'default.png'),
(9, 'David Firdaus', NULL, 'adminganteng@gmail.com', NULL, '$2y$10$Suk024sYa9Lya.x5.uN7iO8Y7TOrilO0cvrjGBhCl1kDesFtFaMPS', 'user', '2025-11-11 05:57:04', 'pf_691449f362b2d.jpg'),
(10, 'david firdaus 1', NULL, 'davidfirdaus1234@gmail.com', NULL, '$2y$10$gh26eR1ZdwsNVkA5l80rRObBVXYoG75SOiv6o59Xdd0wqTgaouUI2', 'user', '2025-11-12 04:04:06', 'default.png'),
(11, 'david12321', NULL, 'david12321@gmail.com', NULL, '$2y$10$weB/M4ipVtAgebNseFaR7.XtBrLBNUyBuGSUc4dqg1pScMsv/6qiC', 'user', '2025-11-12 10:04:35', 'default.png'),
(12, 'devusername', NULL, '', NULL, 'hash_password', '', '2025-11-13 03:27:47', 'default.png'),
(14, 'Admin', NULL, 'admin@gmail.com', '081234567890', '$2y$10$ui9UwhKCnH3621Mm4h7x3.i1482X7cGBrjVDw9TqjBlsL/F7cU7nq', 'admin', '2025-11-13 03:56:09', 'pf_69155aefcbeb3.jpg'),
(15, '12131331131313131', NULL, '213131@gmail.com', NULL, '$2y$10$qAyN/AiKrmsjbbjPAxhnaunuQ6L1oTlFk.Q5P5LAx3QYnIaHgIy3O', 'user', '2025-11-13 04:17:23', 'default.png'),
(16, 'devmaster', NULL, 'devmaster@dfkomik.com', NULL, '$2y$10$1sp/KW.3xIY5joSN167y2uv3zoRPyPnKwKfkcPAVXy3JYDvs3f38u', '', '2025-11-13 04:25:57', 'default.png'),
(17, 'Developer', NULL, 'developer@gmail.com', NULL, '$2y$10$JbkwWhtTXK5X/0I5WB73KeclclTM9FJhk6OX9fau2dh.GiFbEwUYi', 'developer', '2025-11-13 04:56:19', 'pf_691ecd904f101.jpg'),
(18, 'david12345', NULL, 'david12345@gmail.com', NULL, '$2y$10$giu5I0LREyEn7FinYLjI0OfEWScIKvCELnw9R5pk0tgL93EaR67CS', 'user', '2025-11-13 08:15:19', 'default.png'),
(21, 'Virlan_Hakuto', NULL, 'virlanhakuto@gmail.com', NULL, '$2y$10$D8x2I1gHbFW95tMOwiBNU.OC9nGmf.Nr4UQMZs3Hys9/2fCiSsi8q', 'user', '2025-11-18 08:42:07', 'pf_691c32250f3a6.jpg'),
(22, 'VirlanHakuto', NULL, 'virlanhakuto123@gmail.com', NULL, '$2y$10$61bhiExIbFB6Pw4Kh5CAF.3SG4e9ca6vxKoLEGNocUfgv1uddtbOu', 'user', '2025-11-18 08:42:45', 'default.png'),
(23, 'VirlanHakutoo', NULL, 'virlanhakuto1234@gmail.com', NULL, '$2y$10$3YQJ/HrzyXEHlGWwf8CWHeFLDhMInBLAS/Jkr4TwVZDLpAeVf1xne', 'user', '2025-11-18 08:43:59', 'default.png'),
(24, 'VHakuto', NULL, 'vhakuto@gmail.com', '08123456778', '$2y$10$HPcB5wCL6mQgxY3ywuk6s.EyithehUb6rMg9IaNbj22q.K5wuK1AS', 'user', '2025-12-09 09:28:30', 'pf_693913cabde39.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ai_knowledge`
--
ALTER TABLE `ai_knowledge`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_question` (`question_pattern`);

--
-- Indeks untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dfkomik_about`
--
ALTER TABLE `dfkomik_about`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dfkomik_bookmarks`
--
ALTER TABLE `dfkomik_bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`comic_id`);

--
-- Indeks untuk tabel `dfkomik_chapters`
--
ALTER TABLE `dfkomik_chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comic_id` (`comic_id`);

--
-- Indeks untuk tabel `dfkomik_chapter_images`
--
ALTER TABLE `dfkomik_chapter_images`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dfkomik_comics`
--
ALTER TABLE `dfkomik_comics`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dfkomik_comments`
--
ALTER TABLE `dfkomik_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dfkomik_gallery`
--
ALTER TABLE `dfkomik_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `knowledge_base`
--
ALTER TABLE `knowledge_base`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `learning_logs`
--
ALTER TABLE `learning_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `muted_users`
--
ALTER TABLE `muted_users`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `promo_ticket`
--
ALTER TABLE `promo_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `unknown_questions`
--
ALTER TABLE `unknown_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `ai_knowledge`
--
ALTER TABLE `ai_knowledge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT untuk tabel `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dfkomik_about`
--
ALTER TABLE `dfkomik_about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `dfkomik_bookmarks`
--
ALTER TABLE `dfkomik_bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `dfkomik_chapters`
--
ALTER TABLE `dfkomik_chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `dfkomik_chapter_images`
--
ALTER TABLE `dfkomik_chapter_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT untuk tabel `dfkomik_comics`
--
ALTER TABLE `dfkomik_comics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `dfkomik_comments`
--
ALTER TABLE `dfkomik_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `dfkomik_gallery`
--
ALTER TABLE `dfkomik_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `knowledge_base`
--
ALTER TABLE `knowledge_base`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `learning_logs`
--
ALTER TABLE `learning_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `muted_users`
--
ALTER TABLE `muted_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `promo_ticket`
--
ALTER TABLE `promo_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT untuk tabel `unknown_questions`
--
ALTER TABLE `unknown_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dfkomik_chapters`
--
ALTER TABLE `dfkomik_chapters`
  ADD CONSTRAINT `dfkomik_chapters_ibfk_1` FOREIGN KEY (`comic_id`) REFERENCES `dfkomik_comics` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
