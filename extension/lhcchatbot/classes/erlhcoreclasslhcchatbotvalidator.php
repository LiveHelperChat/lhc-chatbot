<?php

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

        if ( $form->hasValidData( 'answer' ) && $form->answer != '') {
            $question->answer = $form->answer;
        } else {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter answer!');
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
            )
        );

        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();
        
        if ( $form->hasValidData( 'name' ) && $form->name != '' ) {
            $context->name = $form->name;
        } else {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter name!');
        }

        if ( $form->hasValidData( 'host' ) && $form->host != '' ) {
            $context->host = $form->host;
        } else {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter host!');
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
        // Save question
        $api = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->getApi();

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

        for ($i = 0; $i < erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['try_times']; $i++)
        {
            foreach ($msgs as $msg) {
                foreach ($combainedData[$msg->chat_id] as $contextId) {
                    if ($contextId > 0 && $msg->msg != '' && strlen($msg->msg) >= 2) {

                        $msgSearch = trim(preg_replace('#([\x{2B50}-\x{2B55}]|[\x{23F0}-\x{23F3}]|[\x{231A}-\x{231B}]|[\x{1F600}-\x{1F64F}]|[\x{1F910}-\x{1F9FF}]|[\x{1F300}-\x{1F5FF}]|[\x{1F680}-\x{1F6FF}]|[\x{2600}-\x{26FF}]|[\x{2700}-\x{27BF}])#u','', $msg->msg));

                        if ($msgSearch != '')
                        {
                            $contextObject = erLhcoreClassModelLHCChatBotContext::fetch($contextId);

                            $answer = self::getAnswer($contextObject->host, $msgSearch);

                            if ($answer['found'] == true) {
                                if ($answer['confidence'] > 0 && (!isset($suggestions[$msg->chat_id]) || !in_array($answer['msg'], $suggestions[$msg->chat_id]))) {

                                    $answerObj = erLhcoreClassModelLHCChatBotQuestion::findOne(array('filter' => array('hash' => $answer['msg'])));

                                    if ($answerObj instanceof erLhcoreClassModelLHCChatBotQuestion) {
                                        $suggestions[$msg->chat_id][] = array('a' => $answerObj->answer, 'ctx' => $contextId, 'q' => $msg->msg, 'in_response' => $answer['in_response'], 'aid' => $answer['msg']);
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

    public static function getAnswer($host, $question)
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

        $response = ['found' => false, 'msg' => '', 'confidence' => 0];

        if (is_array($contentJSON)) {
            if (isset($contentJSON[0][0][0])){
                $response['found'] = true;
                $response['in_response'] = $question;
                $response['msg'] = explode('__',$contentJSON[0][0][0])[1];
                $response['confidence'] = $contentJSON[0][1][$contentJSON[0][2][0]];
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
