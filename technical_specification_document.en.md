🛠️ TECHNICAL SPECIFICATION DOCUMENT

📌 Project Overview
- Project Name: Speakify
- Version: 1.0.0
- Date: March 2025
- Author(s): Jorge

Speakify is a cross-platform PWA designed to facilitate immersive language learning through structured multilingual audio playlists. It supports mobile, desktop, and TV usage, leveraging Translation Blocks (TBs) and user-defined playback schemas to deliver a customizable learning experience. 

---

⚙️ System Architecture

1. High-Level Architecture:
- Frontend (HTML/CSS/JS) serves as a dynamic PWA interface
- Backend (PHP) provides APIs for data retrieval
- JSON files simulate API data
- Database (Planned) for structured storage of translations and schemas

2. Component Breakdown:
- Frontend:
  - HTML, CSS, Bootstrap, JS
  - AJAX loading for playlists and schemas
  - UI for Playback, Playlist Editor, Smart Lists
- Backend:
  - PHP API handler (`api.php`)
  - Handles playlist, TB, and schema retrieval
- Database:
  - Planned schema with tables for translations, languages, users, etc.
  - Support for missing translations, version control
- External Services / APIs:
  - Planned: OpenAI API for translation and TTS (text-to-speech)

---

🧱 Technology Stack

| Layer       | Technology     | Version | Notes                             |
|-------------|----------------|---------|-----------------------------------|
| Frontend    | HTML/CSS/JS    | ES6+    | PWA, responsive UI                |
| Backend     | PHP            | 8.x     | Simple API endpoints              |
| Database    | MySQL (Planned)| TBD     | Structured data model             |
| APIs        | OpenAI (Planned)| N/A     | Translation & TTS services        |
| DevOps/CI   | XAMPP / Manual | N/A     | Local development environment     |
| Others      | JSON files     | N/A     | Simulated API data for dev stage  |

---

📂 Data Model & Structures

1. Database Schema:
- Tables (Planned):
  - `languages`
  - `sentences`
  - `translation_pairs`
  - `sources`
  - `translation_pair_sources`

2. Example JSON Schema:
```
{
  "playlist_id": "123",
  "name": "Basic French",
  "blocks": [
    {
      "tb_id": "456",
      "text_source": "Hello",
      "text_translation": "Bonjour",
      "audio_source": "hello_en.mp3",
      "audio_translation": "bonjour_fr.mp3"
    }
  ]
}
```

---

🌐 API Endpoints

Base URL: http://localhost/speakify/backend/api.php

| Method | Endpoint     | Description             | Auth | Parameters          |
|--------|--------------|-------------------------|------|---------------------|
| GET    | ?action=playlists | Fetch all playlists      | No   |                     |
| GET    | ?action=schemas   | Fetch all schemas        | No   |                     |
| GET    | ?action=tb&id=xxx | Get translation block    | No   | id                  |
| POST   | TBD               | Create/update playlists  | Yes  | playlist, schema    |

---

🧠 Business Logic & Workflows

- Playback Loop (PL) logic:
  - Follows user-defined schema: order, repetitions, pause
  - Iterates through TBs in a playlist
  - Each block has source + one or more translations with audio
- Smart Lists auto-generate playlists based on specific contexts or criteria
- One play loop is expanded at a time
- Open loops show progress bars, repetitions, original and translated text
- Closed loops are summarized on a single line
- Global play/pause button controls full session playback and floats at bottom-right of the screen
- Audio and translation data dynamically loaded from `data/translations.json`

---

🔐 Security

- No authentication in current MVP
- Future:
  - User login with JWT or session cookies
  - Access control for personal playlists
  - Basic input sanitation for API endpoints

---

📱 UI Structure (Developer View)

| Page/View         | Description                        | Components/Files                 |
|-------------------|------------------------------------|----------------------------------|
| Dashboard         | Entry point with shortcuts         | dashboard.html                   |
| Playback          | Main player interface              | playback.html                    |
| Playlist Library  | List of user-created playlists     | playlist-library.html            |
| Playlist Editor   | Create/edit playlists              | playlist-editor.html             |
| Smart Lists       | Context-based auto-playlists       | smart-lists.html                 |
| Schema Editor     | Define playback rules              | schema-editor.html               |
| Settings          | User preferences                   | settings.html                    |
| Achievements      | Progress tracking                  | achievements.html                |
| Login/Profile     | Optional authentication            | login-profile.html               |
| Offline Mode      | Local file management              | offline-mode.html                |

All HTML pages:
- Must use a consistent `<head>` with meta tags and links to `style.css` and `script.js`
- Must define `.header`, `.content`, `.footer-nav`
- `.header` includes 3 interactive icons
- Footer navigation is fixed and responsive

---

🚀 Environments & Deployment

| Environment | URL                        | Notes                     |
|-------------|-----------------------------|---------------------------|
| Local       | http://localhost/speakify/  | XAMPP-based dev setup     |
| Staging     | TBD                         | For QA testing            |
| Production  | TBD                         | Final live deployment     |

---

🧪 Testing Plan

- Manual testing for:
  - Playback functionality
  - Loop progression
  - Playlist editing and schema linking
- Planned:
  - Unit testing for backend APIs (PHPUnit)
  - Frontend playback validation (Jest or Cypress)
- Device testing (mobile, desktop, TV)

---

📊 Performance & Constraints

- Responsive design for mobile-first experience
- Fixed footer and floating controls for usability
- Playback content loaded via AJAX, not hardcoded
- Lazy-loading of audio and transition effects required
- Loop completion scrolls queue and loads next
- Single loop open at a time ensures clarity
- Light, unified design ensures consistent user experience

---

🧰 Appendix

- JSON Examples: `playlists.json`, `schemas.json`, `translations.json`
- Glossary:
  - TB = Translation Block
  - PL = Playback Loop
  - Schema = Sequence logic for playback
- External Services:
  - OpenAI API (planned)
  - TTS and translation services
- File Organization:
  - All files in `speakify/model/`
  - Central script file: `script.js` (deferred loading)
  - Central stylesheet: `style.css` with transitions and global design

---

