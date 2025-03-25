<?php
// file: speakify/public/admin.php

$config = require __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Speakify Admin Panel</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f7fa;
      padding: 2rem;
      max-width: 800px;
      margin: auto;
      color: #333;
    }
    h1 { color: #0057B7; }
    h2 { margin-top: 2rem; }
    button {
      padding: 10px 18px;
      font-size: 16px;
      background-color: #00E676;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin: 10px 0;
    }
    button:hover {
      background-color: #00c667;
    }
    input[type='number'] {
      padding: 6px;
      font-size: 14px;
      width: 80px;
      margin-right: 10px;
    }
    code {
      background: #eee;
      padding: 2px 6px;
      border-radius: 4px;
      font-size: 90%;
    }
    pre {
      background: #111;
      color: #0f0;
      padding: 1em;
      white-space: pre-wrap;
      margin-top: 1em;
      border-radius: 6px;
    }
    section {
      margin-bottom: 2rem;
    }
  </style>
</head>
<body>

  <h1>🔧 Speakify Admin Panel</h1>

  <section>
    <p><strong>Environment:</strong> <?= htmlspecialchars($config['env']) ?></p>
    <p><strong>Base Path:</strong> <code><?= BASEPATH ?></code></p>
    <p><strong>Token required for API calls:</strong> <code><?= htmlspecialchars($config['admin_token']) ?></code></p>
  </section>

  <section>
    <h2>🛠️ Build Database</h2>
    <p>This will execute all SQL schema files in <code>/sql/schema/</code> to (re)build the database structure.</p>
    <button onclick="buildDatabase()">Run Build Script</button>
  </section>

  <section>
    <h2>📥 Test Get Sentences</h2>
    <label for="lang">Language ID:</label>
    <input id="lang" type="number" value="1" min="1" />
    <button onclick="getSentences()">Get Sentences</button>
  </section>

  <section>
    <h2>🧪 Check Database Schema</h2>
    <p>This checks whether all required tables (<code>users</code>, <code>schemas</code>, <code>sessions</code>) exist in the database.</p>
    <button onclick="checkSchema()">Run Schema Check</button>
  </section>

  <pre id="output">Ready to run actions…</pre>

  <script>
    const token = <?= json_encode($config['admin_token']) ?>;

    function buildDatabase() {
      const output = document.getElementById('output');
      output.textContent = '⏳ Running build_database...';
      fetch(`api/index.php?action=build_db&token=${token}`)
        .then(response => response.text())
        .then(data => output.textContent = data)
        .catch(err => output.textContent = '❌ Error: ' + err);
    }

    function getSentences() {
      const output = document.getElementById('output');
      const langId = document.getElementById('lang').value;
      if (!langId) {
        output.textContent = '⚠️ Please provide a valid language ID.';
        return;
      }
      output.textContent = `⏳ Fetching sentences for language ID ${langId}...`;
      fetch(`api/index.php?action=get_sentences&lang_id=${langId}&token=${token}`)
        .then(res => res.text())
        .then(data => output.textContent = data)
        .catch(err => output.textContent = '❌ Error: ' + err);
    }

    function checkSchema() {
      const output = document.getElementById('output');
      output.textContent = '🔍 Checking database tables...';
      fetch(`api/index.php?action=check_schema&token=${token}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'ok') {
            output.textContent = `✅ All tables exist:\n` + JSON.stringify(data.existing, null, 2);
          } else {
            output.textContent =
              `⚠️ Missing tables:\n` +
              JSON.stringify(data.missing, null, 2) +
              `\n\nExisting:\n` +
              JSON.stringify(data.existing, null, 2);
          }
        })
        .catch(err => output.textContent = '❌ Error: ' + err);
    }
  </script>

</body>
</html>
