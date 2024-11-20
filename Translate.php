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
            $pair = $this->getRandomPairOfLanguages();

            //print_r($pair);

            $langID = $pair[0]['id'];

            $sql = "
                SELECT 
                    sa.id sentenceIDA, 
                    sa.languageID languageIDA,
                    sa.text textA,
                    l.`code` languageCode
                FROM sentences AS sa
                LEFT JOIN sentence_pairs AS sp 
                ON sa.id = sp.sentenceIDA AND sa.languageID = sp.languageIDA
                LEFT JOIN languages AS l ON l.id = ".$langID."
                WHERE sa.languageID = ".$langID." AND sp.sentenceIDB IS NULL
                LIMIT 1
            ";

            $db = new Database();
            $rows = $db->query($sql);
            
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

            print_r($data);
        }
    }

?>