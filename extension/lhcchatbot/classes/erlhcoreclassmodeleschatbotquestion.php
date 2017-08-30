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
            'confirmed' => $this->confirmed,
            'cbot_question_id' => $this->cbot_question_id,
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

            case 'cbot_question':

                if ($this->cbot_question_id > 0) {
                    $this->cbot_question = erLhcoreClassModelLHCChatBotQuestion::fetch($this->cbot_question_id);
                } else {
                    $this->cbot_question = new erLhcoreClassModelLHCChatBotQuestion();
                }

                return $this->cbot_question;
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

}