<?php 

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});


error_reporting(E_ALL);

$ws = new WorkspaceSvn('http://svn.frlabs.com/svn/frc', '~/frc_push', true);
//die('nice');
// $ws->ticketExists('J1811_KA_test');
// $ws->dumpBuffer();

// $ws->releaseExists('J1811_KA_test');
// $ws->dumpBuffer();

$ws->ticketExists('KA0830_test');
$ws->dumpBuffer();

$ws->createTicket('KA0830_test');
$ws->dumpBuffer();

$ws->ticketExists('KA0830_test');
$ws->dumpBuffer();