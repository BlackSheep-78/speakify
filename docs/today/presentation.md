# Speakify – Presentation Dossier

## 1. Project Overview

Speakify is a language-learning platform that rethinks how people acquire languages in a digital world. It offers learners a rich, personalized, and interactive experience where listening, repetition, and contextual practice are central. Whether used by individuals for self-guided learning or by educators managing entire classrooms, Speakify adapts to each user’s goals and pace.

At its core, Speakify is about turning passive study into active practice — allowing users to experience the language through structured playlists, smart content generation, real-time audio playback, and flexible schema-driven logic.

## 2. Objectives & Innovation

Speakify was created in response to a recurring challenge in language education: how to keep learners engaged, exposed to real content, and supported with the tools to retain and reuse what they learn. While many platforms offer lessons and flashcards, few support:

- Dynamic, personalized repetition logic  
- Sentence-level mastery tracking  
- AI-assisted content generation  
- Audio customization by voice, language, and provider  
- Group-based learning environments  

Speakify answers this gap with a system built to adapt, grow, and support real-world language practice.

## 3. Key Functional Features

### 3.1 Custom Playlists and Playback Schemas

Users can create playlists filled with "translation blocks" — sentence pairs across two or more languages. These can be replayed in specific sequences defined by reusable playback schemas (e.g. 3x French, 2x English, 1x Spanish with delays). This helps strengthen memorization and recall.

### 3.2 Smart List Generation via AI

Using OpenAI, users can generate:

- Keyword-based playlists ("coffee")  
- Word of the Day prompts  
- Verb conjugation sets  
- Contextual scenarios ("starting a job", "ordering food")  

These lists are flexible, reviewable, and seamlessly added to playlists.

### 3.3 On-the-Go Note Capture

Users can capture spontaneous questions via text or voice ("How would I say this in French?"). Notes can later be translated or turned into playlist items.

### 3.4 TTS Integration

Each sentence supports TTS playback with selectable language, provider, and voice style — key for listening and pronunciation practice.

### 3.5 Mastery & Progress Tracking

Users track familiarity with content. As repetition decreases, items are marked "mastered." This feeds into badges, XP, and streaks for engagement.

### 3.6 Group Features for Teachers

Educators can:

- Create groups  
- Invite students  
- Assign playlists  
- Track progress (future feature)  

This makes Speakify a digital classroom solution.

### 3.7 Social Connectivity (Future)

Planned features may include user matching based on language goals, locations, and interests — enabling chat, calls, or real-life meetups for practice.

## 4. Technical Structure

Speakify’s backend uses a relational schema emphasizing clarity and scalability.

- **Translation Logic**: `translation_pairs`, `sentences`, `languages`  
- **Customization**: `schemas`, `user_settings`, `smart_lists`  
- **Audio System**: `tts_audio`, `tts_providers`, `tts_voices`  
- **Progress Tracking**: `sentence_mastery`, `achievements`  
- **Collaboration**: `groups`, `group_members`, `group_playlists`  
- **User Behavior**: `sessions`, `notes` (planned), `favorites`  

An MCD was created with labeled cardinalities and zones.

## 5. Scalability & Future Development

Planned evolutions include:

- More languages and voices  
- Integration with more AI tools  
- Expanded group/community features  
- Push notifications, gamification, adaptive scheduling  

The project is modular: TTS, AI, progress, and groups can evolve independently while staying integrated.

## 6. Development Timeline

| Phase              | Timeline         | Deliverables                                       |
|-------------------|------------------|----------------------------------------------------|
| Ideation           | March 2025       | Feature planning, project definition, tech stack   |
| Data Modeling      | Late March 2025  | MCD, schema files, config                          |
| Core Dev           | Early April 2025 | Playback, UI logic, session manager                |
| TTS Integration    | Mid-April 2025   | API setup, audio linking                           |
| Smart List & AI    | Mid-April 2025   | List generation features                           |
| Group Features     | Late April 2025  | Group creation, sharing                            |
| Finalization       | Apr 21–24, 2025  | Docs, MCD, soutenance prep                         |

## 7. Conclusion

Speakify blends AI-driven content, interactive audio, and collaborative features into a unique language-learning platform. Built to evolve, it supports both solo learners and educators in building real-world fluency.

---

**Table of Contents**  
1. Project Overview  
2. Objectives & Innovation  
3. Key Functional Features  
    - 3.1 Custom Playlists and Playback Schemas  
    - 3.2 Smart List Generation via AI  
    - 3.3 On-the-Go Note Capture  
    - 3.4 TTS Integration  
    - 3.5 Mastery & Progress Tracking  
    - 3.6 Group Features for Teachers  
    - 3.7 Social Connectivity  
4. Technical Structure  
5. Scalability & Future Development  
6. Development Timeline  
7. Conclusion  
