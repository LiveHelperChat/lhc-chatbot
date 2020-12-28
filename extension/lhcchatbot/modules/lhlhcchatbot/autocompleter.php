<?php

$tpl = erLhcoreClassTemplate::getInstance('lhcchatbot/autocompleter.tpl.php');

$lhcchatboOptions = erLhcoreClassModelChatConfig::fetch('lhcchatbot_options');

$data = (array)$lhcchatboOptions->data;

if ( isset($_POST['StoreOptions']) ) {

    $definition = array(
        'msearch_host' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'public_key' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'enabled' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ( $form->hasValidData( 'enabled' ) && $form->enabled == true ) {
        $data['enabled'] = 1;
    } else {
        $data['enabled'] = 0;
    }

    if ($form->hasValidData( 'public_key' )) {
        $data['public_key'] = $form->public_key;
    } else {
        $data['public_key'] = '';
    }

    if ($form->hasValidData( 'msearch_host' )) {
        $data['msearch_host'] = $form->msearch_host;
    } else {
        $data['msearch_host'] = '';
    }

    $lhcchatboOptions->explain = '';
    $lhcchatboOptions->type = 0;
    $lhcchatboOptions->hidden = 1;
    $lhcchatboOptions->identifier = 'lhcchatbot_options';
    $lhcchatboOptions->value = serialize($data);
    $lhcchatboOptions->saveThis();

    $tpl->set('updated','done');
}

$tpl->set('lhcchatbot_options',$data);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('lhcinsult/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult', 'Reply Predictions')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('module/lhcinsult', 'Auto completer')
    )
);

?>