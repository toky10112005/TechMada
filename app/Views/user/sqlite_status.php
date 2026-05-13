<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status SQLite</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4">Communication SQLite</h1>
                    <p class="mb-4 text-muted">Cette page confirme la lecture de la table <strong>users</strong>.</p>

                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Connexion DB</span>
                            <strong><?= $connected ? 'OK' : 'ECHEC' ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Base active</span>
                            <strong><?= esc((string) $database) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Nombre users</span>
                            <strong><?= esc((string) $userCount) ?></strong>
                        </li>
                    </ul>

                    <?php if ($loggedUser): ?>
                        <div class="alert alert-success">
                            Login valide pour: <strong><?= esc($loggedUser['email']) ?></strong> (role: <?= esc($loggedUser['role']) ?>)
                        </div>
                    <?php endif; ?>

                    <?php if ($sampleUser): ?>
                        <h2 class="h6">Exemple utilisateur (1er en base)</h2>
                        <pre class="bg-light border rounded p-3 mb-4"><?php print_r($sampleUser); ?></pre>
                    <?php else: ?>
                        <div class="alert alert-warning">Aucun utilisateur trouve dans la table users.</div>
                    <?php endif; ?>

                    <a href="/" class="btn btn-primary">Retour au login test</a>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
