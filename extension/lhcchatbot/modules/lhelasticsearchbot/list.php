<?php

$tpl = erLhcoreClassTemplate::getInstance('elasticsearchbot/list.tpl.php');

if (isset($_GET['doSearch'])) {
    $filterParams = erLhcoreClassSearchHandler::getParams(array(
        'customfilterfile' => 'extension/lhcchatbot/classes/filter/questions.php',
        'format_filter' => true,
        'use_override' => true,
        'uparams' => $Params['user_parameters_unordered']
    ));
    $filterParams['is_search'] = true;
} else {
    $filterParams = erLhcoreClassSearchHandler::getParams(array(
        'customfilterfile' => 'extension/lhcchatbot/classes/filter/questions.php',
        'format_filter' => true,
        'uparams' => $Params['user_parameters_unordered']
    ));
    $filterParams['is_search'] = false;
}

$sparams = array(
    'body' => array()
);

if ($filterParams['input_form']->chat_id != '') {
    $sparams['body']['query']['bool']['must'][]['term']['chat_id'] = $filterParams['input_form']->chat_id;
}

$append = erLhcoreClassSearchHandler::getURLAppendFromInput($filterParams['input_form']);

$pages = new lhPaginator();
$pages->serverURL = erLhcoreClassDesign::baseurl('elasticsearchbot/list') . $append;
$pages->items_total = erLhcoreClassModelESChatbotQuestion::getCount($sparams);
$pages->setItemsPerPage(30);
$pages->paginate();

if ($pages->items_total > 0) {
    $tpl->set('items', erLhcoreClassModelESChatbotQuestion::getList(array(
        'offset' => $pages->low,
        'limit' => $pages->items_per_page,
        'body' => array_merge(array(
            'sort' => array(
                'itime' => array(
                    'order' => 'desc'
                )
            )
        ), $sparams['body'])
    )));
}

$tpl->set('pages', $pages);
$tpl->set('input', $filterParams['input_form']);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('lhcchatbot/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhelasticsearch/module', 'ChatBot')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhelasticsearch/list', 'Proposed questions list')
    )
);
