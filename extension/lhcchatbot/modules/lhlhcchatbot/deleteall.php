<?php

if (!$currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    die('Invalid CSFR Token');
    exit;
}

try {
    $question = erLhcoreClassModelLHCChatBotInvalid::fetch($Params['user_parameters']['id']);

    erLhcoreClassExtensionLHCChatBotValidator::deleteReport($question);

    $question->removeThis();

    erLhcoreClassModule::redirect('lhcchatbot/invalid');
    exit;

} catch (Exception $e) {
    $tpl = erLhcoreClassTemplate::getInstance('lhkernel/validation_error.tpl.php');
    $tpl->set('errors',array($e->getMessage()));
    $Result['content'] = $tpl->fetch();
}

?>