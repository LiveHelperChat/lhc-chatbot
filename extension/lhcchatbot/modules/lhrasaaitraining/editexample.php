<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhrasaaitraining/editexample.tpl.php');
$item = erLhcoreClassModelLHCChatBotRasaExample::fetch((int)$Params['user_parameters']['id']);

if ( isset($_POST['Cancel_action']) ) {
    erLhcoreClassModule::redirect('rasaaitraining/listexample');
    exit;
}

if (isset($_POST['Save_action']) || isset($_POST['Update_action']))
{
    $Errors = erLhcoreClassExtensionLHCChatBotValidator::validateExample($item);

    if (count($Errors) == 0)
    {
        $item->saveThis();

        if (isset($_POST['Update_action'])) {
            erLhcoreClassModule::redirect('rasaaitraining/editexample','/' . $item->id);
        } else {
            erLhcoreClassModule::redirect('rasaaitraining/listexample');
        }
        exit;

    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('item',$item);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Reply Predictions')
    ),
    array('url' => erLhcoreClassDesign::baseurl('rasaaitraining/list'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('department/departments','Rasa AI Intent')),
    array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('department/new','New')),
)

?>