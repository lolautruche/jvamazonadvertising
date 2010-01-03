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
