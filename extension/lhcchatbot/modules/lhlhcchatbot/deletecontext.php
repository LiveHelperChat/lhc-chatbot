<?php

if (!$currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
	die('Invalid CSFR Token');
	exit;
}

try {
    $context = erLhcoreClassModelLHCChatBotContext::fetch( $Params['user_parameters']['id']);
    erLhcoreClassExtensionLHCChatBotValidator::deleteContext($context);
    
    erLhcoreClassModule::redirect('lhcchatbot/listcontext');
    exit;

} catch (Exception $e) {
    $tpl = erLhcoreClassTemplate::getInstance('lhkernel/validation_error.tpl.php');
    $tpl->set('errors',array($e->getMessage()));
    $Result['content'] = $tpl->fetch();
}

?>