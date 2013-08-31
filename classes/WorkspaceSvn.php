<?php 
class WorkspaceSvn extends Workspace implements IRepositoryRepo {	
	const TYPE = 'svn';
	private $cmdAppend;
	private $ticketBase = 'branches';
	private $releaseBase = 'releases';
	
	/**
	 * @param string $url
	 * @param string $path
	 * @param MessageLog $messageLog
	 * @param CommandClient $commandClient
	 * @param boolean $verbose
	 */
	public function __construct($url, $path, $messageLog, $commandClient, $verbose = false){
		parent::__construct($url, $path, $messageLog, $commandClient, self::TYPE, $verbose);
		if(!$this->verbose){
			$this->cmdAppend = ' 2>&1';
		}
	}
	
	/**
	 * Determine if provided svn path exists
	 * @param string $url
	 * @return boolean
	 */
	private function pathExists($path){
		$this->messageLog->write("Checking for existance of {$path}");
		$cmd = "svn ls {$this->url}/{$path} {$this->cmdAppend}";
		$success = $this->commandClient->execute($cmd);

		if($success){
			$this->messageLog->write("{$path} found");
			return true;
		} else {
			$this->messageLog->write("{$path} NOT found");
			return false;
		}
	}
	
	/* (non-PHPdoc)
	 * @see IRepositoryRepo::ticketExists()
	 */
	public function ticketExists($ticketName){
		$path = "{$this->ticketBase}/{$ticketName}";
		return $this->pathExists($path);
	}

	/* (non-PHPdoc)
	 * @see IRepositoryRepo::releaseExists()
	 */
	public function releaseExists($releaseName) {
		$path = "{$this->releaseBase}/{$releaseName}";
		return $this->pathExists($path);
	}

	/**
	 * Create branch by copying from $basePath
	 * @param string $path
	 * @param string $basePath
	 */
	private function createBranch($path, $basePath = 'trunk'){
		$this->messageLog->write("Creating {$path} from {$basePath}");
		$cmd = "svn copy {$this->url}/{$basePath} {$this->url}/{$path}"
				. " -m 'Creation of {$path}' {$this->cmdAppend}";
		return $this->commandClient->execute($cmd);
	}
	
	/* (non-PHPdoc)
	 * @see IRepositoryRepo::createTicket()
	 */
	public function createTicket($ticketName) {
		$path = "{$this->ticketBase}/{$ticketName}";
		$this->createBranch($path);
	}

	/* (non-PHPdoc)
	 * @see IRepositoryRepo::createRelease()
	 */
	public function createRelease($releaseName) {
		$path = "{$this->releaseBase}/{$releaseName}";
		$this->createBranch($path);
	}

	/* (non-PHPdoc)
	 * @see IRepositoryRepo::switchWorkspaceToBranch()
	 */
	public function switchWorkspaceToBranch($branchName) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see IRepositoryRepo::switchWorkspaceToRelease()
	 */
	public function switchWorkspaceToRelease($releaseName) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see IRepositoryRepo::setWorkspace()
	 */
	public function setWorkspace($path) {
		// TODO Auto-generated method stub
		
	}

}
