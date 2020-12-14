<?php

class erLhcoreClassModelLHCChatBotContext {

    use erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_lhcchatbot_context';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionLhcchatbot::getSession';

    public static $dbSortOrder = 'DESC';

	public function getState()
	{
		$stateArray = array(
            'id' => $this->id,
            'name' => $this->name,
            'host' => $this->host,
		);

		return $stateArray;
	}

	public function __toString()
	{
		return $this->name;
	}

	public function __get($var)
	{
	    switch ($var) {
	                            
	        default:
	            ;
	            break;
	    }
	}
	
	public $id = null;
	public $name = '';
	public $host = '';
}