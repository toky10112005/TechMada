<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($title ?? 'Mon profil') ?></title>
  <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root{--forest:#1f4d3a;--ink:#173228;--bg:#f5f2eb;--card:#ffffff;--muted:#6c757d;--line:#e6e1d7}
    *{box-sizing:border-box}
    body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:linear-gradient(180deg,#f7f3ec 0%,#f1ede4 100%);color:var(--ink)}
    .shell{min-height:100vh;display:flex}
    .sidebar{width:240px;background:var(--ink);color:#fff;display:flex;flex-direction:column;position:sticky;top:0;height:100vh}
    .sidebar-brand{padding:1.4rem 1.2rem 1rem;border-bottom:1px solid rgba(255,255,255,.06)}
    .sidebar-brand .title{font-weight:700}
    .sidebar-brand .sub{display:block;font-size:.8rem;color:rgba(255,255,255,.55)}
    .sidebar-nav{list-style:none;padding:.75rem;margin:0}
    .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;color:rgba(255,255,255,.7);text-decoration:none}
    .sidebar-nav a.active,.sidebar-nav a:hover{background:rgba(255,255,255,.08);color:#fff}
    .main{flex:1;padding:28px}
    .topbar,.card-panel{background:var(--card);border:1px solid var(--line);border-radius:18px;box-shadow:0 16px 40px rgba(23,50,40,.08)}
    .topbar{padding:18px 22px;display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
    .eyebrow{font-size:.78rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted)}
    .hero{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}
    .card-panel{padding:18px 20px}
    .label{font-size:.78rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em}
    .value{font-size:1.05rem;font-weight:700;margin-top:6px}
    .actions a{margin-right:10px}
    @media (max-width: 900px){.shell{display:block}.sidebar{width:auto;height:auto;position:relative}.main{padding:16px}.topbar{flex-direction:column;align-items:flex-start;gap:10px}}
  </style>
</head>
<body>
  <div class="shell">
    <aside class="sidebar">
      <div class="sidebar-brand">
        <div class="title">TechMada RH</div>
        <span class="sub">Espace employé</span>
      </div>
      <ul class="sidebar-nav">
        <li><a href="/dashboard/user"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
        <li><a href="/employes/nouvelledemande"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
        <li><a href="/conges/my"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
        <li><a href="/dashboard/profil" class="active"><i class="bi bi-person"></i> Mon profil</a></li>
      </ul>
      <div style="margin-top:auto;padding:1rem 1.1rem 1.2rem;border-top:1px solid rgba(255,255,255,.06)">
        <a href="/logout" class="btn btn-outline-light btn-sm w-100">Déconnexion</a>
      </div>
    </aside>

    <main class="main">
      <div class="topbar">
        <div>
          <div class="eyebrow">Profil utilisateur</div>
          <h1 class="h4 mb-0">Mon profil</h1>
        </div>
        <div class="actions">
          <a href="/dashboard/user" class="btn btn-outline-secondary btn-sm">Retour au tableau de bord</a>
          <a href="/employes/nouvelledemande" class="btn btn-success btn-sm">Nouvelle demande</a>
        </div>
      </div>

      <div class="hero">
        <section class="card-panel">
          <div class="label">Nom complet</div>
          <div class="value"><?= esc(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?></div>
        </section>
        <section class="card-panel">
          <div class="label">Email</div>
          <div class="value"><?= esc($user['email'] ?? '-') ?></div>
        </section>
        <section class="card-panel">
          <div class="label">Rôle</div>
          <div class="value"><?= esc($user['role'] ?? '-') ?></div>
        </section>
        <section class="card-panel">
          <div class="label">Congés en attente</div>
          <div class="value"><?= esc($conge_summary['counts']['en_attente'] ?? 0) ?></div>
        </section>
        <section class="card-panel">
          <div class="label">Congés approuvés</div>
          <div class="value"><?= esc($conge_summary['counts']['accepte'] ?? 0) ?></div>
        </section>
        <section class="card-panel">
          <div class="label">Jours restants</div>
          <div class="value"><?= esc($conge_summary['remaining_total'] ?? 0) ?></div>
        </section>
      </div>
    </main>
  </div>
</body>
</html>