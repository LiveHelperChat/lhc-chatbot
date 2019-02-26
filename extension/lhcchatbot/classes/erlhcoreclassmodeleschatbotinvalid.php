<?php

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
            'counter' => $this->counter
        );

        return $stateArray;
    }

    public function __toString()
    {
        return $this->question;
    }

    public $id = null;
    public $question = '';
    public $answer = '';
    public $counter = '';
}