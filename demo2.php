<?php
// WARNING: this tests purchasing a phone number!

// bottom line - use JSON for purchasing
// '{"billing_method": "METERED"}' instead of 'METERED'

// I was able to successfully purchase a number using the following

// based on the demo.php and the examples from the SDK docs
// https://github.com/flowroute/flowroute-numbers-php
// download the PHP sdk and put this file in the root
// change the credentials in src/Configuration.php

// note - I also followed the instructions to to the composer.phar thing
//
//  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
//  php -r "if (hash_file('SHA384', 'composer-setup.php') === '92102166af5abdb03f49ce52a40591073a7b859a86e8ff13338cf7db58a19f7844fbc0bb79b2773bf30791e935dbd938') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
//  php composer-setup.php
//  php composer.phar install

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once('vendor/autoload.php');

// just copying the lines from the SDK docs...

use FlowrouteNumbersLib\Controllers\InboundRoutesController;
use FlowrouteNumbersLib\Controllers\PurchasablePhoneNumbersController;
use FlowrouteNumbersLib\Controllers\TelephoneNumbersController;

use FlowrouteNumbersLib\Models\BillingMethod;
use FlowrouteNumbersLib\Models\Route;

// Here is a documentation problem with the SDK
// don't pass credentials to the controllers even though it is documentented to pass them in - that didn't work for me
// just add the credentials to src/Configuration.php - that did seem to work for me

$irc = new InboundRoutesController();
$pnc = new PurchasablePhoneNumbersController();
$tnc = new TelephoneNumbersController();

// test simple search - the search for numbers should have succeeded

$response = $pnc->search(NULL, '206');
print_r($response);

// attempt to discover if there is a problem with a cache situation
// NOTE: this test will purchase the first 206 number in the search
// and keep looping - it should never see that number again in the search
// we should never be able to purchase the same number twice
// (a purchased number should never show in the search)

$first_tn = NULL;

for ($x = 0; $x <= 10; $x++) {
    echo "Loop number: $x" + "\n";

    // get a list of 206 numbers

    $response = $pnc->search(NULL, '206');
    //print_r($response);

    // for each number, loop through and print them - if we've seen it before, print a warning
    // on the first pass through, save off the first one
    // we're going to buy that number and should never see it again in the search

    foreach ($response->tns as $key=>$option) {
        print "........    " .  $key . "\n";
        if($first_tn == $key) {
            print "  ERMAGERD! MATCH! The number should not be available! " . $key . "\n";
        }

        // only set the "first" tn the first time through the main loop
        if($first_tn === NULL) {
            $first_tn = $key;
        }


    }

    // after printing all the numbers in the search and saving the first one
    // purchase that first number
    // the second time through the main loop it will fail
    // WARNING: every time you run this program, it will try to purchase the new
    //          first number in the list


    // This is what I saw in the code I was sent - a string instead of json:
    //       print var_dump($flowroute_tnum_controller->purchase('METERED',$did));

    // Please pass JSON to the purchase method - if you pass a string, it can cause problems
    // due to insufficient validation on the server side
    // (We're working on fixing it!)


    // This is from the docs for the SDK

    $billing = '{"billing_method": "METERED"}';
    print "Purchasing " . $first_tn . "\n";
    $response = $tnc->purchase($billing, $first_tn);
    print_r($response);
    print "---------------\n";
    // sleep - it will try to purchase the first number seen again and again
    //         so a failure on subsequent loops is expected

    sleep(6);

}

