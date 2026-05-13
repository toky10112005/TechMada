<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Test SQLite</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Test Login</h1>
                    <p class="text-muted">Utilisez un utilisateur seedé pour tester la connexion.</p>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= esc($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="/user/login" method="post" class="vstack gap-3">
                        <?= csrf_field() ?>
                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control" placeholder="admin@example.com" required>
                        </div>
                        <div>
                            <label for="password" class="form-label">Mot de passe</label>
                            <input id="password" name="password" type="password" class="form-control" placeholder="admin123" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tester la connexion</button>
                    </form>

                    <hr>
                    <a href="/user/db-test" class="btn btn-outline-secondary w-100">Voir status SQLite directement</a>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
