/*
  file: script.js
*/

document.addEventListener("DOMContentLoaded", () => {
    const queueEl = document.getElementById("playloop-queue");
    const playButton = document.getElementById("toggle-playback");
    let isPlaying = false;
    let playbackTask = null;
  
    function createPlayLoop(sentence, repeat, index) {
      const div = document.createElement("div");
      div.className = "queue-item closed";
      div.dataset.en = sentence.en;
      div.dataset.fr = sentence.fr;
      div.dataset.repeat = repeat;
      div.dataset.index = index;
      div.innerHTML = `
        <span>🇬🇧 ${sentence.en} · x${repeat}</span>
      `;
      return div;
    }
  
    fetch("data/translations.json")
      .then((res) => res.json())
      .then((translations) => {
        const max = Math.min(translations.length, 50);
        for (let i = 0; i < max; i++) {
          const pair = translations[i];
          const repeat = Math.floor(Math.random() * 3) + 1;
          const loop = createPlayLoop(pair, repeat, i);
          queueEl.appendChild(loop);
        }
  
        if (playButton) {
          playButton.addEventListener("click", () => {
            isPlaying = !isPlaying;
            playButton.textContent = isPlaying ? "⏸️" : "▶️";
  
            if (isPlaying && !playbackTask) {
              playbackTask = simulatePlayback().then(() => {
                playbackTask = null;
                isPlaying = false;
                playButton.textContent = "▶️";
              });
            }
          });
  
          // Make the floating button draggable
          let isDragging = false;
          let offsetX = 0;
          let offsetY = 0;
  
          playButton.addEventListener("mousedown", (e) => {
            isDragging = true;
            offsetX = e.clientX - playButton.getBoundingClientRect().left;
            offsetY = e.clientY - playButton.getBoundingClientRect().top;
            playButton.style.cursor = "grabbing";
          });
  
          document.addEventListener("mousemove", (e) => {
            if (!isDragging) return;
            playButton.style.left = `${e.clientX - offsetX}px`;
            playButton.style.top = `${e.clientY - offsetY}px`;
            playButton.style.right = "auto";
            playButton.style.bottom = "auto";
            playButton.style.position = "fixed";
          });
  
          document.addEventListener("mouseup", () => {
            isDragging = false;
            playButton.style.cursor = "grab";
          });
        }
      });
  
    async function simulatePlayback() {
      const loops = document.querySelectorAll(".queue-item");
  
      for (const loop of loops) {
        if (!isPlaying) return;
  
        const repeat = parseInt(loop.dataset.repeat, 10);
        const en = loop.dataset.en;
        const fr = loop.dataset.fr;
  
        // Mark all others as closed
        loops.forEach(l => l.className = "queue-item closed");
  
        // Open this one
        loop.className = "queue-item open";
        loop.innerHTML = `
          <div class="translation">🇬🇧 ${en}</div>
          <div class="translation">🇫🇷 ${fr}</div>
          <div class="repeat">x${repeat}</div>
          <div class="progress-bar"><div class="progress"></div></div>
        `;
  
        const progress = loop.querySelector(".progress");
        for (let i = 0; i < repeat; i++) {
          if (!isPlaying) return;
          progress.style.width = `${((i + 1) / repeat) * 100}%`;
          await new Promise((r) => setTimeout(r, 600));
        }
  
        loop.style.opacity = "0";
        await new Promise((r) => setTimeout(r, 300));
        loop.remove();
      }
    }
  });
  