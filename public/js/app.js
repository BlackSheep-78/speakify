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


console.log("✅ app.js loaded");

const app = {
  state: {
    validated: false,
    validatedTokenData: null,
  },
  token: null,
  authChecked: false,

  async init() {
    console.log("👋 app.init() running");

    await this.ensureToken();
    await this.updateUI();
    await this.setupPageElements();
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

    this.token = token;
    this.state.validatedTokenData = result;

    console.log("🆗 Final session token in app state:", this.token);
  },


  async validateToken(token) {
    try {
      const res = await fetch(`/speakify/public/api/index.php?action=validate_session&token=${token}`);
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
      const res = await fetch('/speakify/public/api/index.php?action=create_session');
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
    const headerUserLink = document.querySelector('.header a[href="login-profile.html"]');
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

    if (logoutButton) {
      logoutButton.onclick = async () => {
        const token = localStorage.getItem("speakify_token");
    
        // ✅ Call backend to remove user_id, keep session token alive
        if (token) {
          await fetch(`/speakify/public/api/index.php?action=logout&token=${token}`);
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

  async setupPageElements() {
    this.registerFormHandler();
    this.loginFormHandler();
  },

  loginFormHandler() {
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
        const res = await fetch("/speakify/public/api/index.php?action=login", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload)
        });

        const result = await res.json();

        if (result.success) {
          localStorage.setItem("speakify_token", result.token);
          message.textContent = "✅ Connexion réussie ! Redirection...";
          setTimeout(() => window.location.href = "dashboard.html", 1500);
        } else {
          message.textContent = `❌ ${result.error || "Erreur inconnue."}`;
        }
      } catch (err) {
        message.textContent = "❌ Erreur réseau.";
        console.error("Login failed:", err);
      }
    });
  },

  registerFormHandler() {
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
          body: JSON.stringify(payload),
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
  },

  delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  },
};

document.addEventListener("DOMContentLoaded", () => {
  app.init();
});
