<?php

class erLhcoreClassModelLHCChatBotQuestion {

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
            'answer' => $this->answer
		);

		return $stateArray;
	}

	public function __toString()
	{
		return $this->answer;
	}

	public function snapshot()
	{
	    if ($this->id > 0) {
    	    $this->snapshot = array(
    	        'question' => $this->question,
    	        'answer' => $this->answer,
    	    );
	    }
	}

	public function __get($var)
	{
	    switch ($var) {
	
            case 'question_items_snapshot':
                $this->question_items_snapshot = array();
                if (isset($this->snapshot['question'])) {
                    $this->question_items_snapshot = explode("\n", trim($this->snapshot['question']));
                }
                return $this->question_items_snapshot;
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
	public $question = '';
	public $answer = '';
}