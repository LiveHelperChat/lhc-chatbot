<?php

$tpl = erLhcoreClassTemplate::getInstance('elasticsearchbot/viewquestion.tpl.php');

$question =  erLhcoreClassModelESChatbotQuestion::fetch($Params['user_parameters']['id']);

if (ezcInputForm::hasPostData()) {

    if (isset($_POST['Cancel_action'])) {
        erLhcoreClassModule::redirect('elasticsearchbot/list');
        exit ;
    }

    $Errors = erLhcoreClassExtensionLHCChatBotValidator::validateElasticQuestion($question);

    if (count($Errors) == 0) {
        try {

            erLhcoreClassExtensionLHCChatBotValidator::publishElasticQuestion($question);

            $tpl->set('updated',true);
        } catch (Exception $e) {
            $tpl->set('errors',array($e->getMessage()));
        }
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->setArray(array(
    'question' => $question,
));

$sparams = array();
$sparams['body']['query']['bool']['must'][]['term']['question_id'] = $question->id;

$tpl->set('items', erLhcoreClassModelESChatbotAnswer::getList(array(
    'offset' => 0,
    'limit' => 100,
    'body' => array_merge(array(
        'sort' => array(
            'itime' => array(
                'order' => 'desc'
            )
        )
    ), $sparams['body'])
)));

$Result['content'] = $tpl->fetch();
$Result['additional_footer_js'] = '<script src="'.erLhcoreClassDesign::designJS('js/lhc.context.question.ctrl.js').'"></script>';

$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','ChatBot')
    ),
    array (
        'url' =>erLhcoreClassDesign::baseurl('elasticsearchbot/list'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Proposed questions')
    ),
    array (
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Edit')
    )
);

?>