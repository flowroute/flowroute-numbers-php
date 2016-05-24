<?php 
/*
 * FlowrouteNumbersLib
 *
 * Copyright Flowroute, Inc.  2016
 */

namespace FlowrouteNumbersLib\Models;

use JsonSerializable;

class BillingMethod implements JsonSerializable {
    /**
     * VPRI or METERED
     * @param string $billingMethod public property
     */
    protected $billingMethod;

    /**
     * Constructor to set initial or default values of member properties
	 * @param   string  $method    Initialization value for the property $this->billingMethod
     */
    public function __construct($method=null)
    {
        $this->billingMethod  = $method;
    }

    /**
     * Return a property of the response if it exists.
     * Possibilities include: code, raw_body, headers, body (if the response is json-decodable)
     * @param   string  $property   name of the property to return information about  
     * @return mixed or null if property not found
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            //UTF-8 is recommended for correct JSON serialization
            $value = $this->$property;
            if (is_string($value) && mb_detect_encoding($value, "UTF-8", TRUE) != "UTF-8") {
                return utf8_encode($value);
            }
            else {
                return $value;
            }
        }
        return null;
    }
    
    /**
     * Set the properties of this object
     * @param string $property the property name
     * @param mixed $value the property value
     * @return mixed - updated BillingMethod instance
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            //UTF-8 is recommended for correct JSON serialization
            if (is_string($value) && mb_detect_encoding($value, "UTF-8", TRUE) != "UTF-8") {
                $this->$property = utf8_encode($value);
            }
            else {
                $this->$property = $value;
            }
        }

        return $this;
    }

    /**
     * Encode this object to JSON
     * @return string json encoded string for the billing method
     *    '{"billing_method" : "METERED"}'
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['billing_method'] = $this->billingMethod;
        return json_encode($json);
    }
}