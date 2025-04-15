# file: /speakify/docs/checklist.md
# project: speakify
# ✅ Speakify Views – Functionalities, Dependencies, and Progress Checklist

---

## 🏠 1. Dashboard View (`dashboard.html`)

**Functionalities**:
- [ ] Quick links to Playback, Smart Lists, Playlist Library
- [ ] Display last session or progress

**Frontend**:
- [ ] Header + Footer nav
- [ ] Link tiles / buttons
- [ ] Optional: user session detection

**Backend**:
- [ ] [Optional] Load user progress via API

---

## 🎧 2. Playback View (`playback.html`)

**Functionalities**:
- [x] Load Translation Block queue
- [x] Schema-based playback (EN → FR → PT)
- [x] Floating draggable play/pause button
- [x] Visual repeat/progress bars

**Frontend**:
- [x] `#playloop-queue` and `#toggle-playback`
- [x] Playback logic in `app.js`
- [x] Floating control styles in CSS

**Backend**:
- [x] `/api?action=get_sentences`
- [x] Token required

---

## 📚 3. Playlist Library View (`playlist-library.php`)

**Functionalities**:
- [x] Display list of playlists
- [x] Link to playback + edit
- [x] Link to create new playlist
- [ ] Create/Edit playlist
- [ ] Add/Remove translation blocks
- [ ] Assign schema
- [ ] Rearrange block order (drag & drop or up/down buttons)
- [ ] Show block previews (original + translated sentences)
- [ ] Display total TB count in real-time
- [ ] Persist changes to backend

**Frontend**:
- [x] Playlist card layout
- [x] Playback/Edit links
- [x] Add link/button to create new playlist
- [ ] Fetch playlist data via API
- [x] Render playlist cards with:
  - [ ] Playlist name
  - [ ] Translation block count
  - [ ] Schema name
  - [ ] Links to Playback + Edit
  - [ ] Playback link, selects and activates playlist
- [ ] Form fields + TB selector
- [ ] Schema dropdown
- [ ] Sortable TB list component
- [ ] Buttons: Add, Remove, Save
- [ ] Confirmation or toast message on Save
- [ ] Redirect to Playlist Library after save (optional)

**Backend**:
- [x] `/api?action=get_playlists`
- [x] Optional: filter by user token
- [x] Create `PlaylistModel.php` in `backend/classes/`
  - [x] `getPlaylists($user_id = null)`
  - [ ] `getPlaylistDetails($playlist_id)`
- [x] Add controller: `get_playlists.php`
- [x] Connect controller to API action
- [ ] `/api?action=save_playlist` (accepts name, schema, TB list with order)
- [ ] `/api?action=tb_list` (returns TBs with preview info)
- [ ] `/api?action=schemas` (returns available schemas)
- [ ] `ModelPlaylist::save()` — stores playlist & TB links
- [ ] `ModelPlaylist::getById($id)` — for editing existing playlists

**Database**:
- [x] Create `playlists` table with: `id`, `name`, `user_id`, `schema_id`, `created_at`, `updated_at`
- [x] Create `playlist_tb_link` table with: `id`, `playlist_id`, `tb_id`, `order_index`
- [x] Add SQL files: `04_playlists.sql`, `05_playlist_tb_link.sql`
- [ ] Insert sample data for playlists and TBs

---

## ✏️ 4. Playlist Editor View (`playlist-editor.html`)

**Functionalities**:
- [ ] Create/Edit playlist
- [ ] Add/Remove translation blocks
- [ ] Assign schema

**Frontend**:
- [ ] Form fields + TB selector
- [ ] Schema dropdown

**Backend**:
- [ ] `/api?action=save_playlist`
- [ ] `/api?action=tb_list`
- [ ] `/api?action=schemas`

---

## 🛠 5. Schema Editor View (`schema-editor.html`)

**Functionalities**:
- [ ] Build/edit playback schema (order + repetition)
- [ ] Save schema

**Frontend**:
- [ ] Form builder UI
- [ ] Preview component

**Backend**:
- [ ] `/api?action=save_schema`
- [ ] `/api?action=schemas`

---

## 🧠 6. Smart Lists View (`smart-lists.html`)

**Functionalities**:
- [ ] Display smart lists (word of the day, verbs, contexts…)
- [ ] Sentence previews

**Frontend**:
- [ ] Grid/list UI
- [ ] Optional filters

**Backend**:
- [ ] `/api?action=smart_lists` (future)
- [ ] Dummy JSON (for now)

---

## ⚙️ 7. Settings View (`settings.html`)

**Functionalities**:
- [ ] Change playback speed
- [ ] Language preferences
- [ ] Offline mode toggle

**Frontend**:
- [ ] Settings form + controls

**Backend**:
- [ ] `/api?action=save_settings`
- [ ] `/api?action=get_settings`

---

## 🔐 8. Login/Profile View (`login-profile.html`)

**Functionalities**:
- [x] Login form
- [x] Show profile info
- [x] Logout button

**Frontend**:
- [x] Form + profile sections
- [x] `app.js` session handling

**Backend**:
- [x] `/api?action=login`
- [x] `/api?action=get_profile`
- [x] `/api?action=logout`
- [x] `/api?action=validate_session`

---

## 🆕 9. Register View (`register.html`)

**Functionalities**:
- [x] Register new user
- [x] Show status message
- [x] Redirect to login on success

**Frontend**:
- [x] Register form
- [x] `registerFormHandler()` in JS

**Backend**:
- [x] `/api?action=register_user`

---

## 🔌 10. Offline Mode View (`offline-mode.html`)

**Functionalities**:
- [ ] View/download content for offline use
- [ ] Manage cached content

**Frontend**:
- [ ] List of downloadable items
- [ ] Offline controls

**Backend**:
- [ ] None yet (future Service Worker)

---

## 🌐 11. External API Integration (Translation & TTS)

**Functionalities**:
- [ ] Translate user text using external API (e.g., OpenAI, DeepL, Google Translate)
- [ ] Generate audio (TTS) for given text in target language
- [ ] Handle fallback mechanisms for missing API responses
- [ ] Cache or store results to avoid repeated API calls

**Backend**:
- [ ] `/api?action=translate_text`
- [ ] `/api?action=text_to_speech`
- [ ] Add `GoogleTranslateApi.php`, `GoogleTTSApi.php` classes
- [ ] Update `Translate.php` to support multiple providers
- [ ] Add config for API keys in `config.json`
- [ ] Optional: Logging for external API requests

**Frontend**:
- [ ] Optional trigger for on-demand translation/TTS
- [ ] Display status messages or loading indicators

---

## 🧾 12. Certification Improvements – Based on 3W Academy Feedback

**Goal**: Strengthen clarity, technical argumentation, accessibility, and collaboration tools based on feedback from the Cahier des Charges evaluation.

**✅ To Improve – Based on Official Comments:**

**Documentation & Clarity**:
- [ ] Replace bullet lists with detailed explanations in `statement_of_work.*.md`
- [ ] Clearly define the **problem statement** (problématique)
- [ ] Provide details about the **target audience** (context, language level, needs)
- [ ] Include **accessibility, security, SEO, and responsive** priorities in the intro
- [ ] Include a **realistic budget** estimate (even if fictional)
- [ ] Add at least **one future evolution**, explained clearly (not just listed)
- [ ] Add **Deadlines (dates)** to the retro-planning section

**Technical Enhancements**:
- [ ] Add a **visual MCD (data model)** to the project
- [ ] Mention **responsiveness concepts**: mobile-first, breakpoints, etc.
- [ ] Detail **security choices** (XSS, SQL injection prevention, session management)
- [ ] Provide examples of **accessibility design**:
  - [ ] Text alternatives for images
  - [ ] Sufficient contrast
  - [ ] Keyboard navigation
  - [ ] Font legibility
- [ ] Propose at least **one tool or concept from technical watch in English**

**Team & Workflow**:
- [ ] Clarify **interaction between team roles**
- [ ] Link tools (Git, Trello, Discord, Figma…) to **concrete use cases**
- [ ] Add example of how tasks are tracked in Trello or Notion

**Bonus**:
- [ ] Prepare an **MCD diagram** for the oral defense (PDF or PNG)
- [ ] Re-read your CDC and **develop explanations** instead of bullet lists

---


