<?php

class erLhcoreClassModelLHCChatBotRasaExample
{
    use erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_lhcchatbot_rasa_example';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionLhcchatbot::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        $stateArray = array(
            'id' => $this->id,
            'intent_id' => $this->intent_id,
            'example' => $this->example,
            'hash' => $this->hash,
            'verified' => $this->verified,
            'active' => $this->active
        );

        return $stateArray;
    }

    public function beforeSave($params = array())
    {
        $this->hash = md5($this->example);
    }

    public function __toString()
    {
        return $this->intent;
    }

    public function __get($var)
    {
        switch ($var) {
            case 'intent':
                $this->intent = null;
                if ($this->intent_id > 0) {
                    $this->intent = erLhcoreClassModelLHCChatBotRasaIntent::fetch($this->intent_id);
                }
                return $this->intent;
        }
    }

    public $id = null;
    public $intent_id = 0;
    public $example = '';
    public $hash = '';
    public $verified = 0;
    public $active = 0;

}