<?php 

interface IRepositoryRepo {
	/**
	 * @return boolean
	 */
	public function ticketExists($ticketName);
	/**
	 * @return boolean
	 */
	public function releaseExists($releaseName);
	/**
	 * @return void
	 */
	public function createTicket($ticketName);
	/**
	 * @return void
	 */
	public function createRelease($releaseName);
	/**
	 * return void
	 */
	public function switchWorkspaceToBranch($branchName);
	/**
	 * return void
	 */
	public function switchWorkspaceToRelease($releaseName);
	/**
	 * return void
	 */
	public function setWorkspace($path);	
}
