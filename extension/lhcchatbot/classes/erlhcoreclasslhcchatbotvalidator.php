<?php
#[\AllowDynamicProperties]
class erLhcoreClassExtensionLHCChatBotValidator
{
    public static function getContextFilter($userId) {
        $contextId = array();
        $limit = erLhcoreClassUserDep::parseUserDepartmetnsForFilter($userId);
        if ($limit !== true) {
            $items = erLhcoreClassModelLHCChatBotContextLinkDepartment::getList(array('filterin' => array('department_id' => $limit)));
            if (!empty($items)) {
                foreach ($items as $item) {
                    $contextId[] = $item->context_id;
                }
            } else {
                $contextId = array(-1);
            }
        }

        return $contextId;
    }

    public static function validateExample(erLhcoreClassModelLHCChatBotRasaExample & $item)
    {
        $definition = array(
            'intent_id' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
            ),
            'example' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'verified' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
            ),
            'active' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
            )
        );

        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();

        if ( $form->hasValidData( 'example' ) && $form->example != '' ) {
            $item->example = $form->example;
        } else {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter an example!');
        }

        if ( $form->hasValidData( 'active' ) && $form->active == true ) {
            $item->active = 1;
        } else {
            $item->active = 0;
        }

        if ( $form->hasValidData( 'verified' ) && $form->verified == true ) {
            $item->verified = 1;
        } else {
            $item->verified = 0;
        }

        if ( $form->hasValidData( 'intent_id' ) ) {
            $item->intent_id = $form->intent_id;
        } else {
            $item->intent_id = 0;
        }

        return $Errors;
    }

    public static function validateIntent(erLhcoreClassModelLHCChatBotRasaIntent & $item)
    {
        $definition = array(
            'intent' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'name' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'test_samples' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'active' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
            ),
            'context_id' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
            )
        );

        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();

        if ( $form->hasValidData( 'intent' ) && $form->intent != '' ) {
            $item->intent = $form->intent;
        } else {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter an intent!');
        }

        if ( $form->hasValidData( 'name' ) && $form->name != '' ) {
            $item->name = $form->name;
        } else {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter a name!');
        }

        if ( $form->hasValidData( 'test_samples' ) ) {
            $item->test_samples = $form->test_samples;
        }

        if ( $form->hasValidData( 'active' ) && $form->active == true ) {
            $item->active = 1;
        } else {
            $item->active = 0;
        }

        if ( $form->hasValidData( 'context_id' ) ) {
            $item->context_id = $form->context_id;
        } else {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please choose context!');
        }

        return $Errors;
    }

    public static function validate(erLhcoreClassModelLHCChatBotQuestion & $question)
    {
        $definition = array(
            'question' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'answer' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'confirmed' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
            ),
            'context_id' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
            ),
            'rasa_intent_id' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
            ),
            'canned_id' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
            )
        );

        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();
        
        $question->snapshot();
        
        if ( $form->hasValidData( 'question' ) && $form->question != '' ) {
            $question->question = $form->question;
        } else {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter question!');
        }

        if ( $form->hasValidData( 'canned_id' ) ) {
            $question->canned_id = $form->canned_id;
        } else {
            $question->canned_id = 0;
        }

        if ( $form->hasValidData( 'rasa_intent_id' ) ) {
            $question->rasa_intent_id = $form->rasa_intent_id;
        } else {
            $question->rasa_intent_id = 0;
        }

        if ($form->hasValidData( 'answer' ) && $form->answer != '') {
            $question->answer = $form->answer;
        } else {
            $question->answer = '';

            if ($question->canned_id == 0) {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter answer!');
            }
        }

        if ( $form->hasValidData( 'context_id' ) ) {
            $question->context_id = $form->context_id;
        } else {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please choose context!');
        }



        if ( $form->hasValidData( 'confirmed' ) && $form->confirmed == true ) {
            $question->confirmed = 1;
        } else {
            $question->confirmed = 0;
        }

        return $Errors;
    }

    public static function validateElasticQuestion(erLhcoreClassModelESChatbotQuestion & $question)
    {
        $definition = array(
            'question' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'answer' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'context_id' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 1)
            ),
            'answerAdd' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw', null, FILTER_REQUIRE_ARRAY
            ),
            'context_idAdd' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'int', null, FILTER_REQUIRE_ARRAY
            ),
            'context_questionId' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'int', null, FILTER_REQUIRE_ARRAY
            ),
            'confirmed' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
            ),
            'hidden' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
            ),
        );

        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();

        $question->cbot_question->snapshot();

        $questionsAnswers = array();
        if ( $form->hasValidData( 'answerAdd' ) ) {
            foreach ($form->answerAdd as $key => $answer) {
                $questionsAnswers[] = array(
                    'name' => $answer,
                    'context' => $form->context_idAdd[$key],
                    'id' => $form->context_questionId[$key]
                );
            }
        }

        $question->cbot_question_array = $questionsAnswers;

        if ( $form->hasValidData( 'confirmed' ) && $form->confirmed == true ) {
            $question->confirmed = 1;
        } else {
            $question->confirmed = 0;
        }

        if ( $form->hasValidData( 'hidden' ) && $form->hidden == true ) {
            $question->hidden = 1;
        } else {
            $question->hidden = 0;
        }

        if ( $form->hasValidData( 'question' ) && $form->question != '' ) {
            $question->cbot_question->question = $form->question;
        } elseif ($question->confirmed == 1) {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter question!');
        }

        if ( $form->hasValidData( 'answer' ) && $form->answer != '') {
            $question->cbot_question->answer = $form->answer;
        } elseif ($question->confirmed == 1) {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter answer!');
        }

        if ( $form->hasValidData( 'context_id' ) ) {
            $question->cbot_question->context_id = $form->context_id;
        } else {
            $question->cbot_question->context_id = 0; // Track back what happens then context is reset/assigned
        }

        $question->cbot_question->confirmed = $question->confirmed;
    }

    public static function validateContext(erLhcoreClassModelLHCChatBotContext & $context)
    {
        $definition = array(
            'name' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'host' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            ),
            'meili' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'int', array('min_range' => 0,'max_range' => 2)
            )
        );

        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();
        
        if ( $form->hasValidData( 'name' ) && $form->name != '' ) {
            $context->name = $form->name;
        } else {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter name!');
        }

        if (!class_exists('erLhcoreClassInstance')) {
            if ( $form->hasValidData( 'host' ) && $form->host != '' ) {
                $context->host = $form->host;
            } else {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter host!');
            }

            if ( $form->hasValidData( 'meili' )) {
                $context->meili = $form->meili;
            } else {
                $context->meili = 0;
            }
        }

        return $Errors;
    }

    public static function deleteProposedQuestion(erLhcoreClassModelESChatbotQuestion & $question)
    {
        if ($question->cbot_question_id > 0) {
            self::deleteQuestion($question->cbot_question);
        }

        foreach ($question->cbot_question_objects as $questionAnswer) {
            self::deleteQuestion($questionAnswer);
        }

        $question->removeThis();
    }

    /**
     * @desc publish question based on Elastic Search Question
     *
     * @param erLhcoreClassModelESChatbotQuestion $question
     */
    public static function publishElasticQuestion(erLhcoreClassModelESChatbotQuestion & $question)
    {
        // Find answers to remove first
        $updatedQuestions = array();
        $newQuestions = array();
        $idExisting = array();

        foreach ($question->cbot_question_array as $key => $item) {
            if ($item['id'] > 0) {
                $idExisting[] = $item['id'];
                $updatedQuestions[$item['id']] = $item;
            }

            if ($item['id'] == 0) {
                $item['key'] = $key;
                $newQuestions[] = $item;
            }
        }

        // Find which questions has to be removed
        $toRemove = array_diff($question->cbot_question_ids, $idExisting);

        foreach ($toRemove as $questionId) {
            $questionAnswer = erLhcoreClassModelLHCChatBotQuestion::fetch($questionId);
            self::deleteQuestion($questionAnswer);
        }

        // Find all questions which were updated and updated them
        foreach ($question->cbot_question_ids as $questionId) {
            if (key_exists($questionId, $updatedQuestions)) {
                $questionAnswer = erLhcoreClassModelLHCChatBotQuestion::fetch($questionId);
                $questionAnswer->confirmed = $question->confirmed;
                $questionAnswer->snapshot();

                $questionAnswer->question = $question->question;
                $questionAnswer->answer = $updatedQuestions[$questionId]['name'];
                $questionAnswer->context_id = $updatedQuestions[$questionId]['context'];
                self::publishQuestion($questionAnswer);
            }
        }

        foreach ($newQuestions as $newQuestion) {
            $questionAnswer = new erLhcoreClassModelLHCChatBotQuestion();
            $questionAnswer->question = $question->question;
            $questionAnswer->answer = $newQuestion['name'];
            $questionAnswer->context_id = $newQuestion['context'];
            $questionAnswer->confirmed = $question->confirmed;
            self::publishQuestion($questionAnswer);
            $idExisting[] = $questionAnswer->id;
            $question->cbot_question_array[$newQuestion['key']]['id'] = $questionAnswer->id;
        }

        // Assign other questions and answers
        $question->cbot_question_ids = $idExisting;

        // Save main question and answer
        $chatbotQuestion = $question->cbot_question;
        self::publishQuestion($chatbotQuestion);

        $question->cbot_question_id = $chatbotQuestion->id;
        $question->saveThis();
    }

    /**
     * @desc publish question based on Chatbot Question
     *
     * @param erLhcoreClassModelLHCChatBotQuestion $question
     */
    public static function publishQuestion(erLhcoreClassModelLHCChatBotQuestion & $question)
    {
        $question->saveThis();
        erLhcoreClassChatEventDispatcher::getInstance()->dispatch('lhcchatbot.publish_question', array('question' => & $question));
    }

    /**
     * @desc Delete records from bot based on report
     *
     * @param erLhcoreClassModelLHCChatBotInvalid $question
     */
    public static function deleteReport(erLhcoreClassModelLHCChatBotInvalid $question)
    {
        foreach (erLhcoreClassModelLHCChatBotQuestion::getList(array('filter' => array('hash' => $question->hash, 'context_id' => $question->context_id))) as $item) {
            $item->removeThis();
        }

        $question->removeThis();
    }


    public static function deleteQuestion(erLhcoreClassModelLHCChatBotQuestion & $question)
    {
        $question->removeThis();
        erLhcoreClassChatEventDispatcher::getInstance()->dispatch('lhcchatbot.delete_question', array('question' => & $question));
    }

    /**
     * Removes context
     * 
     * @param erLhcoreClassModelLHCChatBotContext $context
     * 
     * @throws Exception
     */
    public static function deleteContext(erLhcoreClassModelLHCChatBotContext & $context)
    {
        if (erLhcoreClassModelLHCChatBotQuestion::getCount(array('filter' => array('context_id' => $context->id))) > 0) {
            throw new Exception('Please unasign questions first!');
        }

        // Remove links
        $db = ezcDbInstance::get();
        $stmt = $db->prepare("DELETE FROM lhc_lhcchatbot_context_link_department WHERE context_id = :context_id");
        $stmt->bindValue(':context_id',$context->id);
        $stmt->execute();

        // Remove context
        $context->removeThis();       
    }
    
    public static function suggestByIds($ids = array(), $chatMode = false)
    {
        if ($chatMode == false) {
            $msgs = erLhcoreClassModelmsg::getList(array('filterin' => array('id' => $ids)));
        } else {
            $msgs = erLhcoreClassModelmsg::getList(array('limit' => 3, 'sort' => 'id DESC','filter' => array('user_id' => 0),'filterin' => array('chat_id' => $ids)));
        }

        $suggestions = array();
        
        // Select messages chat's id
        $chatsId = array();
        foreach ($msgs as $msg) {
            $chatsId[] = $msg->chat_id;
        }
               
        if (empty($chatsId)) {
            return array();
        }
        
        $db = ezcDbInstance::get();
        
        // Select chats departments
        $stmt = $db->prepare("SELECT dep_id, id FROM lh_chat WHERE id IN (" . implode(',', $chatsId) . ')');
        $stmt->execute();
        $dataDepartments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $mappedData = array();
        
        $departments = array();
        
        foreach ($dataDepartments as $chatDepartment) {
            $mappedData[$chatDepartment['id']] = $chatDepartment['dep_id'];
            $departments[] = $chatDepartment['dep_id'];
        }
        
        if (empty($departments)) {
            return array();
        }

        // Select context by departments
        $stmt = $db->prepare("SELECT context_id, department_id FROM lhc_lhcchatbot_context_link_department WHERE department_id IN (" . implode(',', $departments) . ')');
        $stmt->execute();
        $dataContext = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Map departments to multiple context
        $departmentContext = array();
        foreach ($dataContext as $data) {
            $departmentContext[$data['department_id']][] = $data['context_id'];
        }

        // Remap chat's to context
        $combainedData = array();
        foreach ($mappedData as $chatId => $departmentId) {
            $combainedData[$chatId] = isset($departmentContext[$departmentId]) ? $departmentContext[$departmentId] : array(0);
        }

        // Global settings
        if (class_exists('erLhcoreClassInstance')) {
            $dataValue = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['ahosting_settings'];
        } else {
            $dataValue = erLhcoreClassModelChatConfig::fetch('lhcchatbot_options')->data_value;
        }

        for ($i = 0; $i < erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['try_times']; $i++)
        {
            foreach ($msgs as $msg) {
                foreach ($combainedData[$msg->chat_id] as $contextId) {
                    if ($contextId > 0 && $msg->msg != '' && strlen($msg->msg) >= 2) {

                        $msgSearch = trim(preg_replace('#([\x{2B50}-\x{2B55}]|[\x{23F0}-\x{23F3}]|[\x{231A}-\x{231B}]|[\x{1F600}-\x{1F64F}]|[\x{1F910}-\x{1F9FF}]|[\x{1F300}-\x{1F5FF}]|[\x{1F680}-\x{1F6FF}]|[\x{2600}-\x{26FF}]|[\x{2700}-\x{27BF}])#u','', $msg->msg));

                        if ($msgSearch != '')
                        {

                            $contextObject = erLhcoreClassModelLHCChatBotContext::fetch($contextId);

                            if ($contextObject->meili == 1 || class_exists('erLhcoreClassInstance')) {
                                $answer = self::getAnswerMeili($contextObject->id, $msgSearch, $dataValue);
                            } else if ($contextObject->meili == 2) {
                                $answer = self::getAnswerRasa($contextObject->host, $msgSearch);
                            } else {
                                $answer = self::getAnswer($contextObject->host, $msgSearch);
                            }

                            if ($answer['found'] == true) {
                                if (!isset($suggestions[$msg->chat_id]) || !in_array($answer['msg'], $suggestions[$msg->chat_id])) {

                                    if ($contextObject->meili == 1 || class_exists('erLhcoreClassInstance')) {
                                        $answerObj = erLhcoreClassModelLHCChatBotQuestion::findOne(array('filter' => array('id' => $answer['q_id'])));
                                        if ($answerObj instanceof erLhcoreClassModelLHCChatBotQuestion) {
                                            $suggestions[$msg->chat_id][] = array('a' => $answer['msg'], 'ctx' => $contextId, 'q' => $msg->msg, 'in_response' => $answer['in_response'], 'aid' => $answerObj->hash);
                                        }

                                        if (isset($answer['q_id_2'])) {
                                            $answerObj = erLhcoreClassModelLHCChatBotQuestion::findOne(array('filter' => array('id' => $answer['q_id_2'])));
                                            if ($answerObj instanceof erLhcoreClassModelLHCChatBotQuestion) {
                                                $suggestions[$msg->chat_id][] = array('a' => $answer['msg_2'], 'ctx' => $contextId, 'q' => $msg->msg, 'in_response' => $answer['in_response'], 'aid' => $answerObj->hash);
                                            }
                                        }

                                    } elseif ($contextObject->meili == 2) {
                                        $rasaIntent = erLhcoreClassModelLHCChatBotRasaIntent::findOne(['filter' => ['context_id' => $contextId, 'intent' => $answer['msg']]]);
                                        if ($rasaIntent instanceof erLhcoreClassModelLHCChatBotRasaIntent) {
                                            $answerObj = erLhcoreClassModelLHCChatBotQuestion::findOne(array('filter' => array('context_id' =>  $contextId, 'rasa_intent_id' => $rasaIntent->id)));
                                            if ($answerObj instanceof erLhcoreClassModelLHCChatBotQuestion) {
                                                $suggestions[$msg->chat_id][] = array('a' => $answerObj->answer, 'ctx' => $contextId, 'q' => $msg->msg, 'in_response' => $answer['in_response'], 'aid' => $answerObj->hash);
                                            }
                                        }
                                    } else {
                                        $answerObj = erLhcoreClassModelLHCChatBotQuestion::findOne(array('filter' => array('hash' => $answer['msg'])));
                                        if ($answerObj instanceof erLhcoreClassModelLHCChatBotQuestion) {
                                            $suggestions[$msg->chat_id][] = array('a' => $answerObj->answer, 'ctx' => $contextId, 'q' => $msg->msg, 'in_response' => $answer['in_response'], 'aid' => $answer['msg']);
                                        }
                                    }

                                    // Return top two suggestions
                                    if (isset($answer['msg_alt'])) {
                                        $answerObj = erLhcoreClassModelLHCChatBotQuestion::findOne(array('filter' => array('hash' => $answer['msg_alt'])));
                                        if ($answerObj instanceof erLhcoreClassModelLHCChatBotQuestion) {
                                            $suggestions[$msg->chat_id][] = array('a' => $answerObj->answer, 'ctx' => $contextId, 'q' => $msg->msg, 'in_response' => $answer['in_response'], 'aid' => $answer['msg_alt']);
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }

        return $suggestions;
    }

    public static function getAnswerRasa($host, $question, $debug = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $question]));
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $content = curl_exec($ch);

        if (curl_errno($ch)) {
            $http_error = curl_error($ch);
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $contentJSON = json_decode($content, true);

        $response = ['found' => false, 'msg' => ''];

        if ($httpcode == 200 && !empty($contentJSON['intent'])) {
            $response['found'] = true;
            $response['in_response'] = $question;
            $response['msg'] = $contentJSON['intent']['name'];
        }

        return $response;

    }

    public static function getAnswerMeili($context, $question, $params) {

        $response = ['found' => false, 'msg' => ''];

        if (
            isset($params['msearch_answer_host']) && !empty($params['msearch_answer_host']) &&
            isset($params['public_answer_key']) && !empty($params['public_answer_key'])
        ) {

            $inst_id = class_exists('erLhcoreClassInstance') ? erLhcoreClassInstance::$instanceChat->id : 0;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['limit' => 2, 'q' => $question, 'filter' => ['context_id = '.$context]]));
            curl_setopt($ch, CURLOPT_URL, $params['msearch_answer_host'] . '/indexes/lhc_suggest_' . $inst_id . '/search');
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','X-Meili-API-Key: '.$params['public_answer_key']]);
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $content = curl_exec($ch);

            if (curl_errno($ch)) {
                $http_error = curl_error($ch);
            }

            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $contentJSON = json_decode($content, true);

            if ($httpcode == 200 && isset($contentJSON['hits']) && !empty($contentJSON['hits'])) {
                $response['found'] = true;
                $response['in_response'] = $question;
                $response['msg'] = $contentJSON['hits'][0]['answer'];
                $response['q_id'] = explode('_',$contentJSON['hits'][0]['id'])[0];
                if (isset($contentJSON['hits'][1]['id'])) {
                    $response['msg_2'] = $contentJSON['hits'][1]['answer'];
                    $response['q_id_2'] = explode('_',$contentJSON['hits'][1]['id'])[0];
                }
            }
        }

        return $response;

    }

    public static function getAnswer($host, $question, $debug = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['q' => [$question]]));
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $content = curl_exec($ch);

        if (curl_errno($ch)) {
            $http_error = curl_error($ch);
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $contentJSON = json_decode($content, true);

        $response = ['found' => false, 'msg' => ''];

        if (is_array($contentJSON)) {
            if (isset($contentJSON[0][0][0]) && is_array($contentJSON[0][0])) {
                $response['found'] = true;
                $response['in_response'] = $question;
                if ($debug === false) {
                    $response['msg'] = explode('__', $contentJSON[0][0][0])[1];
                    if (isset($contentJSON[0][0][1])) {
                        $response['msg_alt'] = explode('__', $contentJSON[0][0][1])[1];
                    }
                } else {
                    $response['msg'] = $contentJSON[0][0];
                }
            } elseif (isset($contentJSON[0][0]) && is_array($contentJSON[0])) {
                $response['found'] = true;
                $response['in_response'] = $question;
                if ($debug === false) {
                    $response['msg'] = explode('__', $contentJSON[0][0])[1];
                    if (isset($contentJSON[0][1])) {
                        $response['msg_alt'] = explode('__', $contentJSON[0][1])[1];
                    }
                } else {
                    $response['msg'] = $contentJSON[0];
                }
            }
        }

        return $response;
    }

    public static function getUnsupportedChats($ids = array(), $chatMode = false) {

        if ($chatMode == false) {
            $msgs = erLhcoreClassModelmsg::getList(array('filterin' => array('id' => $ids)));
        } else {
            $msgs = erLhcoreClassModelmsg::getList(array('limit' => 3,'sort' => 'id DESC','filter' => array('user_id' => 0),'filterin' => array('chat_id' => $ids)));
        }

        // Select messages chat's id
        $chatsId = array();
        foreach ($msgs as $msg) {
            $chatsId[] = $msg->chat_id;
        }

        if (empty($chatsId)) {
            return array();
        }

        $db = ezcDbInstance::get();
        $stmt = $db->prepare("SELECT dep_id, id FROM lh_chat WHERE id IN (" . implode(',', $chatsId) . ')');
        $stmt->execute();
        $dataDepartments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $mappedData = array();
        $departments = array();

        foreach ($dataDepartments as $chatDepartment) {
            $mappedData[$chatDepartment['id']] = $chatDepartment['dep_id'];
            $departments[] = $chatDepartment['dep_id'];
        }

        if (empty($departments)) {
            return array();
        }

        // Select context by departments
        $stmt = $db->prepare("SELECT department_id FROM lhc_lhcchatbot_context_link_department WHERE department_id IN (" . implode(',', $departments) . ')');
        $stmt->execute();
        $dataContext = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Map departments to multiple context
        $departmentContext = array();
        foreach ($dataContext as $data) {
            $departmentContext[] = $data['department_id'];
        }

        $invalidChatId = array();
        foreach ($mappedData as $chatId => $departmentId) {
            if (!in_array($departmentId, $departmentContext)) {
                $invalidChatId[] = $chatId;
            }
        }

        return $invalidChatId;
    }
}
