-- Languages table
CREATE TABLE languages (
    language_id INT AUTO_INCREMENT PRIMARY KEY,
    language_name VARCHAR(100) NOT NULL,
    language_code VARCHAR(10) NOT NULL UNIQUE,
    language_active BOOLEAN DEFAULT TRUE
);

-- Sentences table
CREATE TABLE sentences (
    sentence_id INT AUTO_INCREMENT PRIMARY KEY,
    sentence_text TEXT NOT NULL,
    language_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (language_id) REFERENCES languages(language_id)
);

-- Translation pairs table
CREATE TABLE translation_pairs (
    pair_id INT AUTO_INCREMENT PRIMARY KEY,
    sentence_id_1 INT NOT NULL,
    sentence_id_2 INT NOT NULL,
    translation_version INT DEFAULT 1,
    source_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sentence_id_1) REFERENCES sentences(sentence_id),
    FOREIGN KEY (sentence_id_2) REFERENCES sentences(sentence_id)
);

-- Sources table (optional but referenced in insert script)
CREATE TABLE sources (
    source_id INT AUTO_INCREMENT PRIMARY KEY,
    source_name VARCHAR(255),
    source_url TEXT
);

-- Translation pair → source linking table
CREATE TABLE translation_pair_sources (
    pair_id INT NOT NULL,
    source_id INT NOT NULL,
    PRIMARY KEY (pair_id, source_id),
    FOREIGN KEY (pair_id) REFERENCES translation_pairs(pair_id),
    FOREIGN KEY (source_id) REFERENCES sources(source_id)
);

-- Sample table for resets
CREATE TABLE sentences_start_sample (
    sentence_text TEXT NOT NULL,
    language_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
