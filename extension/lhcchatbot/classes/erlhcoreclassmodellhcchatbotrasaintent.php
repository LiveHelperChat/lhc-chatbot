<?php

class erLhcoreClassModelLHCChatBotRasaIntent
{

    use erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_lhcchatbot_rasa_intent';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionLhcchatbot::getSession';

    public static $dbSortOrder = 'DESC';

    public static $dbDefaultSort = 'name ASC, intent ASC';

    public function getState()
    {
        $stateArray = array(
            'id' => $this->id,
            'intent' => $this->intent,
            'active' => $this->active,
            'name' => $this->name,
            'context_id' => $this->context_id,
        );

        return $stateArray;
    }

    public function beforeRemove()
    {
        $q = ezcDbInstance::get()->createDeleteQuery();
        foreach (['lhc_lhcchatbot_rasa_example'] as $table){
            $q->deleteFrom($table)->where( $q->expr->eq( 'intent_id', $this->id ) );
            $stmt = $q->prepare();
            $stmt->execute();
        }
    }

    public function __toString()
    {
        return $this->intent;
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
        }
    }

    public $id = null;
    public $intent = '';
    public $active = '';
    public $name = '';
    public $context_id = 0;

}