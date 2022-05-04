<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhrasaaitraining/dashboard.tpl.php');
$tpl->set('items',erLhcoreClassModelLHCChatBotRasaIntent::getList(['sort' => '`context_id` ASC','limit' => false, 'filter' => ['active' => 1]]));

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Reply Predictions')
    ),
    array('url' => erLhcoreClassDesign::baseurl('rasaaitraining/list'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('department/departments','Rasa AI Intent')),
    array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('department/new','Dashboard')),
)

?>