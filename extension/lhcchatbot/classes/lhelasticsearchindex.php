<?php

class erLhcoreClassElasticSearchChatboxIndex
{
    public static function indexChats($params)
    {
        $settingsElastic = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['elastic_settings'];
        $min_score_question = (float)$settingsElastic['min_score_question'];
        $min_score_answer = (float)$settingsElastic['min_score_answer'];

        $totalIndexedChats = 0;

        foreach ($params['chats'] as $chat) {
            $messages = erLhcoreClassModelmsg::getList(array('filternotin' => array('user_id' => array(-1,-2)), 'sort' => 'id ASC', 'filter' => array('chat_id' => $chat->id)));

            if (!empty($messages) && count($messages) > 5) {

                $totalIndexedChats++;

                $senderType = null;

                // We need to group messsages by type
                $messagesGrouped = array();

                $messagesItems = array();
                foreach ($messages as $msg) {

                    $senderTypeNew = $msg->user_id == 0 ? 0 : 1;

                    if ($senderType === null || $senderType != $senderTypeNew) {

                        if (count($messagesItems) > 0) {
                            $messagesGrouped[] = array('type' => $senderType, 'messages' => $messagesItems);
                            $messagesItems = array();
                        }

                        $senderType = $msg->user_id == 0 ? 0 : 1;
                    }

                    $messagesItems[] = $msg->msg;
                }

                if (!empty($messagesItems)){
                    $messagesGrouped[] = array('type' => $senderType, 'messages' => $messagesItems);
                }

                foreach ($messagesGrouped as $index => $msgGroup) {

                    if ($msgGroup['type'] == 0 && isset($messagesGrouped[$index+1])) { // Make sure it's customer message and someone answered it

                        $question = trim(implode("\n",$msgGroup['messages']));

                        // Do nothing with short questions
                        if (mb_strlen($question) <= 30){
                            continue;
                        }

                        $answer = trim(implode("\n",$messagesGrouped[$index+1]['messages']));

                        $sparams = array(
                            'body' => array()
                        );
                        $sparams['body']['query']['bool']['must'][]['match']['question'] = $question;
                        $sparams['body']['min_score'] = $min_score_question;

                        $questionMatched = erLhcoreClassModelESChatbotQuestion::findOne(array(
                            'offset' => 0,
                            'limit' => 10,
                            'body' => array_merge(array(
                                'sort' => array(
                                    array('_score' => array('order' => 'desc'))
                                )
                            ), $sparams['body'])
                        ));

                        // Look for existing question
                        if (!($questionMatched instanceof erLhcoreClassModelESChatbotQuestion)) {
                            $questionMatched = new erLhcoreClassModelESChatbotQuestion();
                            $questionMatched->question = $question;
                            $questionMatched->itime = time()*1000;
                            $questionMatched->match_count = 1;
                            $questionMatched->chat_id = $chat->id;
                            $questionMatched->dep_ids[] = (int)$chat->dep_id;
                            $questionMatched->saveThis();
                        } else {
                            $questionMatched->chat_id = $chat->id;
                            $questionMatched->match_count++;
                            if (!in_array($chat->dep_id, $questionMatched->dep_ids)){
                                $questionMatched->dep_ids[] = (int)$chat->dep_id;
                            }
                            $questionMatched->saveThis();
                        }

                        // Look for existing answer
                        $sparams = array(
                            'body' => array()
                        );

                        $sparams['body']['query']['bool']['must'][]['match']['answer'] = $answer;
                        $sparams['body']['query']['bool']['must'][]['term']['question_id'] = $questionMatched->id;
                        $sparams['body']['min_score'] = $min_score_answer;

                        $answerMatched = erLhcoreClassModelESChatbotAnswer::findOne(array(
                            'offset' => 0,
                            'limit' => 10,
                            'body' => array_merge(array(
                                'sort' => array(
                                    array('_score' => array('order' => 'desc'))
                                )
                            ), $sparams['body'])
                        ));

                        if (!($answerMatched instanceof erLhcoreClassModelESChatbotAnswer)) {
                            $answerMatched = new erLhcoreClassModelESChatbotAnswer();
                            $answerMatched->chat_id = $chat->id;
                            $answerMatched->answer = $answer;
                            $answerMatched->match_count = 1;
                            $answerMatched->question_id = $questionMatched->id;
                            $answerMatched->dep_id = (int)$chat->dep_id;
                            $answerMatched->saveThis();
                        } else {
                            $answerMatched->match_count++;
                            $answerMatched->saveThis();
                        }
                    }
                }
            }
        }
    }

    public static function indexChatDelay($params)
    {
        $db = ezcDbInstance::get();
        $stmt = $db->prepare('INSERT IGNORE INTO lhc_lhcchatbot_index (`chat_id`) VALUES (:chat_id)');
        $stmt->bindValue(':chat_id', $params['chat']->id, PDO::PARAM_STR);
        $stmt->execute();
    }
}