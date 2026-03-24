DROP DATABASE IF EXISTS PROJET_WEB;
CREATE DATABASE PROJET_WEB;
USE PROJET_WEB;

DROP TABLE IF EXISTS `wishlist`;
DROP TABLE IF EXISTS `offre_competence`;
DROP TABLE IF EXISTS `entreprise_adresse`;
DROP TABLE IF EXISTS `candidature`;
DROP TABLE IF EXISTS `avis`;
DROP TABLE IF EXISTS `offre`;
DROP TABLE IF EXISTS `utilisateur`;
DROP TABLE IF EXISTS `entreprise`;
DROP TABLE IF EXISTS `secteur`;
DROP TABLE IF EXISTS `role`;
DROP TABLE IF EXISTS `competence`;
DROP TABLE IF EXISTS `adresse`;

-- Table adresse
CREATE TABLE `adresse` (
  `id_adresse` int NOT NULL AUTO_INCREMENT,
  `nom_rue` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `code_postal` varchar(50) NOT NULL,
  PRIMARY KEY (`id_adresse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table competence
CREATE TABLE `competence` (
  `id_competence` int NOT NULL AUTO_INCREMENT,
  `nom_competence` varchar(50) NOT NULL,
  PRIMARY KEY (`id_competence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table role
CREATE TABLE `role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `nom_role` varchar(50) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table secteur
CREATE TABLE `secteur` (
  `id_secteur` int NOT NULL AUTO_INCREMENT,
  `nom_secteur` varchar(50) NOT NULL,
  PRIMARY KEY (`id_secteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table entreprise
CREATE TABLE `entreprise` (
  `id_entreprise` int NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(50) NOT NULL,
  `description` text,
  `email` varchar(50) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `id_secteur` int DEFAULT NULL,
  PRIMARY KEY (`id_entreprise`),
  KEY `id_secteur` (`id_secteur`),
  CONSTRAINT `entreprise_ibfk_1`
    FOREIGN KEY (`id_secteur`) REFERENCES `secteur` (`id_secteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table utilisateur
CREATE TABLE `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(50) NOT NULL,
  `prenom_utilisateur` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `id_role` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `utilisateur_ibfk_1`
    FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table offre
CREATE TABLE `offre` (
  `id_offre` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) NOT NULL,
  `description` text,
  `gratification` decimal(5,2) DEFAULT NULL,
  `date_offre` date DEFAULT NULL,
  `duree` varchar(50) DEFAULT NULL,
  `id_entreprise` int NOT NULL,
  PRIMARY KEY (`id_offre`),
  KEY `id_entreprise` (`id_entreprise`),
  CONSTRAINT `offre_ibfk_1`
    FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise` (`id_entreprise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table avis
CREATE TABLE `avis` (
  `id_avis` int NOT NULL AUTO_INCREMENT,
  `commentaire` text,
  `note` int DEFAULT NULL,
  `date_avis` date DEFAULT NULL,
  `id_utilisateur` int NOT NULL,
  `id_entreprise` int NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_entreprise` (`id_entreprise`),
  CONSTRAINT `avis_ibfk_1`
    FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  CONSTRAINT `avis_ibfk_2`
    FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise` (`id_entreprise`),
  CONSTRAINT `avis_chk_1`
    CHECK (`note` BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table candidature
CREATE TABLE `candidature` (
  `id_candidature` int NOT NULL AUTO_INCREMENT,
  `cv` varchar(255) DEFAULT NULL,
  `lettre_motivation` text,
  `date_candidature` date DEFAULT NULL,
  `id_utilisateur` int NOT NULL,
  `id_offre` int NOT NULL,
  `statut` varchar(50) DEFAULT 'En attente',
  PRIMARY KEY (`id_candidature`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `id_offre` (`id_offre`),
  CONSTRAINT `candidature_ibfk_1`
    FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  CONSTRAINT `candidature_ibfk_2`
    FOREIGN KEY (`id_offre`) REFERENCES `offre` (`id_offre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table entreprise_adresse
CREATE TABLE `entreprise_adresse` (
  `id_entreprise` int NOT NULL,
  `id_adresse` int NOT NULL,
  PRIMARY KEY (`id_entreprise`, `id_adresse`),
  KEY `id_adresse` (`id_adresse`),
  CONSTRAINT `entreprise_adresse_ibfk_1`
    FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise` (`id_entreprise`),
  CONSTRAINT `entreprise_adresse_ibfk_2`
    FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id_adresse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table offre_competence
CREATE TABLE `offre_competence` (
  `id_offre` int NOT NULL,
  `id_competence` int NOT NULL,
  PRIMARY KEY (`id_offre`, `id_competence`),
  KEY `id_competence` (`id_competence`),
  CONSTRAINT `offre_competence_ibfk_1`
    FOREIGN KEY (`id_offre`) REFERENCES `offre` (`id_offre`),
  CONSTRAINT `offre_competence_ibfk_2`
    FOREIGN KEY (`id_competence`) REFERENCES `competence` (`id_competence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table wishlist
CREATE TABLE `wishlist` (
  `id_utilisateur` int NOT NULL,
  `id_offre` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`, `id_offre`),
  KEY `id_offre` (`id_offre`),
  CONSTRAINT `wishlist_ibfk_1`
    FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  CONSTRAINT `wishlist_ibfk_2`
    FOREIGN KEY (`id_offre`) REFERENCES `offre` (`id_offre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


GRANT ALL PRIVILEGES ON *.* TO `Admin`@`%` IDENTIFIED BY PASSWORD '*B8E22781821DC07031C5EC98BB8BC1012E689E2D' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON `job2main`.* TO `Admin`@`%`;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO `Pilote`@`%` IDENTIFIED BY PASSWORD '*3CE1965C9C626D2F1881AA1B18DA8C4D462BB153';
GRANT SELECT, INSERT, UPDATE, DELETE ON `job2main`.* TO `Pilote`@`%`;

GRANT SELECT, FILE ON *.* TO `etudiant`@`%` IDENTIFIED BY PASSWORD '*9F5FC07C6B0125D07952E4EBA6F7936FA3960B83';

GRANT SELECT ON *.* TO ``@`%`;
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, REFERENCES, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, EVENT, TRIGGER, DELETE HISTORY ON `test`.* TO ``@`%`;
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, REFERENCES, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, EVENT, TRIGGER, DELETE HISTORY ON `test\_%`.* TO ``@`%`;