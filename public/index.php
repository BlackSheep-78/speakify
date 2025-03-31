<?php
// file: speakify/public/index.php

// Load backend/session environment
//require_once __DIR__ . '/../backend/init.php';

// 🔁 Redirect to dashboard view (frontend)
header("Location: /speakify/public/dashboard.html");
exit;
