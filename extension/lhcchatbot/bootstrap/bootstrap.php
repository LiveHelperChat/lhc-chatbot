<?php

/**
 * Direct integration with LHC ChatBot
 * */
class erLhcoreClassExtensionLhcchatbot
{
    private $configData = false;
    private static $persistentSession;
    private $instanceManual = false;
    
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
        
        $dispatcher->listen('instance.extensions_structure', array(
            $this,
            'checkStructure'
        ));
        
        $dispatcher->listen('instance.registered.created', array(
            $this,
            'instanceCreated'
        ));
        
        $dispatcher->listen('instance.destroyed', array(
            $this,
            'instanceDestroyed'
        ));
        
    }
    
    /**
     * Checks automated hosting structure
     *
     * This part is executed once in manager is run this cronjob.
     * php cron.php -s site_admin -e instance -c cron/extensions_update
     *
     * */
    public function checkStructure()
    {
        erLhcoreClassUpdate::doTablesUpdate(json_decode(file_get_contents('extension/lhcchatbot/doc/structure.json'), true));
    }

    /**
     * Used only in automated hosting enviroment
     */
    public function instanceDestroyed($params)
    {
        // Set subdomain manual, so we avoid calling in cronjob
        $this->instanceManual = $params['instance'];
        
        // Drop database
        try {
            $this->getApi()->dropDatabase();
        } catch (Exception $e) {
            erLhcoreClassLog::write(print_r($e, true));
        }
    }
    
    /**
     * Used only in automated hosting enviroment
     */
    public function instanceCreated($params)
    {
        try {
            // Instance created trigger
            $this->instanceManual = $params['instance'];
            
            // Just do table updates
            erLhcoreClassUpdate::doTablesUpdate(json_decode(file_get_contents('extension/lhcchatbot/doc/structure.json'), true));
                    
        } catch (Exception $e) {
            erLhcoreClassLog::write(print_r($e, true));
        }
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
               
        if ($this->configData['ahosting'] == true && $this->configData['id'] == 0) {
            $this->configData['id'] = $this->instanceManual !== false ? $this->instanceManual->id :  erLhcoreClassInstance::getInstance()->id;
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