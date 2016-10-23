<?php 

return array(
    'secret_hash' => '<secret_hash>',   // Secret hash must match same secret hash in python server
    'host' => 'http://127.0.0.1:8080',  // IP and port 
    'id' => 1,                          // Instance ID, just leave 1 in standalone mode, and set 0 in automated hosting environment
    'ahosting' => false                 // Are we in automated hosting environment
);

?>