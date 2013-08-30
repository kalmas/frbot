<?php 
class WorkspaceSvn extends Workspace implements IRepositoryRepo {	
	const TYPE = 'svn';
	private $cmdAppend;
	private $ticketBase = 'branches';
	private $releaseBase = 'releases';
	
	public function __construct($url, $path, $verbose = false){
		parent::__construct($url, $path, self::TYPE, $verbose);
		if(!$this->verbose){
			$this->cmdAppend = ' 2>&1';
		}
	}
	
	/**
	 * 
	 * @param string $url
	 * @return boolean
	 */
	private function pathExists($path){
		$this->addToMessageBuffer("Checking for existance of {$path}");
		$cmd = "svn ls {$this->url}/{$path} {$this->cmdAppend}";
		$this->addToMessageBuffer($cmd);
		$returnVal = 0;
		exec($cmd, $response, $returnVal);
		
		// If $returnVal == 0, it means no errors occured, which means we found the branch
		if($returnVal === 0){
			$this->addToMessageBuffer("{$path} found");
			$this->addToMessageBuffer($response);
			return true;
		} else {
			$this->addToMessageBuffer("{$path} NOT found");
			return false;
		}
	}
	
	/* (non-PHPdoc)
	 * @see IRepositoryRepo::ticketExists()
	 */
	public function ticketExists($ticketName) {
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
	 * @param string $url
	 */
	private function createBranch($path, $basePath = 'trunk'){
		$this->addToMessageBuffer("Creating {$path} from {$basePath}");
		$cmd = "svn copy {$this->url}/{$basePath} {$this->url}/{$path}"
				. " -m 'Creation of {$path}' {$this->cmdAppend}";
		$this->addToMessageBuffer($cmd);
		exec($cmd, $response);
		$this->addToMessageBuffer($response);
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
