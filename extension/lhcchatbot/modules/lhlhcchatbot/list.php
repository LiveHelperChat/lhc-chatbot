<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhcchatbot/list.tpl.php');

if (isset($_GET['doSearch'])) {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/lhcchatbot/classes/filter.php','format_filter' => true, 'use_override' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = true;
} else {
    $filterParams = erLhcoreClassSearchHandler::getParams(array('customfilterfile' => 'extension/lhcchatbot/classes/filter.php','format_filter' => true, 'uparams' => $Params['user_parameters_unordered']));
    $filterParams['is_search'] = false;
}

$append = erLhcoreClassSearchHandler::getURLAppendFromInput($filterParams['input_form']);

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelLHCChatBotQuestion::getCount($filterParams['filter']);
$pages->translationContext = 'chat/pendingchats';
$pages->serverURL = erLhcoreClassDesign::baseurl('lhcchatbot/list').$append;
$pages->paginate();
$tpl->set('pages',$pages);

if ($pages->items_total > 0) {
	$items = erLhcoreClassModelLHCChatBotQuestion::getList(array_merge($filterParams['filter'],array('limit' => $pages->items_per_page,'offset' => $pages->low)));
	$tpl->set('items',$items);
}

$filterParams['input_form']->form_action = erLhcoreClassDesign::baseurl('lhcchatbot/list');
$tpl->set('input',$filterParams['input_form']);
$tpl->set('inputAppend',$append);
$tpl->set('current_user_id',$currentUser->getUserID());

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','ChatBot')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('lhcchatbot/list'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Questions and Answers')
    )
   
);

?>
