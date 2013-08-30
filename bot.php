<?php 

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});


error_reporting(E_ALL);

$ws = new WorkspaceSvn('http://svn.frlabs.com/svn/frc', '~/frc_push');
//die('nice');
$ws->ticketExists('ticket_7191');