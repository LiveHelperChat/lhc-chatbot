<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhrasaaitraining/new.tpl.php');
$item = new erLhcoreClassModelLHCChatBotRasaIntent();

if ( isset($_POST['Cancel_action']) ) {
    erLhcoreClassModule::redirect('rasaaitraining/list');
    exit;
}

if (isset($_POST['Save_action']) || isset($_POST['Update_action']))
{
    $Errors = erLhcoreClassExtensionLHCChatBotValidator::validateIntent($item);

    if (count($Errors) == 0)
    {
        $item->saveThis();

        if (isset($_POST['Update_action'])) {
            erLhcoreClassModule::redirect('rasaaitraining/edit','/' . $item->id);
        } else {
            erLhcoreClassModule::redirect('rasaaitraining/list');
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