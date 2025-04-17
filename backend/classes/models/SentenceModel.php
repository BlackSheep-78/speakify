<?php
// =============================================================================
// 📁 File: backend/classes/SentenceModel.php
// 📌 Purpose: Data access layer for translation sentence pairs.
// =============================================================================
// 🧠 Description:
//   - Provides methods to query translation-related data from the database.
//   - `getSentences()` retrieves all translated pairs for a given original
//     language, grouped by sentence and returned in a minified template format.
//
// 📤 Output Format:
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
// 🏗️ Usage:
//   $model = new SentenceModel($pdo, $config);
//   $result = $model->getSentences(39, $user_id);
//
// 📦 Dependencies:
//   - Requires active PDO connection and configuration array.
//   - Relies on tables: translation_pairs, sentences, languages.
//
// 🔐 Auth: Caller is responsible for validating session before use.
// =============================================================================


class SentenceModel {
    private PDO $pdo;
    private array $config;

    public function __construct(PDO $pdo, array $config) {
        $this->pdo = $pdo;
        $this->config = $config;
    }

    public function getSentences(int $lang_id = 39, ?int $user_id = null): array {
        // Fetch full flat list of pairs
        $stmt = $this->pdo->prepare("
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
        $rows = $stmt->fetchAll();

        // Group by original_sentence_id
        $grouped = [];

        foreach ($rows as $row) {
            $origId = $row['original_sentence_id'];

            if (!isset($grouped[$origId])) {
                $grouped[$origId] = [
                    'group' => [
                        $origId,
                        $row['original_sentence'],
                        $row['original_language_id'],
                        $row['original_language']
                    ],
                    'translations' => []
                ];
            }

            $grouped[$origId]['translations'][] = [
                $row['translated_sentence_id'],
                $row['translated_sentence'],
                $row['translated_language_id'],
                $row['translated_language']
            ];
        }

        return [
            'template' => [
                'group' => [
                    'orig_id',
                    'orig_txt',
                    'orig_lang_id',
                    'orig_lang'
                ],
                'translation' => [
                    'trans_id',
                    'trans_txt',
                    'trans_lang_id',
                    'trans_lang'
                ]
            ],
            'items' => array_values($grouped)
        ];
    }
}
