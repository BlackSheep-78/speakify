<?php

class OpenAiApi
{
    private $apiKey = 'sk-REPLACE_WITH_YOUR_KEY';
    private $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct($apiKey = null)
    {
        if ($apiKey !== null) {
            $this->apiKey = $apiKey;
        }
    }

    public function translate($text, $sourceLanguage, $targetLanguage)
    {
        $prompt = "Translate the following text from $sourceLanguage to $targetLanguage: \"$text\"";

        $postData = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional translator.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.5
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $responseDecoded = json_decode($response, true);
        curl_close($ch);

        if (isset($responseDecoded['choices'][0]['message']['content'])) {
            return trim($responseDecoded['choices'][0]['message']['content']);
        } else {
            return null;
        }
    }
}
?>
