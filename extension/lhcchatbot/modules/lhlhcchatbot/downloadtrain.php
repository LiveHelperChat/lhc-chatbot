<?php

if ((!erLhcoreClassUser::instance()->isLogged() || !erLhcoreClassUser::instance()->hasAccessTo('lhlhcchatbot','use')) && (!isset($_GET['secret_hash']) || erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['secret_hash'] != $_GET['secret_hash'])){
    http_response_code(404);
    exit;
}

$context = erLhcoreClassModelLHCChatBotContext::fetch((int)$Params['user_parameters']['id']);

$file = fopen('php://output', 'w');

header('Content-type: application/csv');
header('Content-Disposition: attachment; filename=train_'. $context->id. ".csv");

fputcsv($file, array("Question","Answer"));

foreach (erLhcoreClassModelLHCChatBotQuestion::getList(array('limit' => false, 'filter' => array('confirmed' => 1, 'context_id' => $context->id))) as $question) {

    if ($question->hash == '') {
        $question->saveThis();
    }

    foreach ($question->question_items as $questionString) {

        $answer = trim($question->answer);

        if ($answer == '' && $question->canned_id > 0) {
            $cannedMessage = erLhcoreClassModelCannedMsg::fetch($question->canned_id);
            if ($cannedMessage instanceof erLhcoreClassModelCannedMsg) {
                $answer = $cannedMessage->msg;
            }
        }

        if (trim($questionString) != '' && $answer != '') {
            fputcsv($file, array(trim($questionString),$answer.'__'.$question->hash));
        }

    }
}

fclose($file);
exit;