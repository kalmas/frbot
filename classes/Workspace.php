<?php 
class Workspace{
	protected $path;
	protected $repoType;
	protected $url;
	protected $verbose;
	protected $messageBuffer = array();
	
	public function __construct($url, $path, $type, $verbose){
		$this->url = $url;
		$this->path = $path;
		$this->repoType = $type;
	}
	
	public function addToMessageBuffer($message, $highlight = false){
		if(is_string($message)){
			$messageBuffer[] = $message;			
		}else if(is_array($message)){
			
		}
	}

}
