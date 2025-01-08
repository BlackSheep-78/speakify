<?php

    include_once("Database.php");
    include_once("GoogleTranslateAPi.php");

    class Translate
    {
        function getRandomPairOfLanguages()
        {
            $sql = "
                SELECT * FROM
                (
                    SELECT * 
                    FROM languages 
                    WHERE languages.`active` = 1
                    ORDER BY RAND()
                    LIMIT 2
                ) AS t1
                ORDER BY id ASC
            ";


            $db = new Database();
            $rows = $db->query($sql);
            return $rows;
        }

        function getOneForTranslation()
        {
            $sql = "
                SELECT 
                    s1.sentence_id AS sentence_id_1,
                    s1.sentence_text AS sentence_text_1,
                    l1.language_name AS language_name_1,
                    l1.language_code AS language_code_1,
                    l1.language_id AS language_id_1,
                    l3.language_name AS language_name_2,
                    l3.language_code AS language_code_2,
                    l3.language_id AS language_id_2
                FROM sentences s1
                JOIN languages l1 ON s1.language_id = l1.language_id
                JOIN languages l3 ON l3.language_active = 1  -- All active languages for missing translation
                WHERE l1.language_active = 1    -- Only consider active languages for the original sentence
                AND l3.language_id != l1.language_id  -- Ensure we are checking other languages for translation
                AND (
                    NOT EXISTS (  -- Original → Translation is missing
                        SELECT 1
                        FROM translation_pairs t1
                        JOIN sentences s2 ON t1.sentence_id_2 = s2.sentence_id
                        WHERE t1.sentence_id_1 = s1.sentence_id
                        AND s2.language_id = l3.language_id  -- Look for sentences in the target language
                    )
                    OR
                    NOT EXISTS (  -- Translation → Original is missing (check reverse direction)
                        SELECT 1
                        FROM translation_pairs t2
                        JOIN sentences s3 ON t2.sentence_id_1 = s3.sentence_id
                        WHERE t2.sentence_id_2 = s1.sentence_id
                        AND s3.language_id = l3.language_id  -- Look for reverse translation pair
                    )
                )
                ORDER BY s1.sentence_id
                LIMIT 1;
            ";

            $db = new Database('translate');
            $db->query($sql);
            $rows = $db->result();
            
            if(count($rows) == 0) { return []; }

            return $rows;
            
   
        
            /*
            $gta = new GoogleTranslateAPi();
            $result = $gta->translate('Hello, World!','en','es');
            print_r($result);
            */
        }

        public function connectToGoogleToTranslate()
        {
            $data = $this->getOneForTranslation();
            $data = $data[0];


            print "<pre>";
            print_r($data);
            print "</pre>";

            $GoogleTranslate = new GoogleTranslateApi();
            $sentence_text_2 = $GoogleTranslate->translate($data['sentence_text_1'],$data['language_code_1'],$data['language_code_2']);

            print "<pre>";
            print_r($sentence_text_2);
            print "</pre>";

            return;

            $db = new Database('translate');
            $db->replace("sentence_text_2",$sentence_text_2);
            $db->replace("language_id_2",$data['language_id_2']);
            $db->replace("sentence_id_1",$data['sentence_id_1']);
            $db->replace("translation_version",1);
            $db->replace("source_id",1);
            $db->file("insert_sentence_translation.sql");
            $rows = $db->result();
        }
    }

?>