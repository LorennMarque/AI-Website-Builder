<?php
// Your Google API key
$apiKey = 'AIzaSyByLctahA8JjQ329kIg8bZRehFT_TBdiFY';

// API endpoint
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey;

// Prepare the data for the request
$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => 'Hello darling!']
            ]
        ]
    ]
];

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute the cURL request and get the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Print the response
    echo 'Response: ' . $response;
}

// Close cURL session
curl_close($ch);
?>
