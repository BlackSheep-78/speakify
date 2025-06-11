<?php
// -----------------------------------------------------------------------------
// Project     : Speakify
// File        : /public/views/update-details.php
// Description : View to allow the user to update their name, email, and password
// -----------------------------------------------------------------------------
?>

<main class="content">
  <h1>🛠️ Modifier Details</h1>

  <form id="update-details-form" class="form-card">
    <label for="name">Nom</label>
    <input type="text" id="name" name="name" placeholder="Votre nom complet" required>

    <label for="email">Adresse e-mail</label>
    <input type="email" id="email" name="email" placeholder="votre@email.com" required>

    <label for="password">Nouveau mot de passe</label>
    <input type="password" id="password" name="password" placeholder="Laisser vide pour ne pas changer">

    <label for="confirm">Confirmer le mot de passe</label>
    <input type="password" id="confirm" name="confirm" placeholder="Confirmer le mot de passe">

    <button type="submit">💾 Enregistrer les modifications</button>

    <p id="update-details-message"></p>
  </form>
</main>
