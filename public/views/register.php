<!-- 
  file: speakify/public/views/register.html
  description: Registration page to create an account in Speakify.
-->

  <!-- Content -->
  <div class="content">
    <h1 class="page-title">Créer un Compte</h1>
    <p class="subtext">Inscription, synchronisation et suivi du progrès</p>

    <!-- ==== START: Speakify Register Form ====# -->
    <section class="card" id="register-section" aria-labelledby="register-title">
      <h2 id="register-title">Inscription</h2>

      <form id="register-form" autocomplete="on">
        <div class="form-group">
          <label for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="votre@email.com" required autocomplete="email" />
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="********" required autocomplete="current-password" />
        </div>

        <div class="form-group">
          <label for="name">Nom Complet</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="Votre nom complet" required />
        </div>

        <button type="submit" class="button primary">S'inscrire</button>
      </form>

      <div id="register-message" class="info" role="status" aria-live="polite"></div>
    </section>
    <!-- ==== END: Speakify Register Form ====# -->


