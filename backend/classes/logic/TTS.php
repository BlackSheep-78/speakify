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

  public static function generateSample()
  {
    $text = "Bonjour et bienvenue sur Speakify.";
    $lang = "fr";
    $provider = "google";
  
    return self::synthesize($text, $lang, $provider);
  }

  public static function synthesize($text, $lang = 'en', $provider = 'google')
  {
    if (trim($text) === '' || !isset(self::$providers[$provider])) {
      throw new Exception("Invalid request: missing text or unsupported provider.");
    }

    $class = self::$providers[$provider];

    // 🎯 Call the provider-specific synthesize method
    $binary = $class::synthesize($text, $lang);

    if (!$binary || !is_string($binary)) {
      throw new Exception("TTS provider did not return valid audio data.");
    }

    // 📁 Save the audio file
    $filename = self::generateFilename($text, $lang, $provider);
    $outputDir = BASEPATH . '/public/assets/audio/tts/';
    if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);
    file_put_contents($outputDir . $filename, $binary);

    // Optional: store metadata in DB (future)

    return [
      'success' => true,
      'file' => 'assets/audio/tts/' . $filename,
      'provider' => $provider,
      'lang' => $lang
    ];
  }

  protected static function generateFilename($text, $lang, $provider)
  {
    $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower(substr($text, 0, 50)));
    $hash = substr(sha1($text . $lang . $provider), 0, 8);
    return "{$slug}-{$lang}-{$provider}-{$hash}.mp3";
  }
}
