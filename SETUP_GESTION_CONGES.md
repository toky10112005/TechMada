# Guide de Configuration - Système de Gestion des Congés TechMada RH CI4

## Vue d'ensemble
Ce document explique comment mettre en place le système complet d'authentification et de gestion des congés pour le projet TechMada RH avec CodeIgniter 4.

## Fichiers créés

### 1. Base de Données

#### Migrations :
- `app/Database/Migrations/2026-05-13-000001_CreateDepartementTable.php` - Crée la table `departement`
- `app/Database/Migrations/2026-05-13-000002_CreateEmployeTable.php` - Crée la table `employe`

**Table `departement` :**
```sql
- id (PK, INT, auto_increment)
- nom (VARCHAR 255, NOT NULL)
- description (TEXT, NULL)
- created_at (DATETIME)
- updated_at (DATETIME)
```

**Table `employe` :**
```sql
- id (PK, INT, auto_increment, UNSIGNED)
- nom (VARCHAR 255, NOT NULL)
- prenom (VARCHAR 255, NOT NULL)
- email (VARCHAR 255, UNIQUE, NOT NULL)
- password (VARCHAR 255, NOT NULL) - Hash BCRYPT
- role (ENUM: 'Admin', 'Employe', 'RH')
- departement_id (INT, UNSIGNED, FK → departement.id, ON DELETE SET NULL)
- date_embauche (DATE, NULL)
- actif (TINYINT, DEFAULT 1)
- created_at (DATETIME)
- updated_at (DATETIME)
```

#### Seeders :
- `app/Database/Seeds/DepartementSeeder.php` - Insère 4 départements (RH, IT, Ventes, Marketing)
- `app/Database/Seeds/EmployeSeeder.php` - Insère 5 comptes de test

**Comptes de test disponibles :**
1. **Admin** : admin@techmada.mg / admin123
2. **RH** : rh@techmada.mg / rh123
3. **Employé** : employe@techmada.mg / emp123
4. **Employé IT** : paul.razafindrahona@techmada.mg / paul123
5. **Employé IT** : sophie.randrianarison@techmada.mg / sophie123

### 2. Modèles

#### UserModel (`app/Models/UserModel.php`)
Méthodes principales :
- `authenticate($email, $password)` - Authentifie un utilisateur
- `getUserByEmail($email)` - Récupère un utilisateur par email
- `getUserById($id)` - Récupère un utilisateur par ID
- `getActiveUsers()` - Récupère tous les utilisateurs actifs
- `getUsersByRole($role)` - Récupère les utilisateurs par rôle
- `isAdmin($userId)` - Vérifie si l'utilisateur est Admin
- `isRH($userId)` - Vérifie si l'utilisateur est RH
- `isEmploye($userId)` - Vérifie si l'utilisateur est Employé

### 3. Contrôleurs

#### Auth Controller (`app/Controllers/Auth.php`)
Méthodes :
- `login()` - Affiche la page de connexion
- `authenticate()` - Traite les données de connexion
- `logout()` - Déconnecte l'utilisateur
- `dashboard()` - Affiche le dashboard (protégé par AuthFilter)

### 4. Filtres

#### AuthFilter (`app/Filters/AuthFilter.php`)
- Vérifie si l'utilisateur est connecté
- Redirige vers `/login` si non authentifié
- Vérifie la session `user_id`

#### RoleFilter (`app/Filters/RoleFilter.php`)
- Vérifie le rôle de l'utilisateur
- Paramètres : `['Admin']`, `['RH']`, `['Employe']` ou `['Admin', 'RH']`
- Redirige avec message d'erreur si droits insuffisants

### 5. Vues

#### Login View (`app/Views/auth/login.php`)
- Page de connexion responsive avec Bootstrap 5.3
- Design inspiré du template fourni
- Affichage des messages d'erreur/succès
- Formulaire CSRF protégé
- Affiche les comptes de démonstration sur le panneau gauche

#### Dashboard View (`app/Views/auth/dashboard.php`)
- Page d'accueil après connexion
- À développer selon les besoins spécifiques

### 6. Routes

Routes configurées dans `app/Config/Routes.php` :

```php
# Pages publiques
GET    /login              Auth::login              (as 'auth.login')
POST   /authenticate       Auth::authenticate      (as 'auth.authenticate')

# Pages protégées
GET    /logout             Auth::logout            (as 'auth.logout')
GET    /dashboard          Auth::dashboard         [filter: auth]

# Redirection accueil
GET    /                   → /login (ou /dashboard si connecté)
```

### 7. Sessions

Données stockées en session après connexion :
```php
session()->set([
    'user_id'    => $user['id'],
    'user_name'  => $user['prenom'] . ' ' . $user['nom'],
    'user_email' => $user['email'],
    'user_role'  => $user['role'],      // 'Admin', 'RH', ou 'Employe'
    'user_dept'  => $user['departement_id'],
    'isLoggedIn' => true,
]);
```

## Instructions d'installation

### 1. Vérifier la configuration de la base de données
Éditer `app/Config/Database.php` si nécessaire :
```php
public array $default = [
    'hostname' => '127.0.0.1',
    'username' => 'votre_utilisateur',
    'password' => 'votre_mot_de_passe',
    'database' => 'regime_db',  // ou votre nom de BD
    'DBDriver' => 'MySQLi',
];
```

### 2. Exécuter les migrations
```bash
# Dans le répertoire du projet
php spark migrate
```
Cela créera les tables `departement` et `employe`.

### 3. Exécuter les seeders
```bash
# Remplir la base de données avec les données de test
php spark db:seed DepartementSeeder
php spark db:seed EmployeSeeder
```

Ou utiliser le seeder principal (s'il existe) :
```bash
php spark db:seed
```

### 4. Tester la connexion
1. Démarrer le serveur de développement :
   ```bash
   php spark serve
   ```

2. Accéder à : `http://localhost:8080`

3. Tester les 3 rôles :
   - **Admin** : admin@techmada.mg / admin123
   - **RH** : rh@techmada.mg / rh123
   - **Employé** : employe@techmada.mg / emp123

## Structure de sécurité

### Authentification
1. **Champ unique** : Email (UNIQUE en base de données)
2. **Mot de passe** : Hashé avec BCRYPT (password_hash)
3. **Vérification** : password_verify() à la connexion
4. **Session** : Utilise les sessions natives de CodeIgniter

### Autorisation par rôle
Les rôles sont identifiés par le champ `role` :
- **Admin** : Accès complet au système
- **RH** : Valide les demandes de son équipe
- **Employe** : Crée et suit ses demandes de congé

### Protection des routes
- `/login` et `/authenticate` : Publiques
- `/dashboard`, `/logout` : Protégées par AuthFilter
- Routes spécifiques : Peuvent utiliser RoleFilter

## Exemple d'utilisation des filtres

### Protéger une route par authentification
```php
$routes->get('/mes-conges', 'Conge::mesDemandes', ['filter' => 'auth']);
```

### Protéger une route par rôle
```php
$routes->get('/validation-conges', 'Conge::validation', ['filter' => 'role:RH']);
$routes->get('/gestion-admin', 'Admin::gestion', ['filter' => 'role:Admin']);
```

### Protéger une route avec plusieurs rôles
```php
$routes->group('admin', ['filter' => 'role:Admin,RH'], function($routes) {
    $routes->get('validation', 'Conge::validation');
    $routes->post('approuver', 'Conge::approuver');
});
```

## Développements futurs

Pour compléter le système, vous pouvez ajouter :

1. **Gestion des demandes de congé**
   - Modèle `DemandeCongeModel`
   - Contrôleur `CongeController`
   - Vues : création, liste, validation, détails

2. **Soldes de congés**
   - Modèle `SoldeCongeModel`
   - Calcul automatique des jours disponibles
   - Historique des consommations

3. **Notifications**
   - Email de confirmation
   - Rappels de validation
   - Alertes pour les responsables RH

4. **Rapports**
   - Statistiques de congés par département
   - Exports PDF/Excel
   - Dashboards analytiques

5. **Audit**
   - Logging des actions
   - Historique des modifications
   - Pistes d'audit complètes

## Troubleshooting

### Erreur : "Table 'employe' doesn't exist"
→ Exécuter les migrations : `php spark migrate`

### Erreur : "Class 'App\Filters\AuthFilter' not found"
→ Vérifier que le fichier `AuthFilter.php` existe dans `app/Filters/`

### Sessions ne persistent pas
→ Vérifier la configuration de session dans `app/Config/Session.php`
→ Le dossier `writable/session/` doit avoir les permissions d'écriture

### Mot de passe incorrect même avec les bonnes données
→ Les mots de passe sont hashés avec BCRYPT
→ Vérifier que le seeder a bien exécuté `password_hash()`

## Support et documentation

- [CodeIgniter 4 Documentation](https://codeigniter.com/user_guide/index.html)
- [CI4 Authentication Guide](https://codeigniter.com/user_guide/general/managing_apps.html)
- [CI4 Filters Guide](https://codeigniter.com/user_guide/incoming/filters.html)
- [CI4 Database Guide](https://codeigniter.com/user_guide/database/index.html)
