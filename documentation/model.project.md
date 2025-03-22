# file: model.project.md

# Structure actuelle de la maquette Speakify

```
speakify/
└── model/
    ├── dashboard.html
    ├── playback.html
    ├── playlist-library.html
    ├── smart-lists.html
    ├── settings.html
    ├── achievements.html
    ├── login-profile.html
    ├── offline-mode.html
    ├── playlist-editor.html
    ├── schema-editor.html
    ├── index.html
    ├── project.md
    ├── style.css
    ├── script.js
    └── data/
        └── translations.json
```

# List of specs

- **HTML Structure**:
  - Every page must have a consistent `<head>` with a viewport meta tag, charset UTF-8, and links to `style.css` and `script.js`.
  - Each page must contain a clearly defined `.header`, `.content`, and `.footer-nav`.
  - The `.header` must contain three clickable icons linking to relevant pages or actions.
  - The playback page must not contain hardcoded translation lists in HTML; all translation content must be loaded dynamically.

- **CSS Styling**:
  - Unified and consistent style across all pages through `style.css`.
  - Header styling: white background, shadow effect, rounded corners, and consistent padding.
  - Footer navigation fixed at the bottom, with clearly defined and uniformly styled buttons.
  - Background gradient from #F5F7FA to #E3E9F0 across all pages.
  - Interactive elements must use transitions for hover effects for smooth visual feedback.
  - The footer navigation must always remain visible at the bottom of the viewport and should never be pushed off-screen.
  - Play loops must be styled as pill-like rounded rectangles with smooth borders and spacing for clear queue visibility.
  - Closed play loops must:
    - Appear on a single line (e.g., "🇬🇧 It’s a beautiful day. · x2").
    - Use the same color and font style as general text (not highlighted).
  - Open (active) play loops must display:
    - Both the original and translated text.
    - A progress bar indicating audio playback per sentence.
    - A repetition counter (e.g., x3).
  - Dashboard list styles must visually match the smart-lists style block.
  - The global play/pause button should float near the bottom of the screen, positioned for thumb access on mobile. It should be optionally draggable to allow the user to reposition it comfortably.

- **JavaScript**:
  - Unified functionality and interactivity managed through a single JavaScript file (`script.js`).
  - Script loading deferred for optimal performance (`defer` attribute).
  - By default, playback views should generate translation pairs dynamically (EN–FR) using data loaded from `data/translations.json`.

- **Navigation**:
  - Footer navigation must consistently link the main views: dashboard, playback, playlist-library, smart-lists, and settings.
  - Internal links on the dashboard page must include achievements, login-profile, and offline-mode.
  - The playlist-library page must internally link to the playlist-editor, which itself must link to schema-editor.

- **Responsive Design**:
  - All pages must be mobile-friendly, adapting gracefully to various screen sizes.
  - Elements like navigation buttons and links must maintain usability across devices.

- **Content Clarity**:
  - Text content and labels must be clear, concise, and easily understandable.
  - Icons and emojis must complement text for intuitive navigation and readability.

- **Interactivity**:
  - The three icons in the header must be clickable, providing intuitive navigation or triggering relevant actions.
  - Each play loop:
    - Has a progress bar showing progress per repetition (`xN`).
    - Automatically plays its audio `xN` times.
    - Closes once complete and smoothly scrolls the queue up to hide the finished loop.
    - Can be open or closed. When **open**, the loop is unfolded to show all sentences in the translation, each with its own progress bar and repeat count. When **closed**, the loop is minimized and presented inline in a neutral tone.
  - A **global play/pause button** must be available to control the entire playlist playback state. This button should be visually distinct, float above the content near the user's thumb (bottom-right corner on mobile), and support being dragged to reposition.

- **Playlist Structure**:
  - A **playlist** is a queue of **play loops**.
  - Each **play loop** represents a group of translation sentences and related metadata.
  - Only one play loop is expanded/open at a time during playback.
  - When in use, the loop is unfolded and displays all its sentences and their progress bars.
  - When complete, the loop is marked closed and the next one automatically opens.

- **File Organization**:
  - All HTML, CSS, JS, and data files must be placed within the `speakify/model/` directory or appropriate subfolders (e.g., `data/`).

Respecting these specs will ensure a unified, professional, and user-friendly design experience across all views.
