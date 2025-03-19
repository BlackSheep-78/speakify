- **File Name**: template.project.md
- **File Version**: 1.0.0

- **Project Name**: Translation Database System

- **Project Description**:
    - This project involves developing a translation database system that efficiently stores and manages translations. It will integrate with various language sources and tools to enable real-time translation lookups and contextual assistance. The project aims to provide a scalable, structured, and queryable translation database for organizations, AI systems, and translators.

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
    - Project directory: translation_database_system

- **Core Features & Objectives**:
    - **Frontend:**
      - Users can create, manage, and log into a personal account.
      - Users can execute a playlist.
      - Users can create playlists.
      - A playlist is composed of:
        - **Translation Blocks**:
          - A Translation Block (TB) contains a sentence in a language and its equivalent translations in zero or multiple other languages.
          - Each translation of the sentence will include an audio file in the same language.
      - Users can rearrange the schema of the Translation Blocks within each playlist.
        - **A Translation Block (TB)** is a structured unit designed to manage multilingual sentence playback with user-defined sequencing. Each TB contains a sentence in a specific language, an optional audio file, and zero or more translations, each with its own text and corresponding audio. The key feature of a TB is its customizable playback schema, which allows users to define the order in which each language version is played, the number of repetitions for each audio file, and the timing of subtitle displays. When a TB schema is assigned to a playlist—a collection of multiple TBs—the system cycles through the playlist, playing each TB according to the defined schema. This ensures a structured and controlled playback experience, making TBs ideal for language learning, multilingual audio guides, speech training, and interactive storytelling. By allowing full customization of playback behavior, TBs provide a powerful and flexible solution for managing multilingual content dynamically.

    - **Backend:**
      - Efficiently store and retrieve translations.
      - Support multiple languages and translation sources.
      - Enable bidirectional querying (Original → Translation and Translation → Original).
      - Implement version control for translations.
      - Handle missing translations with structured tracking.
      - Ensure scalability for future language expansions.

- **File Structure Overview**:
    ```
    C:\xampp\htdocs\translate\
      │
      ├── public/
      │   ├── index.html              ✅ Main UI (Player, Playlist, TB Schema Management)
      │   ├── css/
      │   │   ├── styles.css          ✅ UI Styling (Responsive Design)
      │   ├── js/
      │   │   ├── script.js           ✅ Handles AJAX, Playback, Playlist Management
      │   ├── data/
      │   │   ├── playlists.json      ✅ Simulated API Data (Sample Playlists & TBs)
      │
      ├── backend/
      │   ├── api.php                 ✅ Serves JSON Data to Frontend
      │
      ├── assets/
      │   ├── audio/                  ✅ (Placeholder for audio files)
      │
      ├── index.php                   ❌ **(Needs to be created - Main PHP entry point)**
      └── README.md                   ❌ **(Needs setup instructions & API documentation)**

    ```

- **Initial Tasks and Sub-Tasks** [marked with an 'x' if complete]:
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
    - [ ] Optimize performance and indexing strategies.
    - [ ] Implement unit and integration tests.
    - [ ] Document API or UI interactions.
    - [ ] Finalize for deployment.

- **Database Design & SQL Schema**:
    ```sql
    CREATE TABLE `languages` (
        `language_id` INT(11) NOT NULL AUTO_INCREMENT,
        `language_name` VARCHAR(100) NOT NULL,
        `language_code` VARCHAR(10) NOT NULL,
        `language_active` TINYINT(1) DEFAULT 1,
        PRIMARY KEY (`language_id`),
        UNIQUE INDEX `language_code` (`language_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE `sentences` (
        `sentence_id` INT(11) NOT NULL AUTO_INCREMENT,
        `sentence_text` TEXT NOT NULL,
        `language_id` INT(11) NOT NULL,
        `pair_id` INT(11) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
        PRIMARY KEY (`sentence_id`),
        FOREIGN KEY (`language_id`) REFERENCES `languages` (`language_id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ```

- **Query Examples**:
    ```sql
    -- Find translation pair for a sentence
    SELECT 
        CASE 
            WHEN tp.sentence_id_1 = s1.sentence_id THEN s2.sentence_text
            ELSE s1.sentence_text
        END AS translation
    FROM translation_pairs tp
    JOIN sentences s1 ON tp.sentence_id_1 = s1.sentence_id
    JOIN sentences s2 ON tp.sentence_id_2 = s2.sentence_id
    WHERE s1.sentence_text = 'Hello';
    ```

- **Planned Enhancements**:
    - Integration with external machine translation APIs.
    - Adding a review/approval process for translations.
    - Expanding metadata support (e.g., tone, formality).
    - Scaling to support millions of translations.
    - Enabling polyglot translation capabilities.
