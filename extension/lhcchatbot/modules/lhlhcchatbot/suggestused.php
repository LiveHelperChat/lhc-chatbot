<?php

try {

    $chat =  erLhcoreClassModelChat::fetch($Params['user_parameters']['id']);

    if ($chat instanceof erLhcoreClassModelChat) {

        if (isset($_POST['answer'])) {
            $answer = $_POST['answer'];
        } else {
            throw new Exception('Answer not provided!');
        }

        if (isset($_POST['question'])) {
            $question = $_POST['question'];
        } else {
            throw new Exception('Question not provided!');
        }

        $db = ezcDbInstance::get();

        $stmt = $db->prepare("SELECT context_id FROM lhc_lhcchatbot_context_link_department WHERE department_id = :dep_id");
        $stmt->bindValue(':dep_id', $chat->dep_id);
        $stmt->execute();
        $dataContext = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['elastic_enabled'] == true) {
            // Log that suggestion was used
            $stmt = $db->prepare("INSERT IGNORE INTO lhc_lhcchatbot_used (chat_id) VALUES (:chat_id)");
            $stmt->bindValue(':chat_id', $chat->id);
            $stmt->execute();
        }

        if (is_array($dataContext) && !empty($dataContext)){
            $stmt = $db->prepare("UPDATE lhc_lhcchatbot_question SET was_used = was_used + 1 WHERE context_id IN (" . implode(',', $dataContext) . ") AND answer = :answer AND question LIKE (:question)");
            $stmt->bindValue(':answer', $answer, PDO::PARAM_STR);
            $stmt->bindValue(':question', '%' . $question . '%', PDO::PARAM_STR);
            $stmt->execute();
        } else {
            throw new Exception('Context could not be found!');
        }

        echo json_encode(array('error' => false,'msg' => 'Use counter was updated!'));

    } else {
        throw new Exception('Chat not found!');
    }

} catch (Exception $e) {
    echo json_encode(array('error' => true, 'msg' => $e->getMessage()));
}

exit;

?>