<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcchatbot/editreport.tpl.php');

$report =  erLhcoreClassModelLHCChatBotInvalid::fetch($Params['user_parameters']['id']);

$question = erLhcoreClassModelLHCChatBotQuestion::findOne(array('filter' => array('hash' => $report->hash, 'context_id' => $report->context_id)));

if (!($question instanceof erLhcoreClassModelLHCChatBotQuestion)) {
    $question = new erLhcoreClassModelLHCChatBotQuestion();
    $question->context_id = $report->context_id;
    $question->answer = $report->answer;
    $question->question = $report->question;
    $question->hash = $report->hash;
}

if (ezcInputForm::hasPostData()) {

    if (isset($_POST['Cancel_action'])) {
        erLhcoreClassModule::redirect('lhcchatbot/invalid');
        exit;
    }

    if ($question->id === null) { // Let core take care of it
        // Just to modify existing question
        $question->question = $report->question;
    }

    $Errors = erLhcoreClassExtensionLHCChatBotValidator::validate($question);

    if (count($Errors) == 0) {
        try {

            erLhcoreClassExtensionLHCChatBotValidator::publishQuestion($question);

            // Update report data
            $report->question = $question->question;
            $report->answer = $question->answer;
            $report->hash = $question->hash;
            $report->saveThis();

            erLhcoreClassModule::redirect('lhcchatbot/invalid');
            exit;

        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->setArray(array(
    'report' => $report,
    'question' => $question,
));

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','ChatBot')
    ),
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/invalid'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Invalid questions/answers')
    ),
    array (
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Edit Report')
    )
);

?>