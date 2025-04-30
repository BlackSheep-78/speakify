<?php
// ========================================== 
// Project: Speakify
// File: backend/controllers/get_sentences.php
// Description: Controller to fetch translation blocks for playback.
// ==========================================

global $database, $session;

$lang_id = isset($_GET['lang_id']) ? (int) $_GET['lang_id'] : 39;
$user_id = $session['user_id'] ?? null;

$model = new SentenceModel([
    'db' => $database,
    'lang_id' => $lang_id,
    'user_id' => $user_id
]);

$result = $model->getSentencePairs();

Logger::debug($result);

echo json_encode($result);
