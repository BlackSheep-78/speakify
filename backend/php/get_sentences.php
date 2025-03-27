<?php
// ============================================================================
// File: speakify/backend/php/get_sentences.php
// Description:
//     This script retrieves up to 50 translation pairs where the original
//     sentence belongs to a specified source language (`lang_id`).
//     For each pair, it fetches the original sentence and all associated
//     translations from the `translation_pair_sources` table.
//     The response is returned in a structured JSON format suitable for
//     frontend playback and multilingual sequencing.
//
// Requirements:
//     - `$config` and `$pdo` must be initialized (typically via init.php).
//     - `lang_id` must be passed via GET (?lang_id=XX).
//     - Uses `translation_pairs`, `sentences`, `languages`, and `translation_pair_sources`.
//
// Response format:
//     [
//       {
//         "pair_id": 1,
//         "original": { "sentence": { "text": "...", "lang_id": ..., "language": "..." } },
//         "translation": {
//           "French": { "sentence": { "text": "...", "lang_id": ... } },
//           "Portuguese": { ... }
//         }
//       },
//       ...
//     ]
// ============================================================================
