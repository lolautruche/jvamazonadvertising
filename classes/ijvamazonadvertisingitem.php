<?php
/**
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 */

/**
 * Interface for result items
 */
interface IJVAmazonAdvertisingItem
{
    public function fromArray(array $attributes);
    
    /**
     * Proxy for __get() magic method, in order to be compatible with eZ Publish template system
     * @param string $attrName
     * @return mixed
     */
    public function attribute($attrName);
    
    /**
     * Checks if current item has given attribute
     * @param string $attrName
     * @return bool
     */
    public function hasAttribute($attrName);
    
    /**
     * Sets attributes directly from an associative array.
     * Key is the attribute name
     * @return void
     */
    public function setAttribute($attrName, $attrValue);
    
    /**
     * Returns all the attributes available for current item
     * @return unknown_type
     */
    public function attributes();
    
    /**
     * Generic method to get an attribute of the current item
     * @param string $attrName
     * @return mixed
     */
    public function __get($attrName);
    
    /**
     * Generic method to set an attribute for the current item
     * @param string $attrName
     * @param string $attrValue
     * @return void
     */
    public function __set($attrName, $attrValue);
}