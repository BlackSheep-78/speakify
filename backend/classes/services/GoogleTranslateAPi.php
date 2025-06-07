<?php

// =============================================================================
// Project: Speakify
// File: /backend/classes/services/GoogleTranslateApi.php
// Description: Connects to Google Translate API to translate text.
//              Returns translated sentence or error if the API call fails.
// =============================================================================

class GoogleTranslateApi 
{
    private string $apiKey;

    public function __construct() 
    {
        $config = ConfigLoader::load();
        $keyPath = BASEPATH . '/' . ltrim($config['google']['translate_key_file'] ?? '', '/');
        Logger::debug("GoogleTranslateApi: keyPath = " . $keyPath);

        if (!$keyPath || !file_exists($keyPath)) {
            throw new Exception("Missing or invalid Google Translate key file: {$keyPath}");
        }

        $keyData = json_decode(file_get_contents($keyPath), true);
        $this->apiKey = $keyData['api_key'] ?? null;

        if (!$this->apiKey) {
            throw new Exception("Missing 'api_key' field in translate key file: {$keyPath}");
        }

        Logger::debug("GoogleTranslateApi: apiKey loaded");
    }


    public function translate(string $text, string $sourceLanguage, string $targetLanguage)
    {
        $url = 'https://www.googleapis.com/language/translate/v2'
             . '?key=' . $this->apiKey
             . '&q=' . rawurlencode($text)
             . '&source=' . $sourceLanguage
             . '&target=' . $targetLanguage;
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $curlErr = curl_error($ch);
        curl_close($ch);
    
        if ($curlErr) {
            Logger::error("Erreur cURL: $curlErr");
            return [
                'success' => false,
                'error' => $curlErr,
                'raw' => null
            ];
        }
    
        $responseDecoded = json_decode($response, true);
    
        if (
            !is_array($responseDecoded) ||
            !isset($responseDecoded['data']['translations'][0]['translatedText'])
        ) {
            Logger::error("Réponse invalide de Google Translate");
            Logger::error("Payload brut: " . $response);
            Logger::error("Paramètres envoyés: " . json_encode([
                'text' => $text,
                'from' => $sourceLanguage,
                'to' => $targetLanguage
            ], JSON_UNESCAPED_UNICODE));
    
            return [
                'success' => false,
                'error' => $responseDecoded['error']['message'] ?? 'Unknown error',
                'raw' => $response
            ];
        }
    
        return $responseDecoded['data']['translations'][0]['translatedText'];
    }
    
}
