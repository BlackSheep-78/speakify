<?php
// =============================================================================
// File: backend/classes/logic/TTS.php
// Project: Speakify
// Description: Main orchestrator for text-to-speech generation
// Supports multiple providers (Google, OpenAI, Amazon, etc.)
// =============================================================================


/*

TTS::generateSample()
TTS::generateMissingAudio() → returns $task
TTS::generateFor($task)

*/

class TTS
{
  protected static $providers = [
    'google' => GoogleTTSApi::class
    // future: 'openai' => OpenAITTSApi::class
    // future: 'amazon' => AmazonTTSApi::class
  ];

  public static function getVoices(string $provider): array
  {
    switch (strtolower($provider)) {
      case 'google':
        return GoogleTTSApi::listVoices();
      // case 'openai':
      //   return OpenAITTS::listVoices();
      // case 'aws':
      //   return AmazonTTS::listVoices();
      default:
        throw new Exception("Unsupported TTS provider: $provider");
    }
  }

  public static function generateSample(): array
  {
      try {
          // 🔍 Get one missing audio combo (sentence + language + provider + voice)
          $task = self::generateMissingAudio();
  
          if (!$task) {
              return [
                  'success' => false,
                  'message' => 'No missing audio found.'
              ];
          }
  
          // 🎯 Delegate to the actual generator
          return self::generateFor($task);
      } catch (Throwable $e) {
          Logger::log('ERROR', 'generateSample failed: ' . $e->getMessage());
  
          return [
              'success' => false,
              'error' => 'Sample generation failed',
              'details' => DEBUG ? $e->getMessage() : null
          ];
      }
  }

  public static function generateMissingAudio(): ?array
  {
      $db = Database::init();
  
      $result = $db->file('/tts/generate_missing_audio.sql')
                   ->result(['fetch' => 'assoc']);
  
      return $result[0] ?? null;
  }  

  public static function generateFor(array $task): array
  {
      $db = Database::init();
  
      // 🧠 Extract info
      $sentenceId  = (int)($task['sentence_id'] ?? 0);
      $languageId  = (int)($task['language_id'] ?? 0);
      $providerId  = (int)($task['provider_id'] ?? 0);
      $voice       = $task['voice'] ?? '';
  
      // 🔍 Fetch sentence
      $sentence = $db->file('/tts/get_sentence.sql')
                     ->replace(':SID', $sentenceId, 'i')
                     ->result(['fetch' => 'assoc'])[0]['sentence_text'] ?? null;

  
      if (!$sentence) {
          throw new Exception("Sentence not found: $sentenceId");
      }
  
      // 🧠 Optional: fetch language tag from voice for rendering
      $voiceMeta = $db->file('/tts/get_voice_metadata.sql')
                ->replace(':VOICE', $voice, 's')
                ->replace(':PROVIDER_ID', $providerId, 'i')
                ->result(['fetch' => 'assoc'])[0] ?? null;

  
      if (!$voiceMeta) {
          throw new Exception("Voice metadata not found for $voice");
      }
  
      $langTag = explode('-', $voice)[0] . '-' . explode('-', $voice)[1];
      $provider = $voiceMeta['provider'];
  
      // 🧪 Hash check (prevent duplicates)
      $hash = sha1($sentence . $langTag . $provider . $voice);
      $check = $db->file('/tts/check_audio_exists.sql')
      ->replace(':SID', $sentenceId, 'i')
      ->replace(':LID', $languageId, 'i')
      ->replace(':PID', $providerId, 'i')
      ->replace(':VOICE', $voice, 's')
      ->result(['fetch' => 'assoc']);

  
      if (!empty($check)) {
          return [
              'success' => true,
              'message' => 'Audio already exists',
              'audio'   => $check[0]
          ];
      }
  
      // 🎙️ Render file
      $audio = self::renderAudioFile([
          'text'     => $sentence,
          'lang'     => $langTag,
          'provider' => $provider,
          'voice'    => $voice
      ]);

      if (!$audio || !isset($audio['full_path'])) 
      {
        throw new Exception("Audio file rendering failed or returned invalid format.");
      }
  
      // 💾 Store in DB
      $db->file('/tts/insert_audio.sql')
          ->replace(':SID', $sentenceId, 'i')
          ->replace(':LID', $languageId, 'i')
          ->replace(':PID', $providerId, 'i')
          ->replace(':VOICE', $voice, 's')
          ->replace(':PATH', $audio['path'], 's')   // ✅ use relative_path
          ->replace(':HASH', $audio['hash'], 's')
          ->result();
  
        return [
            'success'      => true,
            'sentence_id'  => $sentenceId,
            'voice'        => $voice,
            'provider'     => $provider,
            'lang'         => $langTag,
            'file'         => $audio['file'],
            'path'         => $audio['path'],
            'full_path'    => $audio['full_path'],
            'hash'         => $audio['hash'],
            'original'     => $sentence // 👈 Add original text
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
  
      // 🎯 Synthesize audio
      $binary = $class::synthesize($text, $lang, $voice);
  
      // 🎩 Generate safe path
      $hash = sha1($text . $lang . $provider . $voice);
      $paths = self::buildAudioPath($hash); // returns full_path + relative_path + file
      $fullPath = $paths['full_path'];
  
      // 📂 Write file
      file_put_contents($fullPath, $binary);
  
      // ✅ Verify the file actually exists and has some size
      if (!file_exists($fullPath) || filesize($fullPath) < 500) {
          Logger::log('ERROR', "TTS file failed sanity check: $fullPath");
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

      if (!is_dir($fullPath)) {
          mkdir($fullPath, 0775, true);
      }

      return [
          'relative_path' => $relativePath,
          'full_path'     => $fullPath . $filename,
          'file'          => $filename
      ];
  }
  
  public static function getAudioFor(int $sentenceId, int $langId): ?array
  {
      $db = Database::init();
  
      return $db->file('/tts/get_audio_for_sentence.sql')
                ->replace(':SID', $sentenceId, 'i')
                ->replace(':LID', $langId, 'i')
                ->result(['fetch' => 'assoc'])[0] ?? null;
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
