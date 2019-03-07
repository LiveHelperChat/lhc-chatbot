<?php

session_write_close();

erLhcoreClassChat::validateFilterIn($Params['user_parameters_unordered']['id']);

if (!empty($Params['user_parameters_unordered']['id'])) {
    $suggestions = erLhcoreClassExtensionLHCChatBotValidator::suggestByIds($Params['user_parameters_unordered']['id'],($Params['user_parameters_unordered']['chat'] == 1 ? true : false));
    $chatsUnsupported = erLhcoreClassExtensionLHCChatBotValidator::getUnsupportedChats($Params['user_parameters_unordered']['id'],($Params['user_parameters_unordered']['chat'] == 1 ? true : false));
    echo json_encode(array('un' => $chatsUnsupported, 'sg' => $suggestions));
}

exit;
?>