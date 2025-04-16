<?php $baseUrl = $config['base_url'] ?? ''; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Speakify</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <base href="<?= rtrim($baseUrl, '/') . '/' ?>" />
  
  <link rel="icon" href="assets/icons/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="css/style.css">
  <script src="js/app.js" defer></script>

</head>
<body>
    <div class="header">
    <div><a href="login-profile">👤 Connexion</a></div>
    <div><a href="achievements">🔥 12 jours</a></div>
    <div><a href="achievements">🌟 2 450 XP</a></div>
    </div>

  
