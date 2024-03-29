<?php 

return array(
    'enabled' => true,
    'id' => 1,                          // Instance ID, just leave 1 in standalone mode, and set 0 in automated hosting environment
    'try_times' => 2,                   // How many times send request for the same message. As we are using random response and we want more than one suggestion so this simulates it.
    'ahosting' => false,                // Are we in automated hosting environment
    'live_teach' => true,               // Operators suggested combinations should go live instantly
    'elastic_enabled' => false,
    'ahosting_settings' => array(
        'msearch_answer_host' => 'http://localhost:7700/',
        'public_answer_key' => '',
    ),
    'secret_hash' => 'secret_hash_to_download_intent',
    'elastic_settings' => array (
        'host' 			=> 'localhost',//'localhost',
        'port' 			=> '9200',
        'index' 		=> 'chatbot',
        'additional_indexes' => array(
        ),
        'min_score_question' => 6,
        'min_score_answer' => 4,
        'use_iam' => false,
        'iam_region' => 'eu-central-1',
        'iam_credentials' => array(
            'access_key' => '',
            'secret_key' => ''
        )
    ),
    'template_settings' => array(
        'event_template' => 'TemplateButton',
        'trigger_template' => 'TemplateButton',
        'bot_id' => 1,
        'trigger_group_id' => 1,
    )
);

?>