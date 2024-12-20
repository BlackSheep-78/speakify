<?php

    class GoogleTranslateAPi 
    {
        public $apiKey = 'AIzaSyBuWyi6rHjpdqn-QFKkLeORAmcI7r_ogDw';

        // Constructor
        public function __construct() 
        {
           
        }

        public function translate($text,$sourceLanguage,$targetLanguage)
        {
            $url = 'https://www.googleapis.com/language/translate/v2?key='.$this->apiKey.'&q='.rawurlencode($text).'&source='.$sourceLanguage.'&target='.$targetLanguage;
            
            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($handle);
            $responseDecoded = json_decode($response, true);
            curl_close($handle);

            return $responseDecoded['data']['translations'][0]['translatedText'];
        }  
    }
?>