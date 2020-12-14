<?php

class erLhcoreClassModelLHCChatBotQuestion
{

    use erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_lhcchatbot_question';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionLhcchatbot::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        $stateArray = array(
            'id' => $this->id,
            'question' => $this->question,
            'hash' => $this->hash,
            'answer' => $this->answer,
            'context_id' => $this->context_id,
            'was_used' => $this->was_used,
            'confirmed' => $this->confirmed,
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
        );

        return $stateArray;
    }

    public function __toString()
    {
        return $this->answer;
    }

    public function beforeSave()
    {
        $this->hash = md5($this->question);
    }

    public function snapshot()
    {
        if ($this->id > 0) {
            $this->snapshot = array(
                'question' => $this->question,
                'answer' => $this->answer,
                'context' => $this->context_id,
            );
        }
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

            case 'question_items_snapshot':
                $this->question_items_snapshot = array();
                if (isset($this->snapshot['question'])) {
                    $this->question_items_snapshot = explode("\n", trim($this->snapshot['question']));
                }
                return $this->question_items_snapshot;
                break;

            case 'user':
                if ($this->user_id > 0) {
                    $this->user = erLhcoreClassModelUser::fetch($this->user_id);
                } else {
                    $this->user = null;
                }
                return $this->user;
                break;

            case 'question_items':
                $this->question_items = explode("\n", trim($this->question));
                return $this->question_items;
                break;
            default:
                ;
                break;
        }
    }

    public $snapshot = array();

    public $id = null;
    public $context_id = 0;
    public $question = '';
    public $hash = '';
    public $answer = '';
    public $was_used = 0;
    public $confirmed = 1;
    public $chat_id = 0;
    public $user_id = 0;
}