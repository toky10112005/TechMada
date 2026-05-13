<?php
// Cette page affiche un simple dashboard après connexion
// Elle sera développée selon les besoins spécifiques
?>
<div style="padding: 2rem; text-align: center;">
  <h1>Bienvenue, <?= session('user_name') ?></h1>
  <p>Rôle: <strong><?= session('user_role') ?></strong></p>
  
  <div style="margin-top: 2rem;">
    <a href="/logout" class="btn btn-danger">Déconnexion</a>
  </div>
</div>
