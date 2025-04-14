<!-- 
  ============================================================================
  📚 File: speakify/public/views/playlist-library.php
  ============================================================================
  Project: Speakify
  View: Playlist Library
  ----------------------------------------------------------------------------
  Description:
    This page allows users to browse, view, and manage language learning
    playlists. Each playlist contains Translation Blocks (TBs) and is linked
    to a customizable Playback Schema. Users can:
      - View existing playlists
      - Launch playback directly
      - Edit playlists
      - Create new playlists

  UI Notes:
    - Consistent with Speakify design: header, content, footer-nav
    - Cards used for playlist layout
    - Navigation icons fixed at bottom

  JS Dependencies:
    - app.js (handles session, playlist events)
    - playlist-library specific handlers (TBD)

  API Endpoints Used:
    - GET /api?action=playlists
    - [Planned] POST /api?action=save_playlist
    - [Planned] GET /api?action=schemas

  Status:
    🚧 In Progress – Functionality to display and interact with playlists
    🔗 Planned Integration with: playback, playlist-editor, schema-editor

  ============================================================================
-->

<div class="content">
  <!-- Page Title -->
  <h1 class="page-title">📚 Mes Playlists</h1>
  <p class="subtext">Créez, modifiez ou lancez vos sessions d'apprentissage</p>

  <!-- Playlist Container -->
  <div id="playlist-list" class="dashboard-links" style="margin-top: 20px;"></div>

  <!-- Optional: Create New Playlist -->
  <div style="text-align: center; margin-top: 40px;">
    <a href="playlist-editor.html" class="button primary">➕ Nouvelle Playlist</a>
  </div>
</div>


