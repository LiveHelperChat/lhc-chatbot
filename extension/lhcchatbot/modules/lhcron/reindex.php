<?php 

/**
 * php cron.php -s site_admin -e lhcchatbot -c cron/reindex
 * */

echo "Reindexing\n";

$pageLimit = 100;

$parts = ceil(erLhcoreClassModelLHCChatBotQuestion::getCount()/$pageLimit);

for ($i = 0; $i < $parts; $i++) {

    echo "Saving questions page - ",($i + 1),"\n";

    $items = erLhcoreClassModelLHCChatBotQuestion::getList(array('offset' => $i*$pageLimit, 'limit' => $pageLimit, 'sort' => 'id ASC'));

    foreach ($items as $item) {
        erLhcoreClassExtensionLHCChatBotValidator::publishQuestion($item);
        echo "Indexing question id - ",$item->id,"\n";
    }
}


?>