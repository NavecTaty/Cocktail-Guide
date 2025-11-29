-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 29 nov. 2025 à 17:53
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `recettesbd`
--

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE `favoris` (
  `id_favoris` int(25) NOT NULL,
  `id_utilisateur` int(25) NOT NULL,
  `id_recettes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hierarchie`
--

CREATE TABLE `hierarchie` (
  `id` int(25) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `super_categorie` varchar(50) DEFAULT NULL,
  `sous_categorie` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `index_recette`
--

CREATE TABLE `index_recette` (
  `id_index` int(25) NOT NULL,
  `id_recettes` int(11) DEFAULT NULL,
  `aliment` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recette`
--

CREATE TABLE `recette` (
  `id_recette` int(11) NOT NULL,
  `titre` varchar(50) DEFAULT NULL,
  `ingrediants` text DEFAULT NULL,
  `preparations` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `uti_id` int(25) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `mdp` varchar(50) NOT NULL,
  `login` varchar(100) NOT NULL,
  `sexe` enum('H','F') DEFAULT NULL,
  `addr_mail` varchar(100) DEFAULT NULL,
  `ddn` date DEFAULT NULL,
  `addresse` text DEFAULT NULL,
  `cp` int(10) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `telephone` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id_favoris`),
  ADD KEY `foreign_key3` (`id_utilisateur`),
  ADD KEY `foreign_key2` (`id_recettes`);

--
-- Index pour la table `hierarchie`
--
ALTER TABLE `hierarchie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `index_recette`
--
ALTER TABLE `index_recette`
  ADD PRIMARY KEY (`id_index`),
  ADD KEY `foreign_key1` (`id_recettes`);

--
-- Index pour la table `recette`
--
ALTER TABLE `recette`
  ADD PRIMARY KEY (`id_recette`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`uti_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id_favoris` int(25) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `hierarchie`
--
ALTER TABLE `hierarchie`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `index_recette`
--
ALTER TABLE `index_recette`
  MODIFY `id_index` int(25) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `recette`
--
ALTER TABLE `recette`
  MODIFY `id_recette` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `uti_id` int(25) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `foreign_key2` FOREIGN KEY (`id_recettes`) REFERENCES `recette` (`id_recette`),
  ADD CONSTRAINT `foreign_key3` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`uti_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `index_recette`
--
ALTER TABLE `index_recette`
  ADD CONSTRAINT `foreign_key1` FOREIGN KEY (`id_recettes`) REFERENCES `recette` (`id_recette`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
