/* project name: Speakify */
/* file name: /speakify/public/js/script.js */
/* DO NOT REMOVE "project name" and "file name" information */

document.addEventListener("DOMContentLoaded", () => {
    loadSchemasFile();
    loadPlaylists();
    document.getElementById("play-btn").addEventListener("click", playNextAudio);
});

let playlists = [];
let schemas = [];
let currentPlaylist = null;  // the current playlist object
let playbackSchema = [];
let currentBlockIndex = 0;   // index for the current translation block in the playlist
let currentSchemaIndex = 0;  // index in the schema's sequence array for the current block
let currentRepeat = 0;       // current repetition count for the current schema element

// Load the global schemas from schemas.json
function loadSchemasFile() {
    fetch("public/data/schemas.json")
        .then(response => response.json())
        .then(data => {
            if (data.schemas && data.schemas.length > 0) {
                schemas = data.schemas;
                console.log("Loaded Schemas Data:", schemas);
            } else {
                console.error("No schemas found in schemas.json");
            }
        })
        .catch(error => console.error("Error loading schemas:", error));
}

// Load playlists from playlists.json
function loadPlaylists() {
    fetch("public/data/playlists.json")
        .then(response => response.json())
        .then(data => {
            console.log("Loaded Playlists Data:", data);
            if (data.playlists && data.playlists.length > 0) {
                playlists = data.playlists;
                populatePlaylistsDropdown(playlists);
                setCurrentPlaylist(0); // Set the first playlist by default
            } else {
                console.error("No playlists found in playlists.json");
            }
        })
        .catch(error => console.error("Error loading playlists:", error));
}

// Populate the playlist dropdown UI
function populatePlaylistsDropdown(playlists) {
    const playlistList = document.getElementById("playlist-list");
    playlistList.innerHTML = "";
    playlists.forEach((playlist, index) => {
        let listItem = document.createElement("li");
        listItem.textContent = playlist.name;
        listItem.addEventListener("click", () => {
            setCurrentPlaylist(index);
        });
        playlistList.appendChild(listItem);
    });
}

// Set the current playlist and load its default schema
function setCurrentPlaylist(playlistIndex) {
    currentPlaylist = playlists[playlistIndex];
    currentBlockIndex = 0;
    updatePlayerTitle(currentPlaylist.name);
    // Load the default schema using defaultSchemaId from the playlist
    let defaultSchemaId = currentPlaylist.defaultSchemaId;
    let defaultSchema = schemas.find(schema => schema.id === defaultSchemaId);
    if (defaultSchema) {
        playbackSchema = defaultSchema.sequence;
        console.log("Loaded default schema:", defaultSchema.name);
    } else {
        console.error("Default schema not found for the selected playlist.");
    }
    // Populate the schema list UI from the global schemas array
    loadSchemasForPlaylist();
    currentSchemaIndex = 0;
    currentRepeat = 0;
}

// Update the player title in the UI
function updatePlayerTitle(playlistName) {
    const playerTitle = document.getElementById("player-title");
    if (playerTitle) {
        playerTitle.textContent = `Now Playing: ${playlistName}`;
    }
}

// Populate the schema selection UI from the global schemas array
function loadSchemasForPlaylist() {
    const schemaList = document.getElementById("schema-list");
    schemaList.innerHTML = "";
    if (schemas && schemas.length > 0) {
        schemas.forEach((schema) => {
            let listItem = document.createElement("li");
            listItem.textContent = schema.name;
            listItem.addEventListener("click", () => selectSchemaById(schema.id));
            schemaList.appendChild(listItem);
        });
    } else {
        console.error("No schemas available to load.");
    }
}

// Allow the user to select a different schema by its ID
function selectSchemaById(schemaId) {
    let selectedSchema = schemas.find(s => s.id === schemaId);
    if (selectedSchema) {
        playbackSchema = selectedSchema.sequence;
        currentSchemaIndex = 0;
        currentRepeat = 0;
        console.log("Selected Schema:", selectedSchema.name);
    } else {
        console.error("Schema not found for id:", schemaId);
    }
}

// Play the next audio using the current playlist and schema sequence
function playNextAudio() {
    // Check if there are more translation blocks in the playlist
    if (!currentPlaylist || !currentPlaylist.translationBlocks || currentBlockIndex >= currentPlaylist.translationBlocks.length) {
        console.log("Playlist finished.");
        return;
    }
    
    // If the schema sequence for the current block is finished, move to the next block
    if (currentSchemaIndex >= playbackSchema.length) {
        currentSchemaIndex = 0;
        currentRepeat = 0;
        currentBlockIndex++;
        playNextAudio();
        return;
    }
    
    // Get the current translation block and schema element
    let currentBlock = currentPlaylist.translationBlocks[currentBlockIndex];
    let currentSchemaElement = playbackSchema[currentSchemaIndex];
    let repeatTarget = currentSchemaElement.repeat;
    
    // Find the translation matching the language specified in the current schema element
    let translation = currentBlock.translations.find(t => t.language === currentSchemaElement.language);
    if (!translation) {
        console.error(`No translation found for language ${currentSchemaElement.language} in block ${currentBlock.id}`);
        // Skip this schema step if the translation is missing
        currentSchemaIndex++;
        playNextAudio();
        return;
    }
    
    let translationText = `${translation.text} (${translation.language})`;
    
    document.getElementById("tb-text").textContent = translationText;
    document.getElementById("tb-audio").src = `assets/audio/${translation.audio}`;
    
    let audioElement = document.getElementById("tb-audio");
    audioElement.play();
    
    console.log(`${translationText} - audio: ${currentRepeat + 1}/${repeatTarget}`);
    
    audioElement.onended = () => {
        if (currentRepeat + 1 < repeatTarget) {
            currentRepeat++;
        } else {
            currentRepeat = 0;
            currentSchemaIndex++;
        }
        playNextAudio();
    };
}



