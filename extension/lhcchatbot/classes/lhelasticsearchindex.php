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

    public static function indexChat($params)
    {
        $db = ezcDbInstance::get();
        $stmt = $db->prepare("SELECT chat_id FROM lhc_lhcchatbot_used WHERE chat_id = :chat_id");
        $stmt->bindParam(':chat_id',$params['chat']->chat_id);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $chatData = $stmt->fetch();

        if (isset($chatData['chat_id']) && is_numeric($chatData['chat_id'])) {
            $botWasUsed = 1;
        } else {
            $botWasUsed = 0;
        }

        $params['chat']->lhc_bot_used = (int)$botWasUsed;
    }

    /**
     * @desc Update elastic search structure
     *
     * @param array $params
     */
    public static function getElasticStructure($params)
    {
        $params['structure']['chat']['types']['lh_chat']['lhc_bot_used'] = array('type' => 'integer');
    }

    /**
     * @param array $params
     *
     * @desc used by elastic search plugin to set attribute
     */
    public static function getState($params)
    {
        if (isset($params['chat']->lhc_bot_used) && is_numeric($params['chat']->lhc_bot_used) && $params['chat']->lhc_bot_used == 1) {
            $params['state']['lhc_bot_used'] = 1;
        } else {
            $params['state']['lhc_bot_used'] = 0;
        }
    }

    /*
     * @desc Used then chat is closed
     * */
    public static function indexChatDelay($params)
    {
        $db = ezcDbInstance::get();
        $stmt = $db->prepare('INSERT IGNORE INTO lhc_lhcchatbot_index (`chat_id`) VALUES (:chat_id)');
        $stmt->bindValue(':chat_id', $params['chat']->id, PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * Appends statistic tab as valid option
     *
     * @param array $params
     */
    public static function appendStatisticTab($params) {
        $params['valid_tabs'][] = 'botusage';
    }

    /**
     * Process this option
     *
     * @param array $paramsExecution
     */
    public static function processTab($paramsExecution) {

        $Params = $paramsExecution['params'];

        if ($Params['user_parameters_unordered']['tab'] == 'botusage')
        {
            if (isset($_GET['doSearch'])) {
                $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/lhcchatbot/classes/filter/botusage.php', 'format_filter' => true, 'use_override' => true, 'uparams' => $Params['user_parameters_unordered']));
            } else {
                $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/lhcchatbot/classes/filter/botusage.php', 'format_filter' => true, 'uparams' => $Params['user_parameters_unordered']));
            }

            $elasticSearchHandler = erLhcoreClassElasticClient::getHandler();

            $sparams = array();
            $sparams['index'] = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionElasticsearch')->settings['index'];
            $sparams['type'] = erLhcoreClassModelESChat::$elasticType;
            $sparams['body']['size'] = 0;
            $sparams['body']['from'] = 0;
            $sparams['body']['aggs']['chats_over_time']['date_histogram']['field'] = 'time';
            $sparams['body']['aggs']['chats_over_time']['date_histogram']['interval'] = 'day';
            $sparams['body']['aggs']['chats_over_time']['aggs']['botusage_aggr']['terms']['field'] = 'lhc_bot_used';

            $dateTime = new DateTime("now");
            $sparams['body']['aggs']['chats_over_time']['date_histogram']['time_zone'] = $dateTime->getOffset() / 60 / 60;

            $paramsOrig = $filterParams;

            if (! isset($paramsOrig['filter']['filtergte']['time']) && ! isset($paramsOrig['filter']['filterlte']['time'])) {
                $paramsOrig['filter']['filtergt']['time'] = mktime(0, 0, 0, date('m'), date('d') - 31, date('y'));
            }

            erLhcoreClassElasticSearchStatistic::formatFilter($paramsOrig['filter'], $sparams);

            $response = $elasticSearchHandler->search($sparams);

            $keyStatus = array(
                0 => 'bot_unused',
                1 => 'bot_used'
            );

            $numberOfChats = array();

            foreach ($response['aggregations']['chats_over_time']['buckets'] as $bucket) {
                $keyDateUnix = $bucket['key'] / 1000;

                foreach ($bucket['botusage_aggr']['buckets'] as $bucketStatus) {
                    if (isset($keyStatus[$bucketStatus['key']])) {
                        $numberOfChats[$keyDateUnix][$keyStatus[$bucketStatus['key']]] = $bucketStatus['doc_count'];
                    }
                }

                foreach ($keyStatus as $mustHave) {
                    if (! isset($numberOfChats[$keyDateUnix][$mustHave])) {
                        $numberOfChats[$keyDateUnix][$mustHave] = 0;
                    }
                }
            }

            ksort($numberOfChats);

            $tpl = $paramsExecution['tpl'];
            $tpl->set('input',$filterParams['input_form']);
            $tpl->set('statistic', $numberOfChats);
        }
    }
}