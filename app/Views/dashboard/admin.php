<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">TechMada - Admin</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/logout">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-5">
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Bienvenue Admin!</h4>
        <p>Vous êtes connecté(e) en tant qu'administrateur. Vous avez accès à tous les contrôles du système.</p>
        <hr>
        <p class="mb-0">Vos permissions: Gestion complète, Gestion des utilisateurs, Gestion des rôles, Accès aux rapports.</p>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="card-text">Gérer les utilisateurs</p>
                    <a href="/employes" class="btn btn-sm btn-primary">Consulter</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Rapports</h5>
                    <p class="card-text">Accéder aux rapports</p>
                    <a href="#" class="btn btn-sm btn-primary">Consulter</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Configurations</h5>
                    <p class="card-text">Gérer les paramètres</p>
                    <a href="#" class="btn btn-sm btn-primary">Configurer</a>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
