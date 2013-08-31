<?php 
class Workspace{
	/**
	 * @var string
	 */
	protected $url;
	/**
	 * @var string
	 */
	protected $path;
	/**
	 * @var MessageLog
	 */
	protected $messageLog;
	/**
	 * @var CommandClient
	 */
	protected $commandClient;
	/**
	 * @var string
	 */
	protected $repoType;
	/**
	 * @var boolean
	 */
	protected $verbose;
	
	/**
	 * @param string $url
	 * @param string $path
	 * @param MessageLog $messageLog
	 * @param CommandClient $commandClient
	 * @param string $type
	 * @param boolean $verbose
	 */
	public function __construct($url, $path, $messageLog, $commandClient, $type, $verbose){
		$this->url = $url;
		$this->path = $path;
		$this->messageLog = $messageLog;
		$this->commandClient = $commandClient;		
		$this->repoType = $type;
	}

}
