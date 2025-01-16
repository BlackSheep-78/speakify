<?php

    require_once BASEPATH."/classes/Database.php";
    require_once BASEPATH."/classes/GoogleTranslateAPi.php";

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
            $db->query($sql);
            $rows = $db->result();

            return $rows;
        }

        function getOneForTranslation()
        {
            $db = new database();
            $db->connect();
            $db->file("get_one_for_translation.sql");
            $rows = $db->result();
            
            if(count($rows) == 0) { return []; }

            return $rows;
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

            $db = new Database();
            $db->connect();
            $db->file("insert_sentence_translation.sql");
            $db->replace("sentence_text_2",$sentence_text_2,"s");
            $db->replace("language_id_2",$data['language_id_2'],"i");
            $db->replace("sentence_id_1",$data['sentence_id_1'],"i");
            $db->replace("translation_version",1,"i");
            $db->replace("source_id",1,"i");
            $rows = $db->result();
        }
    }

?>