<?php 

$tpl = erLhcoreClassTemplate::getInstance('lhcchatbot/test.tpl.php');

if (isset($_GET['question'])) {
    try {
        // Test bot
        $api = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->getApi();
        $tpl->set('answer', $api->getAnswer($_GET['question'], (int)$_GET['context_id']));
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