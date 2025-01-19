CREATE DATABASE IF NOT EXISTS voetbalvisie CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE voetbalvisie;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    wachtwoord VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    categorie_id INT,
    gebruiker_id INT,
    thumbnail VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id),
    FOREIGN KEY (gebruiker_id) REFERENCES users(id)
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blog_id INT,
    gebruiker_id INT,
    reactie TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
    FOREIGN KEY (gebruiker_id) REFERENCES users(id)
);

CREATE TABLE competitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    land VARCHAR(100),
    type ENUM('LEAGUE', 'CUP') NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team1 VARCHAR(100) NOT NULL,
    team2 VARCHAR(100) NOT NULL,
    datum DATETIME NOT NULL,
    uitslag VARCHAR(10),
    competitie_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (competitie_id) REFERENCES competitions(id)
);

CREATE TABLE subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Standaard competities toevoegen
INSERT INTO competitions (naam, land, type, slug) VALUES
('Eredivisie', 'Nederland', 'LEAGUE', 'eredivisie'),
('Premier League', 'Engeland', 'LEAGUE', 'premier-league'),
('La Liga', 'Spanje', 'LEAGUE', 'la-liga'),
('Bundesliga', 'Duitsland', 'LEAGUE', 'bundesliga'),
('Serie A', 'Italië', 'LEAGUE', 'serie-a'),
('Ligue 1', 'Frankrijk', 'LEAGUE', 'ligue-1'),
('Champions League', NULL, 'CUP', 'champions-league'),
('Europa League', NULL, 'CUP', 'europa-league'),
('Conference League', NULL, 'CUP', 'conference-league'),
('KNVB Beker', 'Nederland', 'CUP', 'knvb-beker'),
('FA Cup', 'Engeland', 'CUP', 'fa-cup'),
('Copa del Rey', 'Spanje', 'CUP', 'copa-del-rey'),
('DFB Pokal', 'Duitsland', 'CUP', 'dfb-pokal'),
('Coppa Italia', 'Italië', 'CUP', 'coppa-italia'),
('Coupe de France', 'Frankrijk', 'CUP', 'coupe-de-france'); 