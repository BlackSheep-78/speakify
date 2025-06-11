<?php
// -----------------------------------------------------------------------------
// Project     : Speakify
// File        : /public/views/schema-editor.php
// Description : Interface to create or edit a playback schema
// -----------------------------------------------------------------------------
?>

<main class="content">
  <h1>🎛️ Création de schéma de lecture</h1>

  <form id="schema-editor-form" class="form-card">
    <label for="schema-name">Nom du schéma</label>
    <input type="text" id="schema-name" name="schema-name" placeholder="Ex: Répétition FR/EN" required>

    <div id="schema-steps">
      <!-- JS will populate schema steps here -->
    </div>

    <button type="button" id="add-step">➕ Ajouter une étape</button>
    <button type="submit">💾 Enregistrer</button>

    <p id="schema-editor-message"></p>
  </form>
</main>
