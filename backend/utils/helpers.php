<?php
// =================================================================================
// Project     : Speakify
// File        : backend/utils/helpers.php
// Description : Defines global helper functions used app-wide (e.g., output())
// Author      : Jorge (Blacksheep)
// Created     : 2025-06-07
// =================================================================================

if (!function_exists('output')) 
{
  function output($data) 
  {
    if (!headers_sent()) {
      header('Content-Type: application/json; charset=utf-8');
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
  }
}
