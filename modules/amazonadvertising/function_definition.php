<?php
/**
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 */

$FunctionList = array();

/**
 * Function ItemSearch
 * For all available params, see http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/ItemSearch.html
 */
$FunctionList['item_search'] = array(      'name' => 'item_search',
                                           'operation_types' => 'read',
                                           'call_method' => array( 'class' => 'JVAmazonAdvertisingFunctionCollection',
                                                                   'method' => 'itemSearch' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( array( 'name' => 'keywords',
                                                                         'type' => 'string',
                                                                         'required' => true,
                                                                         'default' => '' ),
                                                                  array( 'name' => 'search_index',
                                                                         'type' => 'string',
                                                                         'required' => false,
                                                                         'default' => null ),
                                                                  array( 'name' => 'item_page',
                                                                         'type' => 'string',
                                                                         'required' => false,
                                                                         'default' => null ),
                                                                  array( 'name' => 'sort',
                                                                         'type' => 'string',
                                                                         'required' => false,
                                                                         'default' => null ),
                                                                  array( 'name' => 'response_group',
                                                                         'type' => 'array',
                                                                         'required' => false,
                                                                         'default' => null ),
                                                                  array( 'name' => 'raw_params',
                                                                         'type' => 'array',
                                                                         'required' => false,
                                                                         'default' => null ) ) );

/**
 * Function ItemLookup
 * For all available params, see http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/ItemLookup.html
 */
$FunctionList['item_lookup'] = array(      'name' => 'item_lookup',
                                           'operation_types' => 'read',
                                           'call_method' => array( 'class' => 'JVAmazonAdvertisingFunctionCollection',
                                                                   'method' => 'itemLookup' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( array( 'name' => 'id',
                                                                         'type' => 'string',
                                                                         'required' => true,
                                                                         'default' => '' ),
                                                                  array( 'name' => 'id_type',
                                                                         'type' => 'string',
                                                                         'required' => false,
                                                                         'default' => 'ASIN' ),
                                                                  array( 'name' => 'search_index',
                                                                         'type' => 'string',
                                                                         'required' => false,
                                                                         'default' => null ),
                                                                  array( 'name' => 'response_group',
                                                                         'type' => 'array',
                                                                         'required' => false,
                                                                         'default' => null ),
                                                                  array( 'name' => 'raw_params',
                                                                         'type' => 'array',
                                                                         'required' => false,
                                                                         'default' => null ) ) );
