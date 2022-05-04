<?php

if ((!erLhcoreClassUser::instance()->isLogged() || !erLhcoreClassUser::instance()->hasAccessTo('lhrasaaitraining','download')) && (!isset($_GET['secret_hash']) || erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['secret_hash'] != $_GET['secret_hash'])){
    http_response_code(404);
    exit;
}

header('Content-Type:text/yaml; charset=UTF-8');

$nlu =
    "version: \"2.0\"

nlu:\n";

$item = erLhcoreClassModelLHCChatBotContext::fetch((int)$Params['user_parameters']['id']);

foreach (erLhcoreClassModelLHCChatBotRasaIntent::getList(['filter' => ['active' => 1, 'context_id' => $item->id]]) as $intent) {
    if ($intent->test_samples != '') {
        $nlu .="  - intent: {$intent->intent}\n    examples: |\n";
        foreach (explode("\n",$intent->test_samples) as $example) {
            $example = trim($example);
            if ($example != "") {
                $nlu .="      - {$example}\n";
            }
        }
    }
}

echo trim($nlu);
exit;