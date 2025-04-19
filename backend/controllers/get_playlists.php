<?php
    /**
     * =============================================================================
     * 📁 File: /backend/controllers/get_playlists.php
     * 📦 Project: Speakify
     * 📌 Description: Standalone script to fetch playlists (optionally filtered by user token).
     * -----------------------------------------------------------------------------
     * 📤 Output: JSON list of playlists
     * 💡 Usage: Previously used directly via API, should now be routed via api.php
     * =============================================================================
     */

     //require BASEPATH . '/backend/classes/PlaylistModel.php';
     
     Logger::debug("get_playlists.php");
     
     $model = new PlaylistModel($pdo);
     $user_id = SessionManager::getUserIdFromToken($_GET['token'] ?? null);

     $playlists = $model->getPlaylists($user_id);

     try 
     {
         $playlists = $model->getPlaylists($user_id);
        echo json_encode([
            'success' => true,
            'playlists' => $playlists
        ]);
     } 
     catch (Exception $e) 
     {
        Logger::debug(print_r($e,true));
        echo json_encode(['error' => 'Failed to fetch playlists']);
     }
     

?>
