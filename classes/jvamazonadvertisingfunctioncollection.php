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

class JVAmazonAdvertisingFunctionCollection
{
	/**
	 * Searches items in Amazon database through AWS
	 * @param string $keywords Keywords query string
	 * @param string $searchIndex The product category to search. See http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/APPNDX_SearchIndexValues.html
	 * @param integer $itemPage Retrieves a specific page of items from all of the items in a response
	 * @param string $sort Means by which the items in the response are ordered. See http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/APPNDX_SortValuesArticle.html
	 * @param array $responseGroup Specifies the types of values to return
	 * @param array $rawParams Params transferred "as is" to AWS
	 * @return array Array of objects implementing IJVAmazonAdvertisingItem (see amazonadvertising.ini for exact class)
	 */
	public static function itemSearch($keywords, $searchIndex=null, $itemPage=null, $sort=null, array $responseGroup=null, array $rawParams=null)
	{
		eZDebug::createAccumulator( 'AWSItemSearch', 'JVAmazonAdvertising' );
        eZDebug::accumulatorStart( 'AWSItemSearch' );
		
		if($rawParams && !is_array($rawParams))
			$rawParams = array($rawParams);
			
		$aParams = array(
			'Operation'		=> 'ItemSearch',
			'Keywords' 		=> $keywords
		);
		if($searchIndex)
			$aParams['SearchIndex'] = $searchIndex;
		if($itemPage)
			$aParams['ItemPage'] = $itemPage;
		if($sort)
			$aParams['Sort'] = $sort;
		if($responseGroup)
			$aParams['ResponseGroup'] = $responseGroup;
		if($rawParams)
			$aParams = array_merge($aParams, $rawParams);
			
		try
		{
			$handler = new JVAmazonAdvertisingHandler();
			$url = $handler->buildQuery($aParams);
			eZDebug::writeNotice($url, 'jvAmazonAdvertising');
			$result = $handler->getResults($url);
			
			eZDebug::accumulatorStop( 'AWSItemSearch' );
			
			return array('result' => $result);
		}
		catch(Exception $e)
		{
			$errMessage = $e->getMessage();
			eZLog::write($errMessage, 'error.log');
			eZDebug::writeError($errMessage, 'jvAmazonAdvertising');
			
			eZDebug::accumulatorStop( 'AWSItemSearch' );
			
			return array('error' => $errMessage);
		}
	}
	
	/**
	 * Fetches details for an item, by its ID
	 * @param string $itemID
	 * @param string $idType Valid Values: ASIN|SKU|UPC|EAN|ISBN (US only, when search index is Books)|JAN. UPC is not valid in the CA locale.
	 * @param string $searchIndex The product category to search. See http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/APPNDX_SearchIndexValues.html
	 * @param array $responseGroup Specifies the types of values to return
	 * @param array $rawParams Params transferred "as is" to AWS
	 * @return array Array of objects implementing IJVAmazonAdvertisingItem (see amazonadvertising.ini for exact class)
	 */
	public static function itemLookup($itemID, $idType='ASIN', $searchIndex=null, array $responseGroup=null, array $rawParams=null)
	{
		eZDebug::createAccumulator( 'AWSItemLookup', 'JVAmazonAdvertising' );
        eZDebug::accumulatorStart( 'AWSItemLookup' );
		
		$aParams = array(
			'Operation'		=> 'ItemLookup',
			'ItemId' 		=> $itemID,
			'IdType'		=> $idType
		);
		
		if($searchIndex)
			$aParams['SearchIndex'] = $searchIndex;
		if($responseGroup)
			$aParams['ResponseGroup'] = $responseGroup;
		if($rawParams)
			$aParams = array_merge($aParams, $rawParams);
			
		try
		{
			$handler = new JVAmazonAdvertisingHandler();
			$url = $handler->buildQuery($aParams);
			eZDebug::writeNotice($url, 'jvAmazonAdvertising');
			$result = $handler->getResults($url);
			
			eZDebug::accumulatorStop( 'AWSItemLookup' );
			
			// Return only first result as it has to be only one result for ItemLookup queries
			return array('result' => $result[0]);
		}
		catch(Exception $e)
		{
			$errMessage = $e->getMessage();
			eZLog::write($errMessage, 'error.log');
			eZDebug::writeError($errMessage, 'jvAmazonAdvertising');
			
			eZDebug::accumulatorStop( 'AWSItemLookup' );
			
			return array('error' => $errMessage);
		}
	}
}