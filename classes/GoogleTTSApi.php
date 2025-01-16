<?php

    class GoogleTTSApi 
    {
        public $apiKey = 'AIzaSyBuWyi6rHjpdqn-QFKkLeORAmcI7r_ogDw';

        // Constructor
        public function __construct() 
        {
           
        }

        function textToSpeech($text, $languageCode = 'en-US', $voiceName = 'en-US-Wavenet-D', $audioFile = 'output.mp3') {
            // Google Cloud TTS endpoint
            $url = "https://texttospeech.googleapis.com/v1/text:synthesize";
        
            // Replace this with the OAuth 2.0 token you generated
            $accessToken = "YOUR_ACCESS_TOKEN_HERE";
        
            // Request payload
            $payload = [
                "input" => ["text" => $text],
                "voice" => [
                    "languageCode" => $languageCode,
                    "name" => $voiceName
                ],
                "audioConfig" => [
                    "audioEncoding" => "MP3"
                ]
            ];
        
            // Initialize cURL
            $ch = curl_init();
        
            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $accessToken",
                "Content-Type: application/json"
            ]);
        
            // Execute the request
            $response = curl_exec($ch);
        
            // Check for errors
            if (curl_errno($ch)) {
                echo 'Error: ' . curl_error($ch);
                curl_close($ch);
                return;
            }
        
            curl_close($ch);
        
            // Decode the JSON response
            $responseJson = json_decode($response, true);
        
            // Save the audio content to a file
            if (isset($responseJson['audioContent'])) {
                file_put_contents($audioFile, base64_decode($responseJson['audioContent']));
                echo "Audio content written to $audioFile\n";
            } else {
                echo "Error: " . $response . "\n";
            }
        } 
    }
?>