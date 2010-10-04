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
 * Interface for ResponseGroup handlers
 */
interface IJVAmazonAdvertisingResponseGroupHandler
{
	/**
	 * Must return an associative array to be embedded with the hydrated object (result item)
	 * Key is the attribute name (beware of duplicate names). Value can be anything.
	 * @param SimpleXMLElement $currentItem Current <Item> XML element returned by AWS
	 * @return array
	 */
	public function handleResult(SimpleXMLElement $currentItem);
}