# Speakify - Cahier des Charges

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

## 4. Graphical Identity (UI/UX Design) 💡 *(Needs to be worked on)*  
- **Color Scheme & Typography**: Define visual identity for Speakify.  
- **Consistency Across Devices**: Ensure a seamless experience between mobile, desktop, and TV.  

---

## 5. Eco-Responsibility 💡 *(Needs to be worked on)*  
- **Server Efficiency Considerations**: Optimize hosting to reduce energy consumption.  
- **Low-Bandwidth Optimization**: Reduce unnecessary data usage.  
- **Offline Mode Enhancements**: Reduce network dependence to minimize power usage.  

---

## 6. SEO & Online Presence 💡 *(Needs to be worked on)*  
- **SEO Strategy**: Define how Speakify will be indexed in search engines.  
- **Keyword Optimization**: Identify relevant keywords for better discoverability.  
- **Multilingual Indexing**: Optimize for global users.  

---

## 7. Team & Workflow 💡 *(Needs to be worked on)*  
- **Development Team**: Define roles and responsibilities.  
- **Project Methodology**: Specify whether Agile, Scrum, or Kanban will be used.  

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

