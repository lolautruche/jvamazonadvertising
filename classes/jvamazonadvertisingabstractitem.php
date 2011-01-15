<?php
/**
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 */

/**
 * Abstract class for items returned by AWS
 */
abstract class JVAmazonAdvertisingAbstractItem implements IJVAmazonAdvertisingItem
{
    /**
     * Attribute holder is an associative array holding all attributes of the item
     * @var array
     */
    protected $attributeHolder = array();
    
    /**
     * An associative array of attributes which maps to member functions, used for fetching data with functions (as for eZPersistentObject)
     * @var array
     */
    protected $attributeFunctions = array();
    
    public function __construct(array $attributes = array())
    {
        if($attributes)
            $this->fromArray($attributes);
    }
    
    /**
     * Sets attributes directly from an associative array.
     * Key is the attribute name
     * @return void
     */
    public function fromArray(array $attributes)
    {
        $this->attributeHolder = array_merge($this->attributeHolder, $attributes);
    }
    
    /**
     * Proxy for __get() magic method, in order to be compatible with eZ Publish template system
     * @param string $attrName
     * @return mixed
     */
    public function attribute($attrName)
    {
        return $this->__get($attrName);
    }
    
    /**
     * Checks if current item has given attribute
     * @param string $attrName
     * @return bool
     */
    public function hasAttribute($attrName)
    {
        $ret = false;
        if(isset($this->attributeHolder[$attrName]) || isset($this->attributeFunctions[$attrName]))
            $ret = true;
            
        return $ret;
    }
    
    public function setAttribute($attrName, $attrValue)
    {
        $this->__set($attrName, $attrValue);
    }

    /**
     * Returns all the attributes available for current item
     * @return unknown_type
     */
    public function attributes()
    {
        $attrs = array();
        $attrs = array_unique( array_merge( $attrs, array_keys( $this->attributeFunctions ) ) );
        $attrs = array_unique( array_merge( $attrs, array_keys( $this->attributeHolder ) ) );
        
        return $attrs;
    }
    
    /**
     * Generic method to get an attribute of the current item
     * @param string $attrName
     * @return mixed
     */
    public function __get($attrName)
    {
        $val = null;
        if(isset($this->attributeHolder[$attrName])) // First test static attributes
        {
            $val = $this->attributeHolder[$attrName];
            if( $attrName == 'url' ) // URL attribute is url encoded, so it must be decoded to be usable
                $val = rawurldecode( $val );
        }
        else if(isset($this->attributeFunctions[$attrName])) // Then test function attributes
        {
            $method = $this->attributeFunctions[$attrName];
            if(method_exists($this, $method))
                $val = $this->$method();
            else
                eZDebug::writeError("Non existent method '$method' for item ".__CLASS__, 'jvAmazonAdvertising');
        }
        
        return $val;
    }
    
    /**
     * Generic method to set an attribute for the current item
     * @param string $attrName
     * @param string $attrValue
     * @return void
     */
    public function __set($attrName, $attrValue)
    {
        $this->attributeHolder[$attrName] = $attrValue;
    }
}