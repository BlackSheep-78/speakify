
1. Introduction
    - Purpose: Briefly explain the purpose of your project. For example:
        - Why are you building a translation database?
        - Who are the users (translators, organizations, AI systems, etc.)?
        - What problem does it solve?
    - Scope: Define the scope of the project, outlining what the system will (and will not) cover.

2. System Overview
    - Objective: Summarize the goals of the system, such as:
        - Efficiently storing translations.
        - Supporting multiple languages and sources.
        - Querying translations both ways (Original → Translation and Translation → Original).
    - Key Features:** Highlight major features, such as:
        - Language and source management.
        - Tracking translation versions.
        - Handling missing translations.
        - Scalability for adding new languages and sentences.

3. Database Design
    - ER Diagram (Entity-Relationship Diagram): Visual representation of the database schema, showing tables, relationships, and foreign keys.
    - Table Descriptions: Provide a detailed explanation of each table and its purpose, such as:
        - languages: Stores supported languages.
        - sentences: Stores the text of sentences and their associated languages.
        - translation_pairs: Links two sentences as translations of each other.
        - sources: Tracks the sources of translations.
        - translation_pair_sources: Links translation pairs to sources.

4. SQL Schema
    - Include the full SQL schema for creating the database tables.
    - Highlight:
        - Primary and foreign keys.
        - Indexes for performance optimization.
        - Default values and constraints.

5. Functional Requirements
    - CRUD Operations:
        - Create, Read, Update, Delete for each table.
    - Sample Queries:
        - Provide examples of important queries, such as:
            - Find a translation for a sentence.
            - List all sentences without translations.
            - Retrieve all translations linked to a specific source.
    - User Interactions: Explain how users will interact with the system (e.g., through an interface or API).

6. Query Examples
    - Basic Queries: Examples for retrieving data, such as:
        - Get all active languages.
        - Find all sentences in a specific language.
    - Complex Queries:
        - Find sentences missing translations.
        - Track translations by source.
        - Retrieve the latest translation version for a pair.

7. System Features and Enhancements
    - Planned Features:
        - Support for additional metadata (e.g., tone or formality of translations).
        - Integration with external tools (e.g., APIs for machine translation).
        - Adding a review or approval process for translations.
    - Future Scalability:
        - Support for larger datasets (e.g., millions of translations).
        - Handling polyglot translations (one sentence translated into multiple languages).

8. Challenges and Considerations
    - Data Integrity:
        - How to ensure translations are accurate and consistent.
    - Performance:
        - Optimizing for fast queries on large datasets.
    - Version Control:
        - Managing multiple versions of the same translation.
    - Redundancy:
        - Avoiding duplicate sentences or translation pairs.

9. Testing
    - Unit Tests: Describe how individual components are tested (e.g., validating database operations).
    - Integration Tests: Test the entire system to ensure different parts (e.g., tables and queries) work together.
    - Sample Data: Provide test data to demonstrate how the system works.

10. Conclusion
    - Summarize the project’s achievements and functionality.
    - Mention how it can be expanded or improved.

11. Appendix
    - Glossary: Define terms used in the project (e.g., "translation pair," "source").
    - References: List any resources, tools, or frameworks used.
    - Changelog: Track changes to the project over time.

12. MySQL Tables

Languages Table (Stores Language Details)

CREATE TABLE `languages` (
    `language_id` INT(11) NOT NULL AUTO_INCREMENT,
    `language_name` VARCHAR(100) NOT NULL,
    `language_code` VARCHAR(10) NOT NULL,
    `language_active` TINYINT(1) DEFAULT 1,  -- Active or Inactive language
    PRIMARY KEY (`language_id`),
    UNIQUE INDEX `language_code` (`language_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


Sentences Table (Stores Sentences in Multiple Languages)

CREATE TABLE `sentences` (
    `sentence_id` INT(11) NOT NULL AUTO_INCREMENT,
    `sentence_text` TEXT NOT NULL,
    `language_id` INT(11) NOT NULL,
    `pair_id` INT(11) NOT NULL,  -- The ID of the translation pair
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (`sentence_id`),
    INDEX `language_id` (`language_id`),
    INDEX `idx_pair_id` (`pair_id`),
    FOREIGN KEY (`language_id`) REFERENCES `languages` (`language_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

Translation Pairs Table (Stores Translation Relationships)

CREATE TABLE `translation_pairs` (
    `pair_id` INT(11) NOT NULL AUTO_INCREMENT,
    `sentence_id_1` INT(11) NOT NULL,  -- First sentence (original)
    `sentence_id_2` INT(11) NOT NULL,  -- Second sentence (translated)
    `translation_version` INT(11) NOT NULL DEFAULT 1,  -- Version of the translation, to allow multiple translations for the same pair
    `source_id` INT(11) NOT NULL,  -- Link to the source for this translation
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (`pair_id`),
    INDEX `sentence_id_1` (`sentence_id_1`),
    INDEX `sentence_id_2` (`sentence_id_2`),
    FOREIGN KEY (`sentence_id_1`) REFERENCES `sentences` (`sentence_id`) ON DELETE CASCADE,
    FOREIGN KEY (`sentence_id_2`) REFERENCES `sentences` (`sentence_id`) ON DELETE CASCADE,
    FOREIGN KEY (`source_id`) REFERENCES `sources` (`source_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

Sources Table (Stores Translation Sources)

CREATE TABLE `sources` (
    `source_id` INT(11) NOT NULL AUTO_INCREMENT,
    `source_name` VARCHAR(255) NOT NULL,
    `source_description` TEXT NULL,
    `source_url` VARCHAR(2083) NULL,  -- URL of the source
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

Translation Pair Sources Table (Linking Translation Pairs and Sources)

CREATE TABLE `translation_pair_sources` (
    `pair_id` INT(11) NOT NULL,
    `source_id` INT(11) NOT NULL,
    `added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`pair_id`, `source_id`),
    FOREIGN KEY (`pair_id`) REFERENCES `translation_pairs` (`pair_id`) ON DELETE CASCADE,
    FOREIGN KEY (`source_id`) REFERENCES `sources` (`source_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

13. MySQL Queries

Find Translation Pair for a Sentence in Any Language

SELECT 
    CASE 
        WHEN tp.sentence_id_1 = s1.sentence_id THEN s2.sentence_text
        ELSE s1.sentence_text
    END AS translation,
    l1.language_name AS language_name_1,
    l2.language_name AS language_name_2
FROM translation_pairs tp
JOIN sentences s1 ON tp.sentence_id_1 = s1.sentence_id
JOIN sentences s2 ON tp.sentence_id_2 = s2.sentence_id
JOIN languages l1 ON s1.language_id = l1.language_id
JOIN languages l2 ON s2.language_id = l2.language_id
WHERE s1.sentence_text = 'Hello';

Find Missing Translations (Original → Translation or Reverse)

SELECT 
    s1.sentence_id AS sentence_id_1,
    s1.sentence_text AS sentence_text_1,
    l1.language_name AS language_name_1,
    l3.language_name AS language_name_2
FROM sentences s1
JOIN languages l1 ON s1.language_id = l1.language_id
JOIN languages l3 ON l3.language_active = 1
WHERE l1.language_active = 1
AND l3.language_id != l1.language_id
AND (
    NOT EXISTS (
        SELECT 1
        FROM translation_pairs t1
        JOIN sentences s2 ON t1.sentence_id_2 = s2.sentence_id
        WHERE t1.sentence_id_1 = s1.sentence_id
        AND s2.language_id = l3.language_id
    )
    OR 
    NOT EXISTS (
        SELECT 1
        FROM translation_pairs t2
        JOIN sentences s3 ON t2.sentence_id_1 = s3.sentence_id
        WHERE t2.sentence_id_2 = s1.sentence_id
        AND s3.language_id = l3.language_id
    )
)
ORDER BY s1.sentence_id
LIMIT 1;

Find All Translation Pairs Linked to a Source

SELECT 
    tp.pair_id,
    s1.sentence_text AS sentence_1,
    s2.sentence_text AS sentence_2
FROM translation_pair_sources tps
JOIN translation_pairs tp ON tps.pair_id = tp.pair_id
JOIN sentences s1 ON tp.sentence_id_1 = s1.sentence_id
JOIN sentences s2 ON tp.sentence_id_2 = s2.sentence_id
WHERE tps.source_id = 1;