<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once('vendor/autoload.php');

use FlowrouteNumbersLib\Controllers\InboundRoutesController;
use FlowrouteNumbersLib\Controllers\PurchasablePhoneNumbersController;
use FlowrouteNumbersLib\Controllers\TelephoneNumbersController;
use FlowrouteNumbersLib\APIException;
use FlowrouteNumbersLib\Models\BillingMethod;

print "Number Control Demo." . PHP_EOL;

// Flowroute API Access Key and Secret Key
$access_key = '78675316';
$secret_key = 'i0WRu1eHXolvfTGv1RIIsrkRfVGJLn0Q';

// Create our controllers
$pnc = new PurchasablePhoneNumbersController($access_key, $secret_key);
$tnc = new TelephoneNumbersController($access_key, $secret_key);
$irc = new InboundRoutesController($access_key, $secret_key);


// Retrieve Available NPAs
print("--Retrieve Available NPAs\n");
$response = $pnc->listAvailableNPAs(10);
print_r($response);

// Retrieve Available NPA NXXs
print("--Retrieve Available NPA NXXs\n");
try {
  $response = $pnc->listAreaAndExchange();
  print_r($response);
} catch(APIException $e) {
  print("Error - " . strval($e->getResponseCode()) . ' ' . $e->getMessage() . PHP_EOL);
}

// Search for purchasable Numbers
print("--Search for numbers in Seattle Washington\n");
try {
  $response = $pnc->search(10,206,641,null,'seattle','wa',null);
  print_r($response);
} catch(APIException $e) {
  print("Error - " . strval($e->getResponseCode()) . ' ' . $e->getMessage() . PHP_EOL);
}

// List Account Phone Numbers
print("--List Account Phone Numbers\n");
try {
  $response = $tnc->listAccountTelephoneNumbers();
  print_r($response);
} catch(APIException $e) {
  print("Error - " . strval($e->getResponseCode()) . ' ' . $e->getMessage() . PHP_EOL);
}

// Retrieve Routes
print("--Retrieve Inbound Routes\n");
try {
  $response = $irc->mlist();
  print_r($response);
} catch(APIException $e) {
  print("Error - " . strval($e->getResponseCode()) . ' ' . $e->getMessage() . PHP_EOL);
}

