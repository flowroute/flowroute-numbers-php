<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once('vendor/autoload.php');
foreach (glob('src/*.php') as $filename){require_once $filename;}
foreach (glob('src/Controllers/*.php') as $filename){require_once $filename;}
foreach (glob('src/Models/*.php') as $filename){require_once $filename;}

use FlowrouteNumbersLib\Controllers\InboundRoutesController;

print "Number Control Demo." . PHP_EOL;
$inbound = new InboundRoutesController();
$response = $inbound->mlist();
print_r($response);
