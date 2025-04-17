<script src="js/admin.js" defer></script>

<h1 class="page-title">🛠 Panneau Admin</h1>
<p class="subtext">Outils de maintenance, génération et gestion avancée</p>

<!-- 🔧 Génération de la structure -->
<section class="card">
  <h2>Générer la structure du projet</h2>
  <p>Cliquez pour exécuter le script <code>generate_file_structure.sh</code>.</p>
  <button id="btn-generate-structure" class="button primary">Générer</button>
  <pre id="output-generate" class="info" style="white-space: pre-wrap; margin-top: 12px;"></pre>
</section>

<!-- 🗣 Test de synthèse vocale (TTS) -->
<section class="card">
  <h2>Test TTS</h2>
  <p>Cliquez pour déclencher une synthèse vocale via l’API Google.</p>
  <button id="btn-tts-trigger" class="button primary">🔊 Générer un échantillon</button>
  <pre id="output-tts" class="info" style="white-space: pre-wrap; margin-top: 12px;"></pre>
</section>

<!-- 🧩 Zone pour d'autres outils -->
<section class="card">
  <h2>À venir</h2>
  <ul>
    <li>📦 Exporter les TBs ou playlists</li>
    <li>👥 Liste et gestion des utilisateurs</li>
    <li>🧠 Réinitialiser le cache de traduction</li>
  </ul>
</section>
