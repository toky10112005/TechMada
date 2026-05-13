<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Gestion d'alimentation</title>

    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/theme.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                <div class="mb-4 text-center">
                    <h1 class="h3 mb-1">Creer un compte</h1>
                    <p class="text-muted mb-0">Renseigne tes informations de base.</p>
                </div>

                <?php if (isset($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php foreach ($errors as $error): ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="/user/inscription" method="post" class="row g-3">
                            <?= csrf_field() ?>
                            <div class="col-12">
                                <label class="form-label" for="username">Nom d'utilisateur</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="ex: Rakoto" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="ex: nom@email.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="password">Mot de passe</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="genre">Genre</label>
                                <select class="form-select" name="genre" id="genre" required>
                                    <option value="Homme">Homme</option>
                                    <option value="Femme">Femme</option>
                                </select>
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Suivant</button>
                                <a class="btn btn-outline-secondary" href="/">Annuler</a>
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