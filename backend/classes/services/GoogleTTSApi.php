<?php
// =============================================================================
// File: backend/classes/services/GoogleTTSApi.php
// Project: Speakify
// Description: Google Cloud TTS integration using inline service account
// =============================================================================

use Google\Auth\Credentials\ServiceAccountCredentials;

class GoogleTTSApi
{
  public static function synthesize($text, $lang = 'en')
  {
    $config = $GLOBALS['CREDENTIALS']['google'] ?? null;
    $creds = $config['credentials'] ?? null;

    if (!$creds || !is_array($creds)) {
      throw new Exception("Google TTS credentials are missing or invalid.");
    }

    $token = self::getAccessToken($creds);

    $voice = self::mapLangToVoice($lang);
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
    if (!isset($json['audioContent'])) {
      throw new Exception("No audio content returned from Google TTS.");
    }

    return base64_decode($json['audioContent']);
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
