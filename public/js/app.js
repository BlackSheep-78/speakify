console.log("✅ app.js loaded");

const app = {
  token: null,

  async init() {
    console.log("👋 app.init() running");
    await this.ensureToken();
    await this.handleAuthVisibility();

    this.queueEl = document.getElementById("playloop-queue");
    this.playButton = document.getElementById("toggle-playback");

    if (this.queueEl && this.playButton) {
      this.setupEvents();
      await this.fetchData();
    }
  },

  async ensureToken() {
    let token = localStorage.getItem('speakify_token');

    const isValid = async (token) => {
      const check = await fetch(`/speakify/public/api/index.php?action=validate_session&token=${token}`);
      const result = await check.json();
      return !result.error && result.user !== undefined;
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

  async handleAuthVisibility() {
    const loginSection = document.getElementById('login-section');
    const profileSection = document.getElementById('profile-section');
    const profileName = document.getElementById('profile-name');
    const profileEmail = document.getElementById('profile-email');
    const profileLastLogin = document.getElementById('profile-last-login');
    const logoutButton = document.getElementById('logout-button');
    const headerUserLink = document.querySelector('.header a[href="login-profile.html"]');

    console.log("🔍 Checking token...");
    const token = localStorage.getItem('speakify_token');
    if (!token) {
      console.warn("⚠️ No token found.");
      if (loginSection) loginSection.hidden = false;
      if (profileSection) profileSection.hidden = true;
      if (headerUserLink) headerUserLink.textContent = "👤 Connexion";
      return;
    }

    try {
      const res = await fetch(`/speakify/public/api/index.php?action=validate_session&token=${token}`);
      const result = await res.json();
      console.log("🧠 validate_session result:", result);

      if (result.error || !result.user || !result.user.name) {
        console.warn("❌ Invalid or anonymous session");
        if (loginSection) loginSection.hidden = false;
        if (profileSection) profileSection.hidden = true;
        if (headerUserLink) headerUserLink.textContent = "👤 Connexion";
        return;
      }

      console.log("✅ Logged in as:", result.user.name);

      if (loginSection) loginSection.hidden = true;
      if (profileSection) profileSection.hidden = false;

      if (profileName) profileName.textContent = result.user.name;
      if (profileEmail) profileEmail.textContent = result.user.email;
      if (profileLastLogin) profileLastLogin.textContent = result.user.last_activity;

      if (headerUserLink) {
        headerUserLink.textContent = `👤 ${result.user.name}`;
      }

      if (logoutButton) {
        logoutButton.onclick = () => {
          localStorage.removeItem('speakify_token');
          location.reload();
        };
      }

    } catch (error) {
      console.error('❌ Error fetching user session:', error);
      if (loginSection) loginSection.hidden = false;
      if (profileSection) profileSection.hidden = true;
      if (headerUserLink) headerUserLink.textContent = "👤 Connexion";
    }
  },

  delay(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }
};

document.addEventListener("DOMContentLoaded", () => {
  app.init();

  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const payload = {
        email: loginForm.email.value.trim(),
        password: loginForm.password.value
      };

      const token = localStorage.getItem("speakify_token");

      const res = await fetch(`/speakify/public/api/index.php?action=login&token=${token}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const result = await res.json();

      if (result.success) {
        localStorage.setItem('speakify_token', result.token);
        await app.handleAuthVisibility();
      } else {
        alert("❌ Login failed: " + (result.error || "unknown error"));
      }
    });
  }

  registerFormHandler();
});

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