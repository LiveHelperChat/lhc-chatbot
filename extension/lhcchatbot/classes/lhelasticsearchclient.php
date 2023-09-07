<?php

use Aws\Credentials\CredentialProvider;
use Aws\Signature\SignatureV4;
use Elasticsearch\ClientBuilder;
use GuzzleHttp\Ring\Future\CompletedFutureArray;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
#[\AllowDynamicProperties]
class erLhcoreClassElasticChatbotClient {
	
	private static $handler = null;
	
	public static function saveThis($handler, & $obj, $indexName, $indexType) {
		
		$create = true; // if false means update
		$updateParams = array();
		$updateParams['index'] = $indexName;
		$updateParams['type'] = $indexType;
		
		try {
			if ($obj->id !== null) {
				$updateParams['id'] = $obj->id;
				$create = false;
				$updateParams['body']['doc'] = $obj->getState();
				$handler->update($updateParams);
			} else {
				$updateParams['body'] = $obj->getState();
				$ret = $handler->index($updateParams);
				$obj->id = $ret['_id'];
			}
		} catch (Exception $e) {
			if (!isset($handler->throwExceptionOnFailure) || $handler->throwExceptionOnFailure === true) {
				throw $e;
			}
		}
	}
	
	public static $lastSearchCount = 0;
	
	public static function searchObjects($handler, $params, $className) {
		
		if (isset($params['enable_sql_cache'])){
			unset($params['enable_sql_cache']);
		}
		
		if (isset($params['sql_cache_timeout'])){
			unset($params['sql_cache_timeout']);
		}
		
		self::$lastSearchCount = 0;
		
		$response = $handler->search($params);
		$returnObjects = array();
		if (isset($response['hits']['hits']) && !empty($response['hits']['hits'])){			
			foreach ($response['hits']['hits'] as $doc) {
				$obj = new $className();
				$obj->setState($doc['_source']);
				$obj->id = $doc['_id'];
				$obj->meta_data = array('score' => $doc['_score']);	
				$returnObjects[$obj->id] = $obj;
			}
			
			self::$lastSearchCount = $response['hits']['total'];
		}	
		
		return $returnObjects;
	}	

	public static function mGet($handler, $params, $className)
	{
	    $documents = $handler->mget($params);
	    
	    $returnObjects = array();
	    
	    foreach ($documents['docs'] as $doc) {
	        if ($doc['found'] == 1) {
	            $obj = new $className();
	            $obj->setState($doc['_source']);
	            $obj->id = $doc['_id'];	          
	            $returnObjects[$obj->id] = $obj;
	        }
	    }
	    
	    return $returnObjects;
	}
	
	public static function bulkSave($handler, $params, & $objects, $paramsExecution = array())
	{	    
	    $action = $handler->bulk($params);
	    
	    if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' ) == true) {	    
	       //erLhcoreClassLog::write(print_r(array('log' => json_encode($params) . ' -------- ' . json_encode($action), 'function' => 'bulk_save'),true));
	    }	    
	    	    
	    if (!empty($action['items'])) {
	        foreach ($objects as $key => & $object) {	                        	            
	            if (isset($action['items'][$key])) {	                
	                if ($object->id == null) {	                    
	                    $object->id = $action['items'][$key]['index']['_id'];
	                    $object->afterSave();	                   
	                } else {
	                    $object->afterUpdate();	                    
	                }
	                 
	                // clear cache only if needed
	                if (isset($paramsExecution['clear_cache']) && $paramsExecution['clear_cache'] == true) {
	                   $object->clearCache();
	                } 
	            }
	        }
	        
	        // Just to clear class cache once
	        $object->clearCache();
	    }
	    
	    // Log bulk save errors
	    if (isset($action['errors']) && $action['errors'] == true) {
	        if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' ) == true) {
	           //erLhcoreClassLog::write(print_r(array('log' => json_encode($action), 'function' => 'bulk_save_error'),true));
	        }
	    }
	    	    
	    return $action;
	}
	
	public static function searchObjectsCount($handler, $params) {
		
		if (isset($params['enable_sql_cache'])){
			unset($params['enable_sql_cache']);
		}
		
		if (isset($params['sql_cache_timeout'])){
			unset($params['sql_cache_timeout']);
		}
			
		$params['size'] = 1;
		$response = $handler->search($params);
				
		$documentsCount = 0;
		if (isset($response['hits']['total']) && !empty($response['hits']['total'])) {			
			$documentsCount = $response['hits']['total'];
		}	
			
		return $documentsCount;
	}
	
	public static function removeObj($handler, & $obj, $indexName, $indexType) {
		
		$deleteParams = array();
		$deleteParams['index'] = $indexName;
		$deleteParams['type'] = $indexType;
		$deleteParams['id'] = $obj->id;
		$handler->delete($deleteParams);
	}

	public static function load($handler, $className, $id, $indexName, $indexType) {
						
		$getParams = array();
		$getParams['index'] = $indexName;
		$getParams['type']  = $indexType;
		$getParams['id']    = $id;
		
		$retDoc = $handler->get($getParams);
		
		if (isset($retDoc['found']) && $retDoc['found'] == 1)
		{			
			$obj = new $className();
			$obj->setState($retDoc['_source']);
			$obj->id = $retDoc['_id'];
			return $obj;
		} else {
			throw new Exception($className.' with id '.$id.' ['.$indexName.']['.$indexType.'] could not be found!');
		}	
	}

	public static function getHandler() {
		
		if (is_null(self::$handler)) {

			$settings = erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionLhcchatbot')->settings['elastic_settings'];
			
			$elasticClient = Elasticsearch\ClientBuilder::create();
			
			if ($settings['use_iam'] == true) {
			    
			    $psr7Handler = Aws\default_http_handler();
			    $signer = new SignatureV4('es', $settings['iam_region']);
			    $credentialProvider = CredentialProvider::defaultProvider();
			    
			    // Construct the handler that will be used by Elasticsearch-PHP
			    $handler = function (array $request) use (
			        $psr7Handler,
			        $signer,
			        $credentialProvider
			    ) {
			        // Amazon ES listens on standard ports (443 for HTTPS, 80 for HTTP).
			        $request['headers']['host'][0] = parse_url($request['headers']['host'][0], PHP_URL_HOST);
			    
			        // Create a PSR-7 request from the array passed to the handler
			        $psr7Request = new Request(
			            $request['http_method'],
			            (new Uri($request['uri']))
			            ->withScheme($request['scheme'])
			            ->withHost($request['headers']['host'][0]),
			            $request['headers'],
			            $request['body']
			        );
			    
			        // Sign the PSR-7 request with credentials from the environment
			        $signedRequest = $signer->signRequest(
			            $psr7Request,
			            call_user_func($credentialProvider)->wait()
			        );
			    
			        // Send the signed request to Amazon ES
			        /** @var ResponseInterface $response */
			        $response = $psr7Handler($signedRequest)->wait();
			    
			        // Convert the PSR-7 response to a RingPHP response
			        return new CompletedFutureArray([
			            'status' => $response->getStatusCode(),
			            'headers' => $response->getHeaders(),
			            'body' => $response->getBody()->detach(),
			            'transfer_stats' => ['total_time' => 0],
			            'effective_url' => (string) $psr7Request->getUri(),
			        ]);
			    };
			    
			    $elasticClient->setHandler($handler);
			}

			self::$handler = $elasticClient->setHosts(array($settings['host'].':'.$settings['port']))->build();
		}
		
		return self::$handler;
	}

	public static function deleteAllDocumentsByType($handler, $indexName, $indexType) {
		
		$params = array();
		$params['index'] = $indexName;
		$params['type'] = $indexType;
		$params['body']['query'] = array('match_all' => array());
		
		$handler->deleteByQuery($params);
	}    
}

?>