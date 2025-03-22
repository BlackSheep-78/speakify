## 📄 Documentation

-    [Readme](https://github.com/BlackSheep-78/speakify/blob/main/README.md)

- 🇫🇷 [Cahier des Charges (FR)](https://github.com/BlackSheep-78/speakify/blob/main/statement_of_work.fr.md)  
- 🇬🇧 [Statement_of_Work  (EN)](https://github.com/BlackSheep-78/speakify/blob/main/statement_of_work.en.md)
  
- 🇫🇷 [Document de Spécifications Techniques (FR)](https://github.com/BlackSheep-78/speakify/blob/main/technical_specification_document.fr.md)  
- 🇬🇧 [Technical Specification Document (EN)](https://github.com/BlackSheep-78/speakify/blob/main/technical_specification_document.en.md)

---
  
🛠️ DOCUMENT DE SPÉCIFICATIONS TECHNIQUES

📌 Présentation du Projet
- Nom du projet : Speakify
- Version : 1.0.0
- Date : Mars 2025
- Auteur(s) : Jorge

Speakify est une application web progressive (PWA) multiplateforme conçue pour faciliter l'apprentissage immersif des langues à travers des playlists audio multilingues structurées. Elle prend en charge une utilisation sur mobile, ordinateur de bureau et téléviseur, en s'appuyant sur des Blocs de Traduction (TB) et des schémas de lecture personnalisés définis par l'utilisateur pour offrir une expérience d'apprentissage sur mesure.

---

⚙️ Architecture du Système

1. Architecture Générale :
- Frontend (HTML/CSS/JS) comme interface PWA dynamique
- Backend (PHP) fournissant des APIs pour la récupération des données
- Fichiers JSON simulant les données de l'API
- Base de données (prévue) pour le stockage structuré des traductions et des schémas

2. Décomposition des Composants :
- Frontend :
  - HTML, CSS, Bootstrap, JS
  - Chargement AJAX des playlists et des schémas
  - Interfaces utilisateur pour la lecture, l'édition de playlists, les Smart Lists
- Backend :
  - Gestionnaire d'API PHP (`api.php`)
  - Gère la récupération des playlists, TB et schémas
- Base de données :
  - Schéma prévu avec des tables pour les traductions, langues, utilisateurs, etc.
  - Support des traductions manquantes, contrôle de versions
- Services/API externes :
  - Prévu : API OpenAI pour la traduction et la synthèse vocale (TTS)

---

🧱 Pile Technologique

| Couche        | Technologie     | Version | Remarques                            |
|---------------|------------------|---------|--------------------------------------|
| Frontend      | HTML/CSS/JS      | ES6+    | PWA, UI réactive                     |
| Backend       | PHP              | 8.x     | Points d'accès API simples           |
| Base de données | MySQL (prévu)    | À définir | Modèle de données structuré          |
| APIs          | OpenAI (prévu)   | N/A     | Services de traduction et TTS        |
| DevOps/CI     | XAMPP / Manuel   | N/A     | Environnement de développement local |
| Autres        | Fichiers JSON    | N/A     | Données API simulées pour développement |

---

📂 Modèle de Données & Structures

1. Schéma de la base de données (prévu) :
- Tables :
  - `languages`
  - `sentences`
  - `translation_pairs`
  - `sources`
  - `translation_pair_sources`

2. Exemple de schéma JSON :
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

🌐 Points d'Accès API

URL de base : http://localhost/speakify/backend/api.php

| Méthode | Endpoint          | Description                   | Auth | Paramètres            |
|---------|-------------------|-------------------------------|------|------------------------|
| GET     | ?action=playlists | Récupère toutes les playlists | Non  |                        |
| GET     | ?action=schemas   | Récupère tous les schémas     | Non  |                        |
| GET     | ?action=tb&id=xxx | Récupère un bloc de traduction | Non  | id                     |
| POST    | À définir          | Crée/met à jour une playlist  | Oui  | playlist, schema       |

---

🧠 Logique Métier & Flux de Travail

- Logique de lecture des Boucles de Lecture (PL) :
  - Suit un schéma défini par l'utilisateur : ordre, répétitions, pause
  - Parcourt les TB dans une playlist
  - Chaque bloc contient une source et une ou plusieurs traductions avec audio
- Smart Lists : auto-génération de playlists selon contexte ou critères
- Une seule boucle est déployée à la fois
- Boucles ouvertes : barres de progression, répétitions, textes originaux et traduits
- Boucles fermées : résumé sur une ligne
- Bouton global lecture/pause contrôle toute la session et flotte en bas à droite de l'écran
- Les données audio et de traduction sont chargées dynamiquement depuis `data/translations.json`

---

🔐 Sécurité

- Pas d'authentification dans le MVP actuel
- Futur :
  - Connexion utilisateur avec JWT ou cookies de session
  - Contrôle d'accès aux playlists personnelles
  - Assainissement de base des entrées API

---

📱 Structure de l'Interface (Vue Développeur)

| Page/Vue           | Description                         | Fichiers/Composants              |
|--------------------|--------------------------------------|----------------------------------|
| Dashboard          | Page d'accueil avec raccourcis       | dashboard.html                   |
| Playback           | Interface principale de lecture      | playback.html                    |
| Playlist Library   | Liste des playlists créées           | playlist-library.html            |
| Playlist Editor    | Création/modification de playlists   | playlist-editor.html             |
| Smart Lists        | Playlists générées automatiquement   | smart-lists.html                 |
| Schema Editor      | Définition des règles de lecture     | schema-editor.html               |
| Settings           | Préférences utilisateur              | settings.html                    |
| Achievements       | Suivi des progrès                    | achievements.html                |
| Login/Profile      | Authentification facultative         | login-profile.html               |
| Offline Mode       | Gestion locale des fichiers          | offline-mode.html                |

Toutes les pages HTML :
- Doivent avoir un `<head>` cohérent avec balises meta et liens vers `style.css` et `script.js`
- Doivent définir `.header`, `.content`, `.footer-nav`
- `.header` contient 3 icônes interactives
- La navigation pied de page est fixe et réactive

---

🚀 Environnements & Déploiement

| Environnement | URL                          | Remarques                   |
|----------------|-------------------------------|-----------------------------|
| Local          | http://localhost/speakify/    | Environnement XAMPP local   |
| Staging        | À définir                     | Tests QA                    |
| Production     | À définir                     | Déploiement final           |

---

🧪 Plan de Tests

- Tests manuels pour :
  - Fonctionnalité de lecture
  - Progression dans les boucles
  - Édition de playlists et liaison aux schémas
- Prévu :
  - Tests unitaires pour les APIs backend (PHPUnit)
  - Validation de la lecture côté frontend (Jest ou Cypress)
- Tests multi-appareils : mobile, desktop, TV

---

📊 Performances & Contraintes

- Design responsive mobile-first
- Pied de page fixe et contrôles flottants pour l'ergonomie
- Contenu audio chargé via AJAX, pas de code en dur
- Lazy-loading des fichiers audio + effets de transition requis
- Fin de boucle déclenche défilement vers la suivante
- Une seule boucle ouverte à la fois pour plus de clarté
- Design unifié et léger pour une expérience utilisateur fluide

---

🧰 Annexe

- Exemples JSON : `playlists.json`, `schemas.json`, `translations.json`
- Glossaire :
  - TB = Bloc de Traduction
  - PL = Boucle de Lecture
  - Schéma = Logique de séquence de lecture
- Services externes :
  - API OpenAI (prévue)
  - Services de traduction et TTS
- Organisation des fichiers :
  - Tous les fichiers dans `speakify/model/`
  - Script central : `script.js` (chargement différé)
  - Feuille de style centrale : `style.css` avec transitions et design global

---

