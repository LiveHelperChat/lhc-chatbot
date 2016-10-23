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
		    throw new Exception('Could not decode - ' . $answer);
		}
		
		if ($answer['error'] === true) {
		     throw new Exception($answer['msg']);
		}
		
		return $answer;
	}

	/* 
	 * 
	 * */
	public function addQuestion($question, $answer) 
	{
	    $response = $this->executeRequest(array(
	        'qq' => $question,
	        'qa' => $answer
	    ));

	    return $response;
	}	

	public function removeQuestion($question)
	{
	    $response = $this->executeRequest(array(
	        'qd' => $question
	    ));
	    
	    return $response;
	}

	public function getAnswer($question)
	{
	    $response = $this->executeRequest(array(
	        'q' => $question
	    ));
	    
	    return $response;	    
	}

	public function dropDatabase()
	{
	    $response = $this->executeRequest(array(
	        'drop' => true
	    ));
	    
	    return $response;	    
	}
}