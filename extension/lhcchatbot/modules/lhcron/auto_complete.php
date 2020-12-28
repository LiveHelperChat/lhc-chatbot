<?php
// /usr/bin/php cron.php -s site_admin -e lhcchatbot -c cron/auto_complete
// Exports data for auto complete process
// You can have similar one to this for your own data

$list = erLhcoreClassModelCannedMsg::getList(array('limit' => false, 'filterlike' => array('msg' => '{')));

$messagesReplaceable = array('op' => [], 'vi' => []);

foreach ($list as $item) {
    if (strpos($item->msg,"\n") === false) {
        if (strpos($item->msg,'{operator}') !== false){
            $messagesReplaceable['op'][] = str_replace(array('{operator}','{firstname}','{nick}'),'(.*?)',str_replace(array('(',')','/'),array('\(','\)','\/'),trim($item->msg)));
        } else {
            $messagesReplaceable['vi'][] = str_replace(array('{operator}','{firstname}','{nick}'),'(.*?)',str_replace(array('(',')','/'),array('\(','\)','\/'),trim($item->msg)));
        }
    }
}

$name = explode("\n",file_get_contents('extension/lhcchatbot/doc/first_names.all.txt'));

foreach (erLhcoreClassModelDepartament::getList(array('filter' => array('archive' => 0))) as $dep) {
    $messagesIndex = [];
    $messagesIndexChats = [];

    // type - 0 - canned messages
    $indexCounter = 1;

    $list = erLhcoreClassModelCannedMsg::getList(array('limit' => false, 'filter_custom' => array('(department_id = ' . $dep->id . ' OR department_id = 0) AND user_id = 0')));
    foreach ($list as $item) {

        $msg = trim($item->msg);

        $messagesIndex[md5($msg)] = array(
            'id' => $indexCounter,
            'title' => $item->title,
            'question' => $msg,
            'type' => 0
        );
        $indexCounter++;
    }

    $chats = erLhcoreClassModelChat::getList(array('sort' => 'id DESC', 'limit' => 180,'filter' => array('dep_id' => $dep->id), 'filtergt' => array('user_id' => 0)));

    $index = 1;

    echo $counter = 0;

    foreach ($chats as $chat) {

        echo $counter++,"-",$chat->id,"\n";

        $messages = erLhcoreClassModelmsg::getList(array('sort' => 'id DESC','limit' => 10000, 'filter' => array('chat_id' => $chat->id), 'filtergt' => array('user_id' => 0)));
        foreach ($messages as $message) {
            $msgSearch = $message->msg;
            if (strlen($msgSearch) > 5 && strpos($msgSearch,"\n") === false) {

                // Check is it one of our canned messages
                foreach ($messagesReplaceable as $type => $msgReplacable) {
                    foreach ($msgReplacable as $replace) {
                        if (preg_match('/'.$replace.'$/is',$msgSearch)) {
                            $matches = array();
                            preg_match_all('/^'.$replace.'$/i', $msgSearch,$matches);
                            if (isset($matches[1][0])){
                                $msgSearch = str_replace($matches[1][0],$type == 'op' ? '{operator}' : '{firstname}', $msgSearch);
                            }
                        }
                    }
                }

                // Check does message have a firstname

                $namesIntersect = array_intersect(explode(' ',str_replace('  ',' ',str_replace(array(',','.','!','?',"\t"), ' ', mb_strtolower($msgSearch)))), $name);

                $skipMessage = false;
                if (!empty($namesIntersect)) {
                    foreach ($namesIntersect as $nameFound) {
                        if (strpos($msgSearch,ucfirst($nameFound)) !== false) {
                            $msgSearch = str_ireplace(ucfirst($nameFound),'{firstname}',$msgSearch);
                        } else {
                            $skipMessage = true;
                            echo $nameFound,"\n";
                        }
                    }
                }

                if (preg_replace('/^\[(.*?)\]$/is','',$msgSearch) == '') {
                    $skipMessage = true;
                }

                if (strpos($msgSearch,'Username - ') !== false) {
                    $skipMessage = true;
                }

                if (strpos($msgSearch,'your username') !== false) {
                    $skipMessage = true;
                }

                if (strpos($msgSearch,'try username') !== false) {
                    $skipMessage = true;
                }

                if (strpos($msgSearch,'is the email it') !== false) {
                    $skipMessage = true;
                }
                if (strpos($msgSearch,' your user name ') !== false) {
                    $skipMessage = true;
                }

                if (strpos($msgSearch,'User Name:') !== false) {
                    $skipMessage = true;
                }

                $msgSearch = preg_replace('/\$[0-9]{1,3}.[0-9]{1,2}/is','${money}',$msgSearch);
                $msgSearch = preg_replace("/( with your user name )(.*?) and/","\\1{username} and",$msgSearch);
                $msgSearch = preg_replace("/(user name is )([a-zA-Z0-9])+/","\\1{username}",$msgSearch);
                $msgSearch = preg_replace("/(username is )([a-zA-Z0-9])+/","\\1{username}",$msgSearch);
                $msgSearch = preg_replace("/(with the user name: )([a-zA-Z0-9])+/","\\1{username}",$msgSearch);
                $msgSearch = preg_replace("/(with the username )([a-zA-Z0-9])+/","\\1{username}",$msgSearch);
                $msgSearch = preg_replace("/(or the username )([a-zA-Z0-9])+/","\\1{username}",$msgSearch);
                $msgSearch = preg_replace("/(, username )([a-zA-Z0-9])+/","\\1{username}",$msgSearch);
                $msgSearch = preg_replace("/(log in with )([a-zA-Z0-9])+( as)/","\\1{username}\\3",$msgSearch);
                $msgSearch = preg_replace("/([a-zA-Z0-9])+( as a temporary password)/","{password}\\2",$msgSearch);
                $msgSearch = preg_replace("/((My|my) name is )([\{\}a-zA-Z0-9])+/","\\1{operator}",$msgSearch);

                $msgSearch = str_replace(array('January','February','March','April','May','June','August','September','October','November','December'),'{month}',$msgSearch);

                $msgSearch = str_replace(array(
                    ' ?',
                    ' ,',
                    'thatr',
                    'shuld',
                    'weeily',
                    'mroning',
                    'waitimg',
                    date('Y'),
                    'its',
                    'yoru',
                    'Remmeber',
                    'Letm e',
                    'all ste',
                    'tranasfer',
                    'tht\'s',
                    'mometn',
                    'winderful',
                    'keybard',
                    'wlll',
                    'done1',
                    'there1',
                    'everthing',
                    'f0or',
                    'Whay',
                    'yougetting',
                    'Happyto',
                    'pateince',
                    'tehre',
                    'forholding',
                    'trnsaction',
                    'eligibel',
                    'welcom e',
                    'rewad',
                    'HI',
                    'chnages',
                    'Youre',
                    '  agood',
                    '  '
                ),array(
                    '?',
                    ',',
                    'that',
                    'should',
                    'weekly',
                    'morning',
                    'waiting',
                    '{year}',
                    'it\'s',
                    'your',
                    'Remember',
                    'Let me',
                    'all set',
                    'transfer',
                    'that\'s',
                    'moment',
                    'wonderful',
                    'keyboard',
                    'will',
                    'done!',
                    'there!',
                    'everything',
                    'for',
                    'What',
                    'you getting',
                    'Happy to',
                    'patience',
                    'there',
                    'for holding',
                    'transaction',
                    'eligible',
                    'welcome',
                    'reward',
                    'Hi',
                    'changes',
                    'You\'re',
                    ' a good',
                    ' ',
                ),$msgSearch);

                $msgSearch = trim(rtrim($msgSearch,'/'));

                if ($dep->email != '' && strpos($msgSearch,'@' . explode('@',$dep->email)[1]) === false && !key_exists(md5($msgSearch),$messagesIndex)) {
                    $msgSearch = preg_replace("/[-\w\.]+@([-a-z0-9]+\.)+[a-z]{2,4}/i",'{email}', $msgSearch);
                    // Only e-mail was found so we exclude
                    if ($msgSearch == '{email}') {
                        $skipMessage = true;
                    }
                }

                if ($skipMessage == false && !key_exists(md5($msgSearch),$messagesIndex)) {
                    $messagesIndexChats[md5($msgSearch)] = array(
                        'id' => (int)$indexCounter,
                        'question' => $msgSearch,
                        'type' => 10,
                    );
                    $indexCounter++;
                }

            }
        }
    }

    $finalMessages = array_values($messagesIndex)+array_values($messagesIndexChats);
    file_put_contents('extension/lhcchatbot/train/autocomplete_hash_' . $dep->id . '.json',json_encode($finalMessages,JSON_PRETTY_PRINT));

    foreach ($finalMessages as $key => $finalMessage) {
        if (isset($finalMessages[$key]['title'])) {
            unset($finalMessages[$key]['title']);
        }
    }

    file_put_contents('extension/lhcchatbot/train/autocomplete_text_' . $dep->id . '.json',json_encode($finalMessages,JSON_PRETTY_PRINT));


}



?>