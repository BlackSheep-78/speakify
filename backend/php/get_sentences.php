<?php

	// file: /speakify/backend/php/get_sentences.php

    require_once BASEPATH."/classes/Database.php";

    $temp   = [];
    $result = [];

    $db = new Database();
    $db->connect();
    $db->file("get_sentences.sql");
    $db->replace("STARTING_LANGUAGE_ID",39,'i');
    $rows = $db->result();



    foreach($rows as $key => $value)
    {
        $id_1 = $value['original_sentence_id'];

        $temp[$id_1]["original"]["sentence"]["id"]   = $value['original_sentence_id'];
        $temp[$id_1]["original"]["sentence"]["text"] = $value['original_sentence'];
        $temp[$id_1]["original"]["language"]["id"]   = $value['original_language_id'];
        $temp[$id_1]["original"]["language"]["name"] = $value['original_language'];

        $id_2 = $value['translated_sentence_id'];

        $temp[$id_1]["translation"][$id_2]['pair']['id']       = $value['pair_id'];
        $temp[$id_1]["translation"][$id_2]['sentence']['id']   = $value['translated_sentence_id'];
        $temp[$id_1]["translation"][$id_2]['sentence']['text'] = $value['translated_sentence'];
        $temp[$id_1]["translation"][$id_2]['language']['id']   = $value['translated_language_id'];
        $temp[$id_1]["translation"][$id_2]['language']['name'] = $value['translated_language'];
    }

    foreach($temp as $key => $value)
    {
        $result[] = $value;
    }



    print json_encode($result);
?>