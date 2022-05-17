<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhrasaaitraining/edit.tpl.php');
$item = erLhcoreClassModelLHCChatBotRasaIntent::fetch((int)$Params['user_parameters']['id']);

if ($Params['user_parameters_unordered']['action'] == 'addaction') {

    if (is_numeric(erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['template_settings']['bot_id'])) {

        $bot = erLhcoreClassModelGenericBotBot::fetch(erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['template_settings']['bot_id']);

        if (!($bot instanceof erLhcoreClassModelGenericBotBot)) {
            throw new Exception('Bot could not be found!');
        }

        $triggerGroup = erLhcoreClassModelGenericBotGroup::fetch(erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['template_settings']['trigger_group_id']);

        if (!($triggerGroup instanceof erLhcoreClassModelGenericBotGroup)) {
            throw new Exception('Trigger group could not be found!');
        }

        $triggerTemplate = erLhcoreClassModelGenericBotTriggerTemplate::findOne(['limit' => false, 'filter' => array('name' => erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['template_settings']['trigger_template'])]);

        $eventTemplate = erLhcoreClassModelGenericBotTriggerEventTemplate::findOne(['limit' => false, 'filter' => array('name' => erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['template_settings']['event_template'])]);

        $newTrigger = new erLhcoreClassModelGenericBotTrigger();

        if ($triggerTemplate instanceof erLhcoreClassModelGenericBotTriggerTemplate) {
            $newTrigger->actions = $triggerTemplate->actions;
        }

        $newTrigger->name = $item->intent;
        $newTrigger->bot_id = $bot->id;
        $newTrigger->group_id = $triggerGroup->id;
        $newTrigger->saveThis();

        if ($eventTemplate instanceof erLhcoreClassModelGenericBotTriggerEventTemplate) {
            foreach ($eventTemplate->configuration_array as $patternData) {
                $triggerEvent = new erLhcoreClassModelGenericBotTriggerEvent();
                $triggerEvent->setState($patternData);
                $triggerEvent->configuration = str_replace('{rasa_replace}',$item->intent,(string)$triggerEvent->configuration);
                $triggerEvent->trigger_id = $newTrigger->id;
                $triggerEvent->bot_id = $newTrigger->bot_id;
                $triggerEvent->pattern = str_replace('{rasa_replace}',$item->intent,(string)$triggerEvent->pattern);
                $triggerEvent->pattern_exc = str_replace('{rasa_replace}',$item->intent,(string)$triggerEvent->pattern_exc);
                $triggerEvent->id = null;
                $triggerEvent->saveThis();
            }
        }
    }

    erLhcoreClassModule::redirect('rasaaitraining/dashboard');
    exit;
}

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