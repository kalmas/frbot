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
		if(is_array($message)){
			foreach($message as $line){
				$this->messageBuffer[] = $line;
			}
		} else {
			$this->messageBuffer[] = $message;
		}

		$this->messageBuffer[] = '---';
	}
	
	public function dumpBuffer($echo = true){
		if($echo){
			foreach($this->messageBuffer as $line){
				echo $line . "\n";
			}
		}
		$this->messageBuffer = array();
	}

}
