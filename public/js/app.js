/*
  ============================================================================
  📌 IMPORTANT: DO NOT REMOVE OR MODIFY THIS HEADER
  ============================================================================
  This header defines the expected behavior of the Speakify frontend session
  and playback logic. These rules must be respected across all pages using
  app.js to avoid invalid session states, inconsistent UI, or unexpected bugs.
  ============================================================================

  ============================================================================
  app.js – Speakify Frontend Session & Playback Manager
  ============================================================================

  🎯 Purpose:
    Provides a consistent mechanism to initialize anonymous sessions,
    validate and store session tokens, and manage playback behavior.

  ✅ Session Behavior Rules:
  1. `app.ensureToken()` must always run, even on non-playback pages.
  2. Session token must be stored and reused via `localStorage.speakify_token`.
  3. Token validation should occur via backend before reuse.
  4. If no valid session exists, a new one must be created via API call.
  5. Token must be attached to all backend API requests.

  ✅ Playback Behavior Rules:
  1. Playback is only initialized if `playloop-queue` and `toggle-playback` exist.
  2. Playback runs through translation blocks using a defined schema.
  3. Visual progress bars and repeat counters update in real-time.
  4. Button is draggable and maintains control state across playback.

  ============================================================================
  File: speakify/public/js/app.js
  Description: Frontend session & playback controller for Speakify.
  ============================================================================
*/

console.log("✅ app.js loaded");

const app = {
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
    console.log("👋 app.init() running");
    await this.ensureToken();

    this.queueEl = document.getElementById("playloop-queue");
    this.playButton = document.getElementById("toggle-playback");

    if (!this.queueEl || !this.playButton) {
      console.info("PlaybackManager: Not a playback page. Skipping playback logic.");
      return;
    }

    this.playButton.innerHTML = `<svg viewBox='0 0 24 24'><path d='M8 5v14l11-7z'/></svg>`;
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
      this.playButton.classList.toggle("playing", this.isPlaying);

      const icon = this.playButton.querySelector("svg");
      icon.innerHTML = this.isPlaying
        ? `<path d='M6 19h4V5H6v14zm8-14v14h4V5h-4z'/>`
        : `<path d='M8 5v14l11-7z'/>`;

      if (this.isPlaying && !this.playbackTask) {
        this.playbackTask = this.simulatePlayback().then(() => {
          this.isPlaying = false;
          this.playbackTask = null;
          this.playButton.classList.remove("playing");
          icon.innerHTML = `<path d='M8 5v14l11-7z'/>`;
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

    const rawPairs = data.pairs;
    const grouped = {};

    rawPairs.forEach(pair => {
      const en = pair.original_sentence.trim();
      const lang = pair.translated_language;
      const translated = pair.translated_sentence.trim();

      if (!grouped[en]) grouped[en] = {};
      grouped[en][lang] = translated;
    });

    let index = 0;
    for (const [en, translations] of Object.entries(grouped)) {
      const fr = translations["French"];
      const pt = translations["Portuguese"];
      if (!fr || !pt) continue;

      const loop = this.createPlayLoop({ en, fr, pt }, index++);
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

document.addEventListener("DOMContentLoaded", () => {
  app.init();
  registerFormHandler();
});

function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

function registerFormHandler() {
  const form = document.getElementById("register-form");
  const message = document.getElementById("register-message");

  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    message.textContent = "⏳ Enregistrement en cours...";

    const payload = {
      email: form.email.value.trim(),
      password: form.password.value,
      name: form.name.value.trim(),
    };

    try {
      const res = await fetch("/speakify/public/api/index.php?action=register_user", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const result = await res.json();

      if (result.success) {
        message.textContent = "✅ Compte créé avec succès ! Redirection...";
        setTimeout(() => window.location.href = "login-profile.html", 1500);
      } else {
        message.textContent = `❌ ${result.error || "Erreur inconnue."}`;
      }
    } catch (err) {
      message.textContent = "❌ Erreur réseau.";
      console.error("Registration failed:", err);
    }
  });
}
