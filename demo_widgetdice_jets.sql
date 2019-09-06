-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le :  ven. 06 sep. 2019 à 14:19
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `wordpressdemo`
--

-- --------------------------------------------------------

--
-- Structure de la table `demo_widgetdice_jets`
--

CREATE TABLE `demo_widgetdice_jets` (
  `id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `idUser` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `DateJet` datetime NOT NULL,
  `StringJet` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `demo_widgetdice_jets`
--

INSERT INTO `demo_widgetdice_jets` (`id`, `idUser`, `DateJet`, `StringJet`) VALUES
(1, 1, '2019-09-06 10:00:00', '1,3,6,5,7,12,20,71'),
(2, 2, '2019-09-19 09:00:00', '2,2,2,2,6,5,3,8,2,6,20,87');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
