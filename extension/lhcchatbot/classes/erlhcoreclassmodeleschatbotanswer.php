<?php
#[\AllowDynamicProperties]
class erLhcoreClassModelESChatbotAnswer
{
    use erLhcoreClassElasticChatbotTrait;

    public function getState()
    {
        $states = array(
            'answer' => $this->answer,
            'match_count' => $this->match_count,
            'question_id' => $this->question_id,
            'chat_id' => $this->chat_id,
            'itime' => $this->itime
        );

        return $states;
    }

    public function __get($var)
    {
        switch ($var) {

            case 'itime_front':
                $this->itime_front = date('Ymd') == date('Ymd', $this->itime / 1000) ? date(erLhcoreClassModule::$dateHourFormat, $this->itime / 1000) : date(erLhcoreClassModule::$dateDateHourFormat, $this->itime / 1000);
                return $this->itime_front;
                ;
                break;

            default:
                break;
        }
    }

    public static $elasticType = 'lh_chatbot_answer';

    public $answer = null;

    public $match_count = null;

    public $chat_id = null;

    public $question_id = null;
}