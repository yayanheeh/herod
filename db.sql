-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2018 at 12:10 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aio_dl`
--

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `ID` bigint(20) NOT NULL,
  `content_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `content_type` int(11) NOT NULL,
  `content_slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content_meta` text COLLATE utf8_unicode_ci NOT NULL,
  `content_text` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`ID`, `content_name`, `content_type`, `content_slug`, `content_meta`, `content_text`) VALUES
(1, 'Terms of Service', 0, 'tos', '', '<h1>Terms of Service</h1><p>You accepted this terms by using this website.</p><ul><li>Lorem ipsum</li><li>Dolor</li><li>Sit amet</li></ul>'),
(2, 'Contact', 0, 'contact', '', '<h1>Contact</h1><p>contact@nicheoffice.web.tr</p>');

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `ID` bigint(20) NOT NULL,
  `download_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `download_meta` longtext COLLATE utf8_unicode_ci NOT NULL,
  `download_links` longtext COLLATE utf8_unicode_ci NOT NULL,
  `download_source` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `option_name`, `option_value`) VALUES
(1, 'general_settings', {{general_settings}}),
(2, 'api_key.soundcloud', ''),
(3, 'api_key.flickr', ''),
(4, 'api_key.instagram', ''),
(5, 'api_key.recaptcha', ''),
(6, 'tracking_code', ''),
(7, 'ads.728x90', '<img class=\"img-auto\" src=\"https://via.placeholder.com/728x90\">'),
(8, 'ads.300x300', '<img class=\"img-auto\" src=\"https://via.placeholder.com/300x300\">'),
(9, 'theme.general', '{\"about\":\"true\",\"ads\":\"true\",\"tos\":\"true\",\"contact\":\"true\",\"social\":\"true\",\"facebook\":\"facebook\",\"twitter\":\"twitter\",\"google\":\"google\",\"youtube\":\"youtube\",\"instagram\":\"instagram\"}'),
(10, 'theme.menu', '[\r\n{\"title\":\"Link\",\"url\":\"#\"},\r\n{\"title\":\"Link\",\"url\":\"#\"}\r\n]'),
(11, 'gdpr_notice', '<link rel=\"stylesheet\" type=\"text/css\" href=\"//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css\"/> <script src=\"//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js\"></script> <script> window.addEventListener(\"load\", function () { window.cookieconsent.initialise({ \"palette\": { \"popup\": { \"background\": \"#252e39\" }, \"button\": { \"background\": \"#14a7d0\" } }, \"position\": \"bottom-right\" }) }); </script>');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `user_login` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `user_pass` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_nicename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_url` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_activation_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_level` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `user_login`, `user_pass`, `user_email`, `user_nicename`, `user_url`, `user_activation_key`, `user_level`) VALUES
(1, 'admin', '{{admin_pass}}', '{{admin_email}}', '{{admin_name}}', NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;