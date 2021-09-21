<?php

if (isset($_GET['setintent'])) {
    $item = erLhcoreClassModelLHCChatBotRasaExample::fetch($_POST['id']);
    $item->intent_id = $_POST['intent_id'];
    $item->updateThis(['update' => ['intent_id']]);
    exit;
}

if (isset($_GET['intentlist'])) {
    echo erLhcoreClassRenderHelper::renderCombobox( array (
        'input_name'     => 'intent_id_'.(int)$_POST['id'],
        'optional_field' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('department/edit','Choose'),
        'display_name'   => function($item) {
            return $item->name . ' ' . ($item->intent);
        },
        'data_attr'      => 'data-id="' . (int)$_POST['id'] . '"',
        'css_class'      => 'form-control form-control-sm intent-item-edit',
        'selected_id'    => (isset($_POST['intent_id']) ? (int)$_POST['intent_id'] : 0),
        'list_function'  => 'erLhcoreClassModelLHCChatBotRasaIntent::getList',
        'list_function_params'  => array('sort' => 'name ASC, intent ASC'),
    ));
    exit;
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhrasaaitraining/listexample.tpl.php');

if (isset($_GET['doSearch'])) {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/lhcchatbot/classes/filter/rasa_example.php','format_filter' => true, 'use_override' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = true;
} else {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/lhcchatbot/classes/filter/rasa_example.php','format_filter' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = false;
}

$append = erLhcoreClassSearchHandler::getURLAppendFromInput($filterParams['input_form']);

if (isset($_GET['ajax']) && isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['change_data'])) {
    $item = erLhcoreClassModelLHCChatBotRasaExample::fetch($_POST['id']);
    $item->verified = isset($_POST['verified']) && $_POST['verified'] == 'true';
    $item->active = isset($_POST['active']) && $_POST['active'] == 'true';
    $item->updateThis(['update' => ['verified','active']]);
}

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelLHCChatBotRasaExample::getCount($filterParams['filter']);
$pages->translationContext = 'chat/pendingchats';
$pages->serverURL = erLhcoreClassDesign::baseurl('rasaaitraining/listexample').$append;
$pages->paginate();
$tpl->set('pages',$pages);

if ($pages->items_total > 0) {
    $items = erLhcoreClassModelLHCChatBotRasaExample::getList(array_merge($filterParams['filter'],array('limit' => $pages->items_per_page,'offset' => $pages->low)));
    $tpl->set('items',$items);
}

$filterParams['input_form']->form_action = erLhcoreClassDesign::baseurl('rasaaitraining/listexample');
$tpl->set('input', $filterParams['input_form']);
$tpl->set('inputAppend', $append);
$tpl->set('ajax', isset($_GET['ajax']));

if (isset($_GET['ajax'])) {
    echo $tpl->fetch();
    exit;
}

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array (
        'url' => erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Reply Predictions')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('lhcchatbot/listexample'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Rasa AI Intent')
    )

);

?>
