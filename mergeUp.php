<?php

	if(!isset($argv[1]) || !isset($argv[2])){
		// Arguments aren't right, print usage and exit
		print "Merge Up Script Usage: mergeup [\033[1;31mticket\033[0m] [\033[1;31mrelease\033[0m] [\033[1;31mdebug = 0\033[0m]\n";
		print "\033[1;31mticket\033[0m - ticket branch \n";
		print "\033[1;31mrelease\033[0m - release branch \n";
		print "\033[1;31mdebug\033[0m - print svn stderr for debugging \n\n";
		
		exit();
	}
	
	$ticket = $argv[1];
	$release = $argv[2];
	$debug = (isset($argv[3])) ? $argv[3] : 0; // Default false
	
	// If debug is off, pipe stderr down into the abyss 
	$cmdAppend = ($debug) ? "" : " 2>&1";	
	
	// Aarry full of repo info
	$repos = array();
	$repos['frc'] = array(
		'name' => 'frc (frontend)',
		'pushing_location' => '~/frc_push',
		'svnBase' => 'http://svn.frlabs.com/svn/frc/',
		'ticketDir' => 'branches/',
		'releaseDir' => 'releases/',
		'trunk' => 'trunk'
	);
	$repos['frms'] = array(
		'name' => 'frms (backend)',
		'pushing_location' => '~/frms_push',
		'svnBase' => 'http://svn.frlabs.com/svn/frms/',
		'ticketDir' => 'branches/',
		'releaseDir' => 'releases/',
		'trunk' => 'trunk'
	);
	$repos['chc'] = array(
		'name' => 'chc',
		'pushing_location' => '~/chc_push',
		'svnBase' => 'http://svn.frlabs.com/svn/frms/chc/',
		'ticketDir' => 'branches/core/',
		'releaseDir' => 'releases/',
		'trunk' => 'trunk'
	);
	
	function branchExists($url){
		global $cmdAppend;
		
		$cmd = "svn ls {$url} {$cmdAppend}";
		$returnVal = 0;
		exec($cmd, $response, $returnVal);

		// If $returnVal == 0, it means no errors occured, which means we found the branch
		if($returnVal === 0){
			return true;
		} else {
			return false;
		}
	}
	
	foreach($repos as $name => $repo){		

		if( branchExists($repo['svnBase'] . $repo['ticketDir'] . $ticket) ){
			echo "Found $ticket in $name \n\n";
			
			if( !branchExists($repo['svnBase'] . $repo['releaseDir'] . $release) ){
				echo "Copying {$release} from {$repo['trunk']} \n";
				$cmd = "svn copy {$repo['svnBase']}{$repo['trunk']} {$repo['svnBase']}{$repo['releaseDir']}{$release}"
						. " -m 'Creation of {$repo['releaseDir']}{$release}' {$cmdAppend}";
				echo $cmd . "\n\n";
				exec($cmd, $message);
			}
			
			echo "Switching pushing dir to $release \n";
			$cmd = "svn sw {$repo['svnBase']}{$repo['releaseDir']}{$release} {$repo['pushing_location']} {$cmdAppend}";			
			echo $cmd . "\n\n";
			exec($cmd, $switchedFiles);
			
			echo "Merging ticket into branch \n";
			$cmd = "svn merge {$repo['svnBase']}{$repo['ticketDir']}{$ticket} {$repo['pushing_location']} {$cmdAppend}";
			echo $cmd . "\n\n";
			exec($cmd, $mergedFiles);
			
			echo "Commiting merge \n";
			$cmd = "svn commit {$repo['pushing_location']} -m 'merged {$repo['ticketDir']}{$ticket} into {$repo['releaseDir']}{$release}'  {$cmdAppend}";
			echo $cmd . "\n\n";
			exec($cmd, $commitedFiles);
			
		} else {
			echo "Didn't Find $ticket in $name \n";
		}

	}


?>