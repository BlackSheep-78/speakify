<?php

    require_once BASEPATH."/classes/Database.php";

    $result    = [];
    $sentences = [];
    $tmp_sent  = [];
    $tmp_pair  = [];

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

        $sentences[$sid1]['translation'][$sid2]['pair']['id']       = $value['pair_id'];
        $sentences[$sid1]['translation'][$sid2]['sentence']['id']   = $value['translated_sentence_id'];
        $sentences[$sid1]['translation'][$sid2]['sentence']['text'] = $value['translated_sentence'];
        $sentences[$sid1]['translation'][$sid2]['language']['id']   = $value['translated_language_id'];
        $sentences[$sid1]['translation'][$sid2]['language']['name'] = $value['translated_language'];
        $sentences[$sid1]['translation'][$sid2]["original"]         = false;
    }

    $result['sentences'] = $sentences; 

    print json_encode($result);
?>