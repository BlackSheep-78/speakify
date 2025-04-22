<?php

    // File : /backend/classes/logic/Translate.php

    class Translate
    {
        function getRandomPairOfLanguages(): array
        {
            $db = Database::init();
            return $db->file('/translate/get_random_pair.sql')->result();
        }

        function getOneForTranslation(): array
        {
            $db = Database::init();
            $rows = $db->file('/translate/get_one_for_translation.sql')->result();
        
            return $rows[0] ?? [];
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
            $db->file("/sentences/insert_sentence_translation.sql");
            $db->replace("sentence_text_2",$sentence_text_2,"s");
            $db->replace("language_id_2",$data['language_id_2'],"i");
            $db->replace("sentence_id_1",$data['sentence_id_1'],"i");
            $db->replace("translation_version",1,"i");
            $db->replace("source_id",1,"i");
            $rows = $db->result();
        }
    }

?>