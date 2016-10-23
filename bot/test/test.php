<?php 

include 'api.php';

$api = new LHCChatBot('http://127.0.0.1:8080',1,'<secret_hash>');

$q = 'how old are you';

$a = $api->getAnswer($q);

echo "Q:" . $q,"\n";
echo 'A:' . $a['msg'],"\n";

$q = 'how far are you';
$api->removeQuestion($q);

$a = $api->getAnswer($q);

if ($a['msg'] === 'like moon from earth') {
    throw new Exception('Answer should not exists!');
}

$q = 'how far are you';
$a = 'like moon from earth';

$r = $api->addQuestion($q, $a);

$a = $api->getAnswer($q);

if ($a['msg'] !== 'like moon from earth') {
    throw new Exception('Incorrect answer received!');
} else {
    echo "\nQ:" . $q,"\n";
    echo 'A:' . $a['msg'],"\n";
}



?>