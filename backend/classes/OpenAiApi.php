<?php
// Your OpenAI API key
$api_key = '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';

// Set the API endpoint for the OpenAI API
$url = 'https://api.openai.com/v1/chat/completions';

// Prepare the request data
$data = [
    'model' => 'gpt-3.5-turbo',  // Using the gpt-3.5-turbo model
    'messages' => [
        ['role' => 'system', 'content' => 'You are a helpful assistant that can translate English to French.'],
        ['role' => 'user', 'content' => 'Translate the following sentence to French: "Hello, how are you today?"']
    ]
];

// Set the headers for the request
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
];

// Initialize a cURL session
$ch = curl_init();

// Set the cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Execute the cURL request and get the response
$response = curl_exec($ch);

// Close the cURL session
curl_close($ch);

// Decode the JSON response
$response_data = json_decode($response, true);

// Check and display the result
if (isset($response_data['choices'][0]['message']['content'])) {
    echo 'Translated Sentence: ' . $response_data['choices'][0]['message']['content'];
} else {
    echo 'Error: ' . $response_data['error']['message'];
}

?>