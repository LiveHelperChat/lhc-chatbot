<?php
#[\AllowDynamicProperties]
class erLhcoreClassModelESChatbotQuestion
{
    use erLhcoreClassElasticChatbotTrait;

    public function getState()
    {
        $states = array(
            'question' => $this->question,
            'match_count' => $this->match_count,
            'hidden' => $this->hidden,
            'itime' => $this->itime,
            'chat_id' => $this->chat_id,
            'confirmed' => $this->confirmed,
            'cbot_question_id' => $this->cbot_question_id,
            'dep_ids' => $this->dep_ids,
            'cbot_question_ids' => $this->cbot_question_ids,
        );

        return $states;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'itime_front':
                $this->itime_front = date('Ymd') == date('Ymd', $this->itime / 1000) ? date(erLhcoreClassModule::$dateHourFormat, $this->itime / 1000) : date(erLhcoreClassModule::$dateDateHourFormat, $this->itime / 1000);
                return $this->itime_front;
                break;

            case 'cbot_question_objects':
                    $this->cbot_question_objects = array();
                    if (!empty($this->cbot_question_ids)) {
                        $this->cbot_question_objects = erLhcoreClassModelLHCChatBotQuestion::getList(array('filterin' => array('id' => $this->cbot_question_ids)));
                    }
                    return $this->cbot_question_objects;
                break;

            case 'cbot_question_array':
                    $this->cbot_question_array = array();

                    foreach ($this->cbot_question_objects as $item) {
                        $this->cbot_question_array[] = array(
                            'name' => $item->answer,
                            'context' => $item->context_id,
                            'id' => $item->id,
                        );
                    }

                    return $this->cbot_question_array;
                break;

            case 'cbot_question':

                if ($this->cbot_question_id > 0) {
                    $this->cbot_question = erLhcoreClassModelLHCChatBotQuestion::fetch($this->cbot_question_id);
                } else {
                    $this->cbot_question = new erLhcoreClassModelLHCChatBotQuestion();
                }

                return $this->cbot_question;
                break;

            case 'dep_ids_obj':
                $this->dep_ids_obj = array();
                if (!empty($this->dep_ids)){
                    $this->dep_ids_obj = erLhcoreClassModelDepartament::getList(array('filterin' => array('id' => $this->dep_ids)));
                }
                return $this->dep_ids_obj;
                break;

            case 'dep_ids_obj_names':
                    $this->dep_ids_obj_names = array();
                    foreach ($this->dep_ids_obj as $dep) {
                        $this->dep_ids_obj_names[] = $dep->name;
                    }
                    return $this->dep_ids_obj_names;
                break;

            default:
                break;
        }
    }

    public static $elasticType = 'lh_chatbot_question';

    public $question = null;

    public $match_count = null;

    public $chat_id = null;

    public $confirmed = 0;

    public $cbot_question_id = 0;

    public $itime = null;

    public $hidden = 0;

    public $dep_ids = [];

    public $cbot_question_ids = [];
}