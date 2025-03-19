<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

$data_file = __DIR__ . '/../public/data/playlists.json';

if (!file_exists($data_file)) {
    echo json_encode(["error" => "Data file not found"]);
    exit;
}

$json_data = file_get_contents($data_file);
$data = json_decode($json_data, true);

if (!$data) {
    echo json_encode(["error" => "Invalid JSON format"]);
    exit;
}

echo json_encode($data, JSON_PRETTY_PRINT);
exit;
