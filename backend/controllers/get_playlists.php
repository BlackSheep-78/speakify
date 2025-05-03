<?php
// =============================================================================
// File: backend/controllers/get_playlists.php
// Project: Speakify
// Description: Controller to fetch all available playlists
// =============================================================================


global $database;

$model = new PlaylistModel(['db' => $database]);

$playlists = $model->getPlaylists(); // assuming this exists and returns an array

output([
    'success' => true,
    'playlists' => $playlists
]);
