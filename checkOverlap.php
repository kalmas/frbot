<?php
	/**
	 * Here's a script to check if files you've changed in one repo exists in the other
	 * so that you can syncronize/delete/ignore them as you see fit
	 * @author kyle.almas
	 * @since 12/20/2012
	 */

	if(!isset($argv[1]) || !isset($argv[2]) || !in_array($argv[1], array("frc", "front", "frontend", "src", "frms", "back", "backend"))){
		// Arguments aren't right, print usage and exit
		print "Check Overlap Script Usage: php check [\033[1;31mrepo\033[0m] [\033[1;31mbranch/rev#\033[0m] [\033[1;31mcompareToBranch = '/trunk'\033[0m] [\033[1;31mdebug = 0\033[0m]\n";
		print "\033[1;31mrepo\033[0m - the repo you are working in and want to look for overlap in: frc (front) or frms (back)\n";
		print "\033[1;31mbranch/rev#\033[0m - a branch or revision number to check from \n";
		print "\033[1;31mcompareToBranch\033[0m - the branch in the other repo to compare with (Default: '/trunk') \n";
		print "\033[1;31mdebug\033[0m - print svn stderr for debugging \n\n";
		
		exit();
	}
	
	$repo = $argv[1];
	$ticketRev = $argv[2];
	$compareToBranch = (isset($argv[3])) ? $argv[3] : '/trunk'; // Default '/trunk'
	$debug = (isset($argv[4])) ? $argv[4] : 0; // Default false
	$svnBaseBack = "http://svn.frlabs.com/svn/frms"; // FRMS Base SVN
	$svnBaseFront = "http://svn.frlabs.com/svn/frc"; // FRC Base SVN
	
	// If debug is off, pipe stderr down into the abyss 
	$cmdAppend = ($debug) ? "" : " 2>&1";
	
	// Translate repo aliases
	if(in_array($repo, array("frc", "front", "frontend", "src"))){
		$repo = "frc";
	} else {
		$repo = "frms";
	}	
	
	// Aarry full of repo info
	$repos = array();
	$repos["frc"] = array(
		"name" => "frc (frontend)",
		"ws_location" => "~/",
		"workspace" => "src",
		"svnBase" => $svnBaseFront,
		"compareUrl" => $svnBaseBack . $compareToBranch
	);
	$repos["frms"] = array(
		"name" => "frms (backend)",
		"ws_location" => "~/",
		"workspace" => "backend",
		"svnBase" => $svnBaseBack,
		"compareUrl" => $svnBaseFront . $compareToBranch
	);
	
	// Compute the revison to look back to
	if(!is_numeric($ticketRev)){
		print "Searching for creation revision for {$ticketRev}...\n";
		if(strstr($ticketRev, "branches/") || strstr($ticketRev, "releases/")){
			// Explicitly a "branch" or "release"
			$branchUrl = $repos[$repo]["svnBase"] . "/" . $ticketRev;
			$ticketName = preg_replace('/^.*\//', '', $ticketRev);
		} else if(strstr($ticketRev, "ticket_")){
			// Looks like it's probably a "branch"
			$branchUrl = $repos[$repo]["svnBase"] . "/branches/" . $ticketRev;
			$ticketName = $ticketRev;
		} else {
			// Maybe it's a "release"
			$branchUrl = $repos[$repo]["svnBase"] . "/releases/" . $ticketRev;
			$ticketName = $ticketRev;
		}
		$cmd = "svn log -l 100 {$branchUrl}{$cmdAppend}";
		exec($cmd, $branchLog);
		$revLine = null;
		foreach($branchLog as $i => $log){
			if(preg_match("/Creation of.*{$ticketName}/i", $log)){
				// Looks like the ticket was most recently created here, look back 2
				// lines for the line containing the revision number
				$revLine = $i - 2;
				break;
			}
		}
		if(!is_null($revLine)){
			// Strip the revision number out of the line
			preg_match('/(^r)(\d*)/', $branchLog[$revLine], $matches);
			$rev = $matches[2];
		} else {
			print "Couldn't find a creation revision for {$ticketRev}. Exiting.\n\n";
			exit();
		}
	} else {
		$rev = $ticketRev;
	}
	
	print "Looking for files changed in \033[1;31m{$repos[$repo]['name']}\033[0m code since ";
	print "revision \033[1;31m{$rev}\033[0m that overlap with files in the other repository...\n\n";
	
	// Get an array of files we've changed since $rev
	$cmd = "svn diff -r {$rev}:HEAD --summarize {$repos[$repo]['ws_location']}{$repos[$repo]['workspace']}{$cmdAppend}";
	exec($cmd, $changedFiles);

	$changedFilesOverlapping = array();

	// Check if the changed files are in the other repository
	foreach ($changedFiles as $file){
		// Strip off the status flags and the leading workspace location
		$file = preg_replace("/^[\s|\w\/]*{$repos[$repo]['workspace']}/", '', $file);
		$cmd = "svn ls {$repos[$repo]['compareUrl']}{$file}{$cmdAppend}";
		$returnVal = 0;
		exec($cmd, $response, $returnVal);
		
		/*
		 * If $returnVal == 0, it means no errors occured, which means we found 
		 * the file in the other repo, which means we want to log it
		 */
		if($returnVal == 0){
			$changedFilesOverlapping[] = $file;
		}
	}
	
	if(empty($changedFilesOverlapping)){
		print "No changed files overlap. Nice!\n\n";
		exit();
	} else {
		print "\033[1;31mFound some changed files that overlap:\033[0m\n";
		foreach($changedFilesOverlapping as $file){
			print $file . "\n";
		}
		print "\n";
		exit();
	}
?>