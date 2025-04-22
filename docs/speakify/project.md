- **File Name**: project.md  
- **File Version**: 1.0.1  

- **Project Name**: Speakify  

- **Project Description**:  
    - Speakify is an interactive language learning platform that allows users to engage with multilingual audio content through structured playlists. It enables users to create and manage custom translation-based audio experiences, making language acquisition more immersive and effective. Speakify is designed to work across mobile, desktop, and TV screens, ensuring seamless accessibility.

- **Engagement Protocols**:  
    - **Note**: All project file content must adhere to the formatting guidelines below to ensure consistency, clarity, and copy/paste friendliness.  
    - File and Naming Conventions:
        - Every file must include the project name and file name at the top.
        - The file name and project name must always be present in every file.
    - Code Block Standards:
        - Code blocks should be clearly marked with a starting comment and an ending comment.
        - All block identifiers must be preserved to ensure clarity and consistency.
        - All code snippets must be provided in plain text within well-formatted code blocks that allow for direct copy and paste.
    - Content Quality and Consistency:
        - All spelling and grammatical errors must be corrected.
        - File content, including code, should always be rendered in a format that is easy to copy and paste.
        - Maintain a consistent formatting style across all files.
    - Task Development and Tracking:
        - Tasks will be developed, tracked, and regularly updated to monitor progress.
        - Each task should be explicitly linked to one or more project goals to ensure alignment.
        - The task list should be reviewed periodically to confirm all high-level goals are represented and to suggest the next steps based on progress.
    - Process and Next Steps:
        - After completing a milestone, review the project goals and adjust the task list accordingly.
        - The system will suggest the next step based on current progress and goal alignment.
        - New tasks should always be derived from and directly aligned with the established project goals.
    - Collaboration and Communication:
        - Regular updates and feedback loops should be maintained to ensure everyone is aware of progress and upcoming priorities.
        - The protocols encourage clear communication, constructive feedback, and ongoing adjustments to keep the project on track.

- **Project Data**:
    - Project directory: speakify

- **Core Features & Objectives**:

    - **Frontend:**
        - Users can create, manage, and log into a personal account.
        - Users can create and manage learning **sessions**, whether logged in or anonymous.
        - The app should detect if a user is logged in or not and adapt the UI accordingly.
        - Users can execute a playlist for language learning.
        - Users can create and edit custom playlists.
        - A playlist is composed of:
            - **Translation Blocks (TBs)**:
                - A Translation Block contains a sentence in a language and its equivalent translations in zero or multiple other languages.
                - Each translation of the sentence will include an audio file in the same language.
        - Users can rearrange the schema of the Translation Blocks within each playlist.

        - **A Translation Block (TB)** is a structured unit designed to manage multilingual sentence playback with user-defined sequencing. Each TB contains a sentence in a specific language, an optional audio file, and zero or more translations, each with its own text and corresponding audio. The key feature of a TB is its customizable playback schema, which allows users to define the order in which each language version is played, the number of repetitions for each audio file, and the timing of subtitle displays. When a TB schema is assigned to a playlist—a collection of multiple TBs—the system cycles through the playlist, playing each TB according to the defined schema. This ensures a structured and controlled playback experience, making TBs ideal for language learning, multilingual audio guides, speech training, and interactive storytelling. By allowing full customization of playback behavior, TBs provide a powerful and flexible solution for managing multilingual content dynamically.

    - **Backend:**
        - Efficiently store and retrieve translations.
        - Support user session creation and login status detection.
        - Handle both logged-in and anonymous users via session tokens or cookies.
        - Support multiple languages and translation sources.
        - Enable bidirectional querying (Original → Translation and Translation → Original).
        - Implement version control for translations.
        - Handle missing translations with structured tracking.
        - Ensure scalability for future language expansions.


- **File Structure Overview**:
    ```
    speakify/
    ├── backend/
    │   ├── actions/
    │   ├── classes/
    │   ├── php/
    │   ├── sql/
    │   │   └── schema/
    │   └── utils/
    ├── config.php
    ├── config.json
    ├── docs/
    │   ├── images/
    │   ├── statement_of_work.en.md
    │   ├── statement_of_work.fr.md
    │   ├── technical_specification_document.en.md
    │   ├── technical_specification_document.fr.md
    │   ├── project.md
    │   ├── model.project.md
    │   └── navigation_logic.md
    ├── public/
    │   ├── api/
    │   ├── assets/
    │   │   ├── audio/
    │   │   ├── flags/
    │   │   └── icons/
    │   ├── css/
    │   ├── js/
    │   │   └── libs/
    │   ├── html/
    │   │   └── template/
    │   ├── index.html
    │   ├── index.php
    │   ├── dashboard.html
    │   ├── login-profile.html
    │   ├── offline-mode.html
    │   ├── playback.html
    │   ├── playlist-editor.html
    │   ├── playlist-library.html
    │   ├── schema-editor.html
    │   ├── settings.html
    │   └── smart-lists.html
    ├── resources/
    │   ├── public_/
    │   │   ├── css/
    │   │   ├── data/
    │   │   └── js/
    │   ├── stuff/
    │   ├── wireframes/
    │   ├── maquet.png
    │   ├── maquet.webp
    │   └── dependencies.json
    ├── file_structure.json
    ├── generate_file_structure.sh
    ├── README.md
    └── index.php
    ```

### ✅ Updated Initial Tasks and Sub-Tasks [marked with an 'x' if complete]:

- [ ] Design and document the database schema.  
    - [ ] Create an ER diagram showing relationships.  
    - [ ] Define tables and constraints.  

- [ ] Implement SQL schema.  
    - [ ] Create `languages` table.  
    - [ ] Create `sentences` table.  
    - [ ] Create `translation_pairs` table.  
    - [ ] Create `sources` table.  
    - [ ] Create `translation_pair_sources` table.  

- [ ] Develop and test CRUD operations.  
    - [ ] Implement queries to retrieve translations.  
    - [ ] Implement queries for missing translations.  

- [ ] User session management  
    - [ ] Allow user to **create a session** (anonymous or logged in).  
    - [ ] Detect if user is **logged in or not**.  
    - [ ] Redirect or adapt views based on login status.  

- [ ] Optimize performance and indexing strategies.  
- [ ] Implement unit and integration tests.  
- [ ] Document API or UI interactions.  
- [ ] Finalize for deployment.


- **Planned Enhancements**:
    - Integration with external machine translation APIs.
    - Adding a review/approval process for translations.
    - Expanding metadata support (e.g., tone, formality).
    - Scaling to support millions of translations.
    - Enabling polyglot translation capabilities.
