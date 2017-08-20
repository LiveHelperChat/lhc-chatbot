<?php

$tpl = erLhcoreClassTemplate::getInstance('elasticsearchbot/elastic.tpl.php');

$command = isset($_POST['Query']) ? $_POST['Query'] : '';
$index = isset($_POST['Index']) ? $_POST['Index'] : 'chatbot';
$type = isset($_POST['Type']) ? $_POST['Type'] : 'lh_chatbot_answer';
$response = '';

if (isset($_POST['doSearch']))
{
    $sessionElasticStatistic = erLhcoreClassModelESChatbotQuestion::getSession();
    
    $sparams = array();
    
    $sparams['body'] = json_decode($_POST['Query'], true);
    
    // Index
    $sparams['index'] = $index;
    
    // Type
    $sparams['type'] = $type;
    
    // Client
    $esSearchHandler = erLhcoreClassElasticChatbotClient::getHandler();
    
    try {
        // Statistic
        $response = $esSearchHandler->search($sparams);
    } catch (Exception $e) {
        $response = $e;
    }
}

$tpl->set('command',$command);
$tpl->set('index',$index);
$tpl->set('type',$type);
$tpl->set('response',$response);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
	array('url' => erLhcoreClassDesign::baseurl('lhcchatbot/index'), 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','ChatBot')),
	array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('elasticsearch/index','Elastic Search Console'))
);

?>