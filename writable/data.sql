CREATE TABLE IF NOT EXISTS departements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS employes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role TEXT NOT NULL CHECK (role IN ('admin', 'user', 'rh')),
    departement_id INTEGER,
    date_embauche DATE,
    actif INTEGER NOT NULL DEFAULT 1 CHECK (actif IN (0, 1)),
    FOREIGN KEY (departement_id) REFERENCES departements(id)
);

CREATE TABLE IF NOT EXISTS type_conges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle VARCHAR(100) NOT NULL,
    jours_annuels INTEGER NOT NULL,
    detuctible INTEGER NOT NULL DEFAULT 0 CHECK (detuctible IN (0, 1))
);

INSERT OR IGNORE INTO employes (nom, prenom, email, password, role, departement_id, date_embauche, actif) VALUES
('Admin', 'User', 'user@gmail.com', 'admin123', 'admin', NULL, '2024-01-01', 1);

-- CREATE TABLE 



