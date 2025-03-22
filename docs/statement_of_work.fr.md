## 📄 Documentation

-    [Readme](https://github.com/BlackSheep-78/speakify/blob/main/README.md)

- 🇫🇷 [Cahier des Charges (FR)](https://github.com/BlackSheep-78/speakify/blob/main/docs/statement_of_work.fr.md)  
- 🇬🇧 [Statement_of_Work  (EN)](https://github.com/BlackSheep-78/speakify/blob/main/docs/statement_of_work.en.md)
  
- 🇫🇷 [Document de Spécifications Techniques (FR)](https://github.com/BlackSheep-78/speakify/blob/main/docs/technical_specification_document.fr.md)  
- 🇬🇧 [Technical Specification Document (EN)](https://github.com/BlackSheep-78/speakify/blob/main/docs/technical_specification_document.en.md)

---
  
# Speakify - Cahier des Charges

## 1. Introduction  
### 1.1 Présentation du projet  
Speakify est une plateforme interactive d’apprentissage des langues qui permet aux utilisateurs d’interagir avec du contenu audio multilingue à travers des playlists structurées. La plateforme permet de créer et de gérer des expériences audio personnalisées basées sur la traduction, rendant l’apprentissage des langues plus immersif et efficace. Speakify est conçu pour être accessible sur mobile, ordinateur et écran TV.

### 1.2 Objectifs  
- Proposer une approche structurée de l’apprentissage linguistique à travers les **boucles de lecture (PLs)**.  
- Permettre aux utilisateurs de créer, gérer et personnaliser des **playlists** avec différents modes d’apprentissage.  
- Assurer une expérience utilisateur fluide sur plusieurs appareils.  
- Prendre en charge plusieurs langues et traductions avec lecture audio.  
- Offrir un backend évolutif pour un stockage et une récupération efficaces des traductions.  
- Développer et déployer le projet entre le **25 mars et le 23 avril 2025**.

---

## 2. Fonctionnalités & Résolution des Problèmes  
### **2.1 Fonctionnalités de l’application**  
- **Playlists audio multilingues** : Les utilisateurs peuvent créer et personnaliser des playlists composées de boucles de lecture avec des fichiers audio.  
- **Boucles de lecture (PLs)** : Une unité structurée contenant une phrase dans une langue source et ses traductions dans plusieurs langues, chacune accompagnée d’un fichier audio.  
- **Schéma de lecture personnalisable** : Les utilisateurs peuvent définir l’ordre de lecture, le nombre de répétitions et le minutage des boucles de lecture.  
- **Compatibilité multi-appareils** : Conçue pour fonctionner sur téléphones mobiles, ordinateurs et téléviseurs pour une expérience d’apprentissage flexible.  
- **Apprentissage interactif** : Fonctions comme le mode boucle, la vitesse de lecture ajustable et la pratique de la prononciation pour renforcer l’apprentissage.  
- **Mode d’apprentissage intensif** : Permet des exercices répétés et ciblés avec une lecture accélérée et une difficulté adaptative pour améliorer la rétention et la fluidité.  
- **Support hors-ligne** (prévu) : Capacité à stocker les traductions et les audios pour une utilisation hors connexion.  
- **Gestion des playlists utilisateur** : Créer, modifier et organiser des sessions d’apprentissage selon les besoins personnels.  
- **Playlists par difficulté et contexte** : Les utilisateurs peuvent classer les playlists par niveaux de difficulté et contextes spécifiques (ex. : vocabulaire professionnel).  
- **Apprentissage nomade** : Idéal pour apprendre en marchant, courant, en transports ou au travail. Conçu pour ceux qui ont peu de temps pour étudier.  
- **Soutien aux immigrés** : Offre un moyen accessible d’apprendre rapidement une nouvelle langue pour s’adapter à un nouvel environnement.  
- **Évolutivité future** : Potentiel d’intégration avec des services de traduction alimentés par IA.  
- **Contrôle de la pertinence des traductions** : **Les utilisateurs peuvent augmenter ou réduire le niveau de pertinence d’une traduction, influençant sa fréquence d’affichage pendant la lecture.**  
- **Schéma basé sur la pertinence** : **Les utilisateurs peuvent appliquer différents schémas de lecture en fonction des niveaux de pertinence pour prioriser ou minimiser certaines traductions.**  
- **Listes intelligentes** :  
  - **Listes contextuelles de mots cibles** : **Phrases incluant un mot spécifique pour illustrer son usage réel.**  
  - **Listes de conjugaisons** : **Phrases utilisant des verbes spécifiques conjugués à différents temps.**  
  - **Listes contextuelles** : **Playlists adaptées à des situations précises (ex. : aéroport, hôpital, restaurant).**  
  - **Phrases aléatoires** : **Listes générées automatiquement pour exposer les apprenants à une large variété de structures et de vocabulaire.**  
  - **Dialogues** : **Playlists simulant des conversations réelles pour renforcer la compréhension.**  
  - **Histoires** : **Playlists narratives pour favoriser un apprentissage naturel par immersion contextuelle.**  
  - **Mot du jour** : **Chaque jour, Speakify propose un mot ou un verbe avec des phrases illustratives pour renforcer le vocabulaire par exposition quotidienne.**

### 2.2 Public Cible  
- **Apprenants de langues** : Personnes souhaitant apprendre ou améliorer une langue via une écoute structurée.  
- **Enseignants & formateurs** : Enseignants souhaitant créer des exercices linguistiques personnalisés.  
- **Professionnels multilingues** : Travailleurs ayant besoin de maîtriser plusieurs langues.  
- **Apprenants sectoriels** : Personnes apprenant un vocabulaire spécifique (médical, juridique, technique, etc.).  
- **Voyageurs & expatriés** : Personnes qui déménagent à l’étranger et ont besoin de maîtriser le langage du quotidien.  
- **Spécialistes de la parole & de l’audition** : Professionnels ou chercheurs travaillant sur le contenu multilingue.  
- **Professionnels occupés** : Personnes ayant peu de temps pour étudier, mais souhaitant apprendre par écoute passive.  
- **Immigrés & nouveaux résidents** : Personnes ayant besoin d’apprendre rapidement une langue pour s’adapter.

---

## 3. Navigation & Maquettes  

### 3.1 Schéma de navigation utilisateur  

Démarrer l’application  
   ↓  
[🏠 Tableau de bord]  
   ├── Taper sur "Nouvelle session" → [🎧 Lecteur]  
   ├── Taper sur "Playlist récente" → [🎧 Lecteur]  
   ├── Taper sur "Mot du jour" → [🧠 Listes intelligentes > Mot du jour]  
   └── Taper sur "Playlists" → [📚 Bibliothèque de playlists]  

[📚 Bibliothèque de playlists]  
   ├── Voir les détails → [🎧 Lecteur]  
   ├── Taper sur "+ Créer playlist" → [✏️ Éditeur de playlist]  
   │     └── Taper sur "Assigner un schéma" → [🛠️ Sélecteur de schéma ou ➕ Créer un nouveau schéma]  
   └── Taper sur "Modifier schéma" → [🛠️ Éditeur de schéma]  

[🛠️ Éditeur de schéma]  
   ├── Définir l’ordre de lecture (ex. : EN → FR → Pause → Répéter)  
   ├── Définir les répétitions par segment  
   ├── Ajuster les délais / vitesses  
   ├── Nommer et enregistrer le schéma  
   └── Retourner à l’Éditeur de playlist ou utiliser immédiatement  

[🧠 Listes intelligentes]  
   ├── Mot du jour → [Liste de phrases contextuelles]  
   ├── Conjugaisons → [Liste de phrases avec verbes conjugués]  
   ├── Contextes thématiques → [Aéroport, Restaurant, etc.]  
   └── Dialogues / Histoires → [Flux audio contextuels]  

[🎧 Lecteur]  
   ├── Utilise la playlist et le schéma sélectionnés  
   └── Contrôles : Lecture, Pause, Boucle, Suivant, Précédent, Vitesse  

[⚙️ Paramètres]  
   ├── Vitesse audio  
   ├── Préférences de langue  
   └── Options hors-ligne  

Optionnel :  
[🔐 Connexion]  
   └── Synchroniser les données, enregistrer le profil, activer le cloud

### 3.2 Maquette de l’écran de lecture 🎧  
L’**écran de lecture** est l’interface principale où les utilisateurs interagissent avec les contenus audio traduits. Il suit une **séquence de lecture structurée** et propose un **suivi des progrès**.

##### 🔹 **En-tête (Statistiques utilisateur)**  
| 👤 Utilisateur | 🔥 Série | 🌟 XP  |  
|----------------|---------|--------|  
| Jorge          | 12 jours | 2 450  |  

##### 🔹 **Séquence de lecture**  
- **Phrases actives** (dépliées avec contrôles)  
  - ▶ Lecture | ⏸ Pause | 🔄 Répéter x2, x3  
- **Phrases en file d’attente** (repliées)  
  - 🔽 Phrase FR 2  
  - 🔽 Phrase FR 3  

##### 🔹 **Navigation inférieure**  
| 🏠 Accueil | 🎧 Lecture | 📚 Playlists | 🧠 Listes intelligentes | ⚙️ Paramètres |

### 3.3 Maquettes en cours  
#### ✅ **Vues principales (MVP)**  
- [x] **3.2 Écran de lecture**  
- [ ] **3.4 Éditeur de schéma** *(priorité suivante)*  
- [ ] **3.5 Bibliothèque de playlists**  
- [ ] **3.6 Éditeur de playlists**  
- [ ] **3.7 Listes intelligentes**  
- [ ] **3.8 Paramètres**  

#### 🔄 **Fonctionnalités supplémentaires (planifiées)**  
- [ ] **3.9 Mot du jour**  
- [ ] **3.10 Connexion & Profil**  
- [ ] **3.11 Statistiques & Succès**  
- [ ] **3.12 Mode hors-ligne**

---

## 4. Identité graphique (UI/UX Design)  

Speakify vise une expérience visuelle claire, immersive et cohérente sur tous les supports : mobile, ordinateur, TV. L’accent est mis sur la lisibilité, la concentration, et la convivialité pour favoriser un engagement durable.

---

### 4.1 Palette de couleurs  

| Usage           | Nom de la couleur   | Code Hex   | Remarques                                      |
|------------------|---------------------|------------|------------------------------------------------|
| Principale       | Bleu profond        | #0057B7    | Confiance, calme, concentration                |
| Accent           | Vert électrique     | #00E676    | Actions importantes (ex. : lecture)            |
| Fond             | Gris clair          | #F5F7FA    | Neutre, réduit la fatigue visuelle             |
| Surface          | Blanc doux          | #FFFFFF    | Fonds des cartes et conteneurs                 |
| Texte principal  | Noir charbon        | #2E2E2E    | Contraste élevé, lisible partout               |
| Alerte / Erreur  | Rouge corail        | #FF5252    | Suppressions, erreurs, alertes critiques       |

---

### 4.2 Typographie  

| Usage            | Police                 | Substituts               |
|------------------|------------------------|--------------------------|
| Titres           | Segoe UI Bold          | system-ui, sans-serif    |
| Texte courant    | Segoe UI Regular       | Helvetica, Arial         |
| Éléments UI      | Antipasto Pro (Light)  | Segoe UI                 |

---

### 4.3 Composants UI & Disposition  

- **Boutons** : Arrondis (≥ 12px), effet hover dynamique.  
- **Cartes** : Ombres douces, fonds neutres, padding uniforme.  
- **Navigation** : Onglets en bas sur mobile ; barre latérale ou en haut sur PC.  
- **Icônes** : Bibliothèques modernes (Lucide, Tabler, Material Symbols).  
- **Animations** : Transitions légères pour retour visuel.  
- **Mode sombre** : Prévu comme option.

---

### 4.4 Cohérence multi-écrans  

Speakify est une **Progressive Web App (PWA)** responsive, avec structure unifiée sur :

- 📱 **Mobiles** : Contrôles adaptés au pouce  
- 💻 **Ordinateurs** : Zones de lecture élargies  
- 📺 **TV / Affichage grand format** : Texte large, contraste élevé  

---

## 5. Écoresponsabilité  

Speakify est conçu avec des pratiques numériques durables, pour limiter la consommation énergétique, le transfert de données, et l’utilisation des ressources.

---

### 5.1 Efficacité serveur  
- **Backends légers** : APIs optimisées.  
- **Requêtes de base de données efficaces** : Mise en cache, pagination.  
- **Hébergement vert** : Fournisseurs à énergie renouvelable.  
- **Infrastructure auto-scalable** : Évite la surconsommation.

---

### 5.2 Optimisation bande passante  
- **Chargement différé** des ressources lourdes.  
- **Streaming audio adaptatif**.  
- **Fichiers compressés** (WebP, Brotli).  
- **Scripts tiers minimisés**.

---

### 5.3 Mode hors-ligne  
- **Cache local** : Par playlist.  
- **Téléchargement à la demande**.  
- **Préchargement contrôlé par l’utilisateur**.  
- **Logique sensible à la batterie** (prévu).

---

### 5.4 Améliorations futures  
- **Mode sombre éco-énergétique**.  
- **Analytique légère** pour identifier les optimisations.  
- **IA allégée** côté serveur ou edge.

---

## 6. SEO & Visibilité  

---

### 6.1 Stratégie SEO  
- **Optimisation PWA** pour le crawl.  
- **URLs claires & localisées**.  
- **Balises meta & Open Graph**.  
- **Sitemaps & robots.txt automatiques**.  
- **Chargement rapide** : respect des Core Web Vitals.

---

### 6.2 Mots-clés  
**Principaux** :  
- application apprentissage langue  
- pratique immersive  
- apprentissage audio traduction  
- écouter langue passivement  
- apprendre le français en voiture  
- créateur de playlists linguistiques  
- entraîneur vocabulaire intelligent  

**Contextuels** :  
- ex. : "phrases médicales espagnol"  
- ex. : "anglais quotidien pour immigrés en France"  
- optimisation pour recherche vocale

---

### 6.3 Indexation multilingue  
- **Hreflang**  
- **Métadonnées localisées**  
- **URLs localisées** : /fr, /en, /es…  
- **Mots-clés traduits** dans chaque langue

---

### 6.4 Présence en ligne  
- **Intégration réseaux sociaux** (OG, Twitter)  
- **Schema.org** pour résultats enrichis  
- **Optimisation store mobile** (wrapper PWA)  
- **Google Discover** : articles structurés  
- **Blog futur** pour trafic organique

---

## 7. Équipe & Organisation  

---

### 7.1 Équipe  

| Rôle                  | Responsabilités                                                                 |
|------------------------|---------------------------------------------------------------------------------|
| **Product Owner**      | Vision produit, validation, priorisation                                       |
| **Chef de produit**    | Timeline, communication, jalons                                                |
| **Lead Dev**           | Architecture, qualité, backend/frontend                                        |
| **Dev Frontend**       | Composants UI, responsive                                                      |
| **Dev Backend**        | API, BDD, logique (boucles, progression)                                       |
| **UX/UI Designer**     | Maquettes, prototypes, style visuel                                            |
| **QA Tester**          | Tests manuels/automatisés, stabilité                                           |
| **Curateur de contenu**| Contenu multilingue, traductions, listes intelligentes                         |
| **DevOps (optionnel)** | Déploiement, cloud, hébergement                                                |

---

### 7.2 Méthodologie  

**Agile léger (Scrum)**  
- **Sprint** : 1 semaine  
- **Daily stand-ups** courts  
- **Kanban** pour suivre les tâches  
- **Planification / Revue** hebdomadaire

**Outils** :

| Outil         | Usage                          |
|---------------|-------------------------------|
| Git + GitHub  | Code source                   |
| Trello/Notion | Sprints                       |
| Figma         | UI/UX                         |
| Slack/Discord | Communication                 |
| VS Code       | Développement                 |

---

### 7.3 Philosophie  

- **Livrer vite, améliorer ensuite**  
- **Documenter au fur et à mesure**  
- **Centré utilisateur**  
- **Culture de la curiosité et progression**

---

## 8. Planning  

| Phase            | Description                        | Date prévue         |
|------------------|-------------------------------------|---------------------|
| **Phase 1**       | Schéma base de données              | 29 mars 2025        |
| **Phase 2**       | Développement API                  | 5 avril 2025        |
| **Phase 3**       | Implémentation frontend             | 12 avril 2025       |
| **Phase 4**       | Tests & optimisations               | 19 avril 2025       |
| **Phase 5**       | Préparation au déploiement          | 23 avril 2025       |

---

## 9. Conclusion  

Speakify n’est pas qu’une application de langue, c’est une plateforme immersive, flexible et centrée sur l’utilisateur, pensée pour la vraie vie. Elle s’adapte aux besoins des apprenants, aux contextes variés, et reste évolutive pour intégrer l’IA et la voix à l’avenir.

Les prochaines étapes : finaliser les prototypes, valider l’identité graphique et optimiser les workflows pour un lancement réussi au 23 avril 2025.

---

**Version du document** : 1.2.0  
**Date** : Mars 2025  
**Auteur** : Jorge  

---
