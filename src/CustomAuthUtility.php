<?php
/*
 * FlowrouteNumbersLib
 *
 * This file was automatically generated for flowroute by APIMATIC BETA v2.0 on 02/12/2016
 */

namespace FlowrouteNumbersLib;
use FlowrouteNumbersLib\APIException;
use FlowrouteNumbersLib\APIHelper;
use FlowrouteNumbersLib\Configuration;
use Unirest\Unirest;

class CustomAuthUtility {
    /**
    * Appends the necessary Custom Authentication credentials for making this authorized call
    * @param HttpRequest $request The out going request to access the resource
    */
    public static function appendCustomAuthParams(
        $method = 'GET',
        $query_url=NULL,
        $headers=array(),
        $body='')
    {
        // TODO: Add your custom authentication here
		// The following properties are available to use
		//     Configuration::$username
		//     Configuration::$password
		// 
		// ie. Add a header through:
		//     $request.headers(array("key" => "value"));

        print_r($query_url);
        if (!isset($query_url)) { $query_url = Configuration::$BASEURI; }
        $timestamp = date('Y-m-d\TH:i:s');
        $headers['X-Timestamp'] = $timestamp;
        $parsedurl = parse_url($query_url);
        print_r($parsedurl);
        if (strlen($body) > 0) {
            $body_md5 = md5($body);
        } else {
            $body_md5 = '';
        }
        if (isset($parsedurl['query'])) {
            parse_str($parsedurl['query'], $qparray);
            $qp = natsort($qparray);
            $qp = http_build_query($qp);
        } else {
            $qp = '';
        }
        
        $canonicalUri = $parsedurl['scheme'] . '://' . $parsedurl['host'] . $parsedurl['path'] . PHP_EOL . $qp;
        print_r($canonicalUri);
        $message_string = $timestamp . PHP_EOL . $method . PHP_EOL . $body_md5 . PHP_EOL . $canonicalUri;
        $message_string = utf8_encode($message_string);
        $signature = hash_hmac('sha1', $message_string, Configuration::$password);
        print_r($signature);

        // Prepare the unirest request
        //Unirest::auth(Configuration::$username, $signature);

        print "Generating the request" . PHP_EOL;
        switch (strtoupper($method)) {
            case 'GET':
                $request = Unirest::get($query_url, $headers, NULL, Configuration::$username, $signature);
                break;
            case 'POST':
                $request = Unirest::post($query_url, $headers, $body_md5, Configuration::$username, $signature);
                break;
            case 'PUT':
                $request = Unirest::put($query_url, $headers, $body_md5, Configuration::$username, $signature);
                break;
            case 'PATCH':
                $request = Unirest::patch($query_url, $headers, $body_md5, Configuration::$username, $signature);
                break;
            case 'DELETE':
                $request = Unirest::delete($query_url, $headers, $body_md5, Configuration::$username, $signature);
                break;
            default:
                trigger_error("Invalid method supplied.", E_USER_ERROR);
                break;
        }
        
        // Process the response
        print_r($request);
        print "Processing the request" . PHP_EOL;
        $response = Unirest::getResponse($request);
        return $response;
    }
}