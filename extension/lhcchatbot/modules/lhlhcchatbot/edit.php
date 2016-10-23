<?php 

$tpl = erLhcoreClassTemplate::getInstance('lhcchatbot/edit.tpl.php');

$question =  erLhcoreClassModelLHCChatBotQuestion::fetch($Params['user_parameters']['id']);

if (ezcInputForm::hasPostData()) {

    if (isset($_POST['Cancel_action'])) {
        erLhcoreClassModule::redirect('lhcchatbot/list');
        exit ;
    }

    $Errors = erLhcoreClassExtensionLHCChatBotValidator::validate($question);

    if (count($Errors) == 0) {
        try {
            erLhcoreClassExtensionLHCChatBotValidator::publishQuestion($question);

            erLhcoreClassModule::redirect('lhcchatbot/list');
            exit;

        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }

    } else {
        $tpl->set('errors',$Errors);
    }       
}

$tpl->setArray(array(
        'question' => $question,
));

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','ChatBot')
    ),
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/list'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Questions and Answers')        
    ),
    array (       
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Edit')
    )
);

?>