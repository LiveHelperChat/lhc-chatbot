<?php

class erLhcoreClassModelLHCChatBotUse
{

    use erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_lhcchatbot_use';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionLhcchatbot::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        $stateArray = array(
            'id' => $this->id,
            'question' => $this->question,
            'answer' => $this->answer,
            'context_id' => $this->context_id,
            'question_id' => $this->question_id,
            'dep_id' => $this->dep_id,
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'ctime' => $this->ctime,
            'type' => $this->type,
        );

        return $stateArray;
    }

    public function __toString()
    {
        return $this->answer;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'context':
                $this->context = '';
                if ($this->context_id > 0) {
                    $this->context = erLhcoreClassModelLHCChatBotContext::fetch($this->context_id);
                }
                return $this->context;
                break;

            case 'user':
                if ($this->user_id > 0) {
                    $this->user = erLhcoreClassModelUser::fetch($this->user_id);
                } else {
                    $this->user = null;
                }
                return $this->user;
                break;

            default:
                ;
                break;
        }
    }

    public $snapshot = array();

    public $id = null;
    public $context_id = 0;
    public $type = 0;
    public $question = '';
    public $answer = '';
    public $question_id = 0;
    public $user_id = 0;
    public $chat_id = 0;
    public $ctime = 0;

}