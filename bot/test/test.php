<?php 

include 'api.php';

$api = new LHCChatBot('http://127.0.0.1:8080',1,'<secret_hash>');

$context = 0;

$q = 'how old are you';

$a = $api->getAnswer($q, $context);

echo "Q:" . $q,"\n";
echo 'A:' . $a['msg'],"\n";

$q = 'how far are you';
$api->removeQuestion($q, $context);

$a = $api->getAnswer($q, $context);

if ($a['msg'] === 'like moon from earth') {
    throw new Exception('Answer should not exists!');
}

$q = 'how far are you';
$a = 'like moon from earth';

$r = $api->addQuestion($q, $a, $context);

$a = $api->getAnswer($q, $context);

if ($a['msg'] !== 'like moon from earth') {
    throw new Exception('Incorrect answer received!');
} else {
    echo "\nQ:" . $q,"\n";
    echo 'A:' . $a['msg'],"\n";
}

/**
 * Try with different context
 */

echo "\n**Trying different context**\n\n";

for ($i = 5; $i < 10; $i++) {
    $context = $i;
    
    $q = 'how old are you';
    
    $a = $api->getAnswer($q, $context);
    
    echo "Q:" . $q,"\n";
    echo 'A:' . $a['msg'],"\n";
    
    $q = 'how far are you';
    $api->removeQuestion($q, $context);
    
    $a = $api->getAnswer($q, $context);
    
    if ($a['msg'] === 'like moon from earth') {
        throw new Exception('Answer should not exists!');
    }
    
    $q = 'how far are you';
    $a = 'like moon from earth';
    
    $r = $api->addQuestion($q, $a, $context);
    
    $a = $api->getAnswer($q, $context);
    
    if ($a['msg'] !== 'like moon from earth') {
        throw new Exception('Incorrect answer received!');
    } else {
        echo "\nQ:" . $q,"\n";
        echo 'A:' . $a['msg'],"\n";
    }
}

?>