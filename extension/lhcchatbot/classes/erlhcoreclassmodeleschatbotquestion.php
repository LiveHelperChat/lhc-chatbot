<?php

class erLhcoreClassModelESChatbotQuestion
{
    use erLhcoreClassElasticChatbotTrait;

    public function getState()
    {
        $states = array(
            'question' => $this->question,
            'match_count' => $this->match_count,
            'itime' => $this->itime,
            'chat_id' => $this->chat_id,
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

    public static $elasticType = 'lh_chatbot_question';

    public $question = null;

    public $match_count = null;

    public $chat_id = null;

    public $itime = null;

}