<?php
// =============================================================================
// File: backend/classes/TTS.php
// Project: Speakify
// Description: Main orchestrator for text-to-speech generation
// Supports multiple providers (Google, OpenAI, Amazon, etc.)
// =============================================================================

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

  public static function generateSample()
  {
    $text = "Bonjour et bienvenue sur Speakify.";
    $lang = "fr";
    $provider = "google";
  
    return self::synthesize($text, $lang, $provider);
  }

  public static function synthesize(array $options)
  {
    $text     = trim($options['text'] ?? '');
    $lang     = $options['lang'] ?? 'en-US';
    $provider = $options['provider'] ?? 'google';

    if ($text === '' || !isset(self::$providers[$provider])) {
      throw new Exception("Invalid request: missing text or unsupported provider.");
    }

    $class = self::$providers[$provider];

    // 🎯 Call the provider-specific synthesize method
    $binary = $class::synthesize($text, $lang);

    if (!$binary || !is_string($binary)) {
      throw new Exception("TTS provider did not return valid audio data.");
    }

    // 📁 Determine secure storage path
    $date = date('Y-m');
    $outputDir = BASEPATH . "/backend/storage/tts/{$lang}/{$provider}/{$date}/";
    if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);

    // 🎩 Generate safe random filename
    $id = bin2hex(random_bytes(8));
    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', substr($text, 0, 30)), '-'));
    $filename = $slug . '--' . $id . '.mp3';
    $fullPath = $outputDir . $filename;

    // 📂 Write audio file to storage
    file_put_contents($fullPath, $binary);

    // 🔐 Return metadata for tracking
    return [
      'success'     => true,
      'id'          => $id,
      'path'        => $fullPath,
      'file'        => "/api?action=get_tts_file&id=$id", // future-safe
      'provider'    => $provider,
      'lang'        => $lang,
      'created_at'  => date('Y-m-d H:i:s')
    ];
  }

  protected static function generateFilename($text, $lang, $provider)
  {
    $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower(substr($text, 0, 50)));
    $hash = substr(sha1($text . $lang . $provider), 0, 8);
    return "{$slug}-{$lang}-{$provider}-{$hash}.mp3";
  }
}
