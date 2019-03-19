<?php 

$tpl = erLhcoreClassTemplate::getInstance('lhcchatbot/editcontext.tpl.php');

$context = erLhcoreClassModelLHCChatBotContext::fetch($Params['user_parameters']['id']);

if (ezcInputForm::hasPostData()) {

    if (isset($_POST['Cancel_action'])) {
        erLhcoreClassModule::redirect('lhcchatbot/listcontext');
        exit ;
    }

    $Errors = erLhcoreClassExtensionLHCChatBotValidator::validateContext($context);

    if (count($Errors) == 0) {
        try {
            $context->saveThis();
            erLhcoreClassModule::redirect('lhcchatbot/listcontext');
            exit;
        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }

    } else {
        $tpl->set('errors',$Errors);
    }       
}

$tpl->setArray(array(
    'context' => $context,
));

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','ChatBot')
    ),
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/listcontext'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Context')        
    ),
    array (       
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Edit')
    )
);

?>