-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 01 avr. 2026 à 09:35
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
-- Base de données : `projet_web`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `id_adresse` int(11) NOT NULL,
  `nom_rue` varchar(50) NOT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `code_postal` varchar(50) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `adresse`
--

INSERT INTO `adresse` (`id_adresse`, `nom_rue`, `ville`, `code_postal`, `latitude`, `longitude`) VALUES
(1, '11 Rue de la Paix', NULL, '75002', NULL, NULL),
(2, '126 Rue de Rivoli', NULL, '75001', NULL, NULL),
(3, '40 Boulevard Haussmann', NULL, '75009', NULL, NULL),
(4, '15 Avenue Kléber', NULL, '75016', NULL, NULL),
(5, '127 Rue du Faubourg Saint-Antoine', 'Paris', '75011', 48.85106990, 2.37742010),
(6, '43 Rue des Écoles', NULL, '75005', NULL, NULL),
(7, '34 Rue des Martyrs', NULL, '75009', NULL, NULL),
(8, '155 Rue de Rennes', NULL, '75006', NULL, NULL),
(9, '1 Avenue Jean Jaurès', NULL, '75019', NULL, NULL),
(10, '22 Rue du 4 Septembre', NULL, '75002', NULL, NULL),
(11, '1 Place d\'Armes', NULL, '57000', NULL, NULL),
(12, '30 Rue des Clercs', NULL, '57000', NULL, NULL),
(13, '10 Rue Serpenoise', NULL, '57000', NULL, NULL),
(14, '5 Rue du Palais', NULL, '57000', NULL, NULL),
(15, '12 Rue Taison', NULL, '57000', NULL, NULL),
(16, '8 Rue Haute Seille', NULL, '57000', NULL, NULL),
(17, '20 Avenue Foch', NULL, '57000', NULL, NULL),
(18, '3 Rue Gambetta', NULL, '57000', NULL, NULL),
(19, '14 Rue Fabert', 'Metz', '57000', 49.11848610, 6.17511210),
(20, '9 Rue du Grand Cerf', NULL, '57000', NULL, NULL),
(21, '18 Rue du 22 Novembre', NULL, '67000', NULL, NULL),
(22, '7 Rue Mercière', 'Strasbourg', '67000', 48.58143320, 7.74965900),
(23, '12 Rue des Tonneliers', NULL, '67000', NULL, NULL),
(24, '5 Place du Château', NULL, '67000', NULL, NULL),
(25, '10 Rue de l\'Outre', NULL, '67000', NULL, NULL),
(26, '24 Rue du Vieux Marché aux Vins', NULL, '67000', NULL, NULL),
(27, '14 Rue des Dentelles', NULL, '67000', NULL, NULL),
(28, '120 Grand Rue', NULL, '67000', NULL, NULL),
(29, '10 Boulevard de la Victoire', NULL, '67000', NULL, NULL),
(30, '1 Quai Ernest Bevin', 'Strasbourg', '67000', 48.59859390, 7.76991130),
(31, '80 Boulevard du Général Leclerc', NULL, '44000', NULL, NULL),
(32, '12 Rue de la République', NULL, '69002', NULL, NULL),
(33, '5 Rue de la Loge', NULL, '34000', NULL, NULL),
(34, '10 Rue Nationale', NULL, '59800', NULL, NULL),
(35, '8 Rue Alsace Lorraine', NULL, '31000', NULL, NULL),
(36, '15 Rue Sainte-Catherine', NULL, '33000', NULL, NULL),
(37, '2 Rue Foch', NULL, '21000', NULL, NULL),
(38, '9 Rue d\'Italie', NULL, '06000', NULL, NULL),
(39, '7 Rue Thiers', NULL, '38000', NULL, NULL),
(40, '6 Rue du Port', NULL, '17000', NULL, NULL),
(41, '25 Avenue de la Gare', NULL, '74000', NULL, NULL),
(42, '18 Rue Pasteur', NULL, '51100', NULL, NULL),
(43, '22 Rue Victor Hugo', NULL, '37000', NULL, NULL),
(44, '3 Rue Nationale', NULL, '37000', NULL, NULL),
(45, '11 Rue Saint-Nicolas', NULL, '54000', NULL, NULL),
(46, '6 Rue des Jardins', NULL, '68000', NULL, NULL),
(47, '14 Rue du Moulin', NULL, '76600', NULL, NULL),
(48, '9 Rue des Fleurs', NULL, '59000', NULL, NULL),
(49, '17 Rue de l\'Église', NULL, '57200', NULL, NULL),
(50, '4 Rue Principale', 'Schiltigheim', '67300', 48.60759570, 7.75215340);

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id_avis` int(11) NOT NULL,
  `commentaire` text DEFAULT NULL,
  `note` int(11) DEFAULT NULL,
  `date_avis` date DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_entreprise` int(11) NOT NULL
) ;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id_avis`, `commentaire`, `note`, `date_avis`, `id_utilisateur`, `id_entreprise`) VALUES
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
(18, 'Super experience, j\'ai beaucoup appris.', 3, '2024-01-02', 44, 3),
(19, 'Stage enrichissant avec de vraies responsabilites.', 4, '2024-10-10', 25, 14),
(20, 'Excellente entreprise, projets interessants.', 1, '2024-01-08', 22, 17),
(21, 'Accueil chaleureux et bonne integration.', 4, '2024-12-26', 58, 17),
(22, 'Bonne ambiance mais missions peu variees.', 4, '2023-10-02', 24, 12),
(23, 'Bonne ambiance mais missions peu variees.', 5, '2024-05-31', 72, 22),
(24, 'Bonne ambiance mais missions peu variees.', 3, '2025-06-28', 40, 22),
(25, 'Accueil chaleureux et bonne integration.', 2, '2024-12-06', 59, 18),
(26, 'Stage enrichissant avec de vraies responsabilites.', 2, '2025-06-03', 52, 13),
(27, 'Super experience, j\'ai beaucoup appris.', 2, '2024-04-13', 22, 15),
(28, 'Missions en adequation avec ma formation.', 2, '2025-05-26', 70, 9),
(29, 'Super experience, j\'ai beaucoup appris.', 4, '2023-11-15', 30, 8),
(30, 'Encadrement de qualite, je recommande.', 3, '2023-11-28', 17, 23),
(31, 'Peu de retours de la part du tuteur.', 2, '2024-01-31', 5, 4),
(32, 'Entreprise serieuse avec de bonnes perspectives.', 5, '2024-11-15', 31, 3),
(33, 'Bonne ambiance mais missions peu variees.', 4, '2025-10-30', 73, 22),
(34, 'Super experience, j\'ai beaucoup appris.', 3, '2025-05-05', 70, 23),
(35, 'Entreprise serieuse avec de bonnes perspectives.', 3, '2024-03-08', 43, 6);

-- --------------------------------------------------------

--
-- Structure de la table `candidature`
--

CREATE TABLE `candidature` (
  `id_candidature` int(11) NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `lettre_motivation` text DEFAULT NULL,
  `date_candidature` date DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_offre` int(11) NOT NULL,
  `statut` varchar(50) DEFAULT 'En attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `candidature`
--

INSERT INTO `candidature` (`id_candidature`, `cv`, `lettre_motivation`, `date_candidature`, `id_utilisateur`, `id_offre`, `statut`) VALUES
(1, NULL, NULL, '2025-03-27', 46, 1, 'En attente'),
(2, NULL, NULL, '2025-06-17', 65, 17, 'En attente'),
(3, NULL, NULL, '2024-09-30', 37, 3, 'En attente'),
(4, NULL, NULL, '2024-05-20', 43, 18, 'En attente'),
(5, NULL, NULL, '2024-05-28', 63, 27, 'En attente'),
(6, NULL, NULL, '2024-03-27', 60, 4, 'En attente'),
(7, NULL, NULL, '2023-04-26', 9, 23, 'En attente'),
(8, NULL, NULL, '2023-07-04', 30, 22, 'En attente'),
(9, NULL, NULL, '2025-05-09', 66, 29, 'En attente'),
(10, NULL, NULL, '2025-08-28', 39, 12, 'En attente'),
(11, NULL, NULL, '2025-07-12', 61, 13, 'En attente'),
(12, NULL, NULL, '2025-01-10', 51, 1, 'En attente'),
(13, NULL, NULL, '2025-01-20', 11, 21, 'En attente'),
(14, NULL, NULL, '2025-03-02', 18, 27, 'En attente'),
(15, NULL, NULL, '2024-02-22', 44, 6, 'En attente'),
(16, NULL, NULL, '2025-06-09', 72, 10, 'En attente'),
(17, NULL, NULL, '2025-07-02', 58, 26, 'En attente'),
(18, NULL, NULL, '2025-06-01', 58, 14, 'En attente'),
(19, NULL, NULL, '2023-05-04', 48, 9, 'En attente'),
(20, NULL, NULL, '2023-04-17', 40, 18, 'En attente'),
(21, NULL, NULL, '2023-09-08', 35, 2, 'En attente'),
(22, NULL, NULL, '2025-04-11', 66, 19, 'En attente'),
(23, NULL, NULL, '2023-10-06', 36, 8, 'En attente'),
(24, NULL, NULL, '2024-07-14', 39, 14, 'En attente'),
(25, NULL, NULL, '2025-05-19', 67, 15, 'En attente'),
(26, NULL, NULL, '2024-08-04', 57, 11, 'En attente'),
(27, NULL, NULL, '2024-04-20', 75, 22, 'En attente'),
(28, NULL, NULL, '2023-07-18', 19, 19, 'En attente'),
(29, NULL, NULL, '2024-08-09', 73, 17, 'En attente'),
(30, NULL, NULL, '2025-10-18', 68, 22, 'En attente'),
(31, NULL, NULL, '2025-03-29', 72, 19, 'En attente'),
(32, NULL, NULL, '2025-03-11', 4, 13, 'En attente'),
(33, NULL, NULL, '2025-07-22', 18, 7, 'En attente'),
(34, NULL, NULL, '2025-10-20', 34, 6, 'En attente'),
(35, NULL, NULL, '2023-02-26', 25, 7, 'En attente'),
(36, NULL, NULL, '2023-01-07', 48, 26, 'En attente'),
(37, NULL, NULL, '2025-08-28', 31, 7, 'En attente'),
(38, NULL, NULL, '2025-01-31', 6, 9, 'En attente'),
(39, NULL, NULL, '2024-08-10', 55, 15, 'En attente'),
(40, NULL, NULL, '2023-08-14', 70, 2, 'En attente'),
(42, 'C:\\xampp\\htdocs\\job2main/uploads/file69cbdf759a5718.36821398.png', 'caca', '2026-03-31', 76, 14, 'En attente');

-- --------------------------------------------------------

--
-- Structure de la table `competence`
--

CREATE TABLE `competence` (
  `id_competence` int(11) NOT NULL,
  `nom_competence` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `competence`
--

INSERT INTO `competence` (`id_competence`, `nom_competence`) VALUES
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

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `id_entreprise` int(11) NOT NULL,
  `nom_entreprise` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `id_secteur` int(11) DEFAULT NULL,
  `id_adresse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`id_entreprise`, `nom_entreprise`, `description`, `email`, `telephone`, `id_secteur`, `id_adresse`) VALUES
(1, 'TransLogistic', 'Reseau de distribution multicanal.', 'contact@translogistic.com', '0352401207', 9, 5),
(2, 'ShopMax', 'Expert en gestion financiere et investissement.', 'contact@shopmax.fr', '0180824106', 5, 7),
(3, 'InvestGroup', 'Agence de voyages et tourisme d\'affaires.', 'contact@investgroup.com', '0177467989', 7, 30),
(4, 'EduLearn', 'Agence de voyages et tourisme d\'affaires.', 'contact@edulearn.com', '0371990718', 4, 26),
(5, 'MediCare Solutions', 'Reseau de distribution multicanal.', 'contact@medicaresolutions.com', '0281700042', 2, 15),
(6, 'BatiPro', 'Specialiste des solutions numeriques innovantes.', 'contact@batipro.com', '0154564023', 9, 43),
(7, 'PharmaCo', 'Specialiste des solutions numeriques innovantes.', 'contact@pharmaco.com', '0353555479', 1, 33),
(8, 'TechVision', 'Plateforme de formation en ligne certifiee.', 'contact@techvision.com', '0551165517', 2, 26),
(9, 'CloudNet', 'Producteur et distributeur de produits frais.', 'contact@cloudnet.fr', '0395371948', 9, 18),
(10, 'EnergiaVert', 'Expert en gestion financiere et investissement.', 'contact@energiavert.com', '0564555266', 8, 23),
(11, 'RapidoTransit', 'Producteur et distributeur de produits frais.', 'contact@rapidotransit.fr', '0147464888', 9, 9),
(12, 'CliniqSante', 'Plateforme de formation en ligne certifiee.', 'contact@cliniqsante.fr', '0261248694', 3, 50),
(13, 'DataSoft', 'Reseau de distribution multicanal.', 'contact@datasoft.com', '0432362329', 2, 45),
(14, 'FinPro Group', 'Operateur logistique national et international.', 'contact@finprogroup.com', '0276641005', 9, 1),
(15, 'CreditPlus', 'Entreprise de construction et renovation.', 'contact@creditplus.fr', '0274021246', 10, 12),
(16, 'SolarTech', 'Plateforme de formation en ligne certifiee.', 'contact@solartech.com', '0459077993', 8, 14),
(17, 'MarketPlace', 'Entreprise de construction et renovation.', 'contact@marketplace.com', '0187486641', 1, 23),
(18, 'CargoExpress', 'Producteur et distributeur de produits frais.', 'contact@cargoexpress.com', '0233218723', 3, 32),
(19, 'SejoursPlus', 'Producteur et distributeur de produits frais.', 'contact@sejoursplus.fr', '0122486374', 4, 47),
(20, 'TravelEasy', 'Expert en gestion financiere et investissement.', 'contact@traveleasy.fr', '0141337777', 4, 21),
(21, 'UrbanBuild', 'Producteur et distributeur de produits frais.', 'contact@urbanbuild.com', '0400353553', 7, 6),
(22, 'AgroFresh', 'Operateur logistique national et international.', 'contact@agrofresh.fr', '0269767911', 3, 27),
(23, 'NutriFood', 'Prestataire de services de sante de proximite.', 'contact@nutrifood.com', '0288696470', 8, 36),
(24, 'SkillAcademy', 'Producteur et distributeur de produits frais.', 'contact@skillacademy.com', '0581594511', 3, 27),
(25, 'FormaPro', 'Expert en gestion financiere et investissement.', 'contact@formapro.fr', '0422294962', 8, 35);

-- --------------------------------------------------------

--
-- Structure de la table `entreprise_adresse`
--

CREATE TABLE `entreprise_adresse` (
  `id_entreprise` int(11) NOT NULL,
  `id_adresse` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprise_adresse`
--

INSERT INTO `entreprise_adresse` (`id_entreprise`, `id_adresse`) VALUES
(1, 5),
(2, 7),
(2, 28),
(2, 29),
(3, 30),
(4, 26),
(4, 34),
(4, 50),
(5, 15),
(6, 43),
(7, 22),
(7, 33),
(7, 48),
(8, 26),
(8, 34),
(8, 38),
(9, 18),
(10, 19),
(10, 23),
(10, 45),
(11, 9),
(11, 47),
(12, 50),
(13, 18),
(13, 45),
(14, 1),
(15, 12),
(16, 14),
(17, 18),
(17, 23),
(17, 39),
(18, 25),
(18, 32),
(18, 47),
(19, 4),
(19, 29),
(19, 47),
(20, 20),
(20, 21),
(21, 6),
(22, 27),
(23, 33),
(23, 36),
(23, 49),
(24, 13),
(24, 27),
(25, 35);

-- --------------------------------------------------------

--
-- Structure de la table `offre`
--

CREATE TABLE `offre` (
  `id_offre` int(11) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `gratification` decimal(5,2) DEFAULT NULL,
  `date_offre` date DEFAULT NULL,
  `duree` varchar(50) DEFAULT NULL,
  `id_entreprise` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offre`
--

INSERT INTO `offre` (`id_offre`, `titre`, `description`, `gratification`, `date_offre`, `duree`, `id_entreprise`) VALUES
(1, 'Stage Developpeur Web', 'Offre de stage dans le domaine Web. Rejoignez une equipe dynamique.', 630.50, '2026-10-20', '5 mois', 10),
(2, 'Stage Data Analyst', 'Offre de stage dans le domaine Analyst. Rejoignez une equipe dynamique.', 720.00, '2026-02-01', '6 mois', 8),
(3, 'Stage Marketing', 'Offre de stage dans le domaine Marketing. Rejoignez une equipe dynamique.', NULL, '2026-04-09', '1 mois', 10),
(4, 'Stage DevOps', 'Offre de stage dans le domaine DevOps. Rejoignez une equipe dynamique.', 750.00, '2026-02-17', '4 mois', 25),
(5, 'Stage UX Designer', 'Offre de stage dans le domaine Designer. Rejoignez une equipe dynamique.', 610.00, '2026-07-13', '2 mois', 12),
(6, 'Stage Comptabilite', 'Offre de stage dans le domaine Comptabilite. Rejoignez une equipe dynamique.', 590.00, '2026-06-12', '4 mois', 17),
(7, 'Stage Cybersecurite', 'Offre de stage dans le domaine Cybersecurite. Rejoignez une equipe dynamique.', 680.00, '2026-06-23', '2 mois', 6),
(8, 'Stage Chef de projet', 'Offre de stage dans le domaine projet. Rejoignez une equipe dynamique.', 680.00, '2026-12-30', '4 mois', 3),
(9, 'Stage RH', 'Offre de stage dans le domaine RH. Rejoignez une equipe dynamique.', 577.50, '2026-10-09', '4 mois', 4),
(10, 'Stage Commercial', 'Offre de stage dans le domaine Commercial. Rejoignez une equipe dynamique.', 600.00, '2026-05-24', '5 mois', 25),
(11, 'Alternance Developpeur', 'Offre de stage dans le domaine Developpeur. Rejoignez une equipe dynamique.', 800.00, '2026-01-03', '6 mois', 15),
(12, 'Stage IA/ML', 'Offre de stage dans le domaine IA/ML. Rejoignez une equipe dynamique.', 770.00, '2026-08-31', '2 mois', 16),
(13, 'Stage Reseau', 'Offre de stage dans le domaine Reseau. Rejoignez une equipe dynamique.', 100.00, '2026-10-26', '1 mois', 6),
(14, 'Stage Communication', 'Offre de stage dans le domaine Communication. Rejoignez une equipe dynamique.', 580.00, '2026-05-09', '2 mois', 7),
(15, 'Stage Finance', 'Offre de stage dans le domaine Finance. Rejoignez une equipe dynamique.', 150.00, '2026-09-07', '1 mois', 12),
(17, 'Stage Developpeur Mobile', 'Offre de stage dans le domaine Mobile. Rejoignez une equipe dynamique.', 740.00, '2026-01-09', '6 mois', 22),
(18, 'Stage Product Manager', 'Offre de stage dans le domaine Manager. Rejoignez une equipe dynamique.', 660.00, '2026-03-06', '5 mois', 16),
(19, 'Stage Support IT', 'Offre de stage dans le domaine IT. Rejoignez une equipe dynamique.', 400.00, '2026-05-31', '2 mois', 10),
(20, 'Stage Juridique', 'Offre de stage dans le domaine Juridique. Rejoignez une equipe dynamique.', 577.50, '2026-01-06', '5 mois', 18),
(21, 'Stage Developpeur Backend', 'Offre de stage dans le domaine Backend. Rejoignez une equipe dynamique.', 750.00, '2026-01-17', '6 mois', 7),
(22, 'Stage Community Manager', 'Offre de stage dans le domaine Manager. Rejoignez une equipe dynamique.', 577.50, '2026-07-29', '3 mois', 2),
(23, 'Stage Ingenieur Donnees', 'Offre de stage dans le domaine Donnees. Rejoignez une equipe dynamique.', 730.00, '2026-05-30', '3 mois', 6),
(24, 'Stage Responsable Qualite', 'Offre de stage dans le domaine Qualite. Rejoignez une equipe dynamique.', 610.00, '2026-02-25', '2 mois', 17),
(25, 'Stage Business Analyst', 'Offre de stage dans le domaine Analyst. Rejoignez une equipe dynamique.', 695.00, '2026-08-24', '2 mois', 11),
(26, 'Stage Infographiste', 'Offre de stage dans le domaine Infographiste. Rejoignez une equipe dynamique.', 590.00, '2026-11-20', '5 mois', 20),
(27, 'Stage Developpeur Frontend', 'Offre de stage dans le domaine Frontend. Rejoignez une equipe dynamique.', 735.00, '2026-10-06', '4 mois', 25),
(28, 'Stage Administrateur Systeme', 'Offre de stage dans le domaine Systeme. Rejoignez une equipe dynamique.', 715.00, '2026-01-02', '3 mois', 21),
(29, 'Stage Achat', 'Offre de stage dans le domaine Achat. Rejoignez une equipe dynamique.', 580.00, '2026-07-14', '6 mois', 15),
(30, 'Stage Audit', 'Offre de stage dans le domaine Audit. Rejoignez une equipe dynamique.', 650.00, '2026-06-02', '3 mois', 18);

-- --------------------------------------------------------

--
-- Structure de la table `offre_competence`
--

CREATE TABLE `offre_competence` (
  `id_offre` int(11) NOT NULL,
  `id_competence` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offre_competence`
--

INSERT INTO `offre_competence` (`id_offre`, `id_competence`) VALUES
(1, 3),
(1, 14),
(2, 2),
(2, 5),
(2, 19),
(3, 3),
(3, 5),
(3, 9),
(4, 9),
(4, 20),
(5, 5),
(5, 9),
(5, 18),
(6, 2),
(6, 5),
(6, 20),
(7, 4),
(7, 8),
(7, 19),
(7, 20),
(8, 7),
(8, 9),
(9, 1),
(9, 8),
(9, 16),
(10, 4),
(10, 11),
(10, 14),
(11, 9),
(11, 10),
(11, 14),
(12, 8),
(12, 11),
(12, 14),
(12, 18),
(13, 2),
(13, 13),
(13, 14),
(14, 13),
(14, 18),
(15, 4),
(15, 7),
(15, 17),
(15, 19),
(17, 14),
(17, 18),
(18, 1),
(18, 5),
(18, 13),
(19, 3),
(19, 17),
(20, 5),
(20, 9),
(20, 16),
(20, 17),
(21, 15),
(21, 19),
(21, 20),
(22, 3),
(22, 14),
(23, 4),
(23, 14),
(23, 15),
(23, 18),
(24, 11),
(24, 13),
(24, 17),
(25, 3),
(25, 5),
(25, 9),
(26, 3),
(26, 6),
(26, 16),
(27, 1),
(27, 3),
(28, 4),
(28, 9),
(28, 19),
(29, 18),
(29, 20),
(30, 9),
(30, 11),
(30, 16);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nom_role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `nom_role`) VALUES
(0, 'etudiant'),
(1, 'pilote'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `secteur`
--

CREATE TABLE `secteur` (
  `id_secteur` int(11) NOT NULL,
  `nom_secteur` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `secteur`
--

INSERT INTO `secteur` (`id_secteur`, `nom_secteur`) VALUES
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

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom_utilisateur` varchar(50) DEFAULT NULL,
  `prenom_utilisateur` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `id_role` int(11) DEFAULT 0,
  `referent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom_utilisateur`, `prenom_utilisateur`, `email`, `telephone`, `mot_de_passe`, `id_role`, `referent_id`) VALUES
(1, 'Martinez', 'Ugo', 'ugo.martinez.1@import.local', '0684269257', '$2y$10$aUYXdAxrFSdFpHxLzqaRDua5Qy2IXBJ4p4QyOE2Y4AIk3uaytBWgC', 0, 4),
(2, 'Robin', 'Emma', 'emma.robin.2@import.local', '0600987236', '$2y$10$X9u4h/zqveMSd3JPqJ2gKO8jTTZX4kKrFGZgZaoBw7K03EKGetxQG', 2, NULL),
(3, 'Garnier', 'Ugo', 'ugo.garnier.3@import.local', '0618184524', '$2y$10$6zGB7rKspPe/iWseqPYkTex4ZcbdHnwOi6aQTbN1HYk8zRDXTbJM6', 0, 8),
(4, 'Lopez', 'Antoine', 'antoine.lopez.4@import.local', '0620991002', '$2y$10$qhzZHRyUDZjXp5Ct7xN/uOFXJILAJenSI8HdCpJ0m6vEJSK2spLpq', 1, NULL),
(5, 'Guerin', 'Lea', 'lea.guerin.5@import.local', '0725128391', '$2y$10$WsV.2Wgkd2qIO1W4z.gGP.0bWAgW8d7vDOfjbYHghwRzFvEOYWYU.', 0, 15),
(6, 'Lopez', 'Ivan', 'ivan.lopez.6@import.local', '0794084151', '$2y$10$3ZnjIOvxk.NSXa7DG985m.e.6f0gZlV0Ua8R.ZcxXrVkBUQiVSM1u', 2, NULL),
(7, 'Girard', 'Raphaël', 'raphael.girard.7@import.local', '0720652079', '$2y$10$VuTEAcojiRqKPSAB6CqnMewM0vwKl2m2NRzsIIzrgkxJa.KnTNyKu', 2, NULL),
(8, 'David', 'Victor', 'victor.david.8@import.local', '0654219685', '$2y$10$ior1kVTdVLckPVcfPmGIUOlNqw.wlgGD8RGE6i0MsmckUxyok3lJW', 1, NULL),
(9, 'Martin', 'Xenia', 'xenia.martin.9@import.local', '0746465368', '$2y$10$A.pTlHjm0OAgntY.HLIOXeZWAcacPeDL4kgprxunY1g1ez1nKlpKy', 0, 27),
(10, 'Garcia', 'Wendy', 'wendy.garcia.10@import.local', '0616414309', '$2y$10$7JmJSOYriX3Kk9Np7AYXUOFtDCwXKUkVqwEPhyejn9B49NTaWVXae', 0, NULL),
(11, 'Guerin', 'Emma', 'emma.guerin.11@import.local', '0616770610', '$2y$10$eDU4Q5c8bIrS6lcnV8kF1uijLFmQ0rt0vZPjdapzP4ldFpuYHXtW6', 0, 34),
(12, 'Simon', 'Ursula', 'ursula.simon.12@import.local', '0648035618', '$2y$10$rap/aFbnIpWCBjGKyomrWekxlvbN3kQ8/RCZJBNi8vaojAf5OqFTS', 0, NULL),
(13, 'Dupont', 'Fabrice', 'fabrice.dupont.13@import.local', '0720019945', '$2y$10$yKoJYXCdRZ3jm.zmodOsdeSBvEjBvOLRuiZJIYDKY2EI7AlZ58lyy', 0, 36),
(14, 'Girard', 'Thomas', 'thomas.girard.14@import.local', '0687185635', '$2y$10$wr0rKFw0R6EoKdCKyjLU.e9hi5kE80CbVQYzEmSI9AWtsFxVeoMNu', 0, 41),
(15, 'Fontaine', 'Lea', 'lea.fontaine.15@import.local', '0672772835', '$2y$10$Fmw6pK9qjAPbcrzjIrwew.VxFZivUCn/12kD6PnskCJJp7rqGqOJi', 1, NULL),
(16, 'Clement', 'Bruno', 'bruno.clement.16@import.local', '0739658595', '$2y$10$tfCBrz4rUiKjUWk7.w1apOzIEjQ0YN/X4tboclwxCpBEkpkPli5oC', 0, 44),
(17, 'Garnier', 'Julien', 'julien.garnier.17@import.local', '0690936845', '$2y$10$JlBNaFvdVI1Nk6Fs46etHuHxD7ltc83hilnBF.0ejASrkarHC88MO', 0, 51),
(18, 'Roux', 'Priya', 'priya.roux.18@import.local', '0633977879', '$2y$10$UTVMCmAQI/IzcieCpHUhH.scWqJlFB2leWhlzgbChQeyileVU.otO', 0, 55),
(19, 'Henry', 'Zoe', 'zoe.henry.19@import.local', '0754091868', '$2y$10$OpAuD/kBVMxCGzC2MM9WiOqckH.koT/RkdYwxtce4CRFIqrwwXKxi', 0, 58),
(20, 'Michel', 'Hugo', 'hugo.michel.20@import.local', '0678233591', '$2y$10$55WwxEzATKIUdFg8Ilx31OhXD79YHXL/DjMwClqwp0hdw3v0rTV36', 0, NULL),
(21, 'Nicolas', 'Helene', 'helene.nicolas.21@import.local', '0769074908', '$2y$10$vbHeNKpg6UXkGU6sjBJevOVek6lkF8yQ23gN8zAQ.p5s2IBQCD2j6', 0, NULL),
(22, 'Roux', 'Gael', 'gael.roux.22@import.local', '0608436201', '$2y$10$aRKpXpHoboCMhZ5XO/HSPuCEqjpJI3pNZyiDzBmNZPX7Z6kdmPi52', 0, 61),
(23, 'Petit', 'Vanessa', 'vanessa.petit.23@import.local', '0678358854', '$2y$10$RCQnkVVmpQbIAO5XstLSxOCzZPsG3X7PblLN1Of3Uv4Ei89j/x1DG', 0, 4),
(24, 'Laurent', 'Mohamed', 'mohamed.laurent.24@import.local', '0711295465', '$2y$10$EmXtzD5gezyTa9cj5H4xROFKblSEgWLK.aj0PWtV0T2/u90nUn5yq', 0, 8),
(25, 'David', 'Gael', 'gael.david.25@import.local', '0715358003', '$2y$10$Da8jKEXm.yeqLB1ta7z4qeLDHkd46VpQ8jRi75kIMtsKtn37Bwh3S', 0, 15),
(26, 'Lefebvre', 'Wendy', 'wendy.lefebvre.26@import.local', '0635673050', '$2y$10$N0onGt0JD6CXMh.m4wNQW.2cBDmmwCTO/hZCubyo5rqn6jmtzcHtq', 0, 27),
(27, 'Dubois', 'Diane', 'diane.dubois.27@import.local', '0623254489', '$2y$10$bZbkvJcMAjqbsdBIKBgJneM40IkWHWYQwE1fNu8Rsks5RR.O6fte6', 1, NULL),
(28, 'Guerin', 'Wendy', 'wendy.guerin.28@import.local', '0745160418', '$2y$10$2Kzpnwh1jczjA6a53m40ZO7sFZu8GV2bAXTUSSQK0POGzGmISIiRe', 0, 34),
(29, 'Dumont', 'Emma', 'emma.dumont.29@import.local', '0609976261', '$2y$10$jD/q.PyRKXV/sttXpm67geUqxkdpnWlrB/WX4Goka9jVgyftc4pJS', 0, NULL),
(30, 'Legrand', 'Vanessa', 'vanessa.legrand.30@import.local', '0759838837', '$2y$10$PF6ZZnMTuO.4r33NGQixh.pJmK53WBwBYYhbTtyBvBc2yGcYSKMjC', 0, 36),
(31, 'Rousseau', 'Helene', 'helene.rousseau.31@import.local', '0691836362', '$2y$10$zJX7Ac70MAJShFDstvku0ufxzz10PM1ZkTrKqGrbO1/3slY0qvXWC', 0, NULL),
(32, 'Legrand', 'Diane', 'diane.legrand.32@import.local', '0718272672', '$2y$10$QGUeUu9h8FxAk0IZgkVFiuZCFobI6kuD/YQdzUFjqyl0YLnqBvAya', 0, 41),
(33, 'Laurent', 'Helene', 'helene.laurent.33@import.local', '0687761359', '$2y$10$CJQXnQ0F9SZbPZcibyyQo.seKvYb5bWlcwaHyMB8115wqG9youG0O', 2, NULL),
(34, 'Lefebvre', 'Antoine', 'antoine.lefebvre.34@import.local', '0795676978', '$2y$10$1/YRF4tCgmMtUK2ERhamvOOvWTBKFKZNsK7vzCefu84KmmcfQZqQi', 1, NULL),
(35, 'Thomas', 'Maxime', 'maxime.thomas.35@import.local', '0616928524', '$2y$10$Uewc8n5Kxb.IRgDeHTKSFO.rGxRANFxH12co9AB0lgg36xQ1GIv0u', 0, 44),
(36, 'Bernard', 'Julien', 'julien.bernard.36@import.local', '0608744961', '$2y$10$.N9vJ8aXnjPuoX88wq7SzOksfhXb.hu5VsBZCX31h.w6ceT0zJA3i', 1, NULL),
(37, 'Lefebvre', 'Ugo', 'ugo.lefebvre.37@import.local', '0600092634', '$2y$10$864w6ZwuESgaX6SBjbYCdurAXuaC.XD3PebKwEr.pBle6NDXeE.S6', 0, 51),
(38, 'Morel', 'Nina', 'nina.morel.38@import.local', '0621393105', '$2y$10$2YbFpVuuTQ/Dep72GJgkd.2knDto1X2NUkumI8HATERVwyghYrgou', 0, 55),
(39, 'Roussel', 'Helene', 'helene.roussel.39@import.local', '0662022910', '$2y$10$6YM/HUITxf/ZlvelROaxx.e1fpaS55H3l4gAm7.t7XKKJaWAMahvS', 0, 58),
(40, 'Roux', 'Vanessa', 'vanessa.roux.40@import.local', '0780596168', '$2y$10$HyPwBkNX3ZaHz4bw00heT.GcjIv5GrHD/IUFKon/cS.1mYaOLee5S', 0, 61),
(41, 'Gauthier', 'Lea', 'lea.gauthier.41@import.local', '0679862766', '$2y$10$2GY/AtfUwRXUs4qUsYGB9Oxw/ESntnp.IGBcDRpKRZW4vI0uo.Qhe', 1, NULL),
(42, 'Girard', 'Zoe', 'zoe.girard.42@import.local', '0675405093', '$2y$10$KRzKVh5X2ZBDVORZv7drcuLLmdZeRupzQHME7ksQJM.XCUC4/h/PW', 0, 4),
(43, 'François', 'Florian', 'florian.francois.43@import.local', '0779022130', '$2y$10$QrzbFK1qKn1uHX5YZoXhJOSqgx.lP.M4fyWGuoTs0FtRILwNPCGlq', 0, NULL),
(44, 'Leroy', 'Hugo', 'hugo.leroy.44@import.local', '0719615518', '$2y$10$AXZ0zGIdzjHs2ljTYkv7z.Lz7hj.8qP1bzj3ykD/CVREoLSD7v28a', 1, NULL),
(45, 'Richard', 'Clara', 'clara.richard.45@import.local', '0738734340', '$2y$10$QoxKtkg/q2L79vs9rfhbvueVSipQmNCD2QM01bIGIJUiSRQZoMOxu', 2, NULL),
(46, 'Morin', 'Mohamed', 'mohamed.morin.46@import.local', '0656375453', '$2y$10$GD4Wd15r/KdRqdhba6kDL.40ZEy1ewQl90BeF3chLZjYPjqaTQVB2', 0, 8),
(47, 'Perrin', 'Ivan', 'ivan.perrin.47@import.local', '0636008079', '$2y$10$LN0bcZliY7G9xMsla9CoY.tPX3jsqW9teYm8SyPXlrUNrc9U/bZnu', 0, NULL),
(48, 'Muller', 'Olivier', 'olivier.muller.48@import.local', '0767508787', '$2y$10$maO/pr9adgYkZgVEus8uCu.0Y8ohIHRQqQWD4ZPUgaPpvsnSiI7Te', 0, NULL),
(49, 'Dumont', 'Nina', 'nina.dumont.49@import.local', '0670757163', '$2y$10$20uv9jgZQAGf.alN3FjpAO3lOxjFq7agmvCC2OZD8MTZuwK3.rJVu', 0, 15),
(50, 'Robert', 'Alice', 'alice.robert.50@import.local', '0757667162', '$2y$10$FDivOfqidNgmY2dsYlFPd.blr7VMJ7.dmb6RHbcU06sXyC7c9NhXS', 0, NULL),
(51, 'Legrand', 'Ugo', 'ugo.legrand.51@import.local', '0789955127', '$2y$10$A/2abx.0oxbH7mwKrQl45.WVxL7o9tXnQj53P7UAkL/IHTFKbqBXy', 1, NULL),
(52, 'Bernard', 'Cedric', 'cedric.bernard.52@import.local', '0759601320', '$2y$10$vNMsXcAANeT8PPTw/nEHGeZ45fIlQCsryxfkEGb13ftN.amLlKO3u', 0, 27),
(53, 'Dumont', 'Yann', 'yann.dumont.53@import.local', '0606116836', '$2y$10$XjPHGiaMJm6OTXdlXN2D4e1kJKMsCtsFdOpQsZONaAJjptVWA20HS', 0, 34),
(54, 'Lefevre', 'Xavier', 'xavier.lefevre.54@import.local', '0730262961', '$2y$10$def6i8naBJtSf5R24Wh0je1uXYCKq20R4rqWcbuhnvmS/2EXp38Ku', 0, NULL),
(55, 'Dupont', 'Maxime', 'maxime.dupont.55@import.local', '0676669619', '$2y$10$bjtynYVNLKF/HKpeaG664upi8ypNINkeBSTLBo3N4KeObHlXWdsRG', 1, NULL),
(56, 'Thomas', 'Priya', 'priya.thomas.56@import.local', '0725356719', '$2y$10$0IgfjJgsiHX0KzfCVMeM2uyLVDDnrCWOF/qMWeFLmZkB9Z8D3S.LK', 0, 34),
(57, 'Chevalier', 'Victor', 'victor.chevalier.57@import.local', '0761809548', '$2y$10$iucmG3P89AmYxbtnG5WrEu34VeazYUCM9pHZEFIsNKOlH9jclic0K', 0, 41),
(58, 'Henry', 'Emma', 'emma.henry.58@import.local', '0641163673', '$2y$10$8dA9igs1UBApkwWadHlwFekL3KGYkvYDmaYOVFqlcn89nx5MUcb96', 1, NULL),
(59, 'Roux', 'Emma', 'emma.roux.59@import.local', '0786794736', '$2y$10$p0FFE.BrZa3kk8c.PTryruW2DyvxvHn2v.razRZvfaAESt7T1D5fO', 0, 44),
(60, 'Girard', 'Vanessa', 'vanessa.girard.60@import.local', '0602489060', '$2y$10$4q3lfT1ymVu9q8mjWPQwauY.CmO6s55Uvqo261NrKPG3u8jnf6xFC', 0, 51),
(61, 'Vincent', 'Nina', 'nina.vincent.61@import.local', '0618661478', '$2y$10$meA3TDnBq0sGQmf9K.ONEu2LXbjDR/oePenwAPbkQrL8Kn0UqrOaG', 1, NULL),
(62, 'Rousseau', 'Laura', 'laura.rousseau.62@import.local', '0680912759', '$2y$10$bLWLjFoGTyJAeEtd63Ny1.32zI.bTLUo.CEUQYgN3rZkt1cZ8UB9m', 2, NULL),
(63, 'Chevalier', 'Xenia', 'xenia.chevalier.63@import.local', '0662973372', '$2y$10$OEAN/IJT82oRh641YFTfP.yFPfiwRpMxFTmKntQtLjCDJRBPJGlNu', 0, 55),
(64, 'Lefevre', 'Thea', 'thea.lefevre.64@import.local', '0715255227', '$2y$10$yIAmXXDlAzVuVuwtW2sxiOl4iODkzXXPO1x3K4k4Uto2Ough/8lnC', 0, 58),
(65, 'Henry', 'Vanessa', 'vanessa.henry.65@import.local', '0622086760', '$2y$10$j0ghgI0MQRELzftIcAH7wux2IHURmV9TIL4C9bBUmvMjFWUuAwAb2', 0, NULL),
(66, 'Moreau', 'Florian', 'florian.moreau.66@import.local', '0780637777', '$2y$10$ytf7Oe2sDZXjGoZRDN4NwOiU0ystx27OJUMcf5t0KYuEY.lSjU2FK', 0, 61),
(67, 'Rousseau', 'Kevin', 'kevin.rousseau.67@import.local', '0606164147', '$2y$10$IILuEE5Ziqg1PYDIUAh.RePtzI06b3Ui.WuOG7CURkXqUd99OQsNm', 0, 4),
(68, 'François', 'Jade', 'jade.francois.68@import.local', '0762674407', '$2y$10$zDHqJTAyKJI8/A6QagsDp.GGhG4o7nffiBfO2fa7WQL65w2EeCFa.', 0, NULL),
(69, 'Thomas', 'Priya', 'priya.thomas.69@import.local', '0723134431', '$2y$10$HvXdHZ8kKpqe0z6R/fIjb.Ut/8GdlHTJCAcuUdSSnS4yL7AQq66ga', 0, 8),
(70, 'David', 'Lea', 'lea.david.70@import.local', '0790032346', '$2y$10$l1/tQ1BgpEC64ONVknGpl.8EeofR/M8eG0LUEkLD4Y2G7OpAdwcRi', 2, NULL),
(71, 'Muller', 'Helene', 'helene.muller.71@import.local', '0778950940', '$2y$10$uK70gW.cVBpwzc5WKL8TlO1V2Ea9GQVfHt0htTo9R2CUzKolGCLSO', 0, NULL),
(72, 'Mercier', 'William', 'william.mercier.72@import.local', '0637132266', '$2y$10$tTkdpyaAGIODoot7Gj7wEelgpTixrrTbRoMAn5d.xr0UkwOf/dxrO', 0, 15),
(73, 'Richard', 'Victor', 'victor.richard.73@import.local', '0703816494', '$2y$10$vgQHUuxKyH1aqSydDN/zzOOf7tgRpw.5d8uXAknzp6kp7sBalcLCG', 0, 27),
(74, 'Rousseau', 'Rachel', 'rachel.rousseau.74@import.local', '0680510472', '$2y$10$8vXG.IdifZ01G2jnyU.eNO.i1AjoCWsw3D3/pGTYDFuFKcEdipOZS', 0, NULL),
(75, 'Robert', 'Yann', 'yann.robert.75@import.local', '0776137209', '$2y$10$ezpRuvsWjUY//DjPbm381enQojLl8qPUjVg0ZzZ3ITQ2b7QNoAgJK', 1, 34),
(76, 'Nom Etudiant test', 'Prenom etudiant test', 'test@etudiant.test', '0000000000', '$2y$10$v54Hy55nPjMnPqe1C.wFMe5Jj93deFOTxC4fZ6DxciMW6VleeKjsK', 0, NULL),
(77, 'Nom pilote Test', 'Prenom pilote Test', 'test@pilote.test', '0000000000', '$2y$10$Jle1tWVnKQlP/7wfyOSjPuVQjdbDMl7XPF9fNnSVFjOfv08FzSpQa', 1, NULL),
(78, 'Nom admin Test', 'Prenom admin Test', 'test@admin.test', '0000000000', '$2y$10$urCLO6yBZY/hjBVF7qczV.NKBxZtRIA8WW04S5/HzUiEau6UUsyvG', 2, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

CREATE TABLE `wishlist` (
  `id_utilisateur` int(11) NOT NULL,
  `id_offre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `wishlist`
--

INSERT INTO `wishlist` (`id_utilisateur`, `id_offre`) VALUES
(4, 30),
(8, 9),
(11, 15),
(12, 27),
(13, 21),
(15, 11),
(17, 5),
(17, 15),
(18, 30),
(20, 5),
(20, 14),
(21, 15),
(24, 26),
(24, 30),
(26, 2),
(30, 3),
(32, 1),
(33, 19),
(35, 14),
(36, 3),
(36, 5),
(37, 15),
(37, 21),
(39, 15),
(40, 1),
(40, 17),
(40, 21),
(41, 22),
(42, 30),
(43, 14),
(44, 18),
(45, 17),
(46, 15),
(48, 9),
(51, 22),
(53, 4),
(53, 29),
(55, 30),
(56, 12),
(57, 24),
(58, 19),
(59, 5),
(61, 8),
(63, 30),
(64, 11),
(65, 1),
(65, 18),
(66, 9),
(67, 24),
(67, 27),
(68, 4),
(68, 8),
(68, 12),
(69, 2),
(69, 14),
(70, 10),
(72, 2),
(76, 3),
(76, 8),
(76, 22),
(76, 25);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`id_adresse`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_entreprise` (`id_entreprise`);

--
-- Index pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD PRIMARY KEY (`id_candidature`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_offre` (`id_offre`);

--
-- Index pour la table `competence`
--
ALTER TABLE `competence`
  ADD PRIMARY KEY (`id_competence`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id_entreprise`),
  ADD KEY `id_secteur` (`id_secteur`);

--
-- Index pour la table `entreprise_adresse`
--
ALTER TABLE `entreprise_adresse`
  ADD PRIMARY KEY (`id_entreprise`,`id_adresse`),
  ADD KEY `id_adresse` (`id_adresse`);

--
-- Index pour la table `offre`
--
ALTER TABLE `offre`
  ADD PRIMARY KEY (`id_offre`),
  ADD KEY `id_entreprise` (`id_entreprise`);

--
-- Index pour la table `offre_competence`
--
ALTER TABLE `offre_competence`
  ADD PRIMARY KEY (`id_offre`,`id_competence`),
  ADD KEY `id_competence` (`id_competence`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `secteur`
--
ALTER TABLE `secteur`
  ADD PRIMARY KEY (`id_secteur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_role` (`id_role`);

--
-- Index pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id_utilisateur`,`id_offre`),
  ADD KEY `id_offre` (`id_offre`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `id_adresse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id_avis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `candidature`
--
ALTER TABLE `candidature`
  MODIFY `id_candidature` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `competence`
--
ALTER TABLE `competence`
  MODIFY `id_competence` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `id_entreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `offre`
--
ALTER TABLE `offre`
  MODIFY `id_offre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `secteur`
--
ALTER TABLE `secteur`
  MODIFY `id_secteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise` (`id_entreprise`);

--
-- Contraintes pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD CONSTRAINT `candidature_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `candidature_ibfk_2` FOREIGN KEY (`id_offre`) REFERENCES `offre` (`id_offre`);

--
-- Contraintes pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD CONSTRAINT `entreprise_ibfk_1` FOREIGN KEY (`id_secteur`) REFERENCES `secteur` (`id_secteur`);

--
-- Contraintes pour la table `entreprise_adresse`
--
ALTER TABLE `entreprise_adresse`
  ADD CONSTRAINT `entreprise_adresse_ibfk_1` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise` (`id_entreprise`),
  ADD CONSTRAINT `entreprise_adresse_ibfk_2` FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id_adresse`);

--
-- Contraintes pour la table `offre`
--
ALTER TABLE `offre`
  ADD CONSTRAINT `offre_ibfk_1` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise` (`id_entreprise`);

--
-- Contraintes pour la table `offre_competence`
--
ALTER TABLE `offre_competence`
  ADD CONSTRAINT `offre_competence_ibfk_1` FOREIGN KEY (`id_offre`) REFERENCES `offre` (`id_offre`),
  ADD CONSTRAINT `offre_competence_ibfk_2` FOREIGN KEY (`id_competence`) REFERENCES `competence` (`id_competence`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);

--
-- Contraintes pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`id_offre`) REFERENCES `offre` (`id_offre`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
