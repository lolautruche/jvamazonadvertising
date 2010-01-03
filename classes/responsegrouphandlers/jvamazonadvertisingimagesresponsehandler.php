<?php
// SOFTWARE NAME: jvAmazonAdvertising
// SOFTWARE RELEASE: @@@VERSION@@@
// COPYRIGHT NOTICE: Copyright (C) 2010 Jerome Vieilledent
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.

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