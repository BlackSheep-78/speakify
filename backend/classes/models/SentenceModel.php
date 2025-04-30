<?php
// ========================================== 
// Project: Speakify
// File: backend/classes/SentenceModel.php
// Description: Handles retrieval of sentence and translation blocks for playback.
// ==========================================


class SentenceModel 
{
    private Database $db;
    private ?int $lang_id = null;
    private $user_id = null;

    public function __construct(array $options = [])
    {
        $this->db = $options['db'] ?? null;

        if (!$this->db instanceof Database) {
            throw new Exception("SentenceModel requires a valid 'db' instance.");
        }

        $this->lang_id = isset($options['lang_id']) ? (int)$options['lang_id'] : null;
        $this->user_id = $options['user_id'] ?? null;
    }

    public function getSentences(int $lang_id = 39, ?int $user_id = null): array 
    {
        $rows = $this->db->file('/sentences/get_sentences.sql')
                   ->replace(':LANG_ID', $lang_id, 'i')
                   ->result();
    
        // Group by original_sentence_id
        $grouped = [];
    
        foreach ($rows as $row) 
        {
            $origId = $row['original_sentence_id'];
    
            if (!isset($grouped[$origId])) 
            {
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
    
            $audio = TTS::getAudioFor((int)$row['translated_sentence_id'], (int)$row['translated_language_id']);
            $audioUrl = $audio['audio_hash'] ?? null
                ? TTS::getSecureAudioUrl($audio['audio_hash'])
                : null;
            
            $grouped[$origId]['translations'][] = [
                $row['translated_sentence_id'],
                $row['translated_sentence'],
                $row['translated_language_id'],
                $row['translated_language'],
                $audioUrl
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
                    'trans_lang',
                    'audio_url'
                ]
            ],
            'items' => array_values($grouped)
        ];
    }

    public function getSentencePairs(): array
    {
        $lang_id = (int)($this->lang_id ?? 39);
    
        $rows = $this->db->file('/sentences/get_sentences_by_lang.sql')
                   ->replace(':LANG_ID', $lang_id, 'i')
                   ->result();
    
        if (!$rows || !is_array($rows)) {
            return [
                'success' => false,
                'error' => 'No sentence data found.'
            ];
        }
    
        $tts = new TTS(['db' => $this->db]); // ✅ One-time instance
    
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
    
            $audio = $tts->getAudioFor(
                (int)$row['translated_sentence_id'],
                (int)$row['translated_language_id']
            );
    
            $audioUrl = isset($audio['audio_hash'])
                ? $tts->getSecureAudioUrl($audio['audio_hash'])
                : null;
    
            $grouped[$origId]['translations'][] = [
                $row['translated_sentence_id'],
                $row['translated_sentence'],
                $row['translated_language_id'],
                $row['translated_language'],
                $audioUrl
            ];
        }
    
        return [
            'success' => true,
            'items' => array_values($grouped),
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
                    'trans_lang',
                    'audio_url'
                ]
            ]
        ];
    }
    
    
    
    
}
