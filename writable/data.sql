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

CREATE TABLE  IF NOT EXISTS soldes(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    annee INTEGER NOT NULL,
    jours_attribues INTEGER NOT NULL,
    jours_pris INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_conge_id) REFERENCES type_conges(id)
);

CREATE TABLE IF NOT EXISTS conges(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    nb_jours INTEGER NOT NULL,
    motif TEXT NOT NULL,
    statut TEXT NOT NULL CHECK (statut IN ('en_attente', 'accepte', 'refuse')),
    commentaire_rh TEXT,
    date_demande DATE NOT NULL DEFAULT CURRENT_DATE,
    traite_par VARCHAR(255),
    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_conge_id) REFERENCES type_conges(id)
    -- FOREIGN KEY (traite_par) REFERENCES employes(nom)
);

INSERT OR IGNORE INTO departements (nom, description) VALUES
('Ressources Humaines', 'Gestion du personnel et des congés'),
('Informatique', 'Support technique et développement'),
('Comptabilité', 'Gestion financière et paie');

INSERT OR IGNORE INTO employes (nom, prenom, email, password, role, departement_id, date_embauche, actif) VALUES
('Admin', 'User', 'user@gmail.com', 'admin123', 'admin', NULL, '2024-01-01', 1),
('RH','RH','rh@gmail.com', 'rh', 'rh', NULL, '2024-01-01', 1),
('John', 'Doe', 'john.doe@gmail.com', 'john', 'user', 1, '2024-01-01', 1),
('Jane', 'Smith', 'jane.smith@gmail.com', 'jane', 'user', 2, '2024-01-01', 1),
('Alice', 'Johnson', 'alice.johnson@gmail.com', 'alice', 'user', 3, '2024-01-01', 1);


