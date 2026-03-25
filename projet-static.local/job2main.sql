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
  `ville` varchar(50) NULL,
  `code_postal` varchar(50) NOT NULL,
  `latitude` DECIMAL(10,8) NULL,
  `longitude` DECIMAL(11,8) NULL,
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

-- Donnees converties depuis le fichier Excel 'Data base.xlsx'
-- Cible : schema SQL fourni dans 'job2main.sql'
-- Hypotheses appliquees pour rendre l'import compatible avec le schema :
-- 1) Table utilisateur : la colonne email n'existe pas dans l'Excel ; des emails techniques ont ete generes.
-- 2) Table adresse : la colonne ville n'existe pas dans l'Excel ; la valeur 'Ville inconnue' a ete renseignee.
-- 3) Table role : le role 'anonyme' a ete supprime ; les roles valides sont 0=etudiant, 1=pilote, 2=admin.
-- 4) Table utilisateur : normalisation des roles vers le referentiel valide (0=etudiant, 1=pilote, 2=admin).
-- 5) Table candidature : la colonne statut n'existe pas dans l'Excel ; la valeur par defaut du schema est conservee.
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
USE PROJET_WEB;

SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM wishlist;
DELETE FROM offre_competence;
DELETE FROM entreprise_adresse;
DELETE FROM candidature;
DELETE FROM avis;
DELETE FROM offre;
DELETE FROM utilisateur;
DELETE FROM entreprise;
DELETE FROM secteur;
DELETE FROM role;
DELETE FROM competence;
DELETE FROM adresse;

SET FOREIGN_KEY_CHECKS = 1;

ALTER TABLE wishlist AUTO_INCREMENT = 1;
ALTER TABLE offre_competence AUTO_INCREMENT = 1;
ALTER TABLE entreprise_adresse AUTO_INCREMENT = 1;
ALTER TABLE candidature AUTO_INCREMENT = 1;
ALTER TABLE avis AUTO_INCREMENT = 1;
ALTER TABLE offre AUTO_INCREMENT = 1;
ALTER TABLE utilisateur AUTO_INCREMENT = 1;
ALTER TABLE entreprise AUTO_INCREMENT = 1;
ALTER TABLE secteur AUTO_INCREMENT = 1;
ALTER TABLE role AUTO_INCREMENT = 1;
ALTER TABLE competence AUTO_INCREMENT = 1;
ALTER TABLE adresse AUTO_INCREMENT = 1;


-- Vidage de toutes les tables du schema avant reimport
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE wishlist;
TRUNCATE TABLE offre_competence;
TRUNCATE TABLE entreprise_adresse;
TRUNCATE TABLE candidature;
TRUNCATE TABLE avis;
TRUNCATE TABLE offre;
TRUNCATE TABLE utilisateur;
TRUNCATE TABLE entreprise;
TRUNCATE TABLE secteur;
TRUNCATE TABLE role;
TRUNCATE TABLE competence;
TRUNCATE TABLE adresse;
SET FOREIGN_KEY_CHECKS = 1;

-- Desactivation a nouveau pendant l'insertion des donnees
SET FOREIGN_KEY_CHECKS = 0;


-- role
INSERT INTO role (id_role, nom_role) VALUES
  (0, 'etudiant'),
  (1, 'pilote'),
  (2, 'admin');

-- secteur
INSERT INTO secteur (id_secteur, nom_secteur) VALUES
  (1, 'Informatique'),
  (2, 'Sante'),
  (3, 'Finance'),
  (4, 'Education'),
  (5, 'Transport'),
  (6, 'Agroalimentaire'),
  (7, 'Energie'),
  (8, 'Commerce'),
  (9, 'BTP'),
  (10, 'Tourisme');

-- adresse
INSERT INTO adresse (id_adresse, nom_rue, code_postal) VALUES
(1, '11 Rue de la Paix', '75002'),
(2, '126 Rue de Rivoli', '75001'),
(3, '40 Boulevard Haussmann', '75009'),
(4, '15 Avenue Kléber', '75016'),
(5, '127 Rue du Faubourg Saint-Antoine', '75011'),
(6, '43 Rue des Écoles', '75005'),
(7, '34 Rue des Martyrs', '75009'),
(8, '155 Rue de Rennes', '75006'),
(9, '1 Avenue Jean Jaurès', '75019'),
(10, '22 Rue du 4 Septembre', '75002'),
(11, '1 Place d''Armes', '57000'),
(12, '30 Rue des Clercs', '57000'),
(13, '10 Rue Serpenoise', '57000'),
(14, '5 Rue du Palais', '57000'),
(15, '12 Rue Taison', '57000'),
(16, '8 Rue Haute Seille', '57000'),
(17, '20 Avenue Foch', '57000'),
(18, '3 Rue Gambetta', '57000'),
(19, '14 Rue Fabert', '57000'),
(20, '9 Rue du Grand Cerf', '57000'),
(21, '18 Rue du 22 Novembre', '67000'),
(22, '7 Rue Mercière', '67000'),
(23, '12 Rue des Tonneliers', '67000'),
(24, '5 Place du Château', '67000'),
(25, '10 Rue de l''Outre', '67000'),
(26, '24 Rue du Vieux Marché aux Vins', '67000'),
(27, '14 Rue des Dentelles', '67000'),
(28, '120 Grand Rue', '67000'),
(29, '10 Boulevard de la Victoire', '67000'),
(30, '1 Quai Ernest Bevin', '67000'),
(31, '80 Boulevard du Général Leclerc', '44000'),
(32, '12 Rue de la République', '69002'),
(33, '5 Rue de la Loge', '34000'),
(34, '10 Rue Nationale', '59800'),
(35, '8 Rue Alsace Lorraine', '31000'),
(36, '15 Rue Sainte-Catherine', '33000'),
(37, '2 Rue Foch', '21000'),
(38, '9 Rue d''Italie', '06000'),
(39, '7 Rue Thiers', '38000'),
(40, '6 Rue du Port', '17000'),
(41, '25 Avenue de la Gare', '74000'),
(42, '18 Rue Pasteur', '51100'),
(43, '22 Rue Victor Hugo', '37000'),
(44, '3 Rue Nationale', '37000'),
(45, '11 Rue Saint-Nicolas', '54000'),
(46, '6 Rue des Jardins', '68000'),
(47, '14 Rue du Moulin', '76600'),
(48, '9 Rue des Fleurs', '59000'),
(49, '17 Rue de l''Église', '57200'),
(50, '4 Rue Principale', '67300');

-- entreprise
INSERT INTO entreprise (id_entreprise, nom_entreprise, description, email, telephone, id_secteur) VALUES
  (1, 'TransLogistic', 'Reseau de distribution multicanal.', 'contact@translogistic.com', '0352401207', 9),
  (2, 'ShopMax', 'Expert en gestion financiere et investissement.', 'contact@shopmax.fr', '0180824106', 5),
  (3, 'InvestGroup', 'Agence de voyages et tourisme d''affaires.', 'contact@investgroup.com', '0177467989', 7),
  (4, 'EduLearn', 'Agence de voyages et tourisme d''affaires.', 'contact@edulearn.com', '0371990718', 4),
  (5, 'MediCare Solutions', 'Reseau de distribution multicanal.', 'contact@medicaresolutions.com', '0281700042', 2),
  (6, 'BatiPro', 'Specialiste des solutions numeriques innovantes.', 'contact@batipro.com', '0154564023', 9),
  (7, 'PharmaCo', 'Specialiste des solutions numeriques innovantes.', 'contact@pharmaco.com', '0353555479', 1),
  (8, 'TechVision', 'Plateforme de formation en ligne certifiee.', 'contact@techvision.com', '0551165517', 2),
  (9, 'CloudNet', 'Producteur et distributeur de produits frais.', 'contact@cloudnet.fr', '0395371948', 9),
  (10, 'EnergiaVert', 'Expert en gestion financiere et investissement.', 'contact@energiavert.com', '0564555266', 8),
  (11, 'RapidoTransit', 'Producteur et distributeur de produits frais.', 'contact@rapidotransit.fr', '0147464888', 9),
  (12, 'CliniqSante', 'Plateforme de formation en ligne certifiee.', 'contact@cliniqsante.fr', '0261248694', 3),
  (13, 'DataSoft', 'Reseau de distribution multicanal.', 'contact@datasoft.com', '0432362329', 2),
  (14, 'FinPro Group', 'Operateur logistique national et international.', 'contact@finprogroup.com', '0276641005', 9),
  (15, 'CreditPlus', 'Entreprise de construction et renovation.', 'contact@creditplus.fr', '0274021246', 10),
  (16, 'SolarTech', 'Plateforme de formation en ligne certifiee.', 'contact@solartech.com', '0459077993', 8),
  (17, 'MarketPlace', 'Entreprise de construction et renovation.', 'contact@marketplace.com', '0187486641', 1),
  (18, 'CargoExpress', 'Producteur et distributeur de produits frais.', 'contact@cargoexpress.com', '0233218723', 3),
  (19, 'SejoursPlus', 'Producteur et distributeur de produits frais.', 'contact@sejoursplus.fr', '0122486374', 4),
  (20, 'TravelEasy', 'Expert en gestion financiere et investissement.', 'contact@traveleasy.fr', '0141337777', 4),
  (21, 'UrbanBuild', 'Producteur et distributeur de produits frais.', 'contact@urbanbuild.com', '0400353553', 7),
  (22, 'AgroFresh', 'Operateur logistique national et international.', 'contact@agrofresh.fr', '0269767911', 3),
  (23, 'NutriFood', 'Prestataire de services de sante de proximite.', 'contact@nutrifood.com', '0288696470', 8),
  (24, 'SkillAcademy', 'Producteur et distributeur de produits frais.', 'contact@skillacademy.com', '0581594511', 3),
  (25, 'FormaPro', 'Expert en gestion financiere et investissement.', 'contact@formapro.fr', '0422294962', 8);

-- utilisateur
INSERT INTO utilisateur (id_utilisateur, nom_utilisateur, prenom_utilisateur, email, telephone, mot_de_passe, id_role) VALUES
  (1, 'Martinez', 'Ugo', 'ugo.martinez.1@import.local', '0684269257', 'ugo347', 0),
  (2, 'Robin', 'Emma', 'emma.robin.2@import.local', '0600987236', 'emma693', 2),
  (3, 'Garnier', 'Ugo', 'ugo.garnier.3@import.local', '0618184524', 'ugo516', 0),
  (4, 'Lopez', 'Antoine', 'antoine.lopez.4@import.local', '0620991002', 'antoine938', 1),
  (5, 'Guerin', 'Lea', 'lea.guerin.5@import.local', '0725128391', 'lea130', 0),
  (6, 'Lopez', 'Ivan', 'ivan.lopez.6@import.local', '0794084151', 'ivan913', 2),
  (7, 'Girard', 'Raphaël', 'raphael.girard.7@import.local', '0720652079', 'raphaël410', 2),
  (8, 'David', 'Victor', 'victor.david.8@import.local', '0654219685', 'victor736', 1),
  (9, 'Martin', 'Xenia', 'xenia.martin.9@import.local', '0746465368', 'xenia575', 0),
  (10, 'Garcia', 'Wendy', 'wendy.garcia.10@import.local', '0616414309', 'wendy914', 0),
  (11, 'Guerin', 'Emma', 'emma.guerin.11@import.local', '0616770610', 'emma855', 0),
  (12, 'Simon', 'Ursula', 'ursula.simon.12@import.local', '0648035618', 'ursula100', 0),
  (13, 'Dupont', 'Fabrice', 'fabrice.dupont.13@import.local', '0720019945', 'fabrice344', 0),
  (14, 'Girard', 'Thomas', 'thomas.girard.14@import.local', '0687185635', 'thomas906', 0),
  (15, 'Fontaine', 'Lea', 'lea.fontaine.15@import.local', '0672772835', 'lea538', 1),
  (16, 'Clement', 'Bruno', 'bruno.clement.16@import.local', '0739658595', 'bruno521', 0),
  (17, 'Garnier', 'Julien', 'julien.garnier.17@import.local', '0690936845', 'julien655', 0),
  (18, 'Roux', 'Priya', 'priya.roux.18@import.local', '0633977879', 'priya677', 0),
  (19, 'Henry', 'Zoe', 'zoe.henry.19@import.local', '0754091868', 'zoe767', 0),
  (20, 'Michel', 'Hugo', 'hugo.michel.20@import.local', '0678233591', 'hugo350', 0),
  (21, 'Nicolas', 'Helene', 'helene.nicolas.21@import.local', '0769074908', 'helene963', 0),
  (22, 'Roux', 'Gael', 'gael.roux.22@import.local', '0608436201', 'gael952', 0),
  (23, 'Petit', 'Vanessa', 'vanessa.petit.23@import.local', '0678358854', 'vanessa722', 0),
  (24, 'Laurent', 'Mohamed', 'mohamed.laurent.24@import.local', '0711295465', 'mohamed638', 0),
  (25, 'David', 'Gael', 'gael.david.25@import.local', '0715358003', 'gael241', 0),
  (26, 'Lefebvre', 'Wendy', 'wendy.lefebvre.26@import.local', '0635673050', 'wendy453', 0),
  (27, 'Dubois', 'Diane', 'diane.dubois.27@import.local', '0623254489', 'diane290', 1),
  (28, 'Guerin', 'Wendy', 'wendy.guerin.28@import.local', '0745160418', 'wendy516', 0),
  (29, 'Dumont', 'Emma', 'emma.dumont.29@import.local', '0609976261', 'emma165', 0),
  (30, 'Legrand', 'Vanessa', 'vanessa.legrand.30@import.local', '0759838837', 'vanessa790', 0),
  (31, 'Rousseau', 'Helene', 'helene.rousseau.31@import.local', '0691836362', 'helene120', 0),
  (32, 'Legrand', 'Diane', 'diane.legrand.32@import.local', '0718272672', 'diane426', 0),
  (33, 'Laurent', 'Helene', 'helene.laurent.33@import.local', '0687761359', 'helene199', 2),
  (34, 'Lefebvre', 'Antoine', 'antoine.lefebvre.34@import.local', '0795676978', 'antoine782', 1),
  (35, 'Thomas', 'Maxime', 'maxime.thomas.35@import.local', '0616928524', 'maxime334', 0),
  (36, 'Bernard', 'Julien', 'julien.bernard.36@import.local', '0608744961', 'julien326', 1),
  (37, 'Lefebvre', 'Ugo', 'ugo.lefebvre.37@import.local', '0600092634', 'ugo389', 0),
  (38, 'Morel', 'Nina', 'nina.morel.38@import.local', '0621393105', 'nina442', 0),
  (39, 'Roussel', 'Helene', 'helene.roussel.39@import.local', '0662022910', 'helene273', 0),
  (40, 'Roux', 'Vanessa', 'vanessa.roux.40@import.local', '0780596168', 'vanessa536', 0),
  (41, 'Gauthier', 'Lea', 'lea.gauthier.41@import.local', '0679862766', 'lea386', 1),
  (42, 'Girard', 'Zoe', 'zoe.girard.42@import.local', '0675405093', 'zoe727', 0),
  (43, 'François', 'Florian', 'florian.francois.43@import.local', '0779022130', 'florian858', 0),
  (44, 'Leroy', 'Hugo', 'hugo.leroy.44@import.local', '0719615518', 'hugo272', 1),
  (45, 'Richard', 'Clara', 'clara.richard.45@import.local', '0738734340', 'clara588', 2),
  (46, 'Morin', 'Mohamed', 'mohamed.morin.46@import.local', '0656375453', 'mohamed257', 0),
  (47, 'Perrin', 'Ivan', 'ivan.perrin.47@import.local', '0636008079', 'ivan626', 0),
  (48, 'Muller', 'Olivier', 'olivier.muller.48@import.local', '0767508787', 'olivier502', 0),
  (49, 'Dumont', 'Nina', 'nina.dumont.49@import.local', '0670757163', 'nina938', 0),
  (50, 'Robert', 'Alice', 'alice.robert.50@import.local', '0757667162', 'alice291', 0),
  (51, 'Legrand', 'Ugo', 'ugo.legrand.51@import.local', '0789955127', 'ugo720', 1),
  (52, 'Bernard', 'Cedric', 'cedric.bernard.52@import.local', '0759601320', 'cedric859', 0),
  (53, 'Dumont', 'Yann', 'yann.dumont.53@import.local', '0606116836', 'yann143', 0),
  (54, 'Lefevre', 'Xavier', 'xavier.lefevre.54@import.local', '0730262961', 'xavier730', 0),
  (55, 'Dupont', 'Maxime', 'maxime.dupont.55@import.local', '0676669619', 'maxime383', 1),
  (56, 'Thomas', 'Priya', 'priya.thomas.56@import.local', '0725356719', 'priya432', 0),
  (57, 'Chevalier', 'Victor', 'victor.chevalier.57@import.local', '0761809548', 'victor765', 0),
  (58, 'Henry', 'Emma', 'emma.henry.58@import.local', '0641163673', 'emma930', 1),
  (59, 'Roux', 'Emma', 'emma.roux.59@import.local', '0786794736', 'emma614', 0),
  (60, 'Girard', 'Vanessa', 'vanessa.girard.60@import.local', '0602489060', 'vanessa250', 0),
  (61, 'Vincent', 'Nina', 'nina.vincent.61@import.local', '0618661478', 'nina802', 1),
  (62, 'Rousseau', 'Laura', 'laura.rousseau.62@import.local', '0680912759', 'laura757', 2),
  (63, 'Chevalier', 'Xenia', 'xenia.chevalier.63@import.local', '0662973372', 'xenia607', 0),
  (64, 'Lefevre', 'Thea', 'thea.lefevre.64@import.local', '0715255227', 'thea731', 0),
  (65, 'Henry', 'Vanessa', 'vanessa.henry.65@import.local', '0622086760', 'vanessa433', 0),
  (66, 'Moreau', 'Florian', 'florian.moreau.66@import.local', '0780637777', 'florian257', 0),
  (67, 'Rousseau', 'Kevin', 'kevin.rousseau.67@import.local', '0606164147', 'kevin932', 0),
  (68, 'François', 'Jade', 'jade.francois.68@import.local', '0762674407', 'jade144', 0),
  (69, 'Thomas', 'Priya', 'priya.thomas.69@import.local', '0723134431', 'priya232', 0),
  (70, 'David', 'Lea', 'lea.david.70@import.local', '0790032346', 'lea603', 2),
  (71, 'Muller', 'Helene', 'helene.muller.71@import.local', '0778950940', 'helene357', 0),
  (72, 'Mercier', 'William', 'william.mercier.72@import.local', '0637132266', 'william669', 0),
  (73, 'Richard', 'Victor', 'victor.richard.73@import.local', '0703816494', 'victor958', 0),
  (74, 'Rousseau', 'Rachel', 'rachel.rousseau.74@import.local', '0680510472', 'rachel612', 0),
  (75, 'Robert', 'Yann', 'yann.robert.75@import.local', '0776137209', 'yann760', 1);

-- entreprise_adresse
INSERT INTO entreprise_adresse (id_entreprise, id_adresse) VALUES
  (1, 5),
  (2, 7),
  (2, 28),
  (2, 29),
  (3, 30),
  (4, 26),
  (4, 50),
  (4, 34),
  (5, 15),
  (6, 43),
  (7, 33),
  (7, 48),
  (7, 22),
  (8, 26),
  (8, 38),
  (8, 34),
  (9, 18),
  (10, 23),
  (10, 45),
  (10, 19),
  (11, 9),
  (11, 47),
  (12, 50),
  (13, 45),
  (13, 18),
  (14, 1),
  (15, 12),
  (16, 14),
  (17, 23),
  (17, 39),
  (17, 18),
  (18, 32),
  (18, 47),
  (18, 25),
  (19, 47),
  (19, 29),
  (19, 4),
  (20, 21),
  (20, 20),
  (21, 6),
  (22, 27),
  (23, 36),
  (23, 49),
  (23, 33),
  (24, 27),
  (24, 13),
  (25, 35);

-- competence
INSERT INTO competence (id_competence, nom_competence) VALUES
  (1, 'Python'),
  (2, 'Java'),
  (3, 'JavaScript'),
  (4, 'SQL'),
  (5, 'React'),
  (6, 'Node.js'),
  (7, 'Docker'),
  (8, 'Git'),
  (9, 'Machine Learning'),
  (10, 'Analyse de donnees'),
  (11, 'Communication'),
  (12, 'Gestion de projet'),
  (13, 'Marketing digital'),
  (14, 'Comptabilite'),
  (15, 'Design UX/UI'),
  (16, 'Cybersecurite'),
  (17, 'Reseaux'),
  (18, 'PHP'),
  (19, 'C++'),
  (20, 'Excel avance');

-- offre
INSERT INTO offre (id_offre, titre, description, gratification, date_offre, duree, id_entreprise) VALUES
  (1, 'Stage Developpeur Web', 'Offre de stage dans le domaine Web. Rejoignez une equipe dynamique.', 630.5, '2026-10-20', '5 mois', 10),
  (2, 'Stage Data Analyst', 'Offre de stage dans le domaine Analyst. Rejoignez une equipe dynamique.', 720, '2026-02-01', '6 mois', 8),
  (3, 'Stage Marketing', 'Offre de stage dans le domaine Marketing. Rejoignez une equipe dynamique.', NULL, '2026-04-09', '1 mois', 10),
  (4, 'Stage DevOps', 'Offre de stage dans le domaine DevOps. Rejoignez une equipe dynamique.', 750, '2026-02-17', '4 mois', 25),
  (5, 'Stage UX Designer', 'Offre de stage dans le domaine Designer. Rejoignez une equipe dynamique.', 610, '2026-07-13', '2 mois', 12),
  (6, 'Stage Comptabilite', 'Offre de stage dans le domaine Comptabilite. Rejoignez une equipe dynamique.', 590, '2026-06-12', '4 mois', 17),
  (7, 'Stage Cybersecurite', 'Offre de stage dans le domaine Cybersecurite. Rejoignez une equipe dynamique.', 680, '2026-06-23', '2 mois', 6),
  (8, 'Stage Chef de projet', 'Offre de stage dans le domaine projet. Rejoignez une equipe dynamique.', 680, '2026-12-30', '4 mois', 3),
  (9, 'Stage RH', 'Offre de stage dans le domaine RH. Rejoignez une equipe dynamique.', 577.5, '2026-10-09', '4 mois', 4),
  (10, 'Stage Commercial', 'Offre de stage dans le domaine Commercial. Rejoignez une equipe dynamique.', 600, '2026-05-24', '5 mois', 25),
  (11, 'Alternance Developpeur', 'Offre de stage dans le domaine Developpeur. Rejoignez une equipe dynamique.', 800, '2026-01-03', '6 mois', 15),
  (12, 'Stage IA/ML', 'Offre de stage dans le domaine IA/ML. Rejoignez une equipe dynamique.', 770, '2026-08-31', '2 mois', 16),
  (13, 'Stage Reseau', 'Offre de stage dans le domaine Reseau. Rejoignez une equipe dynamique.', 100, '2026-10-26', '1 mois', 6),
  (14, 'Stage Communication', 'Offre de stage dans le domaine Communication. Rejoignez une equipe dynamique.', 580, '2026-05-09', '2 mois', 7),
  (15, 'Stage Finance', 'Offre de stage dans le domaine Finance. Rejoignez une equipe dynamique.', 150, '2026-09-07', '1 mois', 12),
  (16, 'Stage Logistique', 'Offre de stage dans le domaine Logistique. Rejoignez une equipe dynamique.', 620, '2026-04-01', '5 mois', 3),
  (17, 'Stage Developpeur Mobile', 'Offre de stage dans le domaine Mobile. Rejoignez une equipe dynamique.', 740, '2026-01-09', '6 mois', 22),
  (18, 'Stage Product Manager', 'Offre de stage dans le domaine Manager. Rejoignez une equipe dynamique.', 660, '2026-03-06', '5 mois', 16),
  (19, 'Stage Support IT', 'Offre de stage dans le domaine IT. Rejoignez une equipe dynamique.', 400, '2026-05-31', '2 mois', 10),
  (20, 'Stage Juridique', 'Offre de stage dans le domaine Juridique. Rejoignez une equipe dynamique.', 577.5, '2026-01-06', '5 mois', 18),
  (21, 'Stage Developpeur Backend', 'Offre de stage dans le domaine Backend. Rejoignez une equipe dynamique.', 750, '2026-01-17', '6 mois', 7),
  (22, 'Stage Community Manager', 'Offre de stage dans le domaine Manager. Rejoignez une equipe dynamique.', 577.5, '2026-07-29', '3 mois', 2),
  (23, 'Stage Ingenieur Donnees', 'Offre de stage dans le domaine Donnees. Rejoignez une equipe dynamique.', 730, '2026-05-30', '3 mois', 6),
  (24, 'Stage Responsable Qualite', 'Offre de stage dans le domaine Qualite. Rejoignez une equipe dynamique.', 610, '2026-02-25', '2 mois', 17),
  (25, 'Stage Business Analyst', 'Offre de stage dans le domaine Analyst. Rejoignez une equipe dynamique.', 695, '2026-08-24', '2 mois', 11),
  (26, 'Stage Infographiste', 'Offre de stage dans le domaine Infographiste. Rejoignez une equipe dynamique.', 590, '2026-11-20', '5 mois', 20),
  (27, 'Stage Developpeur Frontend', 'Offre de stage dans le domaine Frontend. Rejoignez une equipe dynamique.', 735, '2026-10-06', '4 mois', 25),
  (28, 'Stage Administrateur Systeme', 'Offre de stage dans le domaine Systeme. Rejoignez une equipe dynamique.', 715, '2026-01-02', '3 mois', 21),
  (29, 'Stage Achat', 'Offre de stage dans le domaine Achat. Rejoignez une equipe dynamique.', 580, '2026-07-14', '6 mois', 15),
  (30, 'Stage Audit', 'Offre de stage dans le domaine Audit. Rejoignez une equipe dynamique.', 650, '2026-06-02', '3 mois', 18);

-- offre_competence
INSERT INTO offre_competence (id_offre, id_competence) VALUES
  (1, 14),
  (1, 3),
  (2, 19),
  (2, 2),
  (2, 5),
  (3, 3),
  (3, 5),
  (3, 9),
  (4, 20),
  (4, 9),
  (5, 5),
  (5, 9),
  (5, 18),
  (6, 2),
  (6, 20),
  (6, 5),
  (7, 19),
  (7, 8),
  (7, 20),
  (7, 4),
  (8, 7),
  (8, 9),
  (9, 1),
  (9, 16),
  (9, 8),
  (10, 4),
  (10, 11),
  (10, 14),
  (11, 9),
  (11, 14),
  (11, 10),
  (12, 11),
  (12, 18),
  (12, 14),
  (12, 8),
  (13, 2),
  (13, 14),
  (13, 13),
  (14, 18),
  (14, 13),
  (15, 17),
  (15, 7),
  (15, 4),
  (15, 19),
  (16, 12),
  (16, 10),
  (16, 4),
  (17, 18),
  (17, 14),
  (18, 13),
  (18, 5),
  (18, 1),
  (19, 3),
  (19, 17),
  (20, 5),
  (20, 17),
  (20, 16),
  (20, 9),
  (21, 15),
  (21, 20),
  (21, 19),
  (22, 3),
  (22, 14),
  (23, 14),
  (23, 4),
  (23, 15),
  (23, 18),
  (24, 17),
  (24, 13),
  (24, 11),
  (25, 3),
  (25, 5),
  (25, 9),
  (26, 16),
  (26, 3),
  (26, 6),
  (27, 1),
  (27, 3),
  (28, 9),
  (28, 4),
  (28, 19),
  (29, 18),
  (29, 20),
  (30, 9),
  (30, 16),
  (30, 11);

-- wishlist
INSERT INTO wishlist (id_utilisateur, id_offre) VALUES
  (12, 27),
  (26, 2),
  (63, 30),
  (17, 15),
  (8, 9),
  (17, 5),
  (24, 26),
  (32, 1),
  (55, 30),
  (69, 2),
  (40, 21),
  (46, 15),
  (68, 8),
  (58, 19),
  (42, 30),
  (57, 24),
  (43, 14),
  (35, 14),
  (65, 18),
  (53, 4),
  (65, 1),
  (40, 17),
  (30, 3),
  (15, 11),
  (4, 16),
  (59, 5),
  (68, 4),
  (36, 5),
  (13, 21),
  (51, 22),
  (68, 12),
  (72, 2),
  (64, 11),
  (66, 9),
  (56, 12),
  (53, 29),
  (45, 17),
  (44, 18),
  (18, 30),
  (40, 1),
  (21, 15),
  (69, 14),
  (37, 15),
  (70, 10),
  (11, 15),
  (20, 14),
  (67, 24),
  (61, 8),
  (33, 19),
  (41, 22),
  (36, 3),
  (20, 5),
  (48, 9),
  (23, 16),
  (67, 27),
  (39, 15),
  (37, 21),
  (4, 30),
  (24, 30),
  (34, 16);

-- candidature
INSERT INTO candidature (id_candidature, cv, lettre_motivation, date_candidature, id_utilisateur, id_offre) VALUES
  (1, NULL, NULL, '2025-03-27', 46, 1),
  (2, NULL, NULL, '2025-06-17', 65, 17),
  (3, NULL, NULL, '2024-09-30', 37, 3),
  (4, NULL, NULL, '2024-05-20', 43, 18),
  (5, NULL, NULL, '2024-05-28', 63, 27),
  (6, NULL, NULL, '2024-03-27', 60, 4),
  (7, NULL, NULL, '2023-04-26', 9, 23),
  (8, NULL, NULL, '2023-07-04', 30, 22),
  (9, NULL, NULL, '2025-05-09', 66, 29),
  (10, NULL, NULL, '2025-08-28', 39, 12),
  (11, NULL, NULL, '2025-07-12', 61, 13),
  (12, NULL, NULL, '2025-01-10', 51, 1),
  (13, NULL, NULL, '2025-01-20', 11, 21),
  (14, NULL, NULL, '2025-03-02', 18, 27),
  (15, NULL, NULL, '2024-02-22', 44, 6),
  (16, NULL, NULL, '2025-06-09', 72, 10),
  (17, NULL, NULL, '2025-07-02', 58, 26),
  (18, NULL, NULL, '2025-06-01', 58, 14),
  (19, NULL, NULL, '2023-05-04', 48, 9),
  (20, NULL, NULL, '2023-04-17', 40, 18),
  (21, NULL, NULL, '2023-09-08', 35, 2),
  (22, NULL, NULL, '2025-04-11', 66, 19),
  (23, NULL, NULL, '2023-10-06', 36, 8),
  (24, NULL, NULL, '2024-07-14', 39, 14),
  (25, NULL, NULL, '2025-05-19', 67, 15),
  (26, NULL, NULL, '2024-08-04', 57, 11),
  (27, NULL, NULL, '2024-04-20', 75, 22),
  (28, NULL, NULL, '2023-07-18', 19, 19),
  (29, NULL, NULL, '2024-08-09', 73, 17),
  (30, NULL, NULL, '2025-10-18', 68, 22),
  (31, NULL, NULL, '2025-03-29', 72, 19),
  (32, NULL, NULL, '2025-03-11', 4, 13),
  (33, NULL, NULL, '2025-07-22', 18, 7),
  (34, NULL, NULL, '2025-10-20', 34, 6),
  (35, NULL, NULL, '2023-02-26', 25, 7),
  (36, NULL, NULL, '2023-01-07', 48, 26),
  (37, NULL, NULL, '2025-08-28', 31, 7),
  (38, NULL, NULL, '2025-01-31', 6, 9),
  (39, NULL, NULL, '2024-08-10', 55, 15),
  (40, NULL, NULL, '2023-08-14', 70, 2);

-- avis
INSERT INTO avis (id_avis, commentaire, note, date_avis, id_utilisateur, id_entreprise) VALUES
  (1, 'Excellente entreprise, projets interessants.', 3, '2024-03-26', 21, 1),
  (2, 'Bonne ambiance mais missions peu variees.', 3, '2024-06-06', 8, 13),
  (3, 'Encadrement de qualite, je recommande.', 2, '2023-03-17', 37, 24),
  (4, 'Peu de retours de la part du tuteur.', 3, '2024-09-07', 26, 1),
  (5, 'Accueil chaleureux et bonne integration.', 3, '2025-01-22', 16, 9),
  (6, 'Encadrement de qualite, je recommande.', 3, '2024-03-06', 51, 3),
  (7, 'Stage enrichissant avec de vraies responsabilites.', 3, '2023-10-08', 21, 12),
  (8, 'Accueil chaleureux et bonne integration.', 2, '2023-06-16', 49, 13),
  (9, 'Bonne ambiance mais missions peu variees.', 2, '2023-01-28', 37, 1),
  (10, 'Tres bonne experience, equipe accueillante.', 2, '2024-04-17', 1, 23),
  (11, 'Entreprise serieuse avec de bonnes perspectives.', 3, '2025-03-21', 63, 2),
  (12, 'Missions en adequation avec ma formation.', 3, '2023-04-19', 35, 22),
  (13, 'Tres bonne experience, equipe accueillante.', 5, '2023-09-25', 24, 3),
  (14, 'Missions en adequation avec ma formation.', 4, '2023-02-03', 15, 16),
  (15, 'Entreprise serieuse avec de bonnes perspectives.', 2, '2025-09-12', 5, 18),
  (16, 'Missions en adequation avec ma formation.', 3, '2025-12-22', 58, 24),
  (17, 'Peu de retours de la part du tuteur.', 5, '2023-05-01', 40, 18),
  (18, 'Super experience, j''ai beaucoup appris.', 3, '2024-01-02', 44, 3),
  (19, 'Stage enrichissant avec de vraies responsabilites.', 4, '2024-10-10', 25, 14),
  (20, 'Excellente entreprise, projets interessants.', 1, '2024-01-08', 22, 17),
  (21, 'Accueil chaleureux et bonne integration.', 4, '2024-12-26', 58, 17),
  (22, 'Bonne ambiance mais missions peu variees.', 4, '2023-10-02', 24, 12),
  (23, 'Bonne ambiance mais missions peu variees.', 5, '2024-05-31', 72, 22),
  (24, 'Bonne ambiance mais missions peu variees.', 3, '2025-06-28', 40, 22),
  (25, 'Accueil chaleureux et bonne integration.', 2, '2024-12-06', 59, 18),
  (26, 'Stage enrichissant avec de vraies responsabilites.', 2, '2025-06-03', 52, 13),
  (27, 'Super experience, j''ai beaucoup appris.', 2, '2024-04-13', 22, 15),
  (28, 'Missions en adequation avec ma formation.', 2, '2025-05-26', 70, 9),
  (29, 'Super experience, j''ai beaucoup appris.', 4, '2023-11-15', 30, 8),
  (30, 'Encadrement de qualite, je recommande.', 3, '2023-11-28', 17, 23),
  (31, 'Peu de retours de la part du tuteur.', 2, '2024-01-31', 5, 4),
  (32, 'Entreprise serieuse avec de bonnes perspectives.', 5, '2024-11-15', 31, 3),
  (33, 'Bonne ambiance mais missions peu variees.', 4, '2025-10-30', 73, 22),
  (34, 'Super experience, j''ai beaucoup appris.', 3, '2025-05-05', 70, 23),
  (35, 'Entreprise serieuse avec de bonnes perspectives.', 3, '2024-03-08', 43, 6);

SET FOREIGN_KEY_CHECKS = 1;


GRANT ALL PRIVILEGES ON *.* TO `Admin`@`%` IDENTIFIED BY PASSWORD '*B8E22781821DC07031C5EC98BB8BC1012E689E2D' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON `job2main`.* TO `Admin`@`%`;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO `Pilote`@`%` IDENTIFIED BY PASSWORD '*3CE1965C9C626D2F1881AA1B18DA8C4D462BB153';
GRANT SELECT, INSERT, UPDATE, DELETE ON `job2main`.* TO `Pilote`@`%`;

GRANT SELECT, FILE ON *.* TO `etudiant`@`%` IDENTIFIED BY PASSWORD '*9F5FC07C6B0125D07952E4EBA6F7936FA3960B83';

GRANT SELECT ON *.* TO ``@`%`;
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, REFERENCES, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, EVENT, TRIGGER, DELETE HISTORY ON `test`.* TO ``@`%`;
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, REFERENCES, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, EVENT, TRIGGER, DELETE HISTORY ON `test\_%`.* TO ``@`%`;