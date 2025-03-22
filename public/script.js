/*
  file: script.js
*/

document.addEventListener("DOMContentLoaded", () => {
  const queueEl = document.getElementById("playloop-queue");
  const playButton = document.getElementById("toggle-playback");
  let isPlaying = false;
  let playbackTask = null;

  function createPlayLoop(pair, repeatEN, repeatFR, index) {
    const div = document.createElement("div");
    div.className = "queue-item closed";
    div.dataset.en = pair.en;
    div.dataset.fr = pair.fr;
    div.dataset.repeatEn = repeatEN;
    div.dataset.repeatFr = repeatFR;
    div.dataset.index = index;
    div.innerHTML = `
      <span>🇬🇧 ${pair.en} · x${repeatEN}</span>
    `;
    return div;
  }

  fetch("data/translations.json")
    .then(res => res.json())
    .then(translations => {
      const max = Math.min(translations.length, 50);
      for (let i = 0; i < max; i++) {
        const pair = translations[i];
        const repeatEN = Math.floor(Math.random() * 3) + 1;
        const repeatFR = Math.floor(Math.random() * 2) + 1;
        const loop = createPlayLoop(pair, repeatEN, repeatFR, i);
        queueEl.appendChild(loop);
      }

      playButton.addEventListener("click", () => {
        isPlaying = !isPlaying;
        playButton.textContent = isPlaying ? "⏸️" : "▶️";

        if (isPlaying && !playbackTask) {
          playbackTask = simulatePlayback().then(() => {
            isPlaying = false;
            playbackTask = null;
            playButton.textContent = "▶️";
          });
        }
      });

      // Make play button draggable
      let isDragging = false, offsetX = 0, offsetY = 0;

      playButton.addEventListener("mousedown", (e) => {
        isDragging = true;
        offsetX = e.clientX - playButton.getBoundingClientRect().left;
        offsetY = e.clientY - playButton.getBoundingClientRect().top;
        playButton.style.cursor = "grabbing";
      });

      document.addEventListener("mousemove", (e) => {
        if (!isDragging) return;
        playButton.style.position = "fixed";
        playButton.style.left = `${e.clientX - offsetX}px`;
        playButton.style.top = `${e.clientY - offsetY}px`;
        playButton.style.right = "auto";
        playButton.style.bottom = "auto";
      });

      document.addEventListener("mouseup", () => {
        isDragging = false;
        playButton.style.cursor = "grab";
      });
    });

    async function simulatePlayback() {
      const loops = document.querySelectorAll(".queue-item");
    
      for (const loop of loops) {
        if (!isPlaying) return;
    
        const en = loop.dataset.en;
        const fr = loop.dataset.fr;
        const repeatEN = parseInt(loop.dataset.repeatEn, 10);
        const repeatFR = parseInt(loop.dataset.repeatFr, 10);
    
        // Close others
        loops.forEach(l => l.className = "queue-item closed");
    
        // Open current loop
        loop.className = "queue-item open";
        loop.innerHTML = `
          <div>
            🇬🇧 ${en} · <span class="repeat-counter" data-type="en">0/${repeatEN}</span>
          </div>
          <div class="playback-line">
            <div class="progress-bar">
              <div class="progress" style="width: 0%;"></div>
            </div>
          </div>
    
          <div>
            🇫🇷 ${fr} · <span class="repeat-counter" data-type="fr">0/${repeatFR}</span>
          </div>
          <div class="playback-line">
            <div class="progress-bar">
              <div class="progress" style="width: 0%;"></div>
            </div>
          </div>
        `;
    
        const [progressEN, progressFR] = loop.querySelectorAll(".progress");
        const [counterEN, counterFR] = loop.querySelectorAll(".repeat-counter");
    
        // 🇬🇧 English playback
        for (let i = 0; i < repeatEN; i++) {
          if (!isPlaying) return;
    
          const duration = getRandomInt(3000, 5000); // 3-5 seconds
          progressEN.style.transition = "none";
          progressEN.style.width = "0%";
          void progressEN.offsetWidth;
    
          counterEN.textContent = `${i + 1}/${repeatEN}`;
          progressEN.style.transition = `width ${duration}ms linear`;
          progressEN.style.width = "100%";
    
          await delay(duration);
        }
    
        // 🇫🇷 French playback
        for (let i = 0; i < repeatFR; i++) {
          if (!isPlaying) return;
    
          const duration = getRandomInt(3000, 5000);
          progressFR.style.transition = "none";
          progressFR.style.width = "0%";
          void progressFR.offsetWidth;
    
          counterFR.textContent = `${i + 1}/${repeatFR}`;
          progressFR.style.transition = `width ${duration}ms linear`;
          progressFR.style.width = "100%";
    
          await delay(duration);
        }
    
        loop.style.opacity = "0.3";
      }
    }
    
    
    

  function delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }


});


function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}
