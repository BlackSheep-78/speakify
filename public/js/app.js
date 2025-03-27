/*
  file: /speakify/public/js/app.js
  description: Main application script for Speakify.
  - Ensures a valid session exists on every page load.
  - Handles playback logic if on a playback-enabled page.
*/

const App = {
  token: null,
  queueEl: null,
  playButton: null,
  isPlaying: false,
  playbackTask: null,
  data: [],
  playbackSchema: [
    { lang: "EN", repeat: 3 },
    { lang: "FR", repeat: 2 },
    { lang: "PT", repeat: 1 }
  ],

  async init() {
    await this.ensureToken();

    this.queueEl = document.getElementById("playloop-queue");
    this.playButton = document.getElementById("toggle-playback");

    if (!this.queueEl || !this.playButton) {
      console.info("PlaybackManager: Not a playback page. Skipping playback logic.");
      return;
    }

    this.setupEvents();
    await this.fetchData();
  },

  async ensureToken() {
    let token = localStorage.getItem('speakify_token');

    const isValid = async (token) => {
      const check = await fetch(`/speakify/public/api/index.php?action=validate_session&token=${token}`);
      const result = await check.json();
      return !result.error;
    };

    if (token && await isValid(token)) {
      console.log("✅ Valid session reused:", token);
    } else {
      console.warn("⚠️ No valid session, creating new...");
      const res = await fetch('/speakify/public/api/index.php?action=create_session');
      const data = await res.json();
      token = data.token;
      localStorage.setItem('speakify_token', token);
      console.log("✅ New anonymous session created:", token);
    }

    this.token = token;
  },

  setupEvents() {
    this.playButton.addEventListener("click", () => {
      this.isPlaying = !this.isPlaying;
      this.playButton.textContent = this.isPlaying ? "⏸️" : "▶️";

      if (this.isPlaying && !this.playbackTask) {
        this.playbackTask = this.simulatePlayback().then(() => {
          this.isPlaying = false;
          this.playbackTask = null;
          this.playButton.textContent = "▶️";
        });
      }
    });

    let isDragging = false, offsetX = 0, offsetY = 0;

    this.playButton.addEventListener("mousedown", (e) => {
      isDragging = true;
      offsetX = e.clientX - this.playButton.getBoundingClientRect().left;
      offsetY = e.clientY - this.playButton.getBoundingClientRect().top;
      this.playButton.style.cursor = "grabbing";
    });

    document.addEventListener("mousemove", (e) => {
      if (!isDragging) return;
      this.playButton.style.position = "fixed";
      this.playButton.style.left = `${e.clientX - offsetX}px`;
      this.playButton.style.top = `${e.clientY - offsetY}px`;
      this.playButton.style.right = "auto";
      this.playButton.style.bottom = "auto";
    });

    document.addEventListener("mouseup", () => {
      isDragging = false;
      this.playButton.style.cursor = "grab";
    });
  },

  async fetchData() {
    const url = `/speakify/public/api/index.php?action=get_sentences&lang_id=39&token=${this.token}`;

    const res = await fetch(url);
    const data = await res.json();

    if (data.error) {
      console.error("API Error:", data.error);
      this.queueEl.innerHTML = `<div class="error">⚠️ ${data.error}</div>`;
      return;
    }

    this.data = data;
    const max = Math.min(data.length, 50);
    for (let i = 0; i < max; i++) {
      const pairData = data[i];
      const en = pairData.original.sentence.text;
      const fr = Object.values(pairData.translation).find(t => t.language.name === "French")?.sentence.text;
      const pt = Object.values(pairData.translation).find(t => t.language.name === "Portuguese")?.sentence.text;
      if (!fr || !pt) continue;
      const loop = this.createPlayLoop({ en, fr, pt }, i);
      this.queueEl.appendChild(loop);
    }
  },

  createPlayLoop(pair, index) {
    const div = document.createElement("div");
    div.className = "queue-item closed";
    div.dataset.en = pair.en;
    div.dataset.fr = pair.fr;
    div.dataset.pt = pair.pt;
    div.dataset.index = index;
    div.innerHTML = `<span>🇬🇧 ${pair.en} · EN→FR→PT</span>`;
    return div;
  },

  async simulatePlayback() {
    const loops = document.querySelectorAll(".queue-item");

    for (const loop of loops) {
      if (!this.isPlaying) return;

      const en = loop.dataset.en;
      const fr = loop.dataset.fr;
      const pt = loop.dataset.pt;

      loops.forEach(l => l.className = "queue-item closed");
      loop.className = "queue-item open";
      loop.innerHTML = `
        <div>🇬🇧 ${en} · <span class="repeat-counter" data-type="EN">0</span></div>
        <div class="playback-line"><div class="progress-bar"><div class="progress" data-type="EN" style="width: 0%;"></div></div></div>
        <div>🇫🇷 ${fr} · <span class="repeat-counter" data-type="FR">0</span></div>
        <div class="playback-line"><div class="progress-bar"><div class="progress" data-type="FR" style="width: 0%;"></div></div></div>
        <div>🇵🇹 ${pt} · <span class="repeat-counter" data-type="PT">0</span></div>
        <div class="playback-line"><div class="progress-bar"><div class="progress" data-type="PT" style="width: 0%;"></div></div></div>
      `;

      for (const step of this.playbackSchema) {
        for (let i = 0; i < step.repeat; i++) {
          if (!this.isPlaying) return;

          const progress = loop.querySelector(`.progress[data-type="${step.lang}"]`);
          const counter = loop.querySelector(`.repeat-counter[data-type="${step.lang}"]`);

          progress.style.transition = "none";
          progress.style.width = "0%";
          void progress.offsetWidth;

          counter.textContent = `${i + 1}`;
          progress.style.transition = `width 3000ms linear`;
          progress.style.width = "100%";

          await this.delay(3000);
        }
      }

      loop.style.opacity = "0.3";
    }
  },

  delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }
};

document.addEventListener("DOMContentLoaded", () => App.init());

function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}
