<?php 

$tpl = erLhcoreClassTemplate::getInstance('lhcchatbot/new.tpl.php');

$question = new erLhcoreClassModelLHCChatBotQuestion();

if (ezcInputForm::hasPostData()) {
        
    $Errors = erLhcoreClassExtensionLHCChatBotValidator::validate($question);

    if (count($Errors) == 0) {
        try {
            erLhcoreClassExtensionLHCChatBotValidator::publishQuestion($question);

            erLhcoreClassModule::redirect('lhcchatbot/list');
            exit ;

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
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/list') . '/(confirmed)/1',
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Questions and Answers')        
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'New')
    )
);

?>