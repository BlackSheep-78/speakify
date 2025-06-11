### [Readme](https://github.com/BlackSheep-78/speakify/blob/main/README.md)

- description : results and checklist for second defence.

- bloc 1
    - [x] Cahier des charges 
    - [x] Présenter au client interne/externe ou “product owner” le dossier de spécifications techniques pour validation et le planning
    - [x] Formaliser les spécifications de la solution web afin de répondre à la problématique du client interne/externe ou “product owner” en espectant les normes et les standards du web, en particulier de sécurité et d’accessibilité pour les personnes en situation de handicap
    - [ ] Recreate MCD with new coming tables.
    - [ ] Rewrite the Cahier des Charges (CDC) with detailed explanations, not just listings.
    - [ ] Include examples or scenarios for each major functional requirement in the CDC.
    - [ ] Prepare the new MCD including all additional tables and their relationships.
    - [ ] Ensure MCD readability and accessibility for public presentation (use color, spacing, labels).

    ## Jury Feedback from First Soutenance (archived)
        - Feedback : CDC basé sur du listing dépourvu d’explications. Votre CDC était particulièrement difficile à comprendre car vous avez principalement énoncé des éléments (sous forme de liste) sans jamais détailler les points. Votre CDC manque fortement d’explications, de précisions, et de développement. Ne vous contentez pas de lister des points importants. Développez-les ! Prévoyez votre MCD pour la soutenance. Courage !
        - Sccore : 10,29/20

- bloc 2
    - [ ] Analyser conceptuellement la maquette (images, vidéos, etc.) pour la découper en blocs sémantiques puis la traduire en HTML.
    - [ ] Développer une page web et mettre en place la structure HTML (balises sémantiques et génériques) pour intégrer la maquette.
    - [x] Importer des “actifs” (feuilles de style, images, vidéos, fichiers audio, scripts) pour dynamiser la page ou le site (éco-conception).
    - [ ] Manipuler la typographie (corps, graisse, import de polices, etc.) pour mettre en forme les textes et respecter les normes d’accessibilité du site pour les personnes en situation de handicap.
    - [ ] Ordonner l’agencement des blocs et mettre en œuvre la philosophie mobile first (media queries) afin d’assurer une adaptation de l’affichage aux diverses tailles d’écran.
    - [x] Structurer le code en utilisant des préprocesseurs CSS pour faciliter le travail afin d’améliorer l’écriture des fichiers et l'éco-conception.
    - [x] Manipuler la page web pour interagir avec l’utilisateur.
    - [x] Développer des requêtes HTTP asynchrones pour communiquer avec un serveur de manière fluide et transparente.
    - [x] Développer des requêtes HTTP asynchrones pour communiquer avec un serveur de manière fluide et transparente.
    - [ ] Ajouter les balises sémantiques manquantes : <header>, <main>, <footer>, <section>, etc.
    - [ ] Créer une ou plusieurs maquettes visuelles (images, wireframes ou screenshots annotés).
    - [ ] Implémenter une vraie approche responsive mobile-first (au moins 3 points de rupture).
    - [ ] Ajouter des éléments d’accessibilité : texte alternatif, ordre de tabulation, aria-labels.
    - [ ] Utiliser des balises ARIA et vérifier la compatibilité avec les lecteurs d’écran.

    ## Jury Feedback from First Soutenance (archived)
        - Feedback : Absence d'éléments essentiels à la validation : les maquettes et le préprocesseur. De plus, le responsive n'est pas géré, juste une media query pour les petits formats. La sémantique semble connue mais pas utilisée, il n'y a pas les balises basiques telles que <footer> et <header>. L'accessibilité est à améliorer. Les côtés positifs sont la réflexion sur l'UX et l'utilisation de l'application à une seule main, ainsi que la partie JavaScript qui semble maîtrisée.
        - Score : 7,5/20

- bloc 3
    - [x] Modéliser une base de données optimisée répondant aux spécifications techniques et fonctionnelles afin de stocker les données, d’y accéder de manière sécurisée et de limiter la redondance des données dans un souci d’éco-conception.
    - [x] Composer des requêtes HTTP (POST, GET) efficaces via formulaires ou appels d’URL pour gérer les interactions entre l’utilisateur et le serveur et optimiser les performances d’accès et la sécurité.
    - [x] Concevoir une application en mobilisant des modèles de programmation moderne (tels que le Modèle-Vue-Contrôleur) afin de répondre aux besoins fonctionnels de l’application.
    - [x] Développer le code source en suivant les règles de nommage et les bonnes pratiques afin d’optimiser le site et de réduire l’empreinte carbone et d’améliorer l’accessibilité aux personnes en situation de handicap.
    - [ ] Construire un backoffice complet afin de faciliter l’administration de l’application web, en tenant compte des éventuelles situations de handicap des équipes qui utilisent le back office (typographie, accessibilité…).
    - [x] Déployer les techniques de sécurité (authentification, “hash” des mots de passe via des algorithmes récents et éprouvés) pour permettre l’inscription, la connexion/déconnexion de manière sécurisée.
    - [x] Évaluer son propre code et les fonctionnalités du projet pour s’assurer de son bon fonctionnement et mettre en place le cas échéant des solutions correctives.
    - [ ] Établir la maintenance de l’application (suivi des mises à jour et documentation) afin de permettre le maintien dans le temps de l’application par l’équipe, en tenant compte des éventuelles situations de handicap des équipes qui utilisent le back office (typographie, accessibilité…).
    - [ ] Proposer le cas échéant, en réponse à des situations imprévues, des solutions correctives afin d’assurer la continuité du site ou de l’application et les transmettre à l’équipe.
    - [ ] Proposer le cas échéant, en réponse à des situations imprévues, des solutions correctives afin d’assurer la continuité du site ou de l’application et les transmettre à l’équipe.
    - [ ] Protéger l’espace administrateur avec vérification de session utilisateur. 
    - [x] Centraliser toutes les requêtes SQL dans les modèles (pas de SQL dans les contrôleurs).
    - [x] Sécuriser toutes les entrées utilisateurs contre les failles XSS (htmlspecialchars ou équivalent).
    - [x] Respecter une convention de nommage uniforme dans tout le projet (camelCase, snake_case, etc.).
    - [ ] Terminer le backoffice avec au moins 2 écrans fonctionnels (gestion, édition, etc.).
    - [ ] Rédiger une documentation de maintenance (format Markdown ou PDF).
    - [ ] Préparer une checklist de tests à suivre avant toute mise en production.

    ## Jury Feedback from First Soutenance (archived)
        - Feedback : L'application n'est pas encore fonctionnelle, l'espace administrateur ne peut être testé. Il n'y a pas de protection contre la faille XSS et l'architecture n'est pas optimale avec notamment des requêtes SQL un peu partout, un code un peu désorganisé. Les conventions de nommage sont peu respectées. La page admin est accessible sans authentification. Ce qui est positif est une bonne utilisation de la programmation orientée objet et l'utilisation de GIT pour le versionning. Jorge a brièvement évoqué les axes d'améliorations futures.
        - Score : 6,67/20

- Commentaire général : Un projet qui a de l'avenir mais qui n'est pas abouti avec encore trop de code en construction.
