-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 16, 2025 at 08:46 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lsank_import`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `seniority` int(11) NOT NULL,
  `cpf` int(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `designation` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `avatar` varchar(55) DEFAULT '''default.png''',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `seniority`, `cpf`, `name`, `designation`, `email`, `phone`, `description`, `avatar`, `status`, `email_verified_at`, `password`, `remember_token`) VALUES
(1, 5, 46886, 'Chandra Mohan Sinku', NULL, 'sinku_cm@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$jdcH7FNcIYTyVDZQUWQu7eyPVC0D3QfLqhVS2fkMVZU9zRwksfCIu', NULL),
(2, 2, 78570, 'Hemraj Jarwal', NULL, 'jarwal_hemraj@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$7t.Fy5zya/i5LelqNOqZ1O7lDEGMBMzUjhOnfLGXUso8FvcofP6PG', NULL),
(3, 1, 79595, 'BHANU PRATAP SINGH', 'Chief General Manager-Head Logging', 'vishu2002@gmail.com', '9410390960', NULL, 'default.jpg', 1, NULL, '$2y$10$ObqEIrbqOGexP/iJQHsH2eAtzStdN7MIrP5H91amQIAi6/2QOoBR6', NULL),
(4, 43, 80913, 'MUNVVAR ABDULBHAI RAJ', NULL, 'raj_abdulbhaimunvyar@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$9aZertDnDilLtsjEk7wkC.Ca5LtkQ15n1gnas8rWSUkByWjsenjf6', NULL),
(5, 40, 82696, 'J B Patel', 'Operator(Winch)', 'patel_jb5@ongc.co.in', '9429898330', NULL, 'IMG-82696.jpeg', 1, NULL, '$2y$10$8hZSMracD61de.zhUfHmheIZvVk8bq8FSJHkmA1w3b4PA09AlNN/C', NULL),
(6, 44, 96876, 'ARUN SUWARNA', 'JMVD', 'SUWARNA_ARUN@ONGC.CO.IN', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$9aZertDnDilLtsjEk7wkC.Ca5LtkQ15n1gnas8rWSUkByWjsenjf6', NULL),
(7, 7, 105058, 'CHANDRASHEKAR T', NULL, 'go2shekar@gmail.com', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$pbSewKoxt4iXthfE4KJV5OX0WpzdVnGWInAZ0mcBJCLwSoAi9lCGS', NULL),
(8, 10, 121653, 'LOVA RAJU', NULL, 'GUDIMETLA_LOVA@ONGC.CO.IN', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$OeIQ850HQPc6TwSad/CObOo4FBgQ0dVvxKrEobqhwbeoX2GIA5tR6', NULL),
(9, 12, 121804, 'MINTU KUMAR', NULL, 'kumar_mintu@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$FaVpmjZRBjtexNmVpwtUxeQpRkK77B41iaV3MnVhIiDPT6jGYujwa', NULL),
(10, 14, 122501, 'AMIT KUMAR KESHARI', 'SG(W)', 'keshari_amitkumar@ongc.co.in', '', NULL, 'default.jpg', 1, NULL, '$2y$10$PtojzIS4NLicV5NQo7DLGelrg7t5TX4P8uWIzC3KUZzF.n/wNz1Ni', NULL),
(11, 15, 122564, 'V. B. TIWARI', 'SG(W)', 'tiwari_vb@ongc.co.in', NULL, NULL, 'default.jpg', 0, NULL, '$2y$10$9aZertDnDilLtsjEk7wkC.Ca5LtkQ15n1gnas8rWSUkByWjsenjf6', NULL),
(12, 16, 124500, 'PRASHANT MISHRA', 'SG (W)', 'MISHRA_PRASHANT@ONGC.CO.IN', NULL, NULL, 'default.jpg', 0, NULL, '$2y$10$jqGQbPKmhjPqu2ZZlWEjIOVygVOMLjeCoI.jEq68.cH4/3vXPUH1O', NULL),
(13, 42, 126010, 'MAYUR J RATHOD', NULL, 'rathod_j_mayur@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$uDt8KZISI2T0WQSr/bB6EusGjDpJaGcu74wnBhO/y.PID5dqG/bfm', NULL),
(14, 46, 131541, 'TOFIK', 'MVD', 'TOFIK@ONGC.CO.IN', '9409304985', NULL, 'default.jpg', 1, NULL, '$2y$10$9aZertDnDilLtsjEk7wkC.Ca5LtkQ15n1gnas8rWSUkByWjsenjf6', NULL),
(15, 49, 132765, 'VIKASKUMAR CHALALIA', 'MVD HVO', 'chalalia_vikash@ongc.co.in', '9409304084', NULL, 'default.jpg', 1, NULL, '$2y$10$Md4bFhXF5F7WSHkwKbmWKep2gNanAT4hXxSuyTurvJ50qWPL5anPS', NULL),
(16, 50, 133156, 'RAHULSINH PARMAR', 'MVD', 'Parmar_Rahulsinh@ongc.co.in', '7574834272', NULL, 'default.jpg', 1, NULL, '$2y$10$ZOiknV8vXoPZ29K563oT3e02n454/C8VJFOwzXoCdNuQ5o1oUlPjq', NULL),
(17, 20, 133490, 'Somnath Sarkar', 'Senior Geophysicist (W)', 'sarkar_somnath@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$4hRufFM1Sz1efUiv0.CzK.ZOfHCzBdB8hegQ73vd/ces8V0H1jLPK', NULL),
(18, 19, 133526, 'ANIRBAN KARMAKAR', 'Sr. Geophysicist (Wells)', 'KARMAKAR_ANIRBAN@ONGC.CO.IN', '9409304569', NULL, 'default.jpg', 1, NULL, '$2y$10$TvgL1VcyrsIF9TCqKJP1muxjiPJ852XPdv6nM/FYKppQDN5zMQPwe', NULL),
(19, 21, 134063, 'Dheeraj Kumar', 'Senior Geophysicist (W)', 'kumar_dheeraj@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$QtaBHGTsKyBbBHutrB8.P.vqiFCdqKYXWrtTwPSvY54uNhOcPs28.', NULL),
(20, 23, 134283, 'Prateek Mondal', 'Senior Geophysicist(Well)', 'Mondal_Prateek@ongc.co.in', '9476433227', NULL, 'IMG-134283.jpg', 1, NULL, '$2y$10$zWnwKms/lY6hiRAg8dvZT.pVd2M2eZnG8cuLR5OPn1LDkd97GRHQ.', NULL),
(21, 24, 134487, 'Prabhakar Singh', 'SR GEOPHY (WELLS)', 'singh_prabhakar@ongc.co.in', '7993359023', NULL, 'IMG-134487.jpg', 1, NULL, '$2y$10$9aZertDnDilLtsjEk7wkC.Ca5LtkQ15n1gnas8rWSUkByWjsenjf6', NULL),
(22, 26, 135201, 'SARUL BAGLA', 'Sr. Geophysicist (Wells)', 'bagla_sarul@ongc.co.in', '7574846148', NULL, 'IMG-135201.jpg', 1, NULL, '$2y$10$pbSewKoxt4iXthfE4KJV5OX0WpzdVnGWInAZ0mcBJCLwSoAi9lCGS', NULL),
(23, 27, 135209, 'Nagendra Maurya', 'SR GEOPHY (WELLS)', 'maurya_nagendra@ongc.co.in', '7574846147', NULL, 'IMG-135209.jpg', 1, NULL, '$2y$10$jdcH7FNcIYTyVDZQUWQu7eyPVC0D3QfLqhVS2fkMVZU9zRwksfCIu', NULL),
(24, 28, 135329, 'Vinay Kumar Kashyap', 'Senior Geophysicist (W)', 'kashyap_vinay_kumar@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$4hRufFM1Sz1efUiv0.CzK.ZOfHCzBdB8hegQ73vd/ces8V0H1jLPK', NULL),
(25, 32, 138308, 'VIVEK KUMAR', 'GEOPHYSICIST(W)', 'KUMAR_VIVEK@ONGC.CO.IN', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$4hRufFM1Sz1efUiv0.CzK.ZOfHCzBdB8hegQ73vd/ces8V0H1jLPK', NULL),
(26, 51, 138927, 'NARENDRA PATEL', NULL, 'patel_narendra@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$WFDTkUZPnxlvsnBaD3no7.5gG0LKVsnR9CLi3OyQdOVQiipqGR61e', NULL),
(27, 52, 138931, 'Virendra Patel', 'Jr.MVD (W)', 'patel_virendrakumar1@ongc.co.in', '9409304449', NULL, 'default.jpg', 1, NULL, '$2y$10$st9E5nXWJAP3Ba5nHYcgfuP/xpprBxTYQvh24gZDvAdTHvnOKgFKi', NULL),
(28, 53, 138949, 'ABHE SINH', 'MVD', 'SINH_ABHE@ONGC.CO.IN', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$jm/DdrwjSxC1NV26SnmzGeHGoUEfu62ywn9jKcoBKNUyPvbEBdgMW', NULL),
(29, 54, 138959, 'VIPULKUMAR TADVI', 'JMVD-HVO', 'tadvi_vipulkumar@ongc.co.in', '9327107822', NULL, 'IMG-138959.jpg', 1, NULL, '$2y$10$//UGo9qLO1btkwI8b1LGgeDWnO5qDOYXPf/xWV8aW7pMh3DCbbpKG', NULL),
(30, 36, 139581, 'RAJNISH KUMAR MAURYA', 'Geophysicist(W)', 'maurya_rajnish@ongc.co.in', '9409304518', NULL, 'default.jpg', 1, NULL, '$2y$10$o/gZzQ4jFzbm.tu1f9ZrZuGNVBhUsFwxwICdMTQzGIhywh7Xv4LHu', NULL),
(31, 55, 140668, 'Solanki Laljibhai Bhemaji', 'Jr.MVD (winch)', 'solanki_laljibhai@ongc.co.in', '7574846348', NULL, 'default.jpg', 1, NULL, '$2y$10$b2E3U.p/oJU.cA7UahRA3..QL2gvo6zrMGqEa64XlETTbWxyk8X5O', NULL),
(32, 41, 82703, 'Arif mandaviya', NULL, 'mandaviya_arif@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$LH7jJUSvnB5YjXQ9Tyj5L.8qGfaQiUse3.zDpXFIcEVTZ/Q108/vW', NULL),
(33, 35, 139579, 'Madhusudhan', NULL, 'Srirama_Madhusudhan@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$ddl7OPqy82Edfn5TpnXmZuyPG6Uyd5w5UhYyJSoNL2Z10HKVChdDq', NULL),
(34, 48, 132579, 'CHETAN KUMAR DHULIA', NULL, 'dhulia_chetankumar@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$jdcH7FNcIYTyVDZQUWQu7eyPVC0D3QfLqhVS2fkMVZU9zRwksfCIu', NULL),
(35, 29, 135371, 'SANDEEP RANA', 'Sr. Geophysicist (Wells)', 'rana_sandeep1@ongc.co.in', '8257800101', NULL, 'IMG-135371.jpg', 1, NULL, '$2y$10$0DbDU3YmsEz7i99x1RDL.uVJHd88X/OwW.KyQM/M7TxrdH0c9UnSG', NULL),
(36, 45, 122244, 'SANJAYBHAI V TALAVIYA', NULL, 'thalaviya_v_sanjaybhai@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$gyHPRpoAFSO0TY6G9JXMVOqFmt0Al40fT1uEPaMOhP6fBCrriHrAO', NULL),
(37, 33, 139162, 'Arijit Kumar Mondal', 'Geophysicist(Wells)', 'Mondal_Arijit@ongc.co.in', '9409304480', NULL, 'IMG-139162.jpg', 1, NULL, '$2y$10$jdcH7FNcIYTyVDZQUWQu7eyPVC0D3QfLqhVS2fkMVZU9zRwksfCIu', NULL),
(38, 9, 106132, 'Sangram Singh', NULL, 'singh_sangram@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$EA/r7O9YGR6BJG3Ty8aow.CIPaELkVIIpfqbz8ge6GrwTabLR5TN.', NULL),
(39, 38, 69748, 'R. R. Patel', 'Chief F/M', 'patel_rameshbhai@ongc.co.in', '7574846179', NULL, 'default.jpg', 1, NULL, '$2y$10$kn2MF.F7C6G2V1oBT56ZYeoBLCQje1r/DfyqZNNIupc2dd1vMDZX6', NULL),
(40, 34, 139540, 'DEBASISH SAHOO', NULL, 'SAHOO_DEBASISH@ONGC.CO.IN', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$8eEDzmaw6.RCzFklv2AUT.QgVvLozWyZJiTOZcuC4TgBpd.HEUqoO', NULL),
(41, 15, 124224, 'Kundan Singh Chauhan', NULL, '124224@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$PlQqvUe8B6j/Gim2WF7RueoGbRQjqj1c5F8GO4yJ7QXqA1s.MjN7C', NULL),
(42, 18, 133528, 'Vishnu Vardhan Y', NULL, '133528@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$rIyXmHG4KuptBA0GbvDbxen0mI/ir5QJy2ajbqkZZknEglQz.m536', NULL),
(43, 47, 132529, 'Dipak Kumar C Vasava', NULL, '132529@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$GWDCS8Ma8ak2xfn0u6QO/.JYfXAudJmdbWbqXMkwlkT9pIDYijMPO', NULL),
(44, 39, 133151, 'UTPALKUMAR HIRAJIBHAI GAMIT', 'EA(Mech)', 'GAMIT_UTPALKUMAR@GMAIL.COM', '9409696270', NULL, 'default.jpg', 1, NULL, '$2y$10$L4YuJGzAnLNisEYj67sc9u0.AxXK3t9MrILonLRNXLzkWMVzjWyMW', NULL),
(45, 11, 121695, 'Durgaprasad K', NULL, '121695@ongc.co.in', NULL, NULL, 'default.jpg', 1, NULL, '$2y$10$BFNaJCovmU5X7T3KBItaeO94VO8kInb.hgGUn1VojG6PHA2.4Gyaq', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
