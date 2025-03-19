<?php
// Main entry point for the Translation Database System PWA

// Set content type to HTML
header("Content-Type: text/html");

// Define the path to the public index.html
$public_index = __DIR__ . '/public/index.html';

// Check if the file exists before including
if (file_exists($public_index)) {
    readfile($public_index);
} else {
    echo "<h1>Error: index.html not found.</h1>";
}
