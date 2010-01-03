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
