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
- [ ] Display list of playlists
- [ ] Link to playback + edit
- [ ] Link to create new playlist

**Frontend**:
- [ ] Playlist card layout
- [ ] Playback/Edit links

**Backend**:
- [ ] `/api?action=playlists`
- [ ] Optional: filter by user

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

