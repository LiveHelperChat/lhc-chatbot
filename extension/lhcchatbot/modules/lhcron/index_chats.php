<?php

// /usr/bin/php cron.php -s site_admin -e lhcchatbot -c cron/index_chats [-p <dep_id>,<dep_id>]

$filter = array();

if ($cronjobPathOption->value != '') {
    $filter['filterin']['dep_id'] = explode(',',$cronjobPathOption->value);
}

echo "Indexing chats\n";

$pageLimit = 100;

$parts = ceil(erLhcoreClassModelChat::getCount($filter)/$pageLimit);

for ($i = 0; $i < $parts; $i++) {

    echo "Saving chat page - ",($i + 1),"\n";

    $items = erLhcoreClassModelChat::getList(array_merge_recursive(array('offset' => $i*$pageLimit, 'limit' => $pageLimit, 'sort' => 'id ASC'), $filter));

    erLhcoreClassElasticSearchChatboxIndex::indexChats(array('chats' => $items));
}

?>