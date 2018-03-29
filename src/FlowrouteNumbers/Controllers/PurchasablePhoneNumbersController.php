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

class PurchasablePhoneNumbersController {

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
     * Retrieves a list of the NPA-NXXs (area codes and exchanges) that contain purchasable telephone numbers.
     * @param  int|null     $limit     Optional parameter: Number of items to display (Max 200)
     * @param  int|null     $npa       Optional parameter: Restricts the results to this NPA.
     * @param  int|null     $page      Optional parameter: Page to display
     * @return mixed response from the API call
     * @throws APIException
     **/
    public function listAreaAndExchange (
                $limit = NULL,
                $npa = NULL,
                $page = NULL) 
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $queryBuilder = $queryBuilder.'/available-tns/npanxxs/';

        //process optional query parameters
        APIHelper::appendUrlWithQueryParameters($queryBuilder, array (
            'limit' => $limit,
            'npa'   => $npa,
            'page'  => $page,
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
     * Retrieves a list of all NPAs (area codes) that contain purchasable telephone numbers.
     * @param  int     $limit     Required parameter: Number of items to display (Max 200).
     * @return mixed response from the API call
     * @throws APIException
     **/
    public function listAvailableNPAs (
                $limit) 
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $queryBuilder = $queryBuilder.'/available-tns/npas/';

        //process optional query parameters
        APIHelper::appendUrlWithQueryParameters($queryBuilder, array (
            'limit' => $limit,
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
     * Search for phone numbers by a Numbering Plan Area (NPA), Numbering Plan Area and Exchange (NPA-NXX), State, or rate center.
     * @param  int|null        $limit          Optional parameter: Number of items to display (Max 200)
     * @param  int|null        $npa            Optional parameter: Restricts the results to the three digit NPA (area code) specified. This is optional, unless NXX is present
     * @param  int|null        $nxx            Optional parameter: Restricts the results to the three digit NXX (exchange) specified.
     * @param  int|null        $page           Optional parameter: Page to display
     * @param  string|null     $ratecenter     Optional parameter: Restricts the results to the ratecenter specified. If present, state is required
     * @param  string|null     $state          Optional parameter: Restricts the results to the state specified. This is optional, unless ratecenter is present.
     * @param  string|null     $tn             Optional parameter: Restricts the results to the telephone number specified.
     * @return mixed response from the API call
     * @throws APIException
     **/
    public function search (
                $limit = NULL,
                $npa = NULL,
                $nxx = NULL,
                $page = NULL,
                $ratecenter = NULL,
                $state = NULL,
                $tn = NULL) 
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $queryBuilder = $queryBuilder.'/available-tns/tns/';

        //process optional query parameters
        APIHelper::appendUrlWithQueryParameters($queryBuilder, array (
            'limit'      => $limit,
            'npa'        => $npa,
            'nxx'        => $nxx,
            'page'       => $page,
            'ratecenter' => $ratecenter,
            'state'      => $state,
            'tn'         => $tn,
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
        
}
