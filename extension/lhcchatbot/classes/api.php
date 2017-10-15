<?php 

class LHCChatBot {

	private $host = null;
	private $secretHash = null;
	private $instanceId = null;

	/**
	 * @param $host = host where manager is accesses E.g http://manager.livehelperchat.com
	 * */
	public function __construct($host, $instanceId, $hash) 
	{
		$this->host = $host;
		$this->secretHash = $hash;
		$this->instanceId = $instanceId;
	}

	private function executeRequest($urlData)
	{
	    $urlData['id'] = $this->instanceId;
	    $urlData['sh'] = $this->secretHash;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->host . '?' . http_build_query($urlData));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Some hostings produces wargning...
		$content = curl_exec($ch);

		$answer = json_decode($content, true);
		
		if ($answer === null) {
		    throw new Exception('Invalid response [' . $answer . '] URL ' . $this->host . '?' . http_build_query($urlData));
		}
		
		if ($answer['error'] === true) {
		    throw new Exception($answer['msg']);
		}
		
		return $answer;
	}

	/* 
	 * 
	 * */
	public function addQuestion($question, $answer, $context = 0) 
	{
	    $response = $this->executeRequest(array(
	        'qq' => $question,
	        'qa' => $answer,
	        'ct' => $context
	    ));

	    return $response;
	}	

	public function removeQuestion($question, $context = 0)
	{
	    $response = $this->executeRequest(array(
	        'qd' => $question,
	        'ct' => $context
	    ));
	    
	    return $response;
	}

	public function getAnswer($question, $context = 0)
	{
	    $response = $this->executeRequest(array(
	        'q' => $question,
	        'ct' => $context
	    ));
	    
	    return $response;	    
	}
	
	public function dropDatabase($context = 0)
	{
	    $response = $this->executeRequest(array(
	        'drop' => true,
	        'ct' => $context
	    ));
	     
	    return $response;
	}
}