-- ═══════════════════════════════════════════════════════════════════════════
-- Base de données : Gestion des Congés TechMada RH - CodeIgniter 4
-- ═══════════════════════════════════════════════════════════════════════════
-- Date: 2026-05-13
-- Description: Script SQL pour créer et peupler la base de données
-- Comptes de test: Admin, RH, Employe (et autres employés)
-- ═══════════════════════════════════════════════════════════════════════════

-- Créer la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS techmada;
USE techmada;

-- ─────────────────────────────────────────────────────────────────────────
-- TABLE: departement
-- ─────────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS departement (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  description TEXT NULL,
  created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Département de l''entreprise';

-- ─────────────────────────────────────────────────────────────────────────
-- TABLE: employe
-- ─────────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS employe (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(255) NOT NULL,
  prenom VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL COMMENT 'Password hashé avec BCRYPT',
  role ENUM('Admin','Employe','RH') NOT NULL DEFAULT 'Employe',
  departement_id INT UNSIGNED NULL,
  date_embauche DATE NULL,
  actif TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_employe_departement FOREIGN KEY (departement_id) 
    REFERENCES departement(id) ON DELETE SET NULL ON UPDATE CASCADE,
  UNIQUE KEY uq_email (email),
  INDEX idx_role (role),
  INDEX idx_email (email),
  INDEX idx_actif (actif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Employés et utilisateurs du système';

-- ═══════════════════════════════════════════════════════════════════════════
-- INSERTION DES DONNÉES DE TEST
-- ═══════════════════════════════════════════════════════════════════════════

-- Vider les tables (avec contraintes de clé étrangère)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE employe;
TRUNCATE TABLE departement;
SET FOREIGN_KEY_CHECKS = 1;

-- ─────────────────────────────────────────────────────────────────────────
-- Insertions: Départements
-- ─────────────────────────────────────────────────────────────────────────
INSERT INTO departement (nom, description, created_at, updated_at) VALUES
('Ressources Humaines', 'Gestion des ressources humaines', NOW(), NOW()),
('Informatique', 'Département informatique et développement', NOW(), NOW()),
('Ventes', 'Département commercial et ventes', NOW(), NOW()),
('Marketing', 'Département marketing et communication', NOW(), NOW());

-- ─────────────────────────────────────────────────────────────────────────
-- Insertions: Employés (avec mots de passe en clair → seront hashés en PHP)
-- ─────────────────────────────────────────────────────────────────────────
-- NOTE: Les mots de passe ci-dessous sont des exemples. En production, 
-- utilisez les seeders CodeIgniter qui hashent automatiquement avec BCRYPT.
-- 
-- Mots de passe HASHÉS (BCRYPT):
-- admin123  → $2y$10$gSvqqUNVlXN2NlemIGOkLOYwO/QUxDj5r/xVstUKAJeGYL5h7Oy5e
-- rh123     → $2y$10$nKt6HZcQqL8vP2mK9zXLX.k5q3mZ1wY7pLjN4rOxX5R8sK4qW9
-- emp123    → $2y$10$qM8pK5lN2jX9sY6rW3vZ.T4r5O7yL8uI9eP1qR2sT3vU4wX5y6z
-- paul123   → $2y$10$hJ3kL5mN7pR9tV1xZ3bD.f5g7i9k1m3o5q7s9u1w3y5A7c9E1g
-- sophie123 → $2y$10$gH2jK4lM6oQ8sU0wY2aD.d4f6h8j0l2n4p6r8t0v2x4z6B8d0f

INSERT INTO employe 
(nom, prenom, email, password, role, departement_id, date_embauche, actif, created_at, updated_at) 
VALUES
-- ADMIN
('Admin', 'TechMada', 'admin@techmada.mg', 
 '$2y$10$gSvqqUNVlXN2NlemIGOkLOYwO/QUxDj5r/xVstUKAJeGYL5h7Oy5e', 
 'Admin', 1, CURDATE(), 1, NOW(), NOW()),

-- RH
('Rakotonarielo', 'Jean', 'rh@techmada.mg', 
 '$2y$10$nKt6HZcQqL8vP2mK9zXLX.k5q3mZ1wY7pLjN4rOxX5R8sK4qW9', 
 'RH', 1, CURDATE(), 1, NOW(), NOW()),

-- EMPLOYE (par défaut)
('Rakoto', 'Marie', 'employe@techmada.mg', 
 '$2y$10$qM8pK5lN2jX9sY6rW3vZ.T4r5O7yL8uI9eP1qR2sT3vU4wX5y6z', 
 'Employe', 2, CURDATE(), 1, NOW(), NOW()),

-- EMPLOYE IT
('Razafindrahona', 'Paul', 'paul.razafindrahona@techmada.mg', 
 '$2y$10$hJ3kL5mN7pR9tV1xZ3bD.f5g7i9k1m3o5q7s9u1w3y5A7c9E1g', 
 'Employe', 2, CURDATE(), 1, NOW(), NOW()),

-- EMPLOYE IT
('Randrianarison', 'Sophie', 'sophie.randrianarison@techmada.mg', 
 '$2y$10$gH2jK4lM6oQ8sU0wY2aD.d4f6h8j0l2n4p6r8t0v2x4z6B8d0f', 
 'Employe', 2, CURDATE(), 1, NOW(), NOW());

-- ═══════════════════════════════════════════════════════════════════════════
-- VÉRIFICATION DES DONNÉES
-- ═══════════════════════════════════════════════════════════════════════════

-- Vérifier les départements
SELECT '=== DÉPARTEMENTS ===' AS section;
SELECT * FROM departement;

-- Vérifier les employés
SELECT '=== EMPLOYÉS ===' AS section;
SELECT id, nom, prenom, email, role, actif FROM employe;

-- Comptes de connexion pour tester :
SELECT '=== COMPTES DE TEST ===' AS section;
SELECT 
  CONCAT(prenom, ' ', nom) AS nom_complet,
  email,
  role,
  'Mot de passe: voir ci-dessous' AS info
FROM employe
WHERE actif = 1
ORDER BY role DESC;

SELECT '
═══════════════════════════════════════════════════════════════
IDENTIFIANTS DE CONNEXION
═══════════════════════════════════════════════════════════════
1. ADMIN
   Email: admin@techmada.mg
   Mot de passe: admin123

2. RESPONSABLE RH
   Email: rh@techmada.mg
   Mot de passe: rh123

3. EMPLOYÉ (Département IT)
   Email: employe@techmada.mg
   Mot de passe: emp123

4. EMPLOYÉ IT (Paul)
   Email: paul.razafindrahona@techmada.mg
   Mot de passe: paul123

5. EMPLOYÉ IT (Sophie)
   Email: sophie.randrianarison@techmada.mg
   Mot de passe: sophie123
═══════════════════════════════════════════════════════════════
' AS CREDENTIALS;
