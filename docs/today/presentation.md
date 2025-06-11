# Speakify – Dossier de Présentation

## 1. Présentation du Projet

Speakify est une plateforme d’apprentissage des langues qui repense la manière dont les gens acquièrent une langue dans un monde numérique. Elle offre aux apprenants une expérience riche, personnalisée et interactive où l’écoute, la répétition et la pratique contextuelle sont centrales. Que ce soit pour un apprentissage autonome ou pour des enseignants encadrant une classe entière, Speakify s’adapte aux objectifs et au rythme de chaque utilisateur.

Au cœur du projet, Speakify transforme l’étude passive en pratique active — en permettant aux utilisateurs de vivre la langue à travers des playlists structurées, du contenu généré intelligemment, une lecture audio en temps réel et une logique de lecture flexible basée sur des schémas.

## 2. Objectifs & Innovations

Speakify répond à un problème récurrent dans l’éducation linguistique : comment maintenir l’engagement, exposer les apprenants à du contenu authentique et leur fournir les outils pour retenir et réutiliser ce qu’ils apprennent. Contrairement à de nombreuses plateformes qui proposent des leçons et des flashcards, Speakify se distingue par :

- Une logique de répétition personnalisée et dynamique  
- Un suivi de maîtrise phrase par phrase  
- Une génération de contenu assistée par IA  
- Une personnalisation audio (voix, langue, fournisseur)  
- Un environnement d’apprentissage en groupe  

Speakify comble ainsi une lacune importante, avec un système conçu pour s’adapter, évoluer et accompagner la pratique linguistique réelle.

## 3. Fonctionnalités Clés

### 3.1 Playlists et Schémas de Lecture Personnalisés

Les utilisateurs créent des playlists composées de "blocs de traduction" — des paires de phrases dans deux langues ou plus. Ces blocs peuvent être rejoués selon des séquences définies par des schémas réutilisables (ex. : 3x en français, 2x en anglais, 1x en espagnol, avec des pauses). Ce rythme facilite la mémorisation.

### 3.2 Génération Intelligente de Listes via l’IA

Grâce à OpenAI, les utilisateurs peuvent générer :

- Des playlists basées sur des mots-clés ("café")  
- Des "Mots du jour" inspirants  
- Des conjugaisons adaptées à un temps précis  
- Des scénarios de vie réelle ("commencer un nouveau travail", "commander au restaurant")  

Ces listes sont dynamiques, modifiables, et intégrables aux playlists existantes.

### 3.3 Prise de Notes à la Volée

Les utilisateurs peuvent capturer des pensées ou questions spontanées par texte ou voix ("Comment dit-on ça en français ?"). Ces notes peuvent ensuite être traduites ou ajoutées à une playlist.

### 3.4 Intégration TTS (Texte-à-Parole)

Chaque phrase peut être lue avec un système TTS, avec choix de la langue, du fournisseur et du style vocal — essentiel pour la prononciation et la compréhension orale.

### 3.5 Suivi de Maîtrise et de Progression

Les utilisateurs suivent leur familiarité avec les contenus. Moins une phrase est répétée, plus elle est considérée comme "maîtrisée". Le système de progression inclut des badges, de l’XP, et des séries d’activités motivantes.

### 3.6 Fonctionnalités de Groupe pour Enseignants

Les enseignants peuvent :

- Créer des groupes  
- Inviter des élèves  
- Assigner des playlists  
- Suivre la progression (fonctionnalité à venir)  

Speakify devient ainsi un outil de classe numérique.

### 3.7 Connexion Sociale (Futur)

Des fonctionnalités de mise en relation sont prévues : en fonction des langues cibles, des localisations ou centres d’intérêt, les utilisateurs pourraient discuter, s’appeler ou se rencontrer pour pratiquer.

## 4. Structure Technique

L’architecture de Speakify repose sur un schéma relationnel optimisé pour la clarté et l’évolutivité.

- **Traductions** : `translation_pairs`, `sentences`, `languages`  
- **Personnalisation** : `schemas`, `user_settings`, `smart_lists`  
- **Audio** : `tts_audio`, `tts_providers`, `tts_voices`  
- **Progression** : `sentence_mastery`, `achievements`  
- **Collaboration** : `groups`, `group_members`, `group_playlists`  
- **Comportement Utilisateur** : `sessions`, `notes` (prévu), `favorites`  

Un MCD a été créé avec des cardinalités explicites et un zonage visuel.

## 5. Scalabilité & Développements Futurs

Les axes d’évolution prévus :

- Ajout de langues et de voix  
- Intégration d’outils IA supplémentaires  
- Développement des groupes et communautés  
- Notifications, gamification, planning intelligent  

Chaque module (TTS, IA, groupes, progression) peut évoluer indépendamment tout en restant intégré.

## 6. Calendrier de Développement

| Phase              | Période           | Livrables                                        |
|-------------------|-------------------|--------------------------------------------------|
| Idéation           | Mars 2025         | Définition du projet, choix technos              |
| Modélisation       | Fin mars 2025     | MCD, fichiers de schéma, config                  |
| Développement      | Début avril 2025  | Playback, logique UI, gestion des sessions       |
| Intégration TTS    | Mi-avril 2025     | API, fichiers audio                              |
| Listes & IA        | Mi-avril 2025     | Génération de contenu contextuel                 |
| Groupes            | Fin avril 2025    | Création de groupes, partage de playlists        |
| Finalisation       | 21–24 avril 2025  | Docs, MCD, soutenance                            |

## 7. Conclusion

Speakify réunit contenu intelligent, lecture audio interactive, contrôle personnalisé et outils collaboratifs dans une plateforme unique. Conçue pour grandir avec ses utilisateurs, Speakify dépasse le simple outil : c’est une véritable plateforme d’apprentissage vivant et évolutif.

---

**Table des matières**  
1. Présentation du Projet  
2. Objectifs & Innovations  
3. Fonctionnalités Clés  
   - 3.1 Playlists & Schémas  
   - 3.2 Listes intelligentes  
   - 3.3 Notes à la volée  
   - 3.4 TTS  
   - 3.5 Suivi de progression  
   - 3.6 Enseignants  
   - 3.7 Connexion sociale  
4. Structure Technique  
5. Scalabilité & Futur  
6. Calendrier  
7. Conclusion  
