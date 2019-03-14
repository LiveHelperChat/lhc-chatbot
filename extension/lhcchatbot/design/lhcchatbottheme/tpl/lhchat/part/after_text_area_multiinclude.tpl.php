<?php 

$lhcChatBotLast5Messages = array();

foreach (array_reverse($messages) as $msg) {
    if ($msg['user_id'] == 0) {
        $lhcChatBotLast5Messages[] = (int)$msg['id'];
    }
    if (count($lhcChatBotLast5Messages) == 5) {
        break;
    }
}

if (erLhcoreClassUser::instance()->hasAccessTo('lhlhcchatbot','use') == false) {
    echo "<script>lhcChatBot.disabled = true;</script>";
}

if (!empty($lhcChatBotLast5Messages)) {
    echo "<script>lhcChatBot.syncadmin({'chatbotids':". json_encode($lhcChatBotLast5Messages) ."})</script>";
}

?>