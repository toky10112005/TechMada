<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Liste des employes') ?></title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1"><?= esc($title ?? 'Liste des employes') ?></h1>
            <p class="text-muted mb-0">Total: <?= esc((string) ($totalEmployes ?? 0)) ?> employe(s)</p>
        </div>
        <a href="/user/login" class="btn btn-outline-primary">Aller au test login</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Departement</th>
                            <th>Date embauche</th>
                            <th>Actif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($employes)): ?>
                            <?php foreach ($employes as $employe): ?>
                                <tr>
                                    <td><?= esc($employe['id']) ?></td>
                                    <td><?= esc($employe['nom']) ?></td>
                                    <td><?= esc($employe['prenom']) ?></td>
                                    <td><?= esc($employe['email']) ?></td>
                                    <td><?= esc($employe['role']) ?></td>
                                    <td><?= esc($employe['departement_nom'] ?? '-') ?></td>
                                    <td><?= esc($employe['date_embauche'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge bg-<?= ((int) $employe['actif'] === 1) ? 'success' : 'secondary' ?>">
                                            <?= ((int) $employe['actif'] === 1) ? 'Oui' : 'Non' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Aucun employe trouve dans la base.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</body>
</html>
