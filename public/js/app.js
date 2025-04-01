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

    if (token) {
      console.log("✅ Found token:", token);
    } else {
      console.warn("⚠️ No valid session, creating new anonymous session...");
      const res = await fetch('/speakify/public/api/index.php?action=create_session');
      const data = await res.json();
      token = data.token;
      localStorage.setItem('speakify_token', token);
      console.log("✅ New anonymous session created:", token);
    }

    this.token = token;
  },

  async handleAuthVisibility(forcedToken = null) {
    const loginSection = document.getElementById('login-section');
    const profileSection = document.getElementById('profile-section');
    const profileName = document.getElementById('profile-name');
    const headerUserLink = document.querySelector('.header a[href="login-profile.html"]');
    const logoutButton = document.getElementById('logout-button');

    // Default UI
    if (loginSection) loginSection.hidden = false;
    if (profileSection) profileSection.hidden = true;
    if (headerUserLink) headerUserLink.textContent = "👤 Connexion";

    const token = forcedToken || localStorage.getItem('speakify_token');
    if (!token) return;

    try {
      const res = await fetch(`/speakify/public/api/index.php?action=validate_session&token=${token}`);
      const result = await res.json();
      console.log("🧠 validate_session result:", result);

      if (!result.success || !result.name) return;

      // Valid session with user
      if (loginSection) loginSection.hidden = true;
      if (profileSection) profileSection.hidden = false;

      if (profileName) profileName.textContent = result.name;
      if (headerUserLink) headerUserLink.textContent = `👤 ${result.name}`;

      if (logoutButton) {
        logoutButton.onclick = () => {
          localStorage.removeItem('speakify_token');
          location.reload();
        };
      }
    } catch (error) {
      console.error('❌ Error validating session:', error);
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
        await app.handleAuthVisibility(result.token);
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
