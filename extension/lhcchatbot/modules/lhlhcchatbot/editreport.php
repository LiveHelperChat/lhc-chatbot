<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcchatbot/editreport.tpl.php');

$report =  erLhcoreClassModelLHCChatBotInvalid::fetch($Params['user_parameters']['id']);

$question = erLhcoreClassModelLHCChatBotQuestion::findOne(array('filter' => array('answer' => $report->answer, 'context_id' => $report->context_id)));

if (!($question instanceof erLhcoreClassModelLHCChatBotQuestion)) {
    $question = new erLhcoreClassModelLHCChatBotQuestion();
    $question->context_id = $report->context_id;
    $question->answer = $report->answer;
    $question->question = $report->question;
}

if (ezcInputForm::hasPostData()) {

    if (isset($_POST['Cancel_action'])) {
        erLhcoreClassModule::redirect('lhcchatbot/invalid');
        exit;
    }

    if ($question->id === null) { // Let core take care of it
        // Just to modify existing question
        $question->question = $report->question;
    } else {
        $api = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->getApi();
        $api->removeQuestion($report->question, $question->context_id);
    }

    $Errors = erLhcoreClassExtensionLHCChatBotValidator::validate($question);

    if (count($Errors) == 0) {
        try {

            // Update report data
            $report->question = $question->question;
            $report->answer = $question->answer;
            $report->saveThis();

            erLhcoreClassExtensionLHCChatBotValidator::publishQuestion($question);
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