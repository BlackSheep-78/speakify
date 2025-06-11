<?php 
  // -----------------------------------------------------------------------------
  // Project     : Speakify
  // File        : public/views/header.php
  // Description : HTML <head> setup and top navigation bar with semantic tags
  // -----------------------------------------------------------------------------
  $baseUrl = $config['base_url'] ?? ''; 
?>

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

<header class="header">
  <nav>
    <ul class="status-bar">
      <li class="left"><a href="login-profile">👤 Connexion</a></li>
      <li class="center"><a href="achievements">🔥 12 jours</a></li>
      <li class="right"><a href="achievements">🌟 2 450 XP</a></li>
    </ul>
  </nav>
</header>

  
