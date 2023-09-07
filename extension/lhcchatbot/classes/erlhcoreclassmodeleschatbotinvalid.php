<?php
#[\AllowDynamicProperties]
class erLhcoreClassModelLHCChatBotInvalid {

    use erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_lhcchatbot_invalid';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionLhcchatbot::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        $stateArray = array(
            'id' => $this->id,
            'question' => $this->question,
            'answer' => $this->answer,
            'counter' => $this->counter,
            'chat_id' => $this->chat_id,
            'context_id' => $this->context_id,
            'hash' => $this->hash
        );

        return $stateArray;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'context':
                $this->context = erLhcoreClassModelLHCChatBotContext::fetch($this->context_id);
                return $this->context;
                break;

            default:
                break;
        }
    }

    public function __toString()
    {
        return $this->question;
    }

    public $id = null;
    public $question = '';
    public $answer = '';
    public $counter = '';
    public $hash = '';
    public $chat_id = 0;
    public $context_id = 0;
}