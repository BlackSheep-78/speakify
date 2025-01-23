<?php

    require_once BASEPATH."/classes/Database.php";

    $result    = [];
    $sentences = [];
    $temp      = [];

    $db = new Database();
    $db->connect();
    $db->file("get_sentences.sql");
    $db->replace("STARTING_LANGUAGE_ID",39,'i');
    $rows = $db->result();

    foreach($rows as $key => $value)
    {
        $sid1 = $value['original_sentence_id'];

        $sentences[$sid1]["sentence"]["id"]   = $value['original_sentence_id'];
        $sentences[$sid1]["sentence"]["text"] = $value['original_sentence'];
        $sentences[$sid1]["language"]["id"]   = $value['original_language_id'];
        $sentences[$sid1]["language"]["name"] = $value['original_language'];
        $sentences[$sid1]["original"]         = true;

        $sid2 = $value['translated_sentence_id'];

        $sentences[$sid2]['pair']['id']       = $value['pair_id'];
        $sentences[$sid2]['sentence']['id']   = $value['translated_sentence_id'];
        $sentences[$sid2]['sentence']['text'] = $value['translated_sentence'];
        $sentences[$sid2]['language']['id']   = $value['translated_language_id'];
        $sentences[$sid2]['language']['name'] = $value['translated_language'];
        $sentences[$sid2]["original"]         = false;

        $temp[$sid1][$sid2] = $value['pair_id'];
    }

    $result['sentences']    = $sentences;
    $result['translations'] = $temp; 

    print json_encode($result);
?>