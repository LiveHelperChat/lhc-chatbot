<?php 

$tpl = erLhcoreClassTemplate::getInstance('lhcchatbot/test.tpl.php');

if (isset($_GET['question'])) {
    try {
        // Test bot
        $context = erLhcoreClassModelLHCChatBotContext::fetch((int)$_GET['context_id']);

        if ($context instanceof erLhcoreClassModelLHCChatBotContext) {
            $tpl->set('answer', erLhcoreClassExtensionLHCChatBotValidator::getAnswer($context->host, $_GET['question'], true));
        } else {
            $tpl->set('answer', 'Invalid context');
        }

    } catch (Exception $e) {
        $tpl->set('answer', $e->getMessage());
    }
}

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','ChatBot')
    ),
    array (       
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Test')
    )
);

?>