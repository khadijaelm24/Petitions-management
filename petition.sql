-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le : dim. 28 avr. 2024 à 20:23
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `petition`
--

-- --------------------------------------------------------

--
-- Structure de la table `petition`
--

CREATE TABLE `petition` (
  `IDP` int(11) NOT NULL,
  `Titre` varchar(255) NOT NULL,
  `Theme` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `DatePublic` date NOT NULL,
  `DateFin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `petition`
--

INSERT INTO `petition` (`IDP`, `Titre`, `Theme`, `Description`, `DatePublic`, `DateFin`) VALUES
(4, 'Protection de l\'environnement', 'Environnement', 'Une pétition pour renforcer les lois sur la protection de l\'environnement.', '2024-04-01', '2024-06-30'),
(5, 'Réforme de l\'éducation', 'Éducation', 'Cette pétition vise à améliorer les conditions d\'apprentissage dans les écoles publiques.', '2024-04-05', '2024-07-05'),
(6, 'Soutien aux innovations technologiques', 'Technologie', 'Encourager le financement public des startups technologiques.', '2024-04-10', '2024-08-10'),
(7, 'Eviter le gaspillage de l\'eau', 'Environnement', 'Une pétition pour renforcer les lois sur la protection de l\'environnement.', '2024-04-28', '2024-04-30'),
(28, 'Technologie', 'Technologie', 'Description pour une pétition de la technologie', '2024-04-30', '2024-05-15'),
(29, 'Education', 'Éducation', 'Description pour une pétition de l\'education', '2024-05-01', '2024-05-10');

-- --------------------------------------------------------

--
-- Structure de la table `signature`
--

CREATE TABLE `signature` (
  `IDS` int(11) NOT NULL,
  `IDP` int(11) NOT NULL,
  `Nom` varchar(255) NOT NULL,
  `Prenom` varchar(255) NOT NULL,
  `Pays` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `Heure` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `signature`
--

INSERT INTO `signature` (`IDS`, `IDP`, `Nom`, `Prenom`, `Pays`, `Date`, `Heure`) VALUES
(1, 4, 'EL MADANI', 'Khadija', 'Maroc', '2024-04-28', '05:26:56'),
(2, 4, 'EL MADANI', 'Omar', 'Maroc', '2024-04-28', '05:28:06'),
(3, 4, 'EL YOUSFY', 'Khadija', 'Maroc', '2024-04-28', '05:32:28'),
(4, 4, 'El Madani', 'Yassin', 'Maroc', '2024-04-28', '16:20:53'),
(5, 4, 'EL YOUSFY', 'Fatima', 'Maroc', '2024-04-28', '16:28:26'),
(6, 4, 'El Madani', 'Souad', 'Maroc', '2024-04-28', '16:29:27'),
(7, 4, 'Loukili', 'Bouchra', 'Maroc', '2024-04-28', '16:35:12'),
(8, 4, 'Loukili', 'Karim', 'Maroc', '2024-04-28', '16:37:39'),
(9, 4, 'Loukili', 'Omar', 'Maroc', '2024-04-28', '17:21:36'),
(10, 5, 'El Madani', 'Khadija', 'Maroc', '2024-04-28', '17:22:06'),
(11, 5, 'El Madani', 'Omar', 'Maroc', '2024-04-28', '17:24:39'),
(12, 5, 'El Madani', 'Yassine', 'Maroc', '2024-04-28', '17:25:14'),
(13, 5, 'Loukili', 'Bouchra', 'Maroc', '2024-04-28', '17:28:23'),
(14, 4, 'Loukili', 'Othman', 'Maroc', '2024-04-28', '17:30:03'),
(15, 6, 'El Madani', 'Khadija', 'Maroc', '2024-04-28', '17:30:45'),
(16, 5, 'El Madani', 'Othman', 'Maroc', '2024-04-28', '18:26:03'),
(17, 5, 'El Madani', 'Younes', 'Maroc', '2024-04-28', '18:26:16'),
(18, 5, 'Loukili', 'Othman', 'Maroc', '2024-04-28', '18:50:52'),
(20, 7, 'El Madani', 'Khadija', 'Maroc', '2024-04-28', '19:42:48'),
(22, 7, 'Loukili', 'Bouchra', 'Maroc', '2024-04-28', '20:16:17'),
(23, 7, 'El Madani', 'Omar', 'Maroc', '2024-04-28', '20:17:22');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `petition`
--
ALTER TABLE `petition`
  ADD PRIMARY KEY (`IDP`);

--
-- Index pour la table `signature`
--
ALTER TABLE `signature`
  ADD PRIMARY KEY (`IDS`),
  ADD KEY `IDP` (`IDP`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `petition`
--
ALTER TABLE `petition`
  MODIFY `IDP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `signature`
--
ALTER TABLE `signature`
  MODIFY `IDS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `signature`
--
ALTER TABLE `signature`
  ADD CONSTRAINT `signature_ibfk_1` FOREIGN KEY (`IDP`) REFERENCES `petition` (`IDP`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
