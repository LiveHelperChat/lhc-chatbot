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

        if (isset($_POST['aid'])) {
            $aid = $_POST['aid'];
        } else {
            throw new Exception('Answer ID not provided!');
        }

        if (isset($_POST['context_id'])) {
            $context_id = $_POST['context_id'];
        } else {
            throw new Exception('Context not provided!');
        }

        $db = ezcDbInstance::get();

        if (erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['elastic_enabled'] == true) {
            // Log that suggestion was used
            $stmt = $db->prepare("INSERT IGNORE INTO lhc_lhcchatbot_used (chat_id) VALUES (:chat_id)");
            $stmt->bindValue(':chat_id', $chat->id);
            $stmt->execute();
        }

        if (is_numeric($context_id)) {
            $stmt = $db->prepare("SELECT id FROM lhc_lhcchatbot_question WHERE context_id = :context_id AND hash = :hash");
            $stmt->bindValue(':hash', $aid, PDO::PARAM_STR);
            $stmt->bindValue(':context_id', $context_id, PDO::PARAM_STR);
            $stmt->execute();

            $id = $stmt->fetchColumn();

            if (is_numeric($id)){
                $stmt = $db->prepare("UPDATE lhc_lhcchatbot_question SET was_used = was_used + 1 WHERE id = :id");
                $stmt->bindValue(':id', $id, PDO::PARAM_STR);
                $stmt->execute();
            } else {
                $id = 0;
            }

            $stmt = $db->prepare("INSERT INTO `lhc_lhcchatbot_use` (`question`,`answer`,`context_id`,`question_id`,`dep_id`,`chat_id`,`user_id`,`ctime`,`type`) VALUES (:question,:answer,:context_id,:question_id,:dep_id,:chat_id,:user_id,:ctime,:type)");
            $stmt->bindValue(':question', $question);
            $stmt->bindValue(':answer', $answer);
            $stmt->bindValue(':context_id', $context_id);
            $stmt->bindValue(':question_id', (int)$id);
            $stmt->bindValue(':dep_id', $chat->dep_id);
            $stmt->bindValue(':chat_id', $chat->id);
            $stmt->bindValue(':user_id', $currentUser->getUserID());
            $stmt->bindValue(':ctime', time());
            $stmt->bindValue(':type', isset($_POST['type']) && $_POST['type'] == 1 ? 1 : 0);
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