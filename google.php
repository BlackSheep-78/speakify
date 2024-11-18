<?php
    /* Your Google API key */
    $apiKey = 'AIzaSyBuWyi6rHjpdqn-QFKkLeORAmcI7r_ogDw';

    /* the text to translate */
    $text = 'Hello, World!';

    /* url to translate the text from English (en) to Spanish (es) */
    $url = 'https://www.googleapis.com/language/translate/v2?key=' .
        $apiKey . '&q=' . rawurlencode($text) .
        '&source=en&target=es';

    /* curl request */
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);
    $responseDecoded = json_decode($response, true);
    curl_close($handle);

    /* the translated text ¡Hola Mundo! */
    echo $responseDecoded['data']['translations'][0]['translatedText'];

    print_r($responseDecoded);
?>
