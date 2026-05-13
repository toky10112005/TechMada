# Guide de Développement - Système de Suggestion de Régime (ITU S4)

Ce fichier est conçu pour fournir le contexte complet à GitHub Copilot (ou toute autre IA de développement) afin de générer le code source du projet conformément au cahier des charges de Mr. Tahiana.

## 1. Stack Technique & Contraintes
- **Framework :** PHP CodeIgniter (Architecture MVC).
- **Base de Données :** MySQL (Normalisation 3NF sans ENUMs).
- **Frontend :** HTML/CSS, JavaScript (AJAX pour le dynamisme).
- **Date de livraison :** Lundi 11 mai 2026.

## 2. Structure de la Base de Données (Schéma)
Le code doit s'appuyer sur les tables suivantes :
- **Références :** `genres`, `roles`, `objectifs_types`, `categories_plats`, `moments_journee`.
- **Utilisateurs :** `users` (avec `is_gold` et `solde_portefeuille`), `user_health_profiles` (poids/taille).
- **Régime & Plats :** `regimes`, `plats`, `regime_details` (planification par `jour_numero`).
- **Sport :** `activites_sportives`, `regime_activites` (liaison sport-régime).
- **Finances :** `codes_recharge`, `achats_regimes`, `configurations_remises` (pour la remise Gold de 15%).

## 3. Algorithmes de Calcul (Logique Métier)

### A. Calcul de l'IMC
Formule : `IMC = poids / (taille * taille)`

### B. Métabolisme de Base (Mifflin-St Jeor)
- **Homme :** `(10 * poids) + (6.25 * taille_cm) - (5 * age) + 5`
- **Femme :** `(10 * poids) + (6.25 * taille_cm) - (5 * age) - 161`
- **Maintenance (TDEE) :** `MB * 1.2` (Sédentaire par défaut).

### C. Suggestion Dynamique
Le système doit filtrer les régimes selon la balance énergétique :
`Balance = (Calories_Moyennes_Regime) - (Maintenance_Utilisateur) - (Dépense_Sport_Associe)`
- Si objectif = "Réduire" : Suggérer les régimes où `Balance < 0`.
- Si objectif = "Augmenter" : Suggérer les régimes où `Balance > 0`.

### D. Gestion de la Durée (Séquençage)
Les menus en base sont sur 7 jours (`jour_numero` de 1 à 7). Pour une durée calculée de N jours, utiliser le modulo :
`Jour_A_Afficher = (index_boucle % 7 == 0) ? 7 : (index_boucle % 7)`.

### E. Calcul de la Durée Totale
`Duree_Jours = (abs(Poids_Cible - Poids_Actuel) / Poids_Impact_Semaine_Regime) * 7`.

## 4. Fonctionnalités Spécifiques

### Inscription (2 Étapes)
- Étape 1 : Nom, Email, Mot de passe, Genre.
- Étape 2 : Taille, Poids actuel, Poids cible, Type d'objectif.

### Porte-monnaie & Option Gold
- Vérifier `est_valide` dans `codes_recharge` avant de créditer le solde.
- Si l'utilisateur achète un régime et possède l'option Gold (`is_gold = 1`), appliquer la remise de la table `configurations_remises`.

### Export PDF
- Générer un document listant jour par jour les plats (Petit-déjeuner, Déjeuner, Dîner) et l'activité sportive sur toute la durée du programme.

## 5. Directives Copilot
- Générer des Modèles CodeIgniter utilisant le `Query Builder`.
- Utiliser AJAX pour la soumission des codes de recharge sans recharger la page.
- S'assurer que la somme des pourcentages (Viande/Poisson/Volaille) dans le CRUD régime est validée à 100%.
