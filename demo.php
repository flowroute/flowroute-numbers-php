<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once('vendor/autoload.php');
foreach (glob('src/*.php') as $filename){require_once $filename;}
foreach (glob('src/Controllers/*.php') as $filename){require_once $filename;}
foreach (glob('src/Models/*.php') as $filename){require_once $filename;}

use FlowrouteNumbersLib\Controllers\InboundRoutesController;
use FlowrouteNumbersLib\Controllers\PurchasablePhoneNumbersController;
use FlowrouteNumbersLib\Controllers\TelephoneNumbersController;
use FlowrouteNumbersLib\APIException;
use FlowrouteNumbersLib\Models\BillingMethod;

print "Number Control Demo." . PHP_EOL;

//--- Purchasable Phone Numbers
// Create our controller
$pnc = new PurchasablePhoneNumbersController();

// Retrieve Available NPAs
print("--Retrieve Available NPAs\n");
$response = $pnc->listAvailableNPAs(10);
print_r($response);

// Retrieve Available NPA NXXs
print("--Retrieve Available NPA NXXs\n");
$response = $pnc->listAreaAndExchange();
print_r($response);

// Search for purchasable Numbers
print("--Search for numbers in Seattle Washington\n");
$response = $pnc->search(10,206,641,null,'seattle','wa',null);
print_r($response);


//--- Telephone Numbers
// Create our controller
$tnc = new TelephoneNumbersController();

// Purchase a Phone Number
print("--Purchase a Phone Number\n");
$billing = new BillingMethod('METERED');

$number = '12066417661';

try {
    $response = $tnc->purchase($billing, $number);
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

// Retrieve Phone Number Details
print("--Retrieve Number Details\n");
try {
    $response = $tnc->telephoneNumberDetails($number);
    print_r($response);
} catch(APIException $e) {
    print("Error - " . strval($e->getResponseCode()) . ' ' . $e->getMessage() . PHP_EOL);
}


//--- Inbound Routes
// Create our controller
$inbound = new InboundRoutesController();

// Retrieve Routes
print("--Retrieve Inbound Routes\n");
$response = $inbound->mlist();
print_r($response);

