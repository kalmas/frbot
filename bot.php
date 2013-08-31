<?php 
require_once 'bootstrap.php';

$messageLog = new MessageLog();
$messageLog->write('hi');

// $ws = new WorkspaceSvn('http://svn.frlabs.com/svn/frc', '~/frc_push', true);
//die('nice');
// $ws->ticketExists('J1811_KA_test');
// $ws->dumpBuffer();

// $ws->releaseExists('J1811_KA_test');
// $ws->dumpBuffer();

// $ws->ticketExists('KA0830_test');
// $ws->dumpBuffer();

// $ws->createTicket('KA0830_test');
// $ws->dumpBuffer();

// $ws->ticketExists('KA0830_test');
// $ws->dumpBuffer();