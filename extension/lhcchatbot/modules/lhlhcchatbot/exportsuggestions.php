<?php

if ((!erLhcoreClassUser::instance()->isLogged() || !erLhcoreClassUser::instance()->hasAccessTo('lhlhcchatbot','download')) && (!isset($_GET['secret_hash']) || erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['secret_hash'] != $_GET['secret_hash'])){
    http_response_code(404);
    exit;
}

header ( 'content-type: application/json; charset=utf-8' );

$dataExport = [];

foreach (erLhcoreClassModelLHCChatBotQuestion::getList(['limit' => false, 'filter' => ['confirmed' => 1]]) as $question) {

    $answer = trim($question->answer);

    if ($answer == '' && $question->canned_id > 0) {
        $cannedMessage = erLhcoreClassModelCannedMsg::fetch($question->canned_id);
        if ($cannedMessage instanceof erLhcoreClassModelCannedMsg) {
            $answer = $cannedMessage->msg;
        }
    }

    foreach ($question->question_items as $index => $questionString) {
        if (trim($questionString) != '') {
            $dataExport[] = [
                'id' => "{$question->id}_{$index}",
                'msg' => trim($questionString),
                'answer' => $answer,
                'context_id' => $question->context_id
            ];
        }
    }
}

echo json_encode($dataExport, JSON_PRETTY_PRINT);

exit;


?>