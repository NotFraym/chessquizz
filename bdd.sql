-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

-- Listage de la structure de la base pour chessquizz
CREATE DATABASE IF NOT EXISTS `chessquizz` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `chessquizz`;

-- Listage de la structure de table chessquizz. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(255) NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `intitule` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage de la structure de table chessquizz. questions
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` int DEFAULT NULL,
  `bonne_reponse` varchar(255) DEFAULT NULL,
  `img_question` varchar(255) DEFAULT NULL,
  `difficulte` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nom_categorie` (`nom_categorie`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`nom_categorie`) REFERENCES `categorie` (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage de la structure de table chessquizz. utilisateur
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `statut` varchar(50) NOT NULL DEFAULT 'membre',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

