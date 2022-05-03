<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhrasaaitraining/testconsole.tpl.php');

$input = new stdClass();
$input->rasa_ai_server = '';
$input->question = '';

if (isset($_POST['TestAction']))
{
    $definition = array(
        'rasa_ai_server' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'question' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
    );

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('rasaaitraining/testconsole');
        exit;
    }

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ( $form->hasValidData( 'rasa_ai_server' ) && $form->rasa_ai_server != '' ) {
        $input->rasa_ai_server = $form->rasa_ai_server;
        if (!filter_var($input->rasa_ai_server, FILTER_VALIDATE_URL)) {
            $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Server URL is not a valid structure!');
        }
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter a valid Rest API server!');
    }

    if ( $form->hasValidData( 'question' ) && $form->question != '' ) {
        $input->question = $form->question;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter a test sentence!');
    }

    if (empty($Errors)) {
        $url = parse_url($input->rasa_ai_server);
        // Only http/https supported
        if (!in_array($url['scheme'],['http','https'])) {
            $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('xmppservice/operatorvalidator','Please enter a test sentence!');
        }
    }

    if (empty($Errors)) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input->question);
        curl_setopt($ch, CURLOPT_URL, $input->rasa_ai_server);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $content = curl_exec($ch);

        if (curl_errno($ch)) {
            $http_error = curl_error($ch);
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $contentJSON = json_decode($content, true);

        if ($httpcode == 200) {
            $tpl->set('answer',$contentJSON);
        } else {
            $tpl->set('answer',$content);
        }
        
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('input',$input);
$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Reply Predictions')
    ),
    array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('department/departments','Rasa AI test console')),
)

?>