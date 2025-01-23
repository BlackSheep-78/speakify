<?php

    require_once BASEPATH."/classes/Database.php";

    $result       = [];
    $sentences    = [];
    $temp_sent    = [];
    $translations = [];

    $db = new Database();
    $db->connect();
    $db->file("get_sentences.sql");
    $db->replace("STARTING_LANGUAGE_ID",39,'i');
    $rows = $db->result();

    foreach($rows as $key => $value)
    {
        $sid1 = $value['original_sentence_id'];

        if(!isset($temp_sent[$sid1]))
        {
            $sentence = [];

            $sentence["sentence"]["id"]   = $value['original_sentence_id'];
            $sentence["sentence"]["text"] = $value['original_sentence'];
            $sentence["language"]["id"]   = $value['original_language_id'];
            $sentence["language"]["name"] = $value['original_language'];
            $sentence["original"]         = true;

            $sentences[] = $sentence;

            $temp_sent[$sid1] = true;
        }

        $sid2 = $value['translated_sentence_id'];

        if(!isset($temp_sent[$sid2]))
        {
            $sentence = [];

            $sentence['pair']['id']       = $value['pair_id'];
            $sentence['sentence']['id']   = $value['translated_sentence_id'];
            $sentence['sentence']['text'] = $value['translated_sentence'];
            $sentence['language']['id']   = $value['translated_language_id'];
            $sentence['language']['name'] = $value['translated_language'];
            $sentence["original"]         = false;

            $sentences[] = $sentence;
            
            $temp_sent[$sid2] = true;
        }

        $translation = [];
        $translation['id1']  = $sid1;
        $translation['id2']  = $sid2;
        $translation['pair'] = $value['pair_id'];

        $translations[] = $translation;
    }

    $result['sentences']            = $sentences;
    $result['translation']['pairs'] = $translations; 

    print json_encode($result);
?>