speakify/
├── public/                     # Contenu exposé (index.html, assets statiques)
│   ├── index.html
│   ├── css/
│   │   └── styles.css
│   ├── js/
│   │   └── script.js
│   ├── data/
│   │   ├── playlists.json
│   │   ├── schemas.json
│   │   └── translations.json
│   └── assets/
│       ├── audio/
│       ├── icons/
│       └── flags/
│
├── backend/                   # API, PHP et logique serveur
│   ├── api.php
│   ├── config.php             # Fichier de configuration global
│   ├── classes/
│   │   ├── Database.php
│   │   ├── Translate.php
│   │   ├── OpenAiApi.php
│   │   └── Utilities.php
│   └── php/
│       ├── get_sentences.php
│       └── run.php
│
├── model/                     # Maquettes HTML + style/script (prototype UI)
│   ├── *.html
│   ├── style.css
│   └── script.js
│
├── docs/                      # Documentation (Markdown)
│   ├── project.md
│   ├── technical_specification_document.en.md
│   ├── technical_specification_document.fr.md
│   ├── statement_of_work.en.md
│   ├── statement_of_work.fr.md
│   └── model.project.md
│
├── sql/                       # Requêtes et structure DB
│   ├── get_sentences.sql
│   ├── insert_sentence_translation.sql
│   └── reset_database.sql
│
├── resources/                 # Anciennement goodies/, fichiers non essentiels
│   ├── wireframes/
│   └── graph.html
│
├── tests/                     # (Optionnel) Fichiers de test backend/frontend
│   ├── api/
│   └── frontend/
│
├── env/                       # (Optionnel) Config d’environnement locale
│   ├── local.env
│   └── .env.example
│
└── README.md
