<?php

if ((!isset($_GET['secret_hash']) || erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['secret_hash'] != $_GET['secret_hash'])){
    http_response_code(404);
    exit;
}

$lhcchatboOptions = erLhcoreClassModelChatConfig::fetch('lhcchatbot_rasa_status');
$data = (array)$lhcchatboOptions->data;
$data['date'] = time();
$data['outcome'] = isset($_GET['success']) && $_GET['success'] == 1;

$lhcchatboOptions->explain = '';
$lhcchatboOptions->type = 0;
$lhcchatboOptions->hidden = 1;
$lhcchatboOptions->identifier = 'lhcchatbot_rasa_status';
$lhcchatboOptions->value = serialize($data);
$lhcchatboOptions->saveThis();

exit;