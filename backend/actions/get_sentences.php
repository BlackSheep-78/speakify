<?php
// ============================================================================
// File: speakify/backend/actions/get_sentences.php
// Description:
//     Entry point for serving translation sentence pairs. Requires a valid
//     session token and returns original and translated sentence data.
//
// Assumptions:
//     - $config and BASEPATH are already defined in the calling context.
//     - This file is executed via /public/api/index.php?action=get_sentences
//     - language_id = 39 is the default source language (English)
// ============================================================================

ini_set('display_errors', 1);
error_reporting(E_ALL);

error_log("✅ Entered core get_sentences logic");

try {
    if (!isset($config) || !defined('BASEPATH')) {
        http_response_code(500);
        echo json_encode(['error' => 'Environment not initialized']);
        exit;
    }

    if (!isset($pdo)) {
        $pdo = new PDO(
            "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4",
            $config['db_user'],
            $config['db_pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    // ✅ Auth check (sets $auth_user_id or exits with error)
    require_once BASEPATH . '/utils/auth.php';

    // ✅ Use lang_id=39 (English) by default
    $lang_id = $_GET['lang_id'] ?? 39;

    // ✅ Fetch translation pairs starting from English
    $stmt = $pdo->prepare("
        SELECT 
            tp.pair_id,
            s1.sentence_id AS original_sentence_id,
            s1.sentence_text AS original_sentence,
            l1.language_id AS original_language_id,
            l1.language_name AS original_language,
            s2.sentence_id AS translated_sentence_id,
            s2.sentence_text AS translated_sentence,
            l2.language_id AS translated_language_id,
            l2.language_name AS translated_language
        FROM translation_pairs tp
        JOIN sentences s1 ON tp.sentence_id_1 = s1.sentence_id
        JOIN languages l1 ON s1.language_id = l1.language_id
        JOIN sentences s2 ON tp.sentence_id_2 = s2.sentence_id
        JOIN languages l2 ON s2.language_id = l2.language_id
        WHERE s1.language_id = :lang_id
        ORDER BY tp.pair_id, l2.language_name
    ");
    $stmt->execute(['lang_id' => $lang_id]);
    $pairs = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'lang_id' => $lang_id,
        'count' => count($pairs),
        'pairs' => $pairs
    ]);
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
        'error' => 'Unexpected error in get_sentences entry point',
        'details' => $config['debug'] ? $e->getMessage() : null
    ]);
    exit;
}
