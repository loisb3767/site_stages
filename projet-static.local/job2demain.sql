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
  `mot_de_passe` varchar(50) NOT NULL,
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

-- Pré-requis : Entreprises et Secteurs
INSERT INTO secteur (nom_secteur) VALUES ('Informatique'), ('Marketing'), ('Industrie');
INSERT INTO entreprise (nom_entreprise, id_secteur) VALUES ('Cyberdyne', 1), ('Wayne Ent.', 3), ('Stark Ind.', 1);

-- Insertion de 15 offres
INSERT INTO offre (titre, description, gratification, date_offre, duree, id_entreprise) VALUES
('Développeur Web Fullstack', 'Mise en place d’une interface React/Node.', 650.00, '2026-03-01', '6 mois', 1),
('Analyste Cybersécurité', 'Audit de vulnérabilité réseau.', 700.00, '2026-03-05', '4 mois', 1),
('Ingénieur Système', 'Optimisation des serveurs Linux.', 600.00, '2026-03-10', '5 mois', 1),
('Data Scientist Junior', 'Analyse de données de production.', 800.00, '2026-03-12', '6 mois', 1),
('Chef de Projet IT', 'Coordination d’équipes agiles.', 550.00, '2026-03-15', '3 mois', 1),
('Ingénieur Mécanique', 'Conception de pièces en titane.', 750.00, '2026-02-20', '6 mois', 2),
('Technicien Maintenance', 'Maintenance préventive parc machine.', 500.00, '2026-02-25', '2 mois', 2),
('Chargé de Logistique', 'Optimisation de la supply chain.', 580.00, '2026-03-01', '4 mois', 2),
('Designer Industriel', 'Modélisation 3D de nouveaux prototypes.', 620.00, '2026-03-05', '5 mois', 2),
('Responsable Qualité', 'Suivi des normes ISO.', 600.00, '2026-03-08', '6 mois', 2),
('Développeur IA', 'Entraînement de modèles de vision.', 900.00, '2026-03-02', '6 mois', 3),
('Ingénieur Hardware', 'Conception de circuits imprimés.', 850.00, '2026-03-07', '6 mois', 3),
('Spécialiste Réseaux', 'Déploiement fibre et switchs.', 550.00, '2026-03-11', '3 mois', 3),
('Assistant Marketing Tech', 'Promotion de solutions Cloud.', 450.00, '2026-03-14', '4 mois', 3),
('Expert Cloud Azure', 'Migration d’infrastructure locale.', 780.00, '2026-03-18', '6 mois', 3);

-- Insertion des rôles
INSERT INTO role (nom_role) VALUES ('Administrateur'), ('Candidat'), ('Entreprise');

-- Insertion de l'utilisateur test (Lié au rôle 'Candidat' via l'id_role 2)
INSERT INTO utilisateur (nom_utilisateur, prenom_utilisateur, email, telephone, mot_de_passe, id_role) 
VALUES ('Dupont', 'Gontrand', 'gontrand.dupont@viacesi.fr', '0601020304', 'azerty123', 2);