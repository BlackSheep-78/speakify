<?php
// ========================================== 
// Project: Speakify
// File: backend/controllers/get_sentences.php
// Description: Controller to fetch translation blocks for playback.
// ==========================================

global $database, $session;

$lang_id = Input::get('lang_id', 'int', 39);
$user_id = $session['user_id'] ?? null;

$model = new SentenceModel([
    'db' => $database,
    'lang_id' => $lang_id,
    'user_id' => $user_id
]);

$result = $model->getSentencePairs();

//Logger::debug($result);

output($result);
