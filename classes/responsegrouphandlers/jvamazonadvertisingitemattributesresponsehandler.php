<?php
/**
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 * @subpackage responsegrouphandlers
 */

/**
 * Handler for ItemAttributes ResponseGroup
 */
class JVAmazonAdvertisingItemAttributesResponseHandler implements IJVAmazonAdvertisingResponseGroupHandler
{
    public function __construct()
    {
        
    }
    
    /**
     * (non-PHPdoc)
     * @see extension/jvamazonadvertising/classes/responsegrouphandlers/IJVAmazonAdvertisingResponseGroupHandler#handleResult($currentItem)
     */
    public function handleResult(SimpleXMLElement $currentItem)
    {
        $result = array(
            'url'    => (string)$currentItem->DetailPageURL
        );
        
        // Iterate item attributes to build our result object
        $itemAttributes = $currentItem->ItemAttributes->children();
        foreach($itemAttributes as $itemAttr)
        {
            $nodeName = strtolower($itemAttr->getName());
            if(!isset($result[$nodeName]))
                $result[$nodeName] = (string)$itemAttr;
            else // If current attribute is already defined, we concatenate
                $result[$nodeName] .= ', '.(string)$itemAttr;
        }
        
        return $result;
    }
}