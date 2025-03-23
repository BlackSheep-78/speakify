## 📄 Documentation

-    [Readme](https://github.com/BlackSheep-78/speakify/blob/main/README.md)

- 🇫🇷 [Cahier des Charges (FR)](https://github.com/BlackSheep-78/speakify/blob/main/docs/statement_of_work.fr.md)  
- 🇬🇧 [Statement_of_Work  (EN)](https://github.com/BlackSheep-78/speakify/blob/main/docs/statement_of_work.en.md)
  
- 🇫🇷 [Document de Spécifications Techniques (FR)](https://github.com/BlackSheep-78/speakify/blob/main/docs/technical_specification_document.fr.md)  
- 🇬🇧 [Technical Specification Document (EN)](https://github.com/BlackSheep-78/speakify/blob/main/docs/technical_specification_document.en.md)

---

# Speakify - Statement of work

## 1. Introduction  
### 1.1 Project Overview  
Speakify is an interactive language learning platform that enables users to engage with multilingual audio content through structured playlists. The platform allows users to create and manage custom translation-based audio experiences, making language acquisition more immersive and effective. Speakify is designed for accessibility across mobile, desktop, and TV screens.

### 1.2 Objectives  
- Provide a structured approach to language learning through **Playback Loops (PLs)**.  
- Enable users to create, manage, and customize **playlists** with various learning modes.  
- Ensure a seamless user experience across multiple devices.  
- Support multiple languages and translations with audio playback.  
- Offer a scalable backend for efficient storage and retrieval of translations.  
- Develop and deploy within the time frame of **March 25 to April 23, 2025**.

---

## 2. Features & Problem Solving  
### **2.1 Application Features**  
- **Multilingual Audio Playlists**: Users can create and customize playlists consisting of Playback Loops with audio files.  
- **Playback Loops (PLs)**: A structured unit that contains a sentence in a source language and its equivalent translations in multiple languages, each with an associated audio file.  
- **Customizable Playback Schema**: Users can define the playback sequence, repetitions, and timing of Playback Loops.  
- **Cross-Device Compatibility**: Designed to work on mobile phones, desktops, and TVs for a flexible learning experience.  
- **Interactive Learning**: Features like loop mode, adjustable playback speed, and pronunciation practice help reinforce learning.  
- **Intensive Learning Mode**: Allows users to engage in focused and repetitive drills with accelerated playback and adaptive difficulty to enhance retention and fluency.  
- **Offline Support** (Planned): Ability to store translations and audio for offline use.  
- **User Playlists Management**: Create, edit, and organize language learning sessions according to personal learning needs.  
- **Playlists by Difficulty & Context**: Users can categorize playlists based on difficulty levels and specific contexts, such as professional vocabulary for various industries.  
- **Learn on the Go**: Ideal for users who want to learn while walking, running, commuting on the bus or car, or even while working. Designed for people who have limited time to study.  
- **Support for Immigrants**: Provides an accessible way for immigrants to quickly learn and adapt to a new language environment.  
- **Future Expansion**: Potential for integration with AI-powered translation services.  
- **Translation Pertinence Control**: **Users can increase or decrease the pertinence level of a translation, affecting how frequently it is displayed during playback.**  
- **Schema Based on Pertinence**: **Users can apply different playback schemas based on pertinence levels to prioritize or de-emphasize certain translations.**  
- **Smart Lists**:  
  - **Target Word Context Lists**: **Lists of sentences that include a specific word, providing real-world usage examples.**  
  - **Verb Conjugation Lists**: **Lists of sentences using specific verbs in various conjugations and tenses.**  
  - **Context-Based Lists**: **Playlists targeting specific circumstances (e.g., airport, hospital, restaurant).**  
  - **Random Sentences**: **Automatically generated lists to expose learners to a wide variety of structures and vocabulary.**  
  - **Dialogues**: **Playlists simulating real conversations to enhance comprehension and engagement.**  
  - **Stories**: **Narrative-based playlists to promote natural learning through contextual immersion.**  
  - **Word of the Day Lists**: **Each day, Speakify suggests a new word or verb. Users can access a list of sentences that include the word of the day in real contexts, enhancing vocabulary through daily exposure.**

### 2.2 Target Audience  
- **Language Learners**: Individuals looking to learn or improve a new language through structured listening.  
- **Educators & Tutors**: Teachers who want to create custom language exercises for their students.  
- **Multilingual Professionals**: Business professionals who need to master multiple languages for communication.  
- **Industry-Specific Learners**: Individuals looking to learn domain-specific vocabulary, such as medical, legal, or technical terminology.  
- **Travelers & Expats**: People relocating to a new country who need practical, real-world language exposure.  
- **Speech & Hearing Specialists**: Researchers and professionals working with multilingual content for language studies.  
- **Busy Professionals & Workers**: People who have limited time for formal study but want to learn through passive listening.  
- **Immigrants & New Residents**: Individuals who need to learn a new language quickly for adaptation and daily interactions.

---

## 3. Navigation & Wireframes

### 3.1 User Flow Diagram

Start App  
   ↓  
[🏠 Dashboard]  
   ├── Tap "Start New Session" → [🎧 Player]  
   ├── Tap "Recent Playlist" → [🎧 Player]  
   ├── Tap "Daily Word" → [🧠 Smart Lists > Word of the Day]  
   └── Tap "Playlists" → [📚 Playlist Library]  

[📚 Playlist Library]  
   ├── View Playlist Details → [🎧 Player]  
   ├── Tap "+ Create Playlist" → [✏️ Playlist Editor]  
   │     └── Tap "Assign Schema" → [🛠️ Schema Selector or ➕ Create New Schema]  
   └── Tap "Edit Schema" → [🛠️ Schema Editor]  

[🛠️ Schema Editor]  
   ├── Define Playback Order (e.g., EN → FR → Pause → Repeat)  
   ├── Set Repetitions per segment  
   ├── Adjust Delay / Speed  
   ├── Name the schema and Save  
   └── Return to Playlist Editor or use it immediately  

[🧠 Smart Lists]  
   ├── Word of the Day → [List of contextual sentences]  
   ├── Verb Conjugations → [List of verb-based sentences]  
   ├── Thematic Contexts → [Airport, Restaurant, etc.]  
   └── Dialogues / Stories → [Contextual audio flows]  

[🎧 Player]  
   ├── Uses selected playlist and schema  
   └── Controls: Play, Pause, Loop, Next, Previous, Speed  

[⚙️ Settings]  
   ├── Audio Speed  
   ├── Language Preferences  
   └── Offline Mode Options  

Optional:  
[🔐 Login]  
   └── Sync data, save profile, enable cloud features 

### 3.2 Wireframes
This section provides **low-fidelity wireframes** for the core screens of Speakify. These wireframes illustrate layout, structure, and main UI components.

#### 🎧 **Playback View Wireframe**
The **Playback View** is the primary interface where users interact with translation-based audio content. It follows a structured **playback sequence** while providing **user progress tracking and quick navigation**.

##### 🔹 **Header (User Stats)**
| 👤 User | 🔥 Streak | 🌟 XP  |
|---------|----------|--------|
| Jorge   | 12 Days  | 2,450  |

##### 🔹 **Playback Sequence**
- **Active Sentences** (Unfolded, showing controls)
  - ▶ Play | ⏸ Pause | 🔄 Repeat x2, x3  
- **Queued Sentences** (Collapsed, waiting in sequence)
  - 🔽 FR sentence 2 text  
  - 🔽 FR sentence 3 text  

##### 🔹 **Bottom Navigation**
| 🏠 Home | 🎧 Playback | 📚 Playlists | 🧠 Smart Lists | ⚙️ Settings |

The **Playback View** enables structured listening and repetition using translation blocks, helping users absorb language naturally.

### 3.3 Wireframes Work in Progress
The following UI views are in progress for Speakify. Each wireframe will be documented and iterated upon as development continues.

#### ✅ **Core Views (MVP)**
- [x] **3.2 Playback View** (Playback sequence, audio controls, bottom nav)  
- [ ] **3.4 Schema Editor View** *(Next priority - Defines playback rules & logic)*  
- [ ] **3.5 Playlist Library View** *(Browse, search, select, and manage playlists)*  
- [ ] **3.6 Playlist Editor View** *(Create & modify playlists, assign schemas)*  
- [ ] **3.7 Smart Lists View** *(Prebuilt lists: verb conjugations, context-based, etc.)*  
- [ ] **3.8 Settings View** *(Control playback speed, language preferences, offline mode, etc.)*  

#### 🔄 **Extra Features (Planned)**
- [ ] **3.9 Word of the Day View** *(Daily word with example sentences)*  
- [ ] **3.10 Login & Profile View** *(Optional login, progress tracking, cloud sync)*  
- [ ] **3.11 Achievements & Stats View** *(Track XP, learning streaks, goals)*  
- [ ] **3.12 Offline Mode View** *(Download and manage offline resources)*  


---

## 4. Graphical Identity (UI/UX Design)

Speakify aims to deliver a clear, immersive, and consistent visual experience across all devices — mobile, desktop, and TV. The design system prioritizes readability, focus, and user-friendliness to support long-term engagement and effective language learning.

---

### 4.1 Color Scheme & Visual Identity

| Purpose        | Color Name      | Hex Code  | Notes                                       |
|----------------|------------------|-----------|---------------------------------------------|
| Primary        | Deep Blue        | #0057B7   | Trust, calmness, and focus on learning      |
| Accent         | Electric Green   | #00E676   | Highlights important actions (e.g., Play)   |
| Background     | Light Gray       | #F5F7FA   | Neutral, reduces eye strain                 |
| Surface        | Soft White       | #FFFFFF   | Card and container backgrounds              |
| Text Primary   | Charcoal Black   | #2E2E2E   | High contrast, legible across screens       |
| Alert / Error  | Coral Red        | #FF5252   | For deletions, errors, or critical alerts   |

The interface remains predominantly calm and neutral, using vibrant colors only for essential feedback and interactive elements.

---

### 4.2 Typography

Speakify uses clean, modern, and readable fonts optimized for accessibility and multilingual content.

| Use Case      | Font                  | Fallbacks              |
|---------------|------------------------|------------------------|
| Headings      | Segoe UI Bold          | system-ui, sans-serif  |
| Body Text     | Segoe UI Regular       | Helvetica, Arial       |
| UI Highlights | Antipasto Pro (Light)  | Segoe UI               |

Fonts are chosen for their cross-platform availability and aesthetic harmony. All text is responsive and mobile-optimized.

---

### 4.3 UI Components & Layout

- **Buttons**: Rounded (border-radius 12px+), vibrant on hover, clean contrast.
- **Cards & Containers**: Soft drop shadows, consistent padding, neutral backgrounds.
- **Navigation**: Bottom tab navigation on mobile; top nav or side panel on desktop.
- **Icons**: Modern icon libraries (Lucide, Tabler, or Material Symbols).
- **Animations**: Light transitions only for feedback (e.g., active block expansion).
- **Dark Mode**: Planned as an optional setting for comfort during night use.

---

### 4.4 Consistency Across Devices

Speakify is designed as a **responsive progressive web app (PWA)** with consistent behavior and UI structure across:

- 📱 **Mobile phones**: Thumb-friendly controls, bottom navigation
- 💻 **Desktops/Laptops**: Scalable layout with larger reading zones
- 📺 **TVs / Smart Displays**: Large text, high contrast, minimal controls

Grid-based layout, shared styles, and reusable components ensure a unified experience regardless of screen size or input method.

---


---

## 5. Eco-Responsibility

Speakify is committed to sustainable digital practices. As a modern, web-based language learning platform, it will be designed with eco-responsibility in mind — optimizing for low energy consumption, reduced data transfer, and efficient use of resources across devices.

---

### 5.1 Server Efficiency Considerations
To reduce the platform’s environmental footprint, Speakify will prioritize:

- **Lightweight backend services**: Optimized APIs that serve only essential data.
- **Efficient database queries**: Cached lookups and pagination to reduce server load.
- **Green hosting options**: Preference for providers using renewable energy (e.g., Infomaniak, Scaleway Green).
- **Auto-scaling infrastructure**: Dynamic resource allocation based on demand, avoiding overprovisioning.

---

### 5.2 Low-Bandwidth Optimization
The frontend will be designed to work efficiently even in low-speed or unstable networks, especially for mobile users and global learners.

- **Lazy-loading** of images, audio, and large assets.
- **Adaptive streaming** for audio files (lower bitrate for slow networks).
- **Compressed assets** using WebP, Brotli, and modern web formats.
- **Minimal third-party scripts** to reduce bandwidth waste.

---

### 5.3 Offline Mode Enhancements
Speakify’s offline mode supports both eco-responsibility and accessibility for users with limited or costly data plans.

- **Local storage caching**: Translation blocks and audio files saved locally per playlist.
- **Download-on-demand**: Only resources the user requests will be cached.
- **Preloading control**: Users can choose when and what to make available offline.
- **Battery-aware logic** (Planned): Defers heavy tasks on low battery conditions.

---

### 5.4 Future Improvements
- **Energy-aware dark mode**: Lower screen energy use on OLED devices.
- **Usage-based analytics**: Tracking system load to identify optimization opportunities.
- **Push for lightweight AI**: If AI features are integrated, use serverless or edge-based solutions with minimal energy impact.

> By implementing these strategies, Speakify aims to align high-quality learning experiences with a responsible and sustainable digital footprint.
 

---

## 6. SEO & Online Presence

To ensure Speakify reaches a global audience of language learners, educators, and multilingual professionals, a thoughtful SEO strategy will be implemented. The goal is to make the platform highly visible, searchable, and discoverable across multiple languages and regions.

---

### 6.1 SEO Strategy

The SEO approach for Speakify will focus on both **technical SEO** (site structure, performance) and **content SEO** (relevance, clarity).

- **Progressive Web App Optimization**: Ensure that the PWA is crawlable and well-structured, using server-side or dynamic rendering where necessary.
- **Clean URL Structure**: URLs will be semantic and localized (e.g. `/fr/playlists`, `/en/word-of-the-day`).
- **Meta Tags & Open Graph**: Each page will contain meaningful meta titles, descriptions, and social preview metadata.
- **Sitemaps & Robots.txt**: Automatically generated XML sitemaps and properly configured robots.txt files for optimal indexing.
- **Fast Loading Times**: Optimized asset delivery and Core Web Vitals compliance for search engine ranking benefits.

---

### 6.2 Keyword Optimization

Relevant keywords will be researched and integrated into the content, targeting different user intents and learning contexts.

**Primary Keyword Targets:**
- language learning app
- immersive language practice
- translation-based audio learning
- passive language listening
- learn French while commuting
- language playlist builder
- smart vocabulary trainer

**Contextual Keyword Strategies:**
- Long-tail keywords for specific industries (e.g., "learn medical Spanish phrases")
- Location-based or culturally sensitive terms (e.g., "daily English for immigrants in France")
- Voice search optimization (“how do I say hello in Japanese?”)

---

### 6.3 Multilingual Indexing

Since Speakify targets multilingual users worldwide, content must be accessible and indexable in multiple languages.

- **Hreflang Tags**: Used to indicate content language and regional targeting.
- **Language-specific Metadata**: Page titles, descriptions, and slugs will be localized.
- **Localized Content URLs**: `/fr`, `/en`, `/es`, etc. paths for each version of a page.
- **International Keyword Mapping**: Equivalent keywords will be identified across key target languages (EN, FR, ES, DE, etc.).

> All multilingual pages will be SEO-optimized independently to improve visibility in native-language search results.

---

### 6.4 Online Presence & Brand Visibility

Beyond search indexing, Speakify will build awareness through:

- **Social Media Meta Integration** (OG tags, Twitter Cards)
- **Schema.org Markup** for rich search results (e.g., “language course”, “audio lesson”)
- **App Store Optimization** (if PWA is deployed as mobile wrapper)
- **Google Discover Readiness**: Structured articles and featured “Word of the Day” content
- **Future blog or resource center** to generate organic traffic with educational content

> SEO is not just a discovery tool for Speakify — it’s a growth engine aligned with global accessibility and content relevance.


---

## 7. Team & Workflow

Speakify will be developed by a lean, agile, and highly focused team, working collaboratively to deliver a polished product within a tight timeframe. The structure promotes accountability, adaptability, and continuous delivery.

---

### 7.1 Development Team

| Role                   | Responsibility                                                                 |
|------------------------|---------------------------------------------------------------------------------|
| **Project Owner**      | Defines the product vision, prioritizes tasks, approves features                |
| **Product Manager**    | Manages timeline, milestones, and communication between stakeholders            |
| **Lead Developer**     | Oversees architecture, code quality, and core backend/frontend implementation   |
| **Frontend Developer** | Builds UI components, ensures responsive and accessible design                  |
| **Backend Developer**  | Develops API, database, and schema logic (e.g. Playback Loops, user progress)   |
| **UX/UI Designer**     | Creates wireframes, prototypes, visual style, and ensures cross-device UX       |
| **QA Tester**          | Conducts manual/automated testing, validates playback sequences and stability   |
| **Content Curator**    | Prepares and manages multilingual content, translations, and smart lists        |
| **DevOps (Optional)**  | Handles deployment pipeline, cloud sync, and hosting setup (can be part-time)   |

*Note: Some roles may be fulfilled by the same person during the early stages (e.g., Full Stack Developer wearing multiple hats).*

---

### 7.2 Project Methodology

Speakify will use a **lightweight Agile methodology** based on **Scrum**, with sprints adapted to a short development cycle.

#### 📅 Sprint Rhythm
- **Sprint Length**: 1 week  
- **Daily Stand-ups**: 10–15 mins max (asynchronous if remote)  
- **Weekly Planning & Review**: Set priorities, review sprint results, and define upcoming goals  
- **Kanban Board**: Used to track task flow (To Do → In Progress → Review → Done)  

#### 🛠 Tools & Workflow
| Tool           | Purpose                                |
|----------------|----------------------------------------|
| Git + GitHub   | Source control and code collaboration  |
| Trello / Notion| Sprint planning and task tracking      |
| Figma          | UI/UX collaboration                    |
| Slack / Discord| Team communication                     |
| VS Code        | Development environment                |

> This setup encourages clarity, iteration, and accountability while staying adaptable to evolving needs.

---

### 7.3 Collaboration Philosophy

- **Ship fast, improve continuously**: MVP first, polish after validation  
- **Document as we go**: Avoid silos and make handoff easier  
- **User-first thinking**: Every sprint must result in tangible improvements for learners  
- **Learning-friendly**: The dev culture reflects Speakify’s purpose — curiosity, clarity, and growth


---

## 8. Project Timeline & Phases  
| Phase            | Task Description                         | Estimated Completion |
|------------------|------------------------------------------|----------------------|
| **Phase 1**       | Finalize database schema                 | March 29, 2025       |
| **Phase 2**       | Develop core API endpoints               | April 5, 2025        |
| **Phase 3**       | Implement frontend UI                    | April 12, 2025       |
| **Phase 4**       | Conduct testing and optimizations        | April 19, 2025       |
| **Phase 5**       | Prepare for deployment                   | April 23, 2025       |

---

## 9. Conclusion

Speakify is more than just a language app — it’s a flexible, immersive, and user-centered language learning platform designed for real life. Whether on the move, at work, or relaxing at home, users can interact with high-quality multilingual content, tailored playlists, and customizable playback systems.

This document lays the foundation for a powerful language tool that adapts to learners’ needs, supports different contexts, and remains scalable for future innovations like AI translation and voice interaction.

The next steps will be to complete the design prototypes, finalize UI identity, and optimize development workflows to deliver a successful launch by April 23, 2025.


---

**Document Version**: 1.2.0  
**Date**: March 2025  
**Author**: Jorge

---

# Speakify - Technical Specification Document

## 10. System Architecture
### 10.1 Overview
- **Project Name**: Speakify
- **Version**: 1.0.0
- **Date**: March 2025
- **Author(s)**: Jorge

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