<?php 

return array(
    'secret_hash' => '<secret_hash>',   // Secret hash must match same secret hash in python server
    'host' => 'http://127.0.0.1:8080',  // IP and port 
    'id' => 1,                          // Instance ID, just leave 1 in standalone mode, and set 0 in automated hosting environment
    'database_prefix' => 'chatterbot-demo',
    'try_times' => 2,                    // How many times send request for the same message. As we are using random response and we want more than one suggestion so this simulates it.
    'ahosting' => false,                 // Are we in automated hosting environment
    'live_teach' => true,               // Operators suggested combinations should go live instantly
    'elastic_enabled' => false,
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
    )
);

?>