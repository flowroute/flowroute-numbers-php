<?php
/*
 * FlowrouteNumbersLib
 *
 * Copyright Flowroute, Inc. 2016
 */

namespace FlowrouteNumbersLib\Controllers;

use FlowrouteNumbersLib\APIException;
use FlowrouteNumbersLib\APIHelper;
use FlowrouteNumbersLib\Configuration;
use FlowrouteNumbersLib\CustomAuthUtility;
use FlowrouteNumbersLib\Models\BillingMethod;

class TelephoneNumbersController {

    /* private fields for configuration */

    /**
     * Tech Prefix 
     * @var string
     */
    private $username;

    /**
     * API Secret Key 
     * @var string
     */
    private $password;

    /**
     * Constructor with authentication and configuration parameters
     */
    function __construct($username=null, $password=null)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Returns the routing and billing information for the specified telephone number on your account
     * @param  string     $telephoneNumber      Required parameter: This is the TN for which you would like to retrieve configuration details for
     * @return string response from the API call
     * @throws APIException
     **/
    public function telephoneNumberDetails (
                $telephoneNumber) 
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $queryBuilder = $queryBuilder.'/tns/{telephone_number}';

        //process optional query parameters
        APIHelper::appendUrlWithTemplateParameters($queryBuilder, array (
            'telephone_number' => $telephoneNumber,
            ));

        //validate and preprocess url
        $queryUrl = APIHelper::cleanUrl($queryBuilder);

        //prepare headers
        $headers = array (
            'user-agent'     => 'Flowroute SDK 1.0'
        );

        $response = CustomAuthUtility::appendCustomAuthParams('GET',
            $queryUrl, $headers, '', $this->username, $this->password);

        //Error handling using HTTP status codes
        if ($response->code == 400) {
            throw new APIException('USER ERROR', 400, $response->body);
        }

        else if ($response->code == 500) {
            throw new APIException('APPLICATION/SERVER ERROR', 500, $response->body);
        }

        else if (($response->code < 200) || ($response->code > 206)) { //[200,206] = HTTP OK
            throw new APIException("HTTP Response Not OK", $response->code, $response->body);
        }

        return $response->body;
    }
        
    /**
     * Purchases the telephone number indicated by the request URI, with the billing method indicated in the body. Allowable billing methods are returned in the search results for available telephone numbers.
     * @param  BillingMethod    $billing     Required parameter: JSON representing the billing method to apply to the telephone number being purchased.
     * @param  string           $number      Required parameter: Telephone number to purchase
     * @return string           response from the API call
     * @throws APIException
     **/
    public function purchase (
                $billing,
                $number) 
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $queryBuilder = $queryBuilder.'/tns/{number}';

        //process optional query parameters
        APIHelper::appendUrlWithTemplateParameters($queryBuilder, array (
            'number'  => $number,
            ));

        //validate and preprocess url
        $queryUrl = APIHelper::cleanUrl($queryBuilder);

        //prepare headers
        $headers = array (
            'user-agent'    => 'Flowroute SDK 1.0',
            'content-type'  => 'application/json; charset=utf-8'
        );

        $response = CustomAuthUtility::appendCustomAuthParams('PUT',
            $queryUrl, $headers, $billing->jsonSerialize(), $this->username, $this->password);

        print_r($response);

        //Error handling using HTTP status codes
        if ($response->code == 400) {
            throw new APIException('USER ERROR', 400, $response->body);
        }

        else if ($response->code == 500) {
            throw new APIException('APPLICATION/SERVER ERROR', 500, $response->body);
        }

        else if (($response->code < 200) || ($response->code > 206)) { //[200,206] = HTTP OK
            throw new APIException("HTTP Response Not OK", $response->code, $response->body);
        }

        return $response->body;
    }
        
    /**
     * Retrieves a list of all the phone numbers on your account
     * @param  int|null        $limit       Optional parameter: Number of items to display (max 200)
     * @param  int|null        $page        Optional parameter: Page to display
     * @param  string|null     $pattern     Optional parameter: A full or partial telephone number to search for
     * @return mixed response from the API call
     * @throws APIException
     **/
    public function listAccountTelephoneNumbers (
                $limit = NULL,
                $page = NULL,
                $pattern = NULL) 
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $queryBuilder = $queryBuilder.'/tns/';

        //process optional query parameters
        APIHelper::appendUrlWithQueryParameters($queryBuilder, array (
            'limit'   => $limit,
            'page'    => $page,
            'pattern' => $pattern,
        ));

        //validate and preprocess url
        $queryUrl = APIHelper::cleanUrl($queryBuilder);

        //prepare headers
        $headers = array (
            'user-agent'    => 'Flowroute SDK 1.0',
            'Accept'        => 'application/json'
        );

        $response = CustomAuthUtility::appendCustomAuthParams('GET',
            $queryUrl, $headers, '', $this->username, $this->password);

        //Error handling using HTTP status codes
        if ($response->code == 400) {
            throw new APIException('USER ERROR', 400, $response->body);
        }

        else if ($response->code == 500) {
            throw new APIException('APPLICATION/SERVER ERROR', 500, $response->body);
        }

        else if (($response->code < 200) || ($response->code > 206)) { //[200,206] = HTTP OK
            throw new APIException("HTTP Response Not OK", $response->code, $response->body);
        }

        return $response->body;
    }
        
    /**
     * Updates the routing information for a telephone number on your account, as indicated by the specified URI. The body of the request requires two routes listed in order of preference (primary first and fail over second).
     * @param  string     $number           Required parameter: The telephone number who's routing you wish to update
     * @param  string     $primary_route    Required parameter: Name of the preffered primary route
     * @param  string     $secondary_route  Required parameter: Name of the preffered failover route
     * @return string response from the API call
     * @throws APIException
     **/
    public function update (
                $number,
                $primary_route,
                $secondary_route) 
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $queryBuilder = $queryBuilder.'/tns/{number}';

        //process optional query parameters
        APIHelper::appendUrlWithTemplateParameters($queryBuilder, array (
            'number' => $number,
            ));

        //validate and preprocess url
        $queryUrl = APIHelper::cleanUrl($queryBuilder);

        //prepare headers
        $headers = array (
            'user-agent'    => 'Flowroute SDK 1.0',
            'content-type'  => 'application/json; charset=utf-8'
        );
        
        $body = '{"routes": [{"name": "' . $primary_route . '"}, {"name": "' . $secondary_route . '"}]}';
        
        $response = CustomAuthUtility::appendCustomAuthParams('PATCH',
            $queryUrl, $headers, $body, $this->username, $this->password);

        //Error handling using HTTP status codes
        if ($response->code == 400) {
            throw new APIException('USER ERROR', 400, $response->body);
        }

        else if ($response->code == 500) {
            throw new APIException('APPLICATION/SERVER ERROR', 500, $response->body);
        }

        else if (($response->code < 200) || ($response->code > 206)) { //[200,206] = HTTP OK
            throw new APIException("HTTP Response Not OK", $response->code, $response->body);
        }

        return $response->body;
    }
}
