<?php

/**
 * Direct integration with LHC ChatBot
 * */
class erLhcoreClassExtensionLhcchatbot
{
    private $configData = false;
    private static $persistentSession;
    
    public function __construct()
    {
        $this->registerAutoload();
    }

    public function run()
    {
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
        
        /**
         * User events
         */
        $dispatcher->listen('chat.syncadmin', array($this, 'syncAdmin'));
        
    }
    
    public function syncAdmin($params) 
    {
        $msgSearch = array();        
        foreach ($params['messages'] as $msg) {
            if ($msg['user_id'] == 0) {
                $msgSearch[] = (int)$msg['id'];
            }
        }

        if (!empty($msgSearch)) {
            $params['response']['chatbotids'] = $msgSearch;
        }
    }
    
    public function registerAutoload()
    {
        spl_autoload_register(array(
            $this,
            'autoload'
        ), true, false);
    }
    
    public function autoload($className)
    {
        $classesAutoload = array(
            'erLhcoreClassModelLHCChatBotQuestion' => 'extension/lhcchatbot/classes/erlhcoreclassmodellhcchatbotquestion.php',
            'erLhcoreClassExtensionLHCChatBotValidator' => 'extension/lhcchatbot/classes/erlhcoreclasslhcchatbotvalidator.php',
            'LHCChatBot' => 'extension/lhcchatbot/classes/api.php',
        );
    
        if (key_exists($className, $classesAutoload)) {
            include_once $classesAutoload[$className];
        }
    }
    
    public function getConfig()
    {
        if ($this->configData === false) {
            $this->configData = include('extension/lhcchatbot/settings/settings.ini.php');
        }
    }

    public function getApi()
    {
        $this->getConfig();
        
        $api = new LHCChatBot($this->configData['host'], $this->configData['id'], $this->configData['secret_hash']);
        
        return $api;
    }
    
    public static function getSession()
    {
        if (! isset(self::$persistentSession)) {
            self::$persistentSession = new ezcPersistentSession(ezcDbInstance::get(), new ezcPersistentCodeManager('./extension/lhcchatbot/pos'));
        }
        return self::$persistentSession;
    } 
}