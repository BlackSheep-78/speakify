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
    // Retrieve the token from localStorage
    let token = localStorage.getItem('speakify_token');
    console.log("✅ Token retrieved from localStorage:", token); // Debugging line to check the retrieved token
  
    if (token) {
      console.log("✅ Found token:", token); // Show if token exists in localStorage
    } else {
      console.warn("⚠️ No valid session, creating new anonymous session...");
      
      // Create a new session if no token exists
      const res = await fetch('/speakify/public/api/index.php?action=create_session');
      const data = await res.json();
      token = data.token; // Set the new token from the response
  
      // Save the new token to localStorage
      localStorage.setItem('speakify_token', token);
      console.log("✅ New anonymous session created:", token); // Log new token creation
    }
  
    // Force token update by directly re-fetching from localStorage
    token = localStorage.getItem('speakify_token'); // Explicitly fetch updated token
    console.log("✅ Final token used:", token); // Show the final token that will be used in the app
  
    this.token = token; // Store token in the app's state
  }
  ,
  

  async handleAuthVisibility(forcedToken = null) {
    const loginSection = document.getElementById('login-section');
    const profileSection = document.getElementById('profile-section');
    const profileName = document.getElementById('profile-name');
    const headerUserLink = document.querySelector('.header a[href="login-profile.html"]');
    const logoutButton = document.getElementById('logout-button');
  
    // Default UI - show login section, hide profile section
    if (loginSection) loginSection.hidden = false;
    if (profileSection) profileSection.hidden = true;
    if (headerUserLink) headerUserLink.textContent = "👤 Connexion";
  
    // Retrieve token from localStorage or use forced token if provided
    let token = forcedToken || localStorage.getItem('speakify_token');
    console.log("✅ Current token from localStorage:", token); // Log token before validation
  
    if (!token) return; // If no token found, exit
  
    try {
      // Make API request to validate session
      const res = await fetch(`/speakify/public/api/index.php?action=validate_session&token=${token}`);
      const result = await res.json();
      console.log("🧠 validate_session result:", result);
  
      // If the backend provides a new token (different from the old token), update localStorage
      if (result.token && result.token !== token) {
        console.log("⚠️ Old token:", token); // Log old token
        console.log("✅ New token received from backend:", result.token); // Log new token from backend
  
        // If the tokens differ, update the localStorage with the new token
        localStorage.setItem('speakify_token', result.token); // Save the new token in localStorage
        token = result.token; // Update local variable token with the new value
        console.log("✅ Token updated in localStorage:", token); // Log token update
      }
  
      // If the session is valid (even if it's anonymous, the token is valid)
      if (loginSection) loginSection.hidden = true;
      if (profileSection) profileSection.hidden = false;
  
      // Handle the user name (if available) or default to "Guest"
      if (result.name) {
        if (profileName) profileName.textContent = result.name;
        if (headerUserLink) headerUserLink.textContent = `👤 ${result.name}`;
      } else {
        // If name is null (for anonymous session), show default
        if (profileName) profileName.textContent = "Guest";
        if (headerUserLink) headerUserLink.textContent = "👤 Guest";
      }
  
      // Set up logout functionality
      if (logoutButton) {
        logoutButton.onclick = () => {
          localStorage.removeItem('speakify_token'); // Remove token on logout
          location.reload(); // Reload page to reset UI
        };
      }
    } catch (error) {
      console.error('❌ Error validating session:', error); // Handle any errors in session validation
    }
  }
  
  ,
  

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
        // Save the new token to localStorage after successful login
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
