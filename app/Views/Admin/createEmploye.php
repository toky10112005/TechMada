<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer un employé</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background:#f8f6f1;font-family:Arial,sans-serif;color:#1c2b1e}
        .wrap{max-width:900px;margin:40px auto;padding:0 16px}
        .cardx{background:#fff;border:1px solid #dde8e1;border-radius:14px;padding:24px;box-shadow:0 8px 24px rgba(0,0,0,.04)}
        .title{font-family:Georgia,serif;margin:0 0 8px}
        .muted{color:#7a8f80;font-size:.92rem;margin-bottom:18px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        @media (max-width:700px){.grid{grid-template-columns:1fr}}
        .field{display:flex;flex-direction:column;gap:6px;margin-bottom:14px}
        label{font-size:.85rem;font-weight:600}
        input,select{padding:11px 12px;border:1.5px solid #dde8e1;border-radius:8px;font-size:.95rem}
        input:focus,select:focus{outline:none;border-color:#2d5a3d;box-shadow:0 0 0 3px rgba(45,90,61,.1)}
        .actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
        .btn-forest{background:#2d5a3d;color:#fff;border:none;border-radius:8px;padding:11px 16px;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}
        .btn-secondary{background:#fff;color:#1c2b1e;border:1.5px solid #dde8e1;border-radius:8px;padding:11px 16px;text-decoration:none;display:inline-flex;align-items:center;justify-content:center}
        .flash{padding:12px 14px;border-radius:8px;margin-bottom:16px}
        .flash-error{background:#fdf0ee;color:#c0392b;border:1px solid #f0b8b2}
        .flash-success{background:#edf7f2;color:#1e6b3f;border:1px solid #8fd4aa}
        .help{font-size:.8rem;color:#7a8f80}
        .check{display:flex;align-items:center;gap:8px}
    </style>
</head>
<body>
<div class="wrap">
    <div class="cardx">
        <h1 class="title">Créer un employé</h1>
        <div class="muted">Remplissez les informations correspondant à la table <strong>employes</strong>.</div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash flash-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash flash-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <form action="/admin/createEmploye" method="post">
            <?= csrf_field() ?>
            <div class="grid">
                <div class="field">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?= esc(old('nom')) ?>" required>
                </div>
                <div class="field">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="<?= esc(old('prenom')) ?>" required>
                </div>
            </div>

            <div class="grid">
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= esc(old('email')) ?>" required>
                </div>
                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>

            <div class="grid">
                <div class="field">
                    <label for="role">Rôle</label>
                    <select id="role" name="role" required>
                        <option value="">-- Choisir --</option>
                        <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>admin</option>
                        <option value="rh" <?= old('role') === 'rh' ? 'selected' : '' ?>>rh</option>
                        <option value="user" <?= old('role') === 'user' ? 'selected' : '' ?>>user</option>
                    </select>
                </div>
                <div class="field">
                    <label for="departement_id">Département</label>
                    <select id="departement_id" name="departement_id">
                        <option value="">-- Aucun --</option>
                        <?php foreach (($departements ?? []) as $departement): ?>
                            <option value="<?= esc($departement['id']) ?>" <?= old('departement_id') == $departement['id'] ? 'selected' : '' ?>><?= esc($departement['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid">
                <div class="field">
                    <label for="date_embauche">Date d'embauche</label>
                    <input type="date" id="date_embauche" name="date_embauche" value="<?= esc(old('date_embauche') ?: date('Y-m-d')) ?>">
                </div>
                <div class="field">
                    <label>Statut</label>
                    <div class="check">
                        <input type="checkbox" id="actif" name="actif" value="1" <?= old('actif', '1') ? 'checked' : '' ?>>
                        <label for="actif" style="margin:0;font-weight:400">Compte actif</label>
                    </div>
                    <div class="help">Décochez pour créer un compte désactivé.</div>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn-forest">Créer l'employé</button>
                <a href="/dashboard/admin" class="btn-secondary">Retour au dashboard</a>
            </div>
        </form>
    </div>
</div>
<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
