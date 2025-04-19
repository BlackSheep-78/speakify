/**
 * =============================================================================
 * 📁 File: speakify/public/js/app.js
 * 📦 Project: Speakify
 * 📌 Description: Main frontend controller for session, auth, and app state.
 * =============================================================================
 * ⚠️ DO NOT REMOVE OR MODIFY THIS HEADER
 * This file defines core client-side behavior for Speakify. All logic within
 * must respect the session handling contract defined in SessionManager.php.
 * Changes to the rules must be reviewed and documented in project.md.
 * =============================================================================
 *
 * 🔒 Session Rules:
 * - All views must call `app.ensureToken()` during init.
 * - Token is stored in `localStorage` under `speakify_token`.
 * - If no token is found or it is invalid, `create_session` is called.
 * - Once validated, the result is stored in `app.state.validatedTokenData`.
 * - UI updates must rely only on `validatedTokenData`.
 * - `updateUI()` must never call the backend directly.
 *
 * 🧠 Token Logic:
 * - `ensureToken()` ensures a valid token is loaded and stored.
 * - `validateToken()` makes the backend request.
 * - `createNewSession()` starts an anonymous session.
 *
 * 👤 Auth Flow:
 * - `loginFormHandler()` handles user login via `action=login`.
 * - On success, it stores the returned token and redirects.
 * - `registerFormHandler()` handles user registration.
 *
 * 🧩 Page Init:
 * - `app.init()` is triggered on DOMContentLoaded.
 * - It chains `ensureToken()`, `updateUI()`, and `setupPageElements()`.
 * - `setupPageElements()` hooks login/register forms (if found).
 *
 * 📜 See also:
 * - backend/classes/SessionManager.php
 * - backend/actions/login.php
 * - docs/project.md
 */

const app = 
{
  state: 
  {
    validated: false,
    validatedTokenData: null,
    isPlaying: false,         // 🔒 Explicitly off by default
    currentIndex: 0,
    playedItems: [],
    playbackQueue: [],
    schema: [
      { lang: "en", repeat: 1 },
      { lang: "fr", repeat: 2 },
      { lang: "pt", repeat: 1 }
    ],
    mainLang: "en"
  },

  viewHandlers: 
  {
    "dashboard": "initDashboard",
    "playback": "initPlayback",
    "playlist-library": "initPlaylistLibrary",
    "settings": "initSettings",
    "smart-lists": "initSmartLists",
    "admin": "initAdmin"
  },


  token: null,
  authChecked: false,

  async init() 
  {
    console.log("👋 app.init() running");

    this.config = { base_url: document.querySelector("base")?.getAttribute("href") || "/" };

    const configData = await this.api("api/index.php?action=get_config");
    if (!configData?.success) {
      console.error("❌ Failed to load config from API:", configData?.error || configData);
      return;
    }
    Object.assign(this.config, configData);
    console.log("✅ Final config:", this.config);

    const view = this.getCurrentView();

    await this.ensureToken();

    // ✅ Universal UI updates (if needed across all views)
    if (["login-profile", "register", "dashboard", "playback"].includes(view)) 
    {
      await this.updateUI();
      await this.setupPageElements();
    }

    // 🔍 Get the name of the handler function for the current view
    const handler = this.viewHandlers?.[view];
    // ✅ If a handler exists and it's defined as a function, call it
    if (handler && typeof this[handler] === "function") 
    {
      await this[handler]();
    }

  },

  initPlayback() 
  {
    console.log("🎧 initPlayback() called");
  
    const active = document.getElementById("active-sentence");
    const queue = document.getElementById("playloop-queue");
    const played = document.getElementById("played-items");
  
    if (!active || !queue || !played) {
      console.warn("⚠️ One or more playback sections are missing.");
      return;
    }
  
    const langId = this.getLangId(this.state.mainLang);
    app.api(`api/index.php?action=get_sentences&lang_id=${langId}&token=${this.token}`)
    .then(data => {
      if (!data.items || !Array.isArray(data.items) || data.items.length === 0) {
        queue.innerHTML = "<p>⚠️ Aucune donnée à lire.</p>";
        return;
      }
  
      console.log("📦 Loaded sentence items:", data.items);
  
      // ⤵️ keep the rest unchanged
      const template = data.template;
      const assembledItems = data.items.map((item, index) => {
        const group = {};
        template.group.forEach((key, i) => {
          group[key] = item.group?.[i] ?? null;
        });
  
        const translations = item.translations.map((row, tIndex) => {
          const t = {};
          template.translation.forEach((key, i) => {
            t[key] = row?.[i] ?? null;
          });
          return t;
        });
  
        return { ...group, translations };
      });
  
      app.state.playbackQueue = assembledItems;
      app.state.playedItems = [];
      app.state.currentIndex = 0;
      app.state.isPlaying = false;
  
      app.renderPlaybackUI();
      app.startPlaybackLoop();
    })
    .catch(err => {
      console.error("❌ Erreur lors du chargement de la lecture:", err);
      queue.innerHTML = "<p>Erreur lors du chargement.</p>";
    });
  

  },

  async initPlaylistLibrary() 
  {
    console.log("📚 initPlaylistLibrary() called");
  
    const container = document.getElementById("playlist-list");
    if (!container) {
      console.warn("⚠️ No #playlist-list container found.");
      return;
    }
  
    try {
      const data = await app.api(`api/index.php?action=get_playlists&token=${this.token}`);

      console.log(data);
  
      if (!data.success || !Array.isArray(data.playlists)) {
        container.innerHTML = `<p>❌ Erreur lors du chargement des playlists.</p>`;
        return;
      }
  
      // ✅ Render each playlist as a card
      container.innerHTML = data.playlists.map(pl => `
        <div class="card playlist-card">
          <h3>${pl.name}</h3>
          <p>${pl.description || "Sans description."}</p>
          <button onclick="location.href='playback.html?playlist_id=${pl.id}'">▶️ Lire</button>
          <button onclick="location.href='playlist-editor.html?id=${pl.id}'">✏️ Modifier</button>
        </div>
      `).join('');
  
    } catch (err) {
      console.error("❌ Failed to load playlists:", err);
      container.innerHTML = `<p>❌ Erreur réseau.</p>`;
    }
  },
  

  getCurrentView() {
    const path = window.location.pathname;
    return path.split('/').pop().replace('.php', '').replace('.html', '');
  },

  async ensureToken() {
    let token = localStorage.getItem('speakify_token');
    console.log("🧠 Step 1: Token from localStorage:", token);
  
    let result = null;
  
    if (token) {
      result = await this.validateToken(token);
    }
  
    if (!result || !result.success || !result.token) {
      console.warn("⚠️ No valid token. Creating new anonymous session...");
      token = await this.createNewSession();
      result = await this.validateToken(token);
    }
  
    // 🧠 Ensure latest token is always stored and used
    if (result?.token && result.token !== token) {
      localStorage.setItem('speakify_token', result.token);
      token = result.token;
      console.log("🔁 Updated token from backend:", token);
    }
  
    this.token = token;
    this.state.validatedTokenData = result;
  
    console.log("🆗 Final session token in app state:", this.token);
  },


  async validateToken(token) {
    try {
      const res = await fetch(`api/index.php?action=validate_session&token=${token}`);
      const data = await res.json();
      console.log("🔍 Token validation result:", data);

      if (data.success) {
        this.state.validatedTokenData = data;
      }

      return data;
    } catch (err) {
      console.error("❌ Token validation error:", err);
      return { success: false, error: err.message };
    }
  },

  async createNewSession() {
    try {
      const res = await fetch('api/index.php?action=create_session');
      const data = await res.json();

      if (data.success && data.token) {
        localStorage.setItem('speakify_token', data.token);
        console.log("✅ New token stored in localStorage:", data.token);
        return data.token;
      } else {
        console.error("❌ Failed to create session");
        return null;
      }
    } catch (error) {
      console.error("❌ Session creation failed:", error);
      return null;
    }
  },

  async updateUI() {
    const currentView = window.location.pathname.split('/').pop();
    console.log("🔍 Current View:", currentView);

    const loginSection = document.getElementById('login-section');
    const profileSection = document.getElementById('profile-section');
    const profileName = document.getElementById('profile-name');
    const profileEmail = document.getElementById('profile-email');
    const profileLastLogin = document.getElementById('profile-last-login');
    const headerUserLink = document.querySelector('.header a[href="login-profile"]');
    const logoutButton = document.getElementById('logout-button');

    if (!loginSection || !profileSection) return;

    loginSection.hidden = false;
    profileSection.hidden = true;

    if (headerUserLink) headerUserLink.textContent = "👤 Connexion";

    let token = localStorage.getItem('speakify_token');
    if (!token) return;

    const result = this.state.validatedTokenData;
    if (!result || !result.success) return;

    if (!result.success) return;

    if (result.token && result.token !== token) {
      localStorage.setItem('speakify_token', result.token);
      token = result.token;
    }

    if (result.logged_in) {
      if (loginSection) loginSection.hidden = true;
      if (profileSection) profileSection.hidden = false;

      if (result.name) {
        profileName.textContent = result.name;
        headerUserLink.textContent = `👤 ${result.name}`;
      } else {
        profileName.textContent = "Guest";
        headerUserLink.textContent = "👤 Guest";
      }

      profileEmail.textContent = result.email || "No email provided";
      profileLastLogin.textContent = this.formatLastLogin(result.last_login || "");
    }

    if (logoutButton) 
    {
      logoutButton.onclick = async () => 
      {
        const token = localStorage.getItem("speakify_token");
    
        // ✅ Call backend to remove user_id, keep session token alive
        if (token) 
        {
          await app.api(`api/index.php?action=logout&token=${token}`);
        }
    
        // ✅ Keep token, but clear user identity info
        localStorage.removeItem('user_name');
        localStorage.removeItem('user_email');
        localStorage.removeItem('user_id');
    
        // 🔁 Refresh UI with anonymous session
        location.reload();
      };
    }
  },

  formatLastLogin(lastLoginTimestamp) {
    const date = new Date(lastLoginTimestamp);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);

    if (diffInSeconds < 60) return "Just now";

    const diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;

    const diffInDays = Math.floor(diffInHours / 24);
    return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
  },

  async setupPageElements() 
  {
    this.registerFormHandler();
    this.loginFormHandler();
  },

  loginFormHandler() 
  {
    const form = document.getElementById("login-form");
    const message = document.getElementById("login-message");

    if (!form) return;

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      message.textContent = "⏳ Connexion en cours...";

      const payload = {
        email: form.email.value.trim(),
        password: form.password.value
      };

      try {
        const token = localStorage.getItem("speakify_token") || "";

        const result = await app.api(`api/index.php?action=login&token=${token}`, {
          method: "POST",
          body: JSON.stringify(payload),
          headers: { "Content-Type": "application/json" }
        });
      
        if (result.success) {
          localStorage.setItem("speakify_token", result.token);
          message.textContent = "✅ Connexion réussie ! Redirection...";
          setTimeout(() => window.location.href = "dashboard", 1500);
        } else {
          message.textContent = `❌ ${result.error || "Erreur inconnue."}`;
        }
      } catch (err) {
        message.textContent = "❌ Erreur réseau.";
        console.error("Login failed:", err);
      }
      
    });
  }
};

app.registerFormHandler = function () 
{
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
      const result = await app.api("api/index.php?action=register_user", {
        method: "POST",
        body: JSON.stringify(payload),
        headers: { "Content-Type": "application/json" }
      });

      if (result.success) {
        message.textContent = "✅ Compte créé avec succès ! Redirection...";
        setTimeout(() => window.location.href = "login-profile", 1500);
      } else {
        message.textContent = `❌ ${result.error || "Erreur inconnue."}`;
      }
    } catch (err) {
      message.textContent = "❌ Erreur réseau.";
      console.error("Registration failed:", err);
    }
  });
};

app.delay = function(ms) 
{
  return new Promise(resolve => setTimeout(resolve, ms));
}

app.renderPlaybackUI = function() 
{
  const active = document.getElementById("active-sentence");
  const queue = document.getElementById("playloop-queue");
  const played = document.getElementById("played-items");

  const items = app.state.playbackQueue || [];
  const history = app.state.playedItems || [];

  // Played items
  played.innerHTML = history.map(pair => `
    <div class="sentence-pair faded">
      <span class="original">${pair.original_sentence}</span>
      <span class="arrow">→</span>
      <span class="translated">${pair.translated_sentence}</span>
    </div>
  `).join('');

  // Active sentence
  const current = items[app.state.currentIndex];
  active.innerHTML = current ? `
    <div class="sentence-pair highlight">
      <span class="original">${current.original_sentence}</span>
      <span class="arrow">→</span>
      <span class="translated">${current.translated_sentence}</span>
    </div>
  ` : `<p>Fin de la lecture</p>`;

  // Remaining queue
  queue.innerHTML = items.slice(app.state.currentIndex + 1).map(pair => `
    <div class="sentence-pair">
      <span class="original">${pair.original_sentence}</span>
      <span class="arrow">→</span>
      <span class="translated">${pair.translated_sentence}</span>
    </div>
  `).join('');
};

app.state.mainLang = 'en'; // used for collapsed display

app.getLangId = function (code) {
  const map = { en: 39, fr: 45, pt: 75 }; // extend this
  return map[code] || null;
};

app.state.schema = [
  { lang: 'en', repeat: 1 },
  { lang: 'fr', repeat: 2 },
  { lang: 'pt', repeat: 1 }
];

app.startPlaybackLoop = async function () {
  const queue = app.state.playbackQueue;
  const schema = app.state.schema;
  const btn = document.getElementById("toggle-playback");

  if (!queue || !schema || !btn) return;

  let wasPaused = false;

  // UI feedback: show pause icon
  btn.classList.add("playing");
  btn.textContent = "⏸️";

  for (; app.state.currentIndex < queue.length; app.state.currentIndex++) {
    const entry = queue[app.state.currentIndex];

    app.renderPlaybackQueue();

    // 🔃 Scroll to active
    const active = document.querySelector(".sentence-group.active");
    if (active) active.scrollIntoView({ behavior: "smooth", block: "center" });

    for (const s of schema) {
      const text = (s.lang === app.state.mainLang)
        ? entry.orig_txt
        : entry.translations.find(t => t.trans_lang_id === app.getLangId(s.lang))?.trans_txt;

      if (!text) continue;

      for (let i = 0; i < s.repeat; i++) {
        // ⏸️ Wait if playback is paused
        while (!app.state.isPlaying) {
          if (!wasPaused) {
            btn.textContent = "▶️";
            wasPaused = true;
          }
          await app.delay(200);
        }

        if (wasPaused) {
          btn.textContent = "⏸️";
          wasPaused = false;
        }

        console.log(`▶️ Playing ${s.lang.toUpperCase()} [${i + 1}/${s.repeat}]: ${text}`);
        await app.delay(1500); // Simulated audio
      }
    }

    await app.delay(300);
  }

  app.state.isPlaying = false;
  btn.classList.remove("playing");
  btn.textContent = "▶️";
};


app.renderPlaybackQueue = function () {
  const container = document.getElementById("playloop-queue");
  if (!container) return;

  container.innerHTML = ""; // Clear all previous

  app.state.playbackQueue.forEach((entry, index) => {
    const isActive = (index === app.state.currentIndex);
    const groupEl = document.createElement("div");
    groupEl.className = "sentence-group";
    if (isActive) groupEl.classList.add("active");

    // 🔹 Collapsed display for non-active items
    if (!isActive) {
      groupEl.innerHTML = `<div class="collapsed">- ${entry.orig_lang} ${entry.orig_txt}</div>`;
      container.appendChild(groupEl);
      return;
    }

    // 🔸 Expanded display for active block
    const originalLine = `<div class="original">🗣 ${entry.orig_txt}</div>`;
    groupEl.innerHTML += originalLine;

    app.state.schema.forEach(s => {
      const isOrig = (s.lang === app.state.mainLang);

      // Find translation object for this schema lang
      const trans = isOrig
        ? { trans_txt: entry.orig_txt, trans_lang: entry.orig_lang }
        : entry.translations.find(t => t.trans_lang_id === app.getLangId(s.lang));

      if (!trans) return;

      const transLine = `<div class="translation">🌍 ${trans.trans_txt}</div>`;
      const progressLine = `<div class="progress-info">🎧 ${s.lang.toUpperCase()} ×${s.repeat}</div>`;
      groupEl.innerHTML += transLine + progressLine;
    });

    container.appendChild(groupEl);
  });
};

app.renderPlaybackUI = function () {
  const queue = document.getElementById("playloop-queue");
  if (!queue || !Array.isArray(app.state.playbackQueue)) return;

  queue.innerHTML = "";

  app.state.playbackQueue.forEach(entry => {
    const groupDiv = document.createElement("div");
    groupDiv.className = "sentence-group";

    groupDiv.innerHTML = `
      <div class="original"><strong>🗣 ${entry.orig_lang}:</strong> ${entry.orig_txt}</div>
    `;

    const transList = document.createElement("ul");
    transList.className = "translations";

    entry.translations.forEach(trans => {
      const li = document.createElement("li");
      li.innerHTML = `<strong>🌍 ${trans.trans_lang}:</strong> ${trans.trans_txt}`;
      transList.appendChild(li);
    });

    groupDiv.appendChild(transList);
    queue.appendChild(groupDiv);
  });
};

app.config = {};
app.api = function(endpoint, options = {}) {
  return (async function () {
    // Load config if needed
    if (!app.config || !app.config.base_url) {
      try {
        const res = await fetch("/api/index.php?action=get_config");
        const data = await res.json();
        if (data.success) {
          app.config = data;
          console.log("✅ Config loaded in app.api()", app.config);
        } else {
          throw new Error("❌ Failed to load config.");
        }
      } catch (e) {
        console.error("❌ Failed to load initial config:", e);
        return { success: false, error: "Network error during config fetch" };
      }
    }

    const base = app.config.base_url || "";
    const safeBase = base.replace(/\/+$/, "");
    const safeEndpoint = endpoint.replace(/^\/+/, "");
    const url = `${window.location.origin}${safeBase}/${safeEndpoint}`;

    const defaultHeaders = {
      "Content-Type": "application/json"
    };

    try {
      const response = await fetch(url, {
        method: "GET",
        headers: defaultHeaders,
        ...options
      });

      if (!response.ok) {
        const errorText = await response.text();
        return {
          success: false,
          status: response.status,
          error: errorText || "Server returned an error"
        };
      }

      return await response.json();
    } catch (err) {
      console.error("❌ API request failed:", err);
      return {
        success: false,
        error: "Network or server unreachable",
        details: err.message
      };
    }
  })();
};

app.initAdmin = function() {
    // 🔧 Génération de la structure
    const btnStructure = document.getElementById('btn-generate-structure');
    const outputStructure = document.getElementById('output-generate');
  
    if (btnStructure && outputStructure) {
      btnStructure.addEventListener('click', async () => {
        outputStructure.textContent = '⏳ Exécution du script...';
  
        try {
          const res = await app.api(`api/index.php?action=admin_tool&tool=generate_file_structure&token=${app.token}`);
          if (res.success) {
            outputStructure.textContent = `✅ Succès :\n${res.output}`;
          } else {
            outputStructure.textContent = `❌ Échec :\n${res.output || res.error}`;
          }
        } catch (err) {
          outputStructure.textContent = '❌ Erreur réseau ou interne.';
          console.error(err);
        }
      });
    }
  
    // 🔊 TTS test
    const btnTTS = document.getElementById('btn-tts-trigger');
    const outputTTS = document.getElementById('output-tts');
  
    if (btnTTS && outputTTS) {
      btnTTS.addEventListener('click', async () => {
        outputTTS.textContent = "⏳ Requête en cours...";
  
        try {
          const result = await app.api(`api/index.php?action=tts_generate&admin_key=${app.token}`);
          if (result.success) {
            outputTTS.textContent = `✅ Audio généré : ${result.file}`;
          } else {
            outputTTS.textContent = `❌ ${result.error || "Erreur inconnue"}`;
          }
        } catch (err) {
          outputTTS.textContent = "❌ Erreur réseau ou interne.";
          console.error(err);
        }
      });
    }
  };
  




document.addEventListener("DOMContentLoaded", () => {
  app.init();
});


console.log("WE NEED TO SORT THIS BUTTON EVENTUALLY");

const playBtn = document.getElementById("toggle-playback");
if (playBtn) {
  playBtn.addEventListener("click", () => {
    app.state.isPlaying = !app.state.isPlaying;

    if (app.state.isPlaying) {
      console.log("▶️ Start or Resume");
      playBtn.classList.add("playing");

      if (!app._loopRunning) {
        app.state.currentIndex = 0;
        app.state.playedItems = [];
        app.startPlaybackLoop();
      }
    } else {
      console.log("⏸️ Paused");
      playBtn.classList.remove("playing");
    }
  });
}

