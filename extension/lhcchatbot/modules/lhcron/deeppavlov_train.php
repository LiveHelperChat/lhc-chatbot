<?php
// Run me every 5 minutes
// /usr/bin/php cron.php -s site_admin -e lhcchatbot -c cron/deeppavlov_train

echo "\n==Indexing questions== \n";

foreach (erLhcoreClassModelLHCChatBotContext::getList() as $context)
{
    $file = fopen("extension/lhcchatbot/train/train_" . $context->id . ".csv","w");
    fputcsv($file, array("Question","Answer"));

    foreach (erLhcoreClassModelLHCChatBotQuestion::getList(array('limit' => false, 'filter' => array('confirmed' => 1, 'context_id' => $context->id))) as $question) {
        
        if ($question->hash == '') {
            $question->saveThis();
        }

        foreach ($question->question_items as $questionString) {
            if (trim($questionString) != '' && trim($question->answer) != '') {
                fputcsv($file, array(trim($questionString),$question->answer.'__'.$question->hash));
            }
        }
    }

    fclose($file);
}

?>