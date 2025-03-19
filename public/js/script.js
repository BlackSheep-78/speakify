document.addEventListener("DOMContentLoaded", () => {
    loadPlaylists();

    document.getElementById("play-btn").addEventListener("click", playAudio);
    document.getElementById("next-btn").addEventListener("click", nextTB);
    document.getElementById("prev-btn").addEventListener("click", prevTB);
});

let playlists = [];
let currentPlaylist = null;
let currentTBIndex = 0;

function loadPlaylists() {
    fetch("backend/api.php")
        .then(response => response.json())
        .then(data => {
            playlists = data.playlists;
            displayPlaylists();
            populatePlaylistDropdown();
        })
        .catch(error => console.error("Error loading playlists:", error));
}

function displayPlaylists() {
    const playlistList = document.getElementById("playlist-list");
    playlistList.innerHTML = "";
    playlists.forEach(playlist => {
        const li = document.createElement("li");
        li.textContent = playlist.name;
        li.addEventListener("click", () => selectPlaylist(playlist));
        playlistList.appendChild(li);
    });
}

function populatePlaylistDropdown() {
    const playlistSelect = document.getElementById("playlist-select");
    if (!playlistSelect) return;
    playlistSelect.innerHTML = "";
    playlists.forEach(playlist => {
        const option = document.createElement("option");
        option.value = playlist.id;
        option.textContent = playlist.name;
        playlistSelect.appendChild(option);
    });
}

function selectPlaylist(playlist) {
    currentPlaylist = playlist;
    currentTBIndex = 0;
    playCurrentTB();
}

function playCurrentTB() {
    if (!currentPlaylist) return;
    
    const tb = currentPlaylist.blocks[currentTBIndex];
    if (!tb) return;
    
    document.getElementById("tb-text").textContent = tb.text;
    const audioElement = document.getElementById("tb-audio");
    audioElement.src = `assets/audio/${tb.audio}`;
    audioElement.play();
}

function nextTB() {
    if (!currentPlaylist || currentTBIndex >= currentPlaylist.blocks.length - 1) return;
    currentTBIndex++;
    playCurrentTB();
}

function prevTB() {
    if (!currentPlaylist || currentTBIndex <= 0) return;
    currentTBIndex--;
    playCurrentTB();
}

function playAudio() {
    document.getElementById("tb-audio").play();
}
