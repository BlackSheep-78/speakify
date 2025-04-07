<?php

// =============================================================================
// 📁 File: backend/controllers/get_sentences.php
// 📌 Purpose: API endpoint that returns grouped translation sentence pairs.
// =============================================================================
// 🧠 Description:
//   - Uses SentenceModel to retrieve all translation pairs from a given source
//     language (default is English with lang_id = 39).
//   - Output is grouped by original sentence and minified using a template.
//   - Designed for frontend playback UI and translation block rendering.
//   - Requires session token validation (handled by api/index.php).
//
// 🔄 Example Output Format:
// {
//   "template": { "group": [...], "translation": [...] },
//   "items": [
//     {
//       "group": [...],
//       "translations": [[...], [...]]
//     }
//   ]
// }
//
// 📦 Dependencies:
//   - backend/classes/SentenceModel.php
//   - $pdo and $config must be initialized via init.php
//
// 🔐 Auth: Requires validated session (handled in central API router)
// =============================================================================


require_once BASEPATH . '/backend/classes/SentenceModel.php';

try {
    $lang_id = $_GET['lang_id'] ?? 39;
    $model = new SentenceModel($pdo, $config);

    $result = $model->getSentences((int)$lang_id, $GLOBALS['auth_user_id'] ?? null);

    echo json_encode($result);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database connection error',
        'details' => $config['debug'] ? $e->getMessage() : null
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Unexpected error in get_sentences',
        'details' => $config['debug'] ? $e->getMessage() : null
    ]);
    exit;
}
