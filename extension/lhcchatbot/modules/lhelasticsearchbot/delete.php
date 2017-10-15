<?php

if (!$currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    die('Invalid CSFR Token');
    exit;
}

try {
    $question = erLhcoreClassModelESChatbotQuestion::fetch($Params['user_parameters']['id']);
    erLhcoreClassExtensionLHCChatBotValidator::deleteProposedQuestion($question);

    erLhcoreClassModule::redirect('elasticsearchbot/list');
    exit;

} catch (Exception $e) {
    $tpl = erLhcoreClassTemplate::getInstance('lhkernel/validation_error.tpl.php');
    $tpl->set('errors',array($e->getMessage()));
    $Result['content'] = $tpl->fetch();
}