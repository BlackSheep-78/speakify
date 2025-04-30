<?php
// =============================================================================
// File: backend/classes/logic/TTS.php
// Project: Speakify
// Description: Main orchestrator for text-to-speech generation
// Supports multiple providers (Google, OpenAI, Amazon, etc.)
// =============================================================================

class TTS
{
    protected static $providers = [
        'google' => GoogleTTSApi::class
    ];

    private Database $db;

    public function __construct(array $options = []) 
    {
        $this->db = $options['db'] ?? null;
        if (!$this->db instanceof Database) 
        {
            throw new Exception("TTS requires a valid 'db' instance.");
        }
    }

    public static function getVoices(string $provider): array
    {
        switch (strtolower($provider)) {
            case 'google':
                return GoogleTTSApi::listVoices();
            default:
                throw new Exception("Unsupported TTS provider: $provider");
        }
    }

    public static function generateSample(): array
    {
        try {
            $task = TTSModel::getMissingAudioTask();
            if (!$task) {
                return [
                    'success' => false,
                    'message' => 'No missing audio found.'
                ];
            }
            return self::generateFor($task);
        } catch (Throwable $e) {
            Logger::error('generateSample failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Sample generation failed',
                'details' => defined('DEBUG') && DEBUG ? $e->getMessage() : null
            ];
        }
    }

    public static function generateFor(array $task): array
    {
        $sentence = TTSModel::getSentenceText($task['sentence_id']);
        if (!$sentence) throw new Exception("Sentence not found: {$task['sentence_id']}");

        $voiceMeta = TTSModel::getVoiceMetadata($task['voice'], $task['provider_id']);
        if (!$voiceMeta) throw new Exception("Voice metadata not found for {$task['voice']}");

        $langTag = explode('-', $task['voice'])[0] . '-' . explode('-', $task['voice'])[1];
        $provider = $voiceMeta['provider'];

        $hash = sha1($sentence . $langTag . $provider . $task['voice']);

        $existing = TTSModel::checkIfAudioExists(
            $task['sentence_id'],
            $task['language_id'],
            $task['provider_id'],
            $task['voice']
        );

        if (!empty($existing)) {
            return [
                'success' => true,
                'message' => 'Audio already exists',
                'audio'   => $existing
            ];
        }

        $audio = self::renderAudioFile([
            'text'     => $sentence,
            'lang'     => $langTag,
            'provider' => $provider,
            'voice'    => $task['voice']
        ]);

        if (!$audio || !isset($audio['full_path'])) {
            throw new Exception("Audio file rendering failed or returned invalid format.");
        }

        TTSModel::insertAudio(
            $task['sentence_id'],
            $task['language_id'],
            $task['provider_id'],
            $task['voice'],
            $audio['path'],
            $audio['hash']
        );

        return [
            'success'      => true,
            'sentence_id'  => $task['sentence_id'],
            'voice'        => $task['voice'],
            'provider'     => $provider,
            'lang'         => $langTag,
            'file'         => $audio['file'],
            'path'         => $audio['path'],
            'full_path'    => $audio['full_path'],
            'hash'         => $audio['hash'],
            'original'     => $sentence
        ];
    }

    public static function renderAudioFile(array $options)
    {
        $text     = trim($options['text'] ?? '');
        $lang     = $options['lang'] ?? 'en-US';
        $provider = $options['provider'] ?? 'google';
        $voice    = $options['voice'] ?? null;

        if ($text === '' || !isset(self::$providers[$provider])) {
            throw new Exception("Invalid request: missing text or unsupported provider.");
        }

        $class = self::$providers[$provider];
        $binary = $class::synthesize($text, $lang, $voice);

        $hash = sha1($text . $lang . $provider . $voice);
        $paths = self::buildAudioPath($hash);
        $fullPath = $paths['full_path'];

        file_put_contents($fullPath, $binary);

        if (!file_exists($fullPath) || filesize($fullPath) < 500) {
            Logger::info('ERROR', "TTS file failed sanity check: $fullPath");
            throw new Exception("Audio file rendering failed (file missing or too small).");
        }

        return [
            'success'     => true,
            'hash'        => $hash,
            'path'        => $paths['relative_path'],
            'full_path'   => $paths['full_path'],
            'file'        => $paths['file'],
            'provider'    => $provider,
            'lang'        => $lang,
            'created_at'  => date('Y-m-d H:i:s')
        ];
    }

    public static function buildAudioPath(string $hash): array
    {
        $dir1 = substr($hash, 0, 2);
        $dir2 = substr($hash, 2, 2);
        $dir3 = substr($hash, 4, 2);
        $filename = substr($hash, 6) . '.mp3';

        $relativePath = "audio/$dir1/$dir2/$dir3/$filename";
        $fullPath = STORAGE_AUDIO . "/$dir1/$dir2/$dir3/";

        if (!is_dir($fullPath)) mkdir($fullPath, 0775, true);

        return [
            'relative_path' => $relativePath,
            'full_path'     => $fullPath . $filename,
            'file'          => $filename
        ];
    }

    public function getAudioFor(int $sentenceId, int $langId): ?array
    {
        $ttsModel = new TTSModel(['db' => $this->db]);
        return $ttsModel->getAudioForSentence($sentenceId, $langId);
    }    

    public static function getSecureAudioUrl(string $hash): string
    {
        return "/api/index.php?action=get_tts_file&hash=" . urlencode($hash);
    }

    protected static function generateFilename($text, $lang, $provider)
    {
        $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower(substr($text, 0, 50)));
        $hash = substr(sha1($text . $lang . $provider), 0, 8);
        return "{$slug}-{$lang}-{$provider}-{$hash}.mp3";
    }
}
