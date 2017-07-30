<?php

class erLhcoreClassExtensionLHCChatBotValidator
{
    public static function validate(erLhcoreClassModelLHCChatBotQuestion & $question)
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
            $question->context_id = 0; // Track back what happens then context is reset/assigned
        }

        return $Errors;
    }
    
    public static function validateContext(erLhcoreClassModelLHCChatBotContext & $context)
    {
        $definition = array(
            'name' => new ezcInputFormDefinitionElement(
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
        
        return $Errors;
    }

    public static function publishQuestion(erLhcoreClassModelLHCChatBotQuestion & $question)
    {
        // Save question
        $api = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->getApi();

        // Remove questions if there was changes
        foreach ($question->question_items_snapshot as $q)
        {
            $api->removeQuestion(trim($q), isset($question->snapshot['context']) ? $question->snapshot['context'] : 0);
        }
        
        // Add as new questions
        foreach ($question->question_items as $q)
        {
            $api->addQuestion(trim($q), trim($question->answer), $question->context_id);
        }

        $question->saveThis();
    }

    public static function deleteQuestion(erLhcoreClassModelLHCChatBotQuestion & $question)
    {
        // Save question
        $api = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->getApi();
        
        foreach ($question->question_items as $q)
        {
            $api->removeQuestion(trim($q), $q->context_id);
        }
        
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

        // Remove database from bot
        $api = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->getApi();
        $api->dropDatabase($context->id);

        // Remove context
        $context->removeThis();       
    }
    
    public static function suggestByIds($ids)
    {
        // Save question
        $api = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->getApi();
        
        $msgs = erLhcoreClassModelmsg::getList(array('filterin' => array('id' => $ids)));

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

        foreach ($msgs as $msg) {
            foreach ($combainedData[$msg->chat_id] as $contextId) {

                $answer = $api->getAnswer($msg->msg, $contextId);

                if ($answer['error'] == false) {
                    if ($answer['msg'] != 'notfound' && (!isset($suggestions[$msg->chat_id]) || !in_array($answer['msg'], $suggestions[$msg->chat_id]))) {
                        $suggestions[$msg->chat_id][] = $answer['msg'];
                    }
                }
            }
        }

        return $suggestions;
    }
}
