<?php
// ============================================================================
// ⚠️ DO NOT REMOVE OR MODIFY THIS HEADER
// This file is an admin controller to trigger a single translation operation
// using the Translate logic class. It fetches a sentence needing translation,
// sends it to Google Translate, and stores the result in the DB.
// Only works if a sentence is pending translation.
// ----------------------------------------------------------------------------
// 📁 File: backend/controllers/translate_one.php
// 📦 Project: Speakify
// ============================================================================

global $database;

$translator = new Translate(['db'=>$database]);
$response   = $translator->connectToGoogleToTranslate();

header('Content-Type: application/json');
echo json_encode($response);
