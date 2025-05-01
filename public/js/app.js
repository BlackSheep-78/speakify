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
    if (typeof handler === "string" && typeof this[handler] === "function") {
      await this[handler](); // legacy string-based
    } else if (typeof handler === "object" && typeof handler.init === "function") {
      handler.init(); // new object-based view module
    }

    if (localStorage.getItem("test_runner_state")) 
    {
      console.log("🧪 Resuming test sequence from saved state...");
      await this.test(); // continue where it left off
    }

  },

  async initPlaylistLibrary() 
  {
    console.log("📚 initPlaylistLibrary() called");
  
    const container = document.getElementById("playlist-list");
    if (!container) 
    {
      console.warn("⚠️ No #playlist-list container found.");
      return;
    }
  
    try 
    {
      const data = await app.api(`api/index.php?action=get_playlists`);

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

    let token = app.token;
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
        const token = app.token;
    
        // ✅ Call backend to remove user_id, keep session token alive
        if (token) 
        {
          await app.api(`api/index.php?action=logout`);
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

        const result = await app.api(`api/index.php?action=login`, {
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




app.playback = 
{
  queue: 
  {
    render() 
    {
      const container = document.getElementById("playloop-queue");
      if (!container) return;

      container.innerHTML = "";

      app.state.playbackQueue.forEach((entry, index) => 
      {
        const isActive = (index === app.state.currentIndex);
        const groupEl = isActive
          ? this.renderActive(entry, index)
          : this.renderWaiting(entry, index);
        container.appendChild(groupEl);
      });
    },

    renderActive(entry, index) 
    {
      const groupEl = document.createElement("div");
      groupEl.className = "sentence-group active-block";
      groupEl.dataset.index = index;

      const originalLine = `<div class="original">🗣 ${entry.orig_txt}</div>`;
      groupEl.innerHTML += originalLine;

      app.state.schema.forEach(s => {
        const isOrig = (s.lang === app.state.mainLang);
        const trans = isOrig
          ? { trans_txt: entry.orig_txt, trans_lang: entry.orig_lang }
          : entry.translations.find(t => t.trans_lang_id === app.getLangId(s.lang));

        if (!trans) return;

        const transLine = `<div class="translation">🌍 ${trans.trans_txt}</div>`;
        const progressLine = `<div class="progress-info">🎧 ${s.lang.toUpperCase()} ×${s.repeat}</div>`;
        groupEl.innerHTML += transLine + progressLine;
      });

      return groupEl;
    },

    renderWaiting(entry, index) {
      const groupEl = document.createElement("div");
      groupEl.className = "sentence-group collapsed";
      groupEl.dataset.index = index;

      const bubble = document.createElement("div");
      bubble.className = "sentence-bubble";
      bubble.innerHTML = `<span class="lang-badge">${entry.orig_lang.slice(0,2).toUpperCase()}</span> ${entry.orig_txt}`;
      groupEl.appendChild(bubble);

      return groupEl;
    },

    startBlock(index) 
    {
      const el = document.querySelector(`.sentence-group[data-index='${index}']`);
      if (el) {
        el.classList.add("active-block");
        el.classList.remove("collapsed");
      }
    },

    endBlock(index) 
    {
      const el = document.querySelector(`.sentence-group[data-index='${index}']`);
      if (el) {
        el.classList.remove("active-block");
        el.classList.add("collapsed");
      }
    },

    refreshBlock(index, isActive = false) {
      const container = document.getElementById("playloop-queue");
      if (!container) return;
    
      const oldEl = container.querySelector(`.sentence-group[data-index='${index}']`);
      if (oldEl) container.removeChild(oldEl);
    
      const entry = app.state.playbackQueue[index];
      const newEl = isActive
        ? this.renderActive(entry, index)
        : this.renderWaiting(entry, index);
    
      const nextSibling = container.querySelector(`.sentence-group[data-index='${index + 1}']`);
      container.insertBefore(newEl, nextSibling || null);
    },

    renderCollapsedOnly() {
      const container = document.getElementById("playloop-queue");
      if (!container) return;
    
      container.innerHTML = "";
    
      app.state.playbackQueue.forEach((entry, index) => {
        const groupEl = this.renderWaiting(entry, index);
        container.appendChild(groupEl);
      });
    }
    
    
  },

  init: async function () 
  {
    console.log("🎧 Playback module initialized");
  
    const result = await this.fetchSentences();
    app.state.playbackQueue = result;
    app.state.currentIndex = 0;
    app.state.isPlaying = false;
    app.state.loopRunning = false; // NEW guard flag
  
    this.queue.renderCollapsedOnly();
  
    // 🎮 Setup play/pause button — only triggers loop here
    const playBtn = document.getElementById("toggle-playback");
    if (playBtn) 
    {
      playBtn.addEventListener("click", () => {
        app.state.isPlaying = !app.state.isPlaying;
  
        if (app.state.isPlaying) {
          console.log("▶️ Start or Resume");
          playBtn.classList.add("playing");
  
          if (!app.state.loopRunning) {
            app.state.playedItems = [];
            this.loop();
          }
        } else {
          console.log("⏸️ Paused");
          playBtn.classList.remove("playing");
        }
      });
    };

    if (localStorage.getItem('test_runner_state')) {
      console.log("🧪 Resuming test from persisted state...");
      await app.test();
    }

  },
   

  start() 
  {
    console.log("▶️ Playback started");
    this.loop();
  },

  loop: async function () {
    if (app.state.loopRunning) return;
    app.state.loopRunning = true;
  
    const queue = app.state.playbackQueue;
    const schema = app.state.schema;
    const btn = document.getElementById("toggle-playback");
  
    if (!queue || !schema || !btn) return;
  
    let wasPaused = false;
    btn.classList.add("playing");
    btn.textContent = "⏸️";
  
    for (; app.state.currentIndex < queue.length; app.state.currentIndex++) {
      const index = app.state.currentIndex;
  
      if (index > 0) this.queue.refreshBlock(index - 1, false);
      this.queue.refreshBlock(index, true);
  
      const active = document.querySelector(`.sentence-group[data-index="${index}"]`);
      if (active) active.scrollIntoView({ behavior: "smooth", block: "center" });
  
      const entry = queue[index];
  
      for (const s of schema) {
        const trans = (s.lang === app.state.mainLang)
          ? { trans_txt: entry.orig_txt, trans_lang: entry.orig_lang, audio_url: null }
          : entry.translations.find(t => t.trans_lang_id === app.getLangId(s.lang));
  
        const text = trans?.trans_txt;
        const audioPath = trans?.audio_url;
        const base = app.config.base_url?.replace(/\/+$/, '') || '';
        const audioUrl = audioPath ? `${base}${audioPath}` : null;
  
        if (!text) continue;
  
        for (let i = 0; i < s.repeat; i++) {
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
  
          try {
            if (!audioUrl) {
              console.warn("⛔️ No audio file for:", text);
              await app.delay(1500);
              continue;
            }
  
            const audio = new Audio(audioUrl);
  
            await new Promise(resolve => {
              audio.onended = resolve;
              audio.onerror = resolve;
              audio.play().catch(err => {
                console.warn("Audio playback failed:", err);
                resolve();
              });
            });
          } catch (err) {
            console.warn("Audio setup error:", err);
            await app.delay(1500);
          }
        }
      }
  
      await app.delay(300);
    }
  
    app.state.isPlaying = false;
    app.state.loopRunning = false;
    btn.classList.remove("playing");
    btn.textContent = "▶️";
  },
  
  
  
  
  
  
  stop() {
    console.log("⏹️ Playback stopped");
    app.state.isPlaying = false;
  },

  fetchSentences() 
  {
    const langId = app.getLangId(app.state.mainLang);
  
    return app.api(`api/index.php?action=get_sentences&lang_id=${langId}`)
      .then(data => 
      {
        if (!data.items || !Array.isArray(data.items) || data.items.length === 0) 
        {
          console.warn("⚠️ Aucune donnée à lire.");
          return [];
        }
  
        const template = data.template;
        return data.items.map((item, index) => {
          const group = {};
          template.group.forEach((key, i) => {
            group[key] = item.group?.[i] ?? null;
          });
  
          const translations = item.translations.map((row) => {
            const t = {};
            template.translation.forEach((key, i) => {
              t[key] = row?.[i] ?? null;
            });
            return t;
          });
  
          return { ...group, translations };
        });
      });
  }
};


app.viewHandlers = {
  "dashboard": "initDashboard",
  "playback": app.playback,  // 👈 CALL the function here
  "playlist-library": "initPlaylistLibrary",
  "settings": "initSettings",
  "smart-lists": "initSmartLists",
  "admin": "initAdmin"
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


app.config = {};

app.api = function(endpoint, options = {}) 
{
  return (async function () 
  {
    // Load config if needed
    if (!app.config || !app.config.base_url) 
      {
        try 
        {
          const res = await fetch("/api/index.php?action=get_config");
          const data = await res.json();
          if (data.success) 
          {
            app.config = data;
            console.log("✅ Config loaded in app.api()", app.config);
          } 
          else 
          {
            throw new Error("❌ Failed to load config.");
          }
        } 
        catch (e) 
        {
          console.error("❌ Failed to load initial config:", e);
          return { success: false, error: "Network error during config fetch" };
        }
      }

    const base = app.config.base_url || "";
    const safeBase = base.replace(/\/+$/, "");
    const safeEndpoint = endpoint.replace(/^\/+/, "");

    const token = app.token || localStorage.getItem("speakify_token") || "";
    let finalEndpoint = safeEndpoint;
    
    if (token && !finalEndpoint.includes("token=")) {
      finalEndpoint += (finalEndpoint.includes("?") ? "&" : "?") + "token=" + token;
    }
    
    const url = `${window.location.origin}${safeBase}/${finalEndpoint}`;
    

    const defaultHeaders = 
    {
      "Content-Type": "application/json"
    };

    try 
    {
      const response = await fetch(url, {
        method: "GET",
        headers: defaultHeaders,
        ...options
      });

      if (!response.ok) 
      {
        const errorText = await response.text();
        return {
          success: false,
          status: response.status,
          error: errorText || "Server returned an error"
        };
      }

      return await response.json();
    } 
    catch (err) 
    {
      console.error("❌ API request failed:", err);
      
      return {
        success: false,
        error: "Network or server unreachable",
        details: err.message
      };
    }
  })();
};

app.initAdmin = function() 
{

};

app.test = async function (arg = null) 
{
  console.log("🔥 app.test() has started");
  console.log("🧪 Test runner invoked");

  console.log("🧪 test() running: current =", localStorage.getItem("test_runner_state"));

  const defaultDelay = 30;

  // Internal state storage key
  const LS_KEY = 'test_runner_state';

  // Define test plan
  const testPlan = 
  {
    dashboardView: 
    {
      run: true,
      action: () => document.querySelector('.footer-nav a[href="dashboard"]')?.click(),
      endpoints: ['get_config', 'validate_session']
    },

    playbackView: 
    {
      run: true,
      action: () => document.querySelector('.footer-nav a[href="playback"]')?.click(),
      endpoints: ['get_config', 'validate_session','get_sentences']
    },

    'playlist-libraryView': 
    {
      run: true,
      action: () => document.querySelector('.footer-nav a[href="playlist-library"]')?.click(),
      endpoints: ['get_config', 'validate_session','get_playlists']
    },

    'smart-listsView': 
    {
      run: true,
      action: () => document.querySelector('.footer-nav a[href="smart-lists"]')?.click(),
      endpoints: ['get_config', 'validate_session','get_playlists']
    },

    settingsView: 
    {
      run: true,
      action: () => document.querySelector('.footer-nav a[href="settings"]')?.click(),
      endpoints: ['get_config', 'validate_session','get_playlists']
    },

    'login-profileView': 
    {
      run: true,
      action: () => document.querySelector('.header a[href="login-profile"]')?.click(),
      endpoints: ['get_config', 'validate_session']
    },

    achievementsView: 
    {
      run: true,
      action: () => document.querySelector('.header a[href="achievements"]')?.click(),
      endpoints: ['get_config', 'validate_session']
    },

    'offline-modeView': 
    {
      run: true,
      action: () => document.querySelector('.header a[href="offline-mode"]')?.click(),
      endpoints: ['get_config', 'validate_session']
    }    

  };

  const testOrder = 
  [
    'settingsView',
    'dashboardView', 
    'playbackView',
    'playlist-libraryView',
    'smart-listsView',
    'settingsView',
    'login-profileView',
    'achievementsView',
    'offline-modeView',

    'dashboardView'
  ];


    // Handle quick single test execution
    if (typeof arg === 'string') {
      const quick = testPlan[arg];
      if (!quick) {
        console.warn(`❌ No such test: '${arg}'`);
        return;
      }
      console.log(`🎯 Running standalone test '${arg}'`);
      try {
        await quick.action();
      } catch (err) {
        console.error(`❌ Error in '${arg}':`, err);
      }
      return;
    }

    let state;

    if (typeof arg === 'number') {
      localStorage.removeItem('next_test_timestamp');
      state = {
        current: 0,
        results: [],
        delay: arg
      };
    } else {
      state = JSON.parse(localStorage.getItem(LS_KEY) || 'null');
      if (!state) {
        state = {
          current: 0,
          results: [],
          delay: defaultDelay
        };
      }
    }

   // Always persist the resolved state
   localStorage.setItem(LS_KEY, JSON.stringify(state));

// 🕒 Delay gate (based on saved timestamp)
const nextAt = parseInt(localStorage.getItem('next_test_timestamp') || '0');
const now = Date.now();
if (now < nextAt) {
  const wait = nextAt - now;
  console.log(`⏳ Waiting ${Math.ceil(wait / 1000)}s before next test...`);
  await new Promise(res => setTimeout(res, wait));
}

  const nextTestName = testOrder[state.current];
  const test = testPlan[nextTestName];

  console.log("🧠 nextTestName =", nextTestName);
  console.log("🧠 test =", test);

   if (!nextTestName || !test) 
  {
    console.log("🛑 No next test. Should trigger report.");
    console.log("✅ All tests completed.");

    // ✅ Test Results
    console.group("📋 Test Results");
    console.table(state.results);
        // 🚫 Skipped tests
    const skipped = Object.keys(testPlan).filter(k => !testPlan[k].run);
    console.log("⏭️ Skipped tests:", skipped);
        // 📦 Coverage check
    const testedEndpoints = Object.values(testPlan)
      .flatMap(t => Array.isArray(t.endpoints) ? t.endpoints : []);
    const uniqueTested = [...new Set(testedEndpoints)];
    const coverageScan = await app.api("api/index.php?action=tests&step=scan");
    const backendControllers = coverageScan?.controllers || [];
    const backendViews = coverageScan?.views || [];
    const uncovered = backendControllers.filter(ctrl => !uniqueTested.includes(ctrl));
    console.log("✅ Covered endpoints:", uniqueTested);
    console.log("⚠️ Uncovered endpoints:", uncovered);
    console.groupEnd();
        console.groupEnd();
    
        // 🧭 View coverage check
        const testPlanViews = Object.keys(testPlan).map(k => k.replace(/View$/, ''));
        const missingViewTests = backendViews.filter(view => !testPlanViews.includes(view));
        const orphanedTests = testPlanViews.filter(view => !backendViews.includes(view));
    
        console.group("🗺️ View Coverage");
        console.log("📂 Views found:", backendViews);
        console.log("✅ Views covered by tests:", testPlanViews);
        console.log("❗ Views NOT covered by testPlan:", missingViewTests);
        console.log("❗ Tests defined for NON-EXISTENT views:", orphanedTests);

        // 🧱 PHP + DB Logs
    const report = await app.api("api/index.php?action=tests&step=report");
    console.group("🧱 Error Logs");
    console.log("🐘 PHP error.log:");
    console.log((report?.error_log || []).join('\n') || "✅ No recent PHP errors");
        console.log("🗃️ Logger DB:");
    console.table(report?.log_db || []);
    console.groupEnd();
        // 🔥 Cleanup
    localStorage.removeItem(LS_KEY);
    localStorage.removeItem('next_test_timestamp');
    return;
  }

    console.log(`🚀 Running test ${state.current + 1}/${testOrder.length}: ${nextTestName}`);
  
    // Advance BEFORE triggering navigation
    state.current += 1;
    localStorage.setItem(LS_KEY, JSON.stringify(state));

    const delayInMs = state.delay * 1000;
    const nextTime = Date.now() + delayInMs;
    localStorage.setItem('next_test_timestamp', nextTime.toString());
  
      try {
          setTimeout(() => {
            test.action();
          }, 100); // 🔁 Give DOM 100ms to settle
      // 🧠 Skip result logging here — next page handles it
    } catch (err) {
      console.error(`❌ Error running '${nextTestName}'`, err);
      const stateNow = JSON.parse(localStorage.getItem(LS_KEY));
      if (stateNow) {
        stateNow.results.push({ name: nextTestName, status: 'error', message: err.message });
        localStorage.setItem(LS_KEY, JSON.stringify(stateNow));
      }
    }

};


app.resetTest = function (delay = 30) {
  localStorage.setItem('test_runner_state', JSON.stringify({
    current: 0,
    results: [],
    delay: delay
  }));
  localStorage.removeItem('next_test_timestamp');
  window.location.href = 'dashboard'; // or the first view you expect
};


  




document.addEventListener("DOMContentLoaded", () => {
  app.init();
});






