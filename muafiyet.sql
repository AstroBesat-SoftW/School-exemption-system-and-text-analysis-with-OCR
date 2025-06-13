-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 13 Haz 2025, 16:32:04
-- Sunucu sürümü: 10.4.27-MariaDB
-- PHP Sürümü: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `muafiyet`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bolumler`
--

CREATE TABLE `bolumler` (
  `id` int(11) NOT NULL,
  `bolum_ad` varchar(255) NOT NULL,
  `universite_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `bolumler`
--

INSERT INTO `bolumler` (`id`, `bolum_ad`, `universite_id`) VALUES
(7, 'Elektrik Mühendisliği', 4),
(9, 'İnşaat Mühendisliği', 4),
(10, 'Mekatronik Mühendisliği', 4),
(11, 'Makine Mühendisliği', 4),
(27, 'Elektrik-Elektronik Mühendisliği', 5),
(28, 'Makine Mühendisliği', 5),
(29, 'Bilgisayar Mühendisliği', 5),
(67, 'Ziraat Mühendisliği', 7),
(83, 'Bilgisayar Mühendisliği', 7),
(100, 'Ziraat Fakültesi', 9),
(103, 'Biyomedikal Mühendisliği', 9),
(105, 'Elektrik-Elektronik', 9),
(106, 'Makine Mühendisliği', 9);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `dersler`
--

CREATE TABLE `dersler` (
  `id` int(11) NOT NULL,
  `ders_kodu` varchar(50) NOT NULL,
  `ders_adi` varchar(255) NOT NULL,
  `ders_akts` int(11) NOT NULL,
  `ders_Teorik_saati` int(11) NOT NULL,
  `ders_uygulama_saati` int(11) NOT NULL,
  `bolum_id` int(11) NOT NULL,
  `ders_icerigi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `dersler`
--

INSERT INTO `dersler` (`id`, `ders_kodu`, `ders_adi`, `ders_akts`, `ders_Teorik_saati`, `ders_uygulama_saati`, `bolum_id`, `ders_icerigi`) VALUES
(9, 'EE201', 'Elektrik Devreleri', 5, 3, 2, 7, '1'),
(11, 'INS101', 'Betonarme Yapılar', 6, 3, 2, 9, '0'),
(12, 'MEK301', 'Mekatronik Sistemler', 5, 3, 2, 10, '1'),
(13, 'MM101', 'Akışkanlar Mekaniği', 6, 3, 2, 11, '1'),
(17, 'ELE401', 'Mikroelektronik', 6, 3, 2, 27, '1'),
(18, 'ME202', 'Isı Transferi', 6, 3, 2, 28, '1'),
(19, 'CS303', 'Yapay Zeka', 6, 3, 2, 29, '0'),
(25, 'ZRM101', 'Bitki Yetiştiriciliği', 5, 3, 2, 67, '1'),
(37, 'ZRA101', 'Tarla Bitkileri II', 6, 3, 2, 100, '1'),
(39, 'BLM101', 'Algoritma ve Programlama', 6, 3, 2, 83, '1'),
(40, 'BLM102', 'Bilgisayar Donanımı', 5, 3, 2, 83, '0'),
(41, 'BLM103', 'Ayrık Matematik', 5, 3, 0, 83, '0'),
(42, 'BLM104', 'Lineer Cebir', 5, 3, 0, 83, '0'),
(43, 'BLM201', 'Veri Yapıları ve Algoritmalar', 6, 3, 2, 83, '0'),
(44, 'BLM202', 'Nesne Yönelimli Programlama', 6, 3, 2, 83, '1'),
(45, 'BLM203', 'Web Programlama', 6, 3, 2, 83, '1'),
(47, 'BLM301', 'İşletim Sistemleri', 6, 3, 2, 83, '0'),
(48, 'BLM302', 'Bilgisayar Ağları', 6, 3, 2, 83, '1'),
(105, 'CS104', 'C Programlamaya Giriş', 6, 3, 4, 83, '1'),
(108, 'CS201', 'Algoritmalar ve Veri Yapıları', 6, 3, 4, 29, '1'),
(109, 'CS202', 'Yazılım Mühendisliği', 6, 3, 4, 29, '1'),
(110, 'CS203', 'Dijital Sistemler', 6, 3, 4, 29, '1'),
(111, 'CS204', 'Matematiksel Mantık', 6, 3, 4, 29, '1'),
(112, 'CS205', 'Veri Bilimi ve İstatistik', 6, 3, 4, 29, '1'),
(113, 'CS206', 'Mobil Uygulama Geliştirme', 6, 3, 4, 29, '1');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `muaf_ogrenci`
--

CREATE TABLE `muaf_ogrenci` (
  `id` int(11) NOT NULL,
  `isim` varchar(100) NOT NULL,
  `soyisim` varchar(100) NOT NULL,
  `tc` varchar(11) NOT NULL,
  `telefon` varchar(15) NOT NULL,
  `geldigi_okul` varchar(255) NOT NULL,
  `geldigi_bolum` varchar(255) NOT NULL,
  `gittigi_okul` varchar(255) NOT NULL,
  `gittigi_bolum` varchar(255) NOT NULL,
  `tarih` date NOT NULL,
  `muaf_gorsel` varchar(255) NOT NULL,
  `transkript` varchar(255) NOT NULL,
  `ders_icerigi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `muaf_ogrenci`
--

INSERT INTO `muaf_ogrenci` (`id`, `isim`, `soyisim`, `tc`, `telefon`, `geldigi_okul`, `geldigi_bolum`, `gittigi_okul`, `gittigi_bolum`, `tarih`, `muaf_gorsel`, `transkript`, `ders_icerigi`) VALUES
(7, 'Ahmet', 'Tampınar', '41563452254', '5345345345', 'ODTÜ', 'Bilgisayar Mühendisliği', 'Namık Kemal Üniversitesi', 'Bilgisayar Mühendisliği', '2025-01-07', '41563452254_tablo_gorsel.png', '41563452254_transkript.pdf', '41563452254_icerikler.pdf'),
(8, 'Elif', 'Karasu', '2423545', '32423534', 'ODTÜ', 'Bilgisayar Mühendisliği', 'Namık Kemal Üniversitesi', 'Bilgisayar Mühendisliği', '2025-01-07', '2423545_tablo_gorsel.png', '2423545_transkript.pdf', '2423545_icerikler.pdf'),
(9, 'Ceylin', 'Karasu', '12324213423', '1231', 'Boğaziçi Üniversitesi', 'Elektrik Mühendisliği', 'Namık Kemal Üniversitesi', 'Ziraat Mühendisliği', '2025-01-08', '12324213423_tablo_gorsel.png', '12324213423_transkript.pdf', '12324213423_icerikler.pdf'),
(11, 'Cansu', 'Lenovo', '12345678911', '12345678911', 'ODTÜ', 'Elektrik-Elektronik Mühendisliği', 'Namık Kemal Üniversitesi', 'Bilgisayar Mühendisliği', '2025-01-08', '12345678911_tablo_gorsel.png', '12345678911_transkript.pdf', '12345678911_icerikler.pdf');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `universiteler`
--

CREATE TABLE `universiteler` (
  `id` int(11) NOT NULL,
  `universite_ad` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `universiteler`
--

INSERT INTO `universiteler` (`id`, `universite_ad`) VALUES
(4, 'Boğaziçi Üniversitesi'),
(5, 'ODTÜ'),
(7, 'Namık Kemal Üniversitesi'),
(8, 'İstanbul Üniversitesi'),
(9, 'Ege Üniversitesi');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yonetici`
--

CREATE TABLE `yonetici` (
  `id` int(11) NOT NULL,
  `yonetici_tc` varchar(11) NOT NULL,
  `yonetici_sifre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `yonetici`
--

INSERT INTO `yonetici` (`id`, `yonetici_tc`, `yonetici_sifre`) VALUES
(1, '123', '123');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `bolumler`
--
ALTER TABLE `bolumler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `universite_id` (`universite_id`);

--
-- Tablo için indeksler `dersler`
--
ALTER TABLE `dersler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bolum_id` (`bolum_id`);

--
-- Tablo için indeksler `muaf_ogrenci`
--
ALTER TABLE `muaf_ogrenci`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tc` (`tc`);

--
-- Tablo için indeksler `universiteler`
--
ALTER TABLE `universiteler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `yonetici`
--
ALTER TABLE `yonetici`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `bolumler`
--
ALTER TABLE `bolumler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- Tablo için AUTO_INCREMENT değeri `dersler`
--
ALTER TABLE `dersler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- Tablo için AUTO_INCREMENT değeri `muaf_ogrenci`
--
ALTER TABLE `muaf_ogrenci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- Tablo için AUTO_INCREMENT değeri `universiteler`
--
ALTER TABLE `universiteler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `yonetici`
--
ALTER TABLE `yonetici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `bolumler`
--
ALTER TABLE `bolumler`
  ADD CONSTRAINT `bolumler_ibfk_1` FOREIGN KEY (`universite_id`) REFERENCES `universiteler` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `dersler`
--
ALTER TABLE `dersler`
  ADD CONSTRAINT `dersler_ibfk_1` FOREIGN KEY (`bolum_id`) REFERENCES `bolumler` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
