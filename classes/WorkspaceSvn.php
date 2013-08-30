<?php 
class WorkspaceSvn extends Workspace implements IRepositoryRepo {	
	const TYPE = 'svn';
	private $cmdAppend;
	private $ticketBase = 'branches';
	private $releaseBase = 'releases';
	
	public function __construct($url, $path, $verbose = false){
		parent::__construct($url, $path, self::TYPE, $verbose);
		if($this->verbose){
			$this->cmdAppend = ' 2>&1';
		}
	}
	
	/* (non-PHPdoc)
	 * @see IRepositoryRepo::ticketExists()
	 */
	public function ticketExists($ticketName) {
		$cmd = "svn ls {$this->url}/{$this->ticketBase}/$ticketName {$this->cmdAppend}";
		$returnVal = 0;
		exec($cmd, $response, $returnVal);
		
		var_dump($response);
		// If $returnVal == 0, it means no errors occured, which means we found the branch
		if($returnVal === 0){
			return true;
		} else {
			return false;
		}		
	}

	/* (non-PHPdoc)
	 * @see IRepositoryRepo::releaseExists()
	 */
	public function releaseExists($releaseName) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see IRepositoryRepo::createTicket()
	 */
	public function createTicket($ticketName) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see IRepositoryRepo::createRelease()
	 */
	public function createRelease($releaseName) {
		// TODO Auto-generated method stub
		
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
