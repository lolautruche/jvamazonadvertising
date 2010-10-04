<?php
/**
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 */

class JVAmazonAdvertisingImageHolder extends JVAmazonAdvertisingAbstractItem
{
	/**
	 * Constructor
	 * @param array $imageVariations Associative array of image variations. Key is the variation name
	 * 								 Each variation is an associative array with :
	 * 								 	- url
	 * 								 	- width
	 * 								 	- height
	 * @return JVAmazonAdvertisingImageHolder
	 */
	public function __construct(array $imageVariations)
	{
		parent::__construct($imageVariations);
	}
	
	public function __toString()
	{
		$name = __CLASS__;
		$amazonINI = eZINI::instance('amazonadvertising.ini');
		$defaultImageVariation = $amazonINI->variable('ImageSettings', 'DefaultImageVariation');
		
		if(isset($this->attributeHolder[$defaultImageVariation]))
			$name = $this->attributeHolder[$defaultImageVariation]['url'];
			
		return $name;
	}
}
