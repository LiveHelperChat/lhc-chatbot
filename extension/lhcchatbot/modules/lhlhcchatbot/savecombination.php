<?php

try {

    $chat =  erLhcoreClassModelChat::fetch($Params['user_parameters']['id']);

    if ($chat instanceof erLhcoreClassModelChat) {

        if (isset($_POST['answer'])) {
            $answer = trim($_POST['answer']);
        } else {
            throw new Exception('Answer not provided!');
        }

        if (isset($_POST['question'])) {
            $question = trim($_POST['question']);
        } else {
            throw new Exception('Question not provided!');
        }

        $questionItem = erLhcoreClassModelLHCChatBotQuestion::findOne(array('filter' => array(
            'question' => $question,
            'answer' => $answer,
        )));

        if (!($questionItem instanceof erLhcoreClassModelLHCChatBotQuestion)) {

            $db = ezcDbInstance::get();

            $stmt = $db->prepare("SELECT context_id FROM lhc_lhcchatbot_context_link_department WHERE department_id = :dep_id");
            $stmt->bindValue(':dep_id', $chat->dep_id);
            $stmt->execute();
            $dataContext = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($dataContext)) {

                $questionItem = new erLhcoreClassModelLHCChatBotQuestion();
                $questionItem->counter = 1;
                $questionItem->question = $question;
                $questionItem->answer = $answer;
                $questionItem->context_id = $dataContext[0];
                $questionItem->saveThis();

                erLhcoreClassExtensionLHCChatBotValidator::publishQuestion($questionItem);
                
                $tpl = erLhcoreClassTemplate::getInstance('lhkernel/alert_success.tpl.php');
                $tpl->set('msg','Bot trained!');
                echo json_encode(array('error' => false,'result' => $tpl->fetch()));
            } else {
                echo json_encode(array('error' => true,'msg' => 'Data context could not be found!'));
            }

        } else {
            echo json_encode(array('error' => true,'msg' => 'This combination already exists'));
        }

    } else {
        throw new Exception('Chat not found!');
    }

} catch (Exception $e) {
    echo json_encode(array('error' => true, 'msg' => $e->getMessage()));
}

exit;

?>