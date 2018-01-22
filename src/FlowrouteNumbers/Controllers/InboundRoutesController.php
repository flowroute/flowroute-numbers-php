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

class InboundRoutesController {

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
     * Return a list of your inbound routes
     * @param  int|null     $limit     Optional parameter: Number of items to display (max 200)
     * @param  int|null     $page      Optional parameter: Page to display if paginated
     * @return mixed - list of inbound routes
     * @throws APIException 
     **/
    public function mlist (
                $limit = NULL,
                $page = NULL) 
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $queryBuilder = $queryBuilder.'/routes/';

        //process optional query parameters
        APIHelper::appendUrlWithQueryParameters($queryBuilder, array (
            'limit' => $limit,
            'page'  => $page,
        ));

        //validate and preprocess url
        $queryUrl = APIHelper::cleanUrl($queryBuilder);

        //prepare headers
        $headers = array (
            'user-agent'    => 'Flowroute SDK 1.0',
            'Accept'        => 'application/json'
        );

        //append custom auth authorization headers
        $response = CustomAuthUtility::appendCustomAuthParams('GET',
            $queryUrl, $headers, '', $this->username, $this->password);

        //Error handling using HTTP status codes
        if ($response->code == 401) {
            throw new APIException('USER AUTHENTICATION ERROR', 401, $response->body);
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
     * Create a new inbound route to be used by a phone number
     * @param  string     $routeName      Required parameter: New unique name for the route
     * @param  string     $type           Required parameter: Type of inbound route
     * @param  string     $value          Required parameter: The value to be associated with a route.
     * @return string response from the API call
     * @throws APIException
     **/
    public function createNewRoute (
                $routeName,
                $type,
                $value) 
    {
        //the base uri for api requests
        $queryBuilder = Configuration::$BASEURI;
        
        //prepare query string for API call
        $queryBuilder = $queryBuilder.'/routes/{route_name}';

        //process optional query parameters
        APIHelper::appendUrlWithTemplateParameters($queryBuilder, array (
            'route_name' => $routeName,
            ));

        //validate and preprocess url
        $queryUrl = APIHelper::cleanUrl($queryBuilder);
        $body = '{"type": "' . $type . '", "value": "' . $value . '"}';

        //prepare headers
        $headers = array (
            'user-agent'    => 'Flowroute SDK 1.0',
            'content-type'  => 'application/json; charset=utf-8'
        );

        //and invoke the API call request to fetch the response
        $response = CustomAuthUtility::appendCustomAuthParams('PUT',
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
