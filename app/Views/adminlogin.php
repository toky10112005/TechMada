<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion Admin • Gestion d'alimentation</title>

    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/theme.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                Gestion d'alimentation
            </a>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h4 mb-2">Connexion administrateur</h1>
                        <p class="text-muted mb-4">Connecte-toi pour gerer les regimes et les activites.</p>

                        <form action="/admin/login" method="post" class="d-grid gap-3">
                            <?= csrf_field() ?>

                            <div>
                                <label for="username" class="form-label">Nom d'utilisateur</label>
                                <input id="username" type="text" name="username" class="form-control" placeholder="username" required>
                            </div>

                            <div>
                                <label for="password" class="form-label">Mot de passe</label>
                                <input id="password" type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Se connecter</button>
                                <a href="/admin/insert" class="btn btn-outline-secondary">Creer admin</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>