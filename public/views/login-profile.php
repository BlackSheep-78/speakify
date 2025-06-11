<?php
// -----------------------------------------------------------------------------
// Project     : Speakify
// File        : /public/views/login-profile.php
// Description : Login & Profile view with authentication and user info panel
// -----------------------------------------------------------------------------
?>

<main class="content">
  <h1 class="page-title">Mon Compte</h1>
  <p class="subtext">Connexion, synchronisation et suivi du progrès</p>

  <!-- ==== START: Login Form ====# -->
  <section class="card" id="login-section" aria-labelledby="login-title">
    <h2 id="login-title">Connexion</h2>

    <form id="login-form" autocomplete="on" novalidate>
      <div class="form-group">
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="votre@email.com" required autocomplete="email" />
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="********" required autocomplete="current-password" />
      </div>

      <button type="submit" class="button primary">Se connecter</button>
    </form>

    <div id="login-message" class="info" role="status" aria-live="polite"></div>

    <div class="register-link">
      <p>Pas encore de compte ? <a href="register">Créez un compte ici</a>.</p>
    </div>
  </section>
  <!-- ==== END: Login Form ====# -->

  <!-- ==== START: Profile Info Panel ====# -->
  <section class="card" id="profile-section" aria-labelledby="profile-title" hidden>
    <h2 id="profile-title">Profil</h2>
    <p><strong>Nom :</strong> <span id="profile-name">-</span></p>
    <p><strong>Email :</strong> <span id="profile-email">-</span></p>
    <p><strong>Dernière activité :</strong> <span id="profile-last-login">-</span></p>
    <button id="logout-button" class="button danger">Se déconnecter</button>
  </section>
  <!-- ==== END: Profile Info Panel ====# -->
</main>
