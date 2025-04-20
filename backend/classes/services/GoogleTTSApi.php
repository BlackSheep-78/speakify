<?php
// =============================================================================
// File: backend/classes/services/GoogleTTSApi.php
// Project: Speakify
// Description: Google Cloud TTS integration using inline service account
// =============================================================================

use Google\Auth\Credentials\ServiceAccountCredentials;

class GoogleTTSApi
{
  public static function synthesize($text, $lang = 'en', $voiceName = null)
  {
      $config = $GLOBALS['CREDENTIALS']['google'] ?? null;
      $keyFile = $config['tts_key_file'] ?? null;
  
      if (!$keyFile) {
          throw new Exception("Google TTS key file path not configured.");
      }
  
      $keyPath = BASEPATH . '/' . ltrim($keyFile, '/');
      if (!file_exists($keyPath)) {
          throw new Exception("Google TTS key file not found: $keyPath");
      }
  
      $creds = json_decode(file_get_contents($keyPath), true);
      if (!$creds || !is_array($creds)) {
          throw new Exception("Google TTS credentials are missing or invalid.");
      }
  
      $token = self::getAccessToken($creds);
  
      // ✅ Use passed voice name, or fallback to internal resolver
      $voice = $voiceName
          ? ['lang' => $lang, 'name' => $voiceName]
          : self::mapLangToVoice($lang);
  
      $postData = [
          'input' => ['text' => $text],
          'voice' => [
              'languageCode' => $voice['lang'],
              'name' => $voice['name']
          ],
          'audioConfig' => [
              'audioEncoding' => 'MP3',
              'speakingRate' => 1.0
          ]
      ];

      Logger::log('DEBUG', 'Google TTS POST: ' . json_encode($postData));
  
      $ch = curl_init('https://texttospeech.googleapis.com/v1/text:synthesize');
      curl_setopt_array($ch, [
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => [
              'Content-Type: application/json',
              "Authorization: Bearer $token"
          ],
          CURLOPT_POSTFIELDS => json_encode($postData)
      ]);
  
      $result = curl_exec($ch);
      $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
  
      if ($status !== 200 || !$result) {
          throw new Exception("Google TTS request failed (status $status): " . substr($result, 0, 200));
      }
  
      $json = json_decode($result, true);

      Logger::log('DEBUG', 'Google TTS RESPONSE: ' . $result); // Add this

      if (!isset($json['audioContent'])) {
          throw new Exception("No audio content returned from Google TTS.");
      }
  
      $binary = base64_decode($json['audioContent']);

      if (!$binary || !is_string($binary)) 
      {
        throw new Exception("TTS provider returned invalid binary.");
      } 
      elseif (strlen($binary) < 1000) 
      {
          Logger::log('WARN', 'TTS binary is unusually small: ' . strlen($binary));
      }
      
      return $binary;
  }
  
  public static function listVoices(): array
  {
    $config = $GLOBALS['CREDENTIALS']['google'] ?? null;
    $keyFile = $config['tts_key_file'] ?? null;

    if (!$keyFile) {
      throw new Exception("Google TTS key file path not configured.");
    }

    $keyPath = BASEPATH . '/' . ltrim($keyFile, '/');
    if (!file_exists($keyPath)) {
      throw new Exception("Google TTS key file not found: $keyPath");
    }

    $creds = json_decode(file_get_contents($keyPath), true);
    if (!$creds || !is_array($creds)) {
      throw new Exception("Google TTS credentials are missing or invalid.");
    }

    $token = self::getAccessToken($creds);

    $ch = curl_init('https://texttospeech.googleapis.com/v1/voices');
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token
      ]
    ]);

    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status !== 200 || !$result) {
      throw new Exception("Failed to list Google TTS voices (status $status): " . substr($result, 0, 200));
    }

    $json = json_decode($result, true);
    return $json['voices'] ?? [];
  }

  protected static function getAccessToken(array $credentials)
  {
    $scope = 'https://www.googleapis.com/auth/cloud-platform';

    $jwt = new ServiceAccountCredentials($scope, $credentials);
    $tokenData = $jwt->fetchAuthToken();

    if (empty($tokenData['access_token'])) {
      throw new Exception("Failed to retrieve Google Cloud access token.");
    }

    return $tokenData['access_token'];
  }

  protected static function mapLangToVoice($lang)
  {
    $voices = [
      'en' => ['lang' => 'en-US', 'name' => 'en-US-Wavenet-D'],
      'fr' => ['lang' => 'fr-FR', 'name' => 'fr-FR-Wavenet-B'],
      'pt' => ['lang' => 'pt-PT', 'name' => 'pt-PT-Wavenet-A']
    ];

    return $voices[$lang] ?? $voices['en'];
  }
}
