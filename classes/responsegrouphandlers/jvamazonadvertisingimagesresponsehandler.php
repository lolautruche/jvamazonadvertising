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
 * Handler for Images ResponseGroup
 */
class JVAmazonAdvertisingImagesResponseHandler implements IJVAmazonAdvertisingResponseGroupHandler
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
		$result = array();
		$imageVariations = array();
		
		if(!$currentItem->ImageSets->ImageSet)
			throw new JVAmazonAWSException('<ImageSets> tag not found in XML tree !', JVAmazonAWSException::RESPONSE_GROUP_ERROR);
			
		foreach($currentItem->ImageSets->ImageSet->children() as $imageSet)
		{
			$variation = array(
				'url'		=> (string)$imageSet->URL,
				'width'		=> (int)$imageSet->Width,
				'height'	=> (int)$imageSet->Height
			);
			$variationName = str_replace('image', '', strtolower($imageSet->getName()));
			$imageVariations[$variationName] = $variation;
		}
		
		$imageHolder = new JVAmazonAdvertisingImageHolder($imageVariations);
		$result['image'] = $imageHolder;
		
		return $result;
	}
}