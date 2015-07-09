-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2015. júl. 09. 17:54
-- Szerver verzió: 5.1.41
-- PHP verzió: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Adatbázis: `gyakorlat`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet: `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_hungarian_ci NOT NULL,
  `text` text COLLATE utf8_hungarian_ci NOT NULL,
  `public` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `news`
--

INSERT INTO `news` (`id`, `title`, `text`, `public`, `date`, `user_id`) VALUES
(1, 'Első teszt', 'Ez itt az első hírünk.', 1, '2015-07-03 11:48:21', 2),
(2, 'A legújabb hírünk', 'Tesztelni tökéletes ez a szöveg, hisz tele van mindenféle ékezettel. Így ellenőrizni tudjuk ezt.', 1, '2015-07-03 13:05:32', 2),
(3, 'Új felhasználó is próbálkozik', 'Most aztán mindenki tud híreket felvenni. :)', 1, '2015-07-09 17:07:34', 3);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_hungarian_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_hungarian_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_hungarian_ci NOT NULL,
  `salt` int(5) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `salt`, `active`) VALUES
(1, 'testuser', 'test@gyakorlat.test', 'b904e5800264e8cc05ac7418fa8b9b20', 76112, 1),
(2, 'Gécseg Dániel', 'g.danika@gmail.com', '8b6c40d6872fba89eac7667d3bd8ea07', 35643, 1),
(3, '', 'test2@test.eu', '50fb1395c53751a5b526769b20027b68', 65170, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
