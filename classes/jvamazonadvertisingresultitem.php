<?php
/**
 * JVAmazonAdvertisingResultItem
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 */

class JVAmazonAdvertisingResultItem extends JVAmazonAdvertisingAbstractItem
{
	public function __construct(array $attributes = array())
	{
		parent::__construct($attributes);
	}
	
	public function __toString()
	{
		return $this->title;
	}
}