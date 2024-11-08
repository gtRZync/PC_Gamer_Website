<?php
$host = 'localhost';
$dbname = 'boutique'; 
$user = 'root'; 
$password = ''; 

try {
    // Connexion à MySQL
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la base de données si elle n'existe pas, puis utilisation
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci");
    $pdo->exec("USE `$dbname`");

    $sql = <<<SQL
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 05, 2024 at 03:06 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Table structure for table `categories`
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
`id` int NOT NULL AUTO_INCREMENT,
`nom` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table structure for table `panier`
DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
`id` int NOT NULL AUTO_INCREMENT,
`produit_id` int DEFAULT NULL,
`quantite` int DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `produit_id` (`produit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table structure for table `produits`
DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
`id` int NOT NULL AUTO_INCREMENT,
`nom` varchar(255) NOT NULL,
`description` text,
`prix` decimal(10,2) NOT NULL,
`categorie_id` int DEFAULT NULL,
`image` varchar(255) DEFAULT NULL,
`processeur` varchar(255) DEFAULT NULL,
`carte_graphique` varchar(255) DEFAULT NULL,
`ram` varchar(255) DEFAULT NULL,
`stockage` varchar(255) DEFAULT NULL,
`taille_ecran` varchar(10) DEFAULT NULL,
`resolution` varchar(20) DEFAULT NULL,
`frequence_rafraichissement` int DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `categorie_id` (`categorie_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insertion des données dans la table `produits`
INSERT INTO `produits` (`id`, `nom`, `description`, `prix`, `categorie_id`, `image`, `processeur`, `carte_graphique`, `ram`, `stockage`, `taille_ecran`, `resolution`, `frequence_rafraichissement`) VALUES
(1, 'MSI - Pc Portable Gaming GF63 I5 12450 / 16 Go / 512 Go SSD / RTX 4060 / 15.6\"', 'Un PC portable puissant pour le gaming', 1200.00, 6, 'images/msi_laptop.png', 'Intel Core i5-12450H', 'NVIDIA GeForce RTX 4060', '16GB DDR4', '512GB SSD', '', '',144),
(2, 'Player: Two | AMD Gamescom Edition', 'H6 Flow RGB Radeon RX 7800 XT Gaming PC', 2300.00, 5, 'images/amd_pc_gamer_fixe.png', 'AMD Ryzen 7 9700X \r\n   ', 'AMD Radeon RX 7800 XT', '32GB (2x16GB) DDR5 6000MHz', '2TB NVme M.2 SSD','', '', 0),
(3, 'Player: Three ', 'H7 Flow RTX 4070 Ti SUPER Prebuilt Gaming PC', 2149.00, 5, 'images/intel_pc_gamer_fixe.png', 'Intel Core i7-14700KF', 'NVIDIA GeForce RTX 4070 Ti SUPER', '32GB (2x16GB) DDR5 6000MHz', '1TB NVme M.2 SSD','','', 0),
(4, 'Samsung Odyssey OLED G8', 'A 34\" curved gaming monitor with a vibrant OLED display and with 0.03 response time, ideal for immersive gaming experiences.', 794.25, 4, 'https://microless.com/cdn/products/5006db34d25677427dfb675f36b9cbfa-md.jpg', '', '', '', '', '', '3440 x 1440', 175),
(5, 'MSI MAG 341CQP QD-OLED', 'A 34\" ultra-wide curved monitor, optimized for competitive gaming with a QD-OLED display.', 729.99, 4, 'https://c1.neweggimages.com/productimage/nb1280/24-475-350-11.jpg', '', '', '', '', '', '3440 x 1440 ', 240),
(6, 'MSI MAG 271QPX', 'A fast 27\" gaming monitor with QD-OLED technology, perfect for competitive FPS games.', 720.99, 4, 'https://c1.neweggimages.com/productimage/nb1280/24-475-360-10.jpg', '', '', '', '', '', '2560 x 1440', 360),
(7, 'Dell 27 Gaming Monitor (S2721DGF)', 'A 27-inch QHD monitor designed for high-end gaming with G-Sync compatibility.', 449.99, 4, 'https://multimedia.bbycastatic.ca/multimedia/products/500x500/163/16368/16368658.jpeg', '', '', '', '', '', '1920x1080', 165),
(8, 'Skytech Archangel Gaming PC', 'High-performance gaming PC with Ryzen 5 7600X processor, optimized for gaming and streaming.', 1938.09, 5, 'https://newegg.com', 'Ryzen 5 7600X', 'RTX 4070 Super 12GB', '32GB DDR5 RAM', '1TB SSD', '', '', 0),
(9, 'CyberPowerPC Gamer Xtreme', 'Prebuilt gaming desktop with Intel i7 processor and RTX 4060 Ti, providing high performance for gaming.', 2200, 5, 'https://bestbuy.com', 'Intel Core i7-13700F', 'RTX 4060 Ti', '16GB DDR5 RAM', '2TB SSD', '', '', 0),
(10, 'Periphio Gaming PC', 'A budget-friendly gaming desktop, ideal for entry-level gaming.', 749.99, 5, 'https://newegg.com', 'Ryzen 5 5600G', 'Radeon Vega 7', '16GB DDR4 RAM', '1TB SSD', '', '', 0),
(15, 'Skytech Archangel Gaming PC', 'High-performance gaming PC with Ryzen 5 7600X processor, optimized for gaming and streaming.', 2200, 5, 'https://newegg.com', 'Ryzen 5 7600X', 'RTX 4070 Super 12GB', '32GB DDR5 RAM', '1TB SSD', '', '', 0),
(16, 'CyberPowerPC Gamer Xtreme', 'Prebuilt gaming desktop with Intel i7 processor and RTX 4060 Ti, providing high performance for gaming.', 2300, 5, 'https://bestbuy.com', 'Intel Core i7-13700F', 'RTX 4060 Ti', '16GB DDR5 RAM', '2TB SSD', '', '', 0),
(17, 'Periphio Gaming PC', 'A budget-friendly gaming desktop, ideal for entry-level gaming.', 749.99, 5, 'https://newegg.com', 'Ryzen 5 5600G', 'Radeon Vega 7', '16GB DDR4 RAM', '1TB SSD', '', '', 0),
(18, 'Alienware Aurora R13', 'Alienware''s premium desktop offering for serious gamers and content creators.', 2199.99, 5, 'https://pisces.bbystatic.com/image2/BestBuy_US/images/products/6577/6577352_sd.jpg;maxHeight=200;maxWidth=223;format=webp', 'Intel i9-12900KF', 'RTX 3080 Ti', '64GB DDR5 RAM', '2TB SSD', '', '', 0),
(19, 'ASUS TUF Dash F15', 'A powerful, portable gaming laptop for gamers on the go.', 1200.00, 6, 'https://microless.com', 'Intel i7-12650H', 'RTX 3070 8GB', '16GB DDR5 RAM', '1TB SSD', '', '', 0),
(20, 'Dell G15', 'High-performance gaming laptop with a 165Hz display and RTX 4060 GPU.', 1820.45, 6, 'https://microless.com', 'Intel Core i7-13650HX', 'RTX 4060', '16GB DDR5 RAM', '512GB SSD', '', '', 0),
(21, 'Acer Predator Helios Neo 16', 'High-end gaming laptop with powerful specs for serious gaming.', 2000.99, 6, 'https://microless.com', 'Intel i9-13900HX', 'RTX 4060 8GB', '16GB DDR5 RAM', '1TB SSD', '', '', 0),
(22, 'ASUS Zenbook 14X OLED', 'A sleek and compact laptop with an OLED display, ideal for gaming and professional use.', 612.33, 6, 'https://microless.com', 'Intel i5-13500H', 'RTX 4070 Super Mobile 8 GB GDDR6', '8GB DDR5 RAM', '512GB SSD', '', '', 0),
(24, 'Razer DeathAdder V2', 'Ergonomic gaming mouse designed for precision in competitive gaming.', 69.99, 7, 'https://bestbuy.com', '', '', '', '', '', '', 0),
(25, 'SteelSeries Rival 600', 'Dual-sensor gaming mouse with adjustable weight system.', 79.99, 7, 'https://bestbuy.com', '', '', '', '', '', '', 0),
(26, 'Corsair Harpoon RGB Wireless', 'Wireless gaming mouse with a lightweight design and high DPI.', 49.99, 7, 'https://bestbuy.com', '', '', '', '', '', '', 0),
(27, 'Corsair K95 RGB Platinum', 'Premium mechanical keyboard with RGB lighting and macro support.', 199.99, 8, 'https://bestbuy.com', '', '', '', '', '', '', 0),
(28, 'Razer Huntsman Elite', 'Fast-response mechanical keyboard with Razer’s proprietary optical switches.', 179.99, 8, 'https://bestbuy.com', '', '', '', '', '', '', 0),
(29, 'SteelSeries Apex Pro', 'A fully customizable mechanical keyboard for high-end gaming.', 199.99, 8, 'https://bestbuy.com', '', '', '', '', '', '', 0),
(30, 'AOC Mechanical Gaming Keyboard GK410', 'Affordable RGB mechanical keyboard for gaming and office use.', 32.99, 8, 'https://newegg.com', '', '', '', '', '', '', 0);


-- Table structure for table `produit_categories`
DROP TABLE IF EXISTS `produit_categories`;
CREATE TABLE IF NOT EXISTS `produit_categories` (
`produit_id` int NOT NULL,
`category_id` int NOT NULL,
PRIMARY KEY (`produit_id`,`category_id`),
KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insertion des données dans la table `produit_categories`
INSERT INTO `produit_categories` (`produit_id`, `category_id`) VALUES
--Test pour la barre de recherches qui n'a jamais abouti
(1, 5),
(1, 6);

-- Table structure for table `utilisateurs`
DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
`id` int NOT NULL AUTO_INCREMENT,
`username` varchar(50) NOT NULL,
`email` varchar(100) NOT NULL,
`password` varchar(255) NOT NULL,
`created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
`reset_token` varchar(100) DEFAULT NULL,
`usertype` varchar(50) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insertion des données dans la table `utilisateurs`
INSERT INTO `utilisateurs` (`id`, `username`, `email`, `password`, `created_at`, `reset_token`, `usertype`) VALUES
(10, 'test', 'test@gmail.com', '$2y$10$hEctrjwwUDELtR9JWyFQSeqtYgdUQpaARLGIDhM4IodBlkRMVj5za', '2024-10-25 13:22:08', NULL, 'user'),
(52, 'admintest', 'admintest@gmail.com', '$2y$10$cuhLBlNWrX2n33ABye1Vbu37kQGtQ7abVASM.xrfrrn6rJUOxOGxK', '2024-10-25 02:04:24', NULL, 'admin'),
(31, 'sky jacob', 'skyjacob@gmail.com', '$2y$10$okpzd8nOdgDfSpY.578QjuSK05G9cYL2cixxYqqsKEO2YWi/yxNAq', '2024-11-04 03:36:07', NULL, 'admin');
COMMIT;
SQL;

    // Exécution des requêtes SQL
    $pdo->exec($sql);
    echo "Installation réussie ! La base de données et les tables ont été créées avec succès.";
} catch (PDOException $e) {
    echo "Erreur lors de l'installation : " . $e->getMessage();
}

