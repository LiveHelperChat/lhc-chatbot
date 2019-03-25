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
        include 'extension/lhcchatbot/vendor/autoload.php';

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
        
        $dispatcher->listen('department.modified', array(
            $this,
            'departmentModified'
        ));

        // Elastic Search store statistic regarding was bot used in particular chat
        if ($this->settings['elastic_enabled'] == true) {

            $dispatcher->listen('chat.close', 'erLhcoreClassElasticSearchChatboxIndex::indexChatDelay');

            $dispatcher->listen('system.getelasticstructure', 'erLhcoreClassElasticSearchChatboxIndex::getElasticStructure');
            $dispatcher->listen('elasticsearch.indexchat', 'erLhcoreClassElasticSearchChatboxIndex::indexChat');
            $dispatcher->listen('elasticsearch.getstate', 'erLhcoreClassElasticSearchChatboxIndex::getState');

            $dispatcher->listen('statistic.valid_tabs', 'erLhcoreClassElasticSearchChatboxIndex::appendStatisticTab');
            $dispatcher->listen('statistic.process_tab', 'erLhcoreClassElasticSearchChatboxIndex::processTab');
        }
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
     * Executed then department is modified
     * 
     * @param array $params
     */
    public function departmentModified($params)
    {
        $department = $params['department'];
        
        $db = ezcDbInstance::get();
        
        $stmt = $db->prepare("SELECT context_id FROM lhc_lhcchatbot_context_link_department WHERE department_id = :department_id");
        $stmt->bindValue(':department_id',$department->id,PDO::PARAM_INT);
        $stmt->execute();
        $contextIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $newIds = isset($_POST['context_id']) && is_array($_POST['context_id']) ? $_POST['context_id'] : array();
               
        $newContext = array_diff($newIds, $contextIds);
                
        foreach ($newContext as $id) {
            $contextLinkDepartment = new erLhcoreClassModelLHCChatBotContextLinkDepartment();
            $contextLinkDepartment->context_id = $id;
            $contextLinkDepartment->department_id = $department->id;
            $contextLinkDepartment->saveThis();
        }
        
        $deleteContext = array_diff($contextIds, $newIds);
        
        if (!empty($deleteContext)) {
            $contextDepartments = erLhcoreClassModelLHCChatBotContextLinkDepartment::getList(array('filterin' => array('context_id' => $deleteContext),'filter' => array('department_id' => $department->id)));
            foreach ($contextDepartments as $contextDepartment) {
                $contextDepartment->removeThis();
            }
        }
    }

    public static function getDepartmentContext($department) {
        if (is_numeric($department->id)) {
            $db = ezcDbInstance::get();
            
            $stmt = $db->prepare("SELECT context_id FROM lhc_lhcchatbot_context_link_department WHERE department_id = :department_id");
            $stmt->bindValue(':department_id',$department->id,PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        
        return array();        
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
            'erLhcoreClassModelLHCChatBotUse' => 'extension/lhcchatbot/classes/erlhcoreclassmodellhcchatbotuse.php',
            'erLhcoreClassModelLHCChatBotInvalid' => 'extension/lhcchatbot/classes/erlhcoreclassmodeleschatbotinvalid.php',
            'erLhcoreClassModelLHCChatBotContext' => 'extension/lhcchatbot/classes/erlhcoreclassmodellhcchatbotcontext.php',
            'erLhcoreClassModelLHCChatBotContextLinkDepartment' => 'extension/lhcchatbot/classes/erlhcoreclassmodellhcchatbotcontextlinkdepartment.php',
            'erLhcoreClassExtensionLHCChatBotValidator' => 'extension/lhcchatbot/classes/erlhcoreclasslhcchatbotvalidator.php',
            'LHCChatBot' => 'extension/lhcchatbot/classes/api.php',

            'erLhcoreClassElasticSearchChatbotUpdate'   => 'extension/lhcchatbot/classes/lhelasticsearchupdate.php',
            'erLhcoreClassElasticChatbotClient'         => 'extension/lhcchatbot/classes/lhelasticsearchclient.php',
            'erLhcoreClassElasticChatbotTrait'          => 'extension/lhcchatbot/classes/lhelastictrait.php',
            'erLhcoreClassModelESChatbotQuestion'       => 'extension/lhcchatbot/classes/erlhcoreclassmodeleschatbotquestion.php',
            'erLhcoreClassModelESChatbotAnswer'         => 'extension/lhcchatbot/classes/erlhcoreclassmodeleschatbotanswer.php',
            'erLhcoreClassElasticSearchChatboxIndex'    => 'extension/lhcchatbot/classes/lhelasticsearchindex.php',
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

    public function getId(){
        $this->getConfig();
        return $this->configData['id'];
    }

    public function __get($var)
    {
        switch ($var) {

            case 'settings':
                $this->settings = include ('extension/lhcchatbot/settings/settings.ini.php');
                return $this->settings;
                break;

            case 'settings_personal':
                $esOptions = erLhcoreClassModelChatConfig::fetch('elasticsearch_options');
                $this->settings_personal = (array)$esOptions->data;
                return $this->settings_personal;
                break;

            default:
                ;
                break;
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