
Translation Database Project: Description and Overview

This project involves building a database to store translations from multiple languages. 
The database needs to handle sentence pairs in different languages, allow querying translation pairs both ways, and provide detailed management of languages and sources.


Project MySql Tables:

    languages:                
        Contains details about the languages supported by the system.

    sentences:                
        Contains sentences in different languages along with their associated language ID.

    translation_pairs:        
        Contains translation pairs that link two sentences as being translations of each other.

    sources:                  
        Stores information about the sources used for the translations.

    translation_pair_sources: 
        Links translation pairs to their sources.

Query Requirements:

    - The translation pairs should be queried both ways (Original → Translation, and Translation → Original).
    - The system should be able to track the sources of translations.
    - Store information about sources used for the translations.
    - Link the translation pairs with their sources.



Sample Queries:

    Find Translation Pair for a Sentence in Any Language: Given an input sentence, find its translation in a target language.
    Find Missing Translations: Find sentences that are missing translations in either direction (Original → Translation or Translation → Original).
    Find All Translation Pairs Linked to a Source: Retrieve all translation pairs linked to a particular translation source.


Translation Database Project: SQL Schema and Queries

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

Sample Queries

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

