<?php

trait erLhcoreClassElasticChatbotTrait
{
    static $indexName = null;

    public function setState(array $properties)
    {
        foreach ($properties as $key => $val) {
            $this->$key = $val;
        }
    }

    public function saveThis()
    {
        $this->beforeSave();
        erLhcoreClassElasticChatbotClient::saveThis(self::getSession(), $this, self::$indexName, self::$elasticType);
        $this->afterSave();
        $this->clearCache();
    }

    public function updateThis()
    {
        $this->beforeUpdate();
        erLhcoreClassElasticChatbotClient::saveThis(self::getSession(), $this, self::$indexName, self::$elasticType);
        $this->afterUpdate();
        $this->clearCache();
    }

    public function removeThis()
    {
        $this->beforeRemove();
        erLhcoreClassElasticChatbotClient::removeObj(self::getSession(), $this, self::$indexName, self::$elasticType);
        $this->afterRemove();
        $this->clearCache();
    }

    public function beforeSave()
    {}

    public function beforeUpdate()
    {}

    public function beforeRemove()
    {}

    public function afterSave()
    {}

    public function afterUpdate()
    {}

    public function afterRemove()
    {}

    public function clearCache()
    {
        $cache = CSCacheAPC::getMem();
        $cache->increaseCacheVersion('site_attributes_version_' . strtolower(__CLASS__));
        $cache->delete('object_' . strtolower(__CLASS__) . '_' . $this->id);
        
        if (isset($GLOBALS[__CLASS__ . $this->id])) {
            unset($GLOBALS[__CLASS__ . $this->id]);
        }
        
        $this->clearCacheClassLevel();
    }

    public function clearCacheClassLevel()
    {}

    public static function getSession()
    {
        static $dbHandler = false;
        
        if ($dbHandler === false) {
            
            $settings = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['elastic_settings'];
            
            $dbHandler = erLhcoreClassElasticChatbotClient::getHandler();
            if (isset(self::$index) && self::$index != '') {
                $additional_indexes = $settings['additional_indexes'];
                if (isset($additional_indexes[self::$index])) {
                    self::$indexName = $additional_indexes[self::$index];
                } else {
                    throw new Exception(__t('elastic/message', 'No index set'));
                }
            } else {
                self::$indexName = $settings['index'];
            }
        }
        
        return $dbHandler;
    }

    public static function fetch($id, $useCache = true)
    {
        if (isset($GLOBALS[__CLASS__ . $id]) && $useCache == true)
            return $GLOBALS[__CLASS__ . $id];
        
        try {
            $GLOBALS[__CLASS__ . $id] = erLhcoreClassElasticChatbotClient::load(self::getSession(), __CLASS__, $id, self::$indexName, self::$elasticType);
        } catch (Exception $e) {
            $GLOBALS[__CLASS__ . $id] = false;
        }
        
        return $GLOBALS[__CLASS__ . $id];
    }

    /**
     * Similar to above just uses memcache if available
     */
    public static function fetchCache($id)
    {
        $cache = CSCacheAPC::getMem();
        $cacheKey = 'object_' . strtolower(__CLASS__) . '_' . $id;
        
        if (($object = $cache->restore($cacheKey)) === false) {
            $object = self::fetch($id, true);
            $cache->store($cacheKey, $object);
        }
        
        return $object;
    }

    public static function findOne($paramsSearch = array())
    {
        $paramsSearch['limit'] = 1;
        $list = self::getList($paramsSearch);
        if (! empty($list)) {
            reset($list);
            return current($list);
        }
        
        return false;
    }

    public static function getCount($params = array())
    {
        if (isset($params['enable_sql_cache']) && $params['enable_sql_cache'] == true) {
            $sql = erLhcoreClassModuleFunctions::multi_implode(',', $params);
            
            $cache = CSCacheAPC::getMem();
            $cacheKey = isset($params['cache_key']) ? md5($sql . $params['cache_key']) : md5('objects_count_' . strtolower(__CLASS__) . '_v_' . $cache->getCacheVersion('site_attributes_version_' . strtolower(__CLASS__)) . $sql);
            
            if (($result = $cache->restore($cacheKey)) !== false) {
                return $result;
            }
        }
        
        if (isset($params['limit'])) {
            unset($params['limit']);
        }
        
        if (isset($params['offset'])) {
            unset($params['offset']);
        }
        
        $searchHandler = self::getSession();
        
        $paramsDefault = array(
            'index' => self::$indexName,
            'type' => self::$elasticType
        );
        $params = array_merge($paramsDefault, $params);
        
        $result = erLhcoreClassElasticChatbotClient::searchObjectsCount($searchHandler, $params);
        
        if (isset($params['enable_sql_cache']) && $params['enable_sql_cache'] == true) {
            $cache->store($cacheKey, $result);
        }
        
        return $result;
    }

    public static function getList($params = array())
    {
        $paramsDefault = array(
            'limit' => 1000,
            'offset' => 0
        );
        $params = array_merge($paramsDefault, $params);
        
        if (isset($params['enable_sql_cache']) && $params['enable_sql_cache'] == true) {
            $sql = erLhcoreClassModuleFunctions::multi_implode(',', $params);
            
            $cache = CSCacheAPC::getMem();
            $cacheKey = isset($params['cache_key']) ? md5($sql . $params['cache_key']) : md5('objects_list_' . strtolower(__CLASS__) . '_v_' . $cache->getCacheVersion('site_attributes_version_' . strtolower(__CLASS__)) . $sql);
            
            if (($result = $cache->restore($cacheKey)) !== false) {
                return $result;
            }
        }
        
        $searchHandler = self::getSession();
        
        $params['index'] = self::$indexName;
        $params['type'] = self::$elasticType;
        
        // Convert pagination parameters to elastic one
        if (isset($params['limit'])) {
            if ($params['limit'] > 0) {
                $params['size'] = $params['limit'];
            }
            unset($params['limit']);
        }
        
        if (isset($params['offset'])) {
            if ($params['offset'] > 0) {
                $params['from'] = $params['offset'];
            }
            unset($params['offset']);
        }
        
        $objects = erLhcoreClassElasticChatbotClient::searchObjects($searchHandler, $params, __CLASS__);
        
        if (isset($params['enable_sql_cache']) && $params['enable_sql_cache'] == true) {
            if (isset($params['sql_cache_timeout'])) {
                $cache->store($cacheKey, $objects, $params['sql_cache_timeout']);
            } else {
                $cache->store($cacheKey, $objects);
            }
        }
        
        return $objects;
    }

    public static function mGet($ids)
    {
        $searchHandler = self::getSession();
        
        $params['index'] = self::$indexName;
        $params['type'] = self::$elasticType;
        $params['body']['ids'] = array_values($ids);
        
        return erLhcoreClassElasticChatbotClient::mGet($searchHandler, $params, __CLASS__);
    }

    public static function bulkSave(& $objects, $paramsExecution = array())
    {
        $searchHandler = self::getSession();
        
        $params['index'] = self::$indexName;
        $params['type'] = self::$elasticType;
        
        $operations = array();
        
        foreach ($objects as $object) {
            if ($object->id == null) {
                $object->beforeSave();
                $operations[] = "{ \"index\":  {} }";
            } else {
                $object->beforeUpdate();
                $operations[] = '{ "index":  { "_id": "' . $object->id . '"} }';
            }
            $operations[] = json_encode($object->getState());
        }

        if (! empty($operations)) {
            $operations[] = "";
            $params['body'] = implode("\n", $operations);
            return erLhcoreClassElasticChatbotClient::bulkSave($searchHandler, $params, $objects, $paramsExecution);
        }
    }

    public static function bulkDelete(& $objects, $paramsExecution = array())
    {
        $searchHandler = self::getSession();

        $params['index'] = self::$indexName;
        $params['type'] = self::$elasticType;

        $operations = array();

        foreach ($objects as $object) {
            if ($object->id != null) {
                $object->beforeRemove();
                $operations[] = '{ "delete":  { "_id": "' . $object->id . '"} }';
            }
        }

        if (! empty($operations)) {
            $operations[] = "";
            $params['body'] = implode("\n", $operations);
            return erLhcoreClassElasticChatbotClient::bulkSave($searchHandler, $params, $objects, $paramsExecution);
        }
    }
}

?>