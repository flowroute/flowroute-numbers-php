<?php 
/*
 * FlowrouteNumbersLib
 *
 * Copyright Flowroute, Inc. 2016
 */

namespace FlowrouteNumbersLib\Models;

use JsonSerializable;

class Route implements JsonSerializable {
    /**
     * Unique name of the inbound route, or the reserved options of 'sip-reg'.
     * @param string $name public property
     */
    protected $name;

    /**
     * Constructor to set initial or default values of member properties
	 * @param   string            $name   Initialization value for the property $this->name
     */
    public function __construct($name=null)
    {
        $this->name = 'sip-reg';
        if(!is_null($name)) {
            $this->name = $name;
        }
    }

    /**
     * Return a property of the response if it exists.
     * Possibilities include: code, raw_body, headers, body (if the response is json-decodable)
     * @param   string  $property       name of the property to retrieve information about
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
     * @return  Route instance that has been updated
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
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['name'] = $this->name;
        return $json;
    }
}