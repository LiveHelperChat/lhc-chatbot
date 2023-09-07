<?php
#[\AllowDynamicProperties]
class erLhcoreClassModelLHCChatBotContextLinkDepartment
{

    use erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_lhcchatbot_context_link_department';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionLhcchatbot::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        $stateArray = array(
            'id' => $this->id,
            'context_id' => $this->context_id,
            'department_id' => $this->department_id
        );

        return $stateArray;
    }

    public function __toString()
    {
        return $this->context;
    }

    public function __get($var)
    {
        switch ($var) {

            default:
                break;
        }
    }

    public $id = null;
    public $context_id = 0;
    public $department_id = 0;
}