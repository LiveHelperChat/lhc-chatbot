<?php

$tpl = erLhcoreClassTemplate::getInstance('elasticsearch/options.tpl.php');

$esOptions = erLhcoreClassModelChatConfig::fetch('elasticsearch_options');
$data = (array)$esOptions->data;

if ( isset($_POST['StoreOptions']) ) {

    $definition = array(
        'use_es_statistic' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'last_index_msg_id' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int'
        )
    );
      
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
            
    if ( $form->hasValidData( 'use_es_statistic' ) && $form->use_es_statistic == true ) {
        $data['use_es_statistic'] = 1;
    } else {
        $data['use_es_statistic'] = 0;
    }
            
    if ( $form->hasValidData( 'last_index_msg_id' )) {
        $data['last_index_msg_id'] = $form->last_index_msg_id ;
    } else {
        $data['last_index_msg_id'] = 0;
    }
     
    $esOptions->explain = '';
    $esOptions->type = 0;
    $esOptions->hidden = 1;
    $esOptions->identifier = 'elasticsearch_options';
    $esOptions->value = serialize($data);
    $esOptions->saveThis();
    
    $tpl->set('updated','done');
}

$tpl->set('es_options',$data);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('elasticsearch/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhelasticsearch/module', 'Elastic Search')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhelasticsearch/module', 'Options')
    )
);

?>