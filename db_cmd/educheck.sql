-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 21 juin 2024 à 09:54
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
-- Base de données : `educheck`
--

-- --------------------------------------------------------

--
-- Structure de la table `anneescolaire`
--

CREATE TABLE `anneescolaire` (
  `Id_anneescolaire` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  `statut` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `assigner`
--

CREATE TABLE `assigner` (
  `Id_utilisateurs` int(11) NOT NULL,
  `Id_Matieres` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `concerner`
--

CREATE TABLE `concerner` (
  `Id_emargements` int(11) NOT NULL,
  `Id_niveaux` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `emargements`
--

CREATE TABLE `emargements` (
  `Id_emargements` int(11) NOT NULL,
  `dates` varchar(80) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `Id_Matieres` int(11) NOT NULL,
  `Id_utilisateurs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `Id_Matieres` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `nbre_heure` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `niveaux`
--

CREATE TABLE `niveaux` (
  `Id_niveaux` int(11) NOT NULL,
  `nom_niveaux` varchar(50) NOT NULL,
  `Id_anneescolaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `Id_role` int(11) NOT NULL,
  `libelle_role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `Id_utilisateurs` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `motpasse` varchar(25) NOT NULL,
  `Id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `anneescolaire`
--
ALTER TABLE `anneescolaire`
  ADD PRIMARY KEY (`Id_anneescolaire`);

--
-- Index pour la table `assigner`
--
ALTER TABLE `assigner`
  ADD PRIMARY KEY (`Id_utilisateurs`,`Id_Matieres`),
  ADD KEY `Id_Matieres` (`Id_Matieres`);

--
-- Index pour la table `concerner`
--
ALTER TABLE `concerner`
  ADD PRIMARY KEY (`Id_emargements`,`Id_niveaux`),
  ADD KEY `Id_niveaux` (`Id_niveaux`);

--
-- Index pour la table `emargements`
--
ALTER TABLE `emargements`
  ADD PRIMARY KEY (`Id_emargements`),
  ADD KEY `Id_Matieres` (`Id_Matieres`),
  ADD KEY `Id_utilisateurs` (`Id_utilisateurs`);

--
-- Index pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`Id_Matieres`);

--
-- Index pour la table `niveaux`
--
ALTER TABLE `niveaux`
  ADD PRIMARY KEY (`Id_niveaux`),
  ADD KEY `Id_anneescolaire` (`Id_anneescolaire`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`Id_role`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`Id_utilisateurs`),
  ADD KEY `Id_role` (`Id_role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `anneescolaire`
--
ALTER TABLE `anneescolaire`
  MODIFY `Id_anneescolaire` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `emargements`
--
ALTER TABLE `emargements`
  MODIFY `Id_emargements` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `Id_Matieres` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `niveaux`
--
ALTER TABLE `niveaux`
  MODIFY `Id_niveaux` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `Id_role` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `Id_utilisateurs` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `assigner`
--
ALTER TABLE `assigner`
  ADD CONSTRAINT `assigner_ibfk_1` FOREIGN KEY (`Id_utilisateurs`) REFERENCES `utilisateurs` (`Id_utilisateurs`),
  ADD CONSTRAINT `assigner_ibfk_2` FOREIGN KEY (`Id_Matieres`) REFERENCES `matieres` (`Id_Matieres`);

--
-- Contraintes pour la table `concerner`
--
ALTER TABLE `concerner`
  ADD CONSTRAINT `concerner_ibfk_1` FOREIGN KEY (`Id_emargements`) REFERENCES `emargements` (`Id_emargements`),
  ADD CONSTRAINT `concerner_ibfk_2` FOREIGN KEY (`Id_niveaux`) REFERENCES `niveaux` (`Id_niveaux`);

--
-- Contraintes pour la table `emargements`
--
ALTER TABLE `emargements`
  ADD CONSTRAINT `emargements_ibfk_1` FOREIGN KEY (`Id_Matieres`) REFERENCES `matieres` (`Id_Matieres`),
  ADD CONSTRAINT `emargements_ibfk_2` FOREIGN KEY (`Id_utilisateurs`) REFERENCES `utilisateurs` (`Id_utilisateurs`);

--
-- Contraintes pour la table `niveaux`
--
ALTER TABLE `niveaux`
  ADD CONSTRAINT `niveaux_ibfk_1` FOREIGN KEY (`Id_anneescolaire`) REFERENCES `anneescolaire` (`Id_anneescolaire`);

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `utilisateurs_ibfk_1` FOREIGN KEY (`Id_role`) REFERENCES `roles` (`Id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
