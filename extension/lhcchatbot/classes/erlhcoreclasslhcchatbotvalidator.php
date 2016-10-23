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

        return $Errors;
    }

    public static function publishQuestion(erLhcoreClassModelLHCChatBotQuestion & $question)
    {
        // Save question
        $api = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->getApi();

        // Remove questions if there was changes
        foreach ($question->question_items_snapshot as $q)
        {
            $api->removeQuestion(trim($q));
        }
        
        // Add as new questions
        foreach ($question->question_items as $q)
        {
            $api->addQuestion(trim($q), trim($question->answer));
        }

        $question->saveThis();
    }

    public static function deleteQuestion(erLhcoreClassModelLHCChatBotQuestion & $question)
    {
        // Save question
        $api = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->getApi();
        
        foreach ($question->question_items as $q)
        {
            $api->removeQuestion(trim($q));
        }
        
        $question->removeThis();
    }

    public static function suggestByIds($ids)
    {
        // Save question
        $api = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->getApi();
        
        $msgs = erLhcoreClassModelmsg::getList(array('filterin' => array('id' => $ids)));

        $suggestions = array();
        
        foreach ($msgs as $msg) {
            $answer = $api->getAnswer($msg->msg);
            
            if ($answer['error'] == false) {
                $suggestions[$msg->chat_id][] = $answer['msg'];
            }
        }
        
        return $suggestions;
    }
}
