<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Gestion d'alimentation</title>

    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/theme.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav>
        <a href="/redirectadmin">Connexion Administrateur</a>
    </nav>


    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-7 col-lg-5">
                <div class="text-center mb-4">
                    <h1 class="h3 mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-egg-fried mb-2" viewBox="0 0 16 16">
                            <path d="M8 0a3 3 0 0 0-3 3c0 .342.023.674.065.993v.5H2a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1h-3v-.5c.042-.32.065-.65.065-.993a3 3 0 0 0-3-3zm.5 3a.5.5 0 0 1 0 1 .5.5 0 0 1 0-1z"/>
                        </svg><br>
                        Gestion d'alimentation
                    </h1>
                    <p class="text-muted mb-0">Connecte-toi pour acceder a ton tableau de bord.</p>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= esc($error) ?>
                    </div>
                <?php endif ?>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="/user/login" method="post" class="vstack gap-3">
                            <?= csrf_field() ?>
                            <div>
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="ex: nom@email.com" required>
                            </div>
                            <div>
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                    </div>
                    <div class="card-footer bg-white border-0 px-4 pb-4">
                        <div class="d-grid">
                            <a class="btn btn-outline-secondary" href="/user/redirectinscription">Creer un compte</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>