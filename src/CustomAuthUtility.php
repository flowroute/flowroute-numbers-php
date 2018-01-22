<?php
/*
 * FlowrouteNumbersLib
 *
 * Copyright Flowroute, Inc. 2016
 */

namespace FlowrouteNumbersLib;
use Unirest\Unirest;

class CustomAuthUtility {
    /**
    * Appends the necessary Custom Authentication credentials for making this authorized call
    * @param    string  $method     The out going request to access the resource
    * @param    string  $query_url  The url to send the query to
    * @param    array   $headers    Header array to send in the request
    * @param    string  $body       Message body to send
    * @param    string  $username   API access key
    * @param    string  $password   API secret key
    * @return   response from HTTP request
    */
    public static function appendCustomAuthParams(
        $method = 'GET',
        $query_url=NULL,
        $headers=array(),
        $body='',
        $username=NULL,
        $password=NULL)
    {
        if (!isset($query_url)) { $query_url = Configuration::$BASEURI; }
        if (!isset($username)) { $username = Configuration::$username; }
        if (!isset($password)) { $password = Configuration::$password; }
       
        $timestamp = gmdate('Y-m-d\TH:i:s');
        $headers['X-Timestamp'] = $timestamp;
        $parsedurl = parse_url($query_url);
        if (strlen($body) > 0) {
            $body_md5 = md5($body);
        } else {
            $body_md5 = '';
        }
        if (isset($parsedurl['query'])) {
            parse_str($parsedurl['query'], $qparray);
            $qp = http_build_query($qparray);
        } else {
            $qp = '';
        }
        
        $canonicalUri = $parsedurl['scheme'] . '://' . $parsedurl['host'] . $parsedurl['path'] . PHP_EOL . $qp;
        $message_string = $timestamp . PHP_EOL;
        $message_string .= $method . PHP_EOL;
        $message_string .= $body_md5 . PHP_EOL . $canonicalUri;
        $message_string = utf8_encode($message_string);
        $signature = hash_hmac('sha1', $message_string, $password);

        switch (strtoupper($method)) {
            case 'GET':
                $request = Unirest::get($query_url, $headers, NULL, $username, $signature);
                break;
            case 'POST':
                $request = Unirest::post($query_url, $headers, $body, $username, $signature);
                break;
            case 'PUT':
                $request = Unirest::put($query_url, $headers, $body, $username, $signature);
                break;
            case 'PATCH':
                $request = Unirest::patch($query_url, $headers, $body, $username, $signature);
                break;
            case 'DELETE':
                $request = Unirest::delete($query_url, $headers, $body, $username, $signature);
                break;
            default:
                trigger_error("Invalid method supplied.", E_USER_ERROR);
                break;
        }
        
        // Process the response
        $response = Unirest::getResponse($request);
        return $response;
    }
}
