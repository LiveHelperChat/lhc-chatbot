<?php
// Run me every 5 minutes
// /usr/bin/php cron.php -s site_admin -e lhcchatbot -c cron/cron

echo "\n==Indexing chats== \n";

$totalIndex = 0;

$db = ezcDbInstance::get();
$db->beginTransaction();

for ($i = 0; $i < 100; $i++) {
    $stmt = $db->prepare('SELECT chat_id FROM lhc_lhcchatbot_index LIMIT :limit FOR UPDATE ');
    $stmt->bindValue(':limit',100,PDO::PARAM_INT);
    $stmt->execute();
    $chatsId = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($chatsId)) {
        $chats = erLhcoreClassModelChat::getList(array('filterin' => array('id' => $chatsId)));

        if (!empty($chats)){
            $totalIndex+= count($chats);
            erLhcoreClassElasticSearchChatboxIndex::indexChats(array('chats' => $chats));
        }

        // Delete indexed chat's records
        $stmt = $db->prepare('DELETE FROM lhc_lhcchatbot_index WHERE chat_id IN (' . implode(',', $chatsId) . ')');
        $stmt->execute();

    } else {
        break;
    }
}

$db->commit();

echo "total indexed chats - {$totalIndex}\n";

?>
