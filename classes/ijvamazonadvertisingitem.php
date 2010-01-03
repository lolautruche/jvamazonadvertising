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
 * Interface for result items
 */
interface IJVAmazonAdvertisingItem
{
	public function fromArray(array $attributes);
	
	/**
	 * Proxy for __get() magic method, in order to be compatible with eZ Publish template system
	 * @param string $attrName
	 * @return mixed
	 */
	public function attribute($attrName);
	
	/**
	 * Checks if current item has given attribute
	 * @param string $attrName
	 * @return bool
	 */
	public function hasAttribute($attrName);
	
	/**
	 * Sets attributes directly from an associative array.
	 * Key is the attribute name
	 * @return void
	 */
	public function setAttribute($attrName, $attrValue);
	
	/**
	 * Returns all the attributes available for current item
	 * @return unknown_type
	 */
	public function attributes();
	
	/**
	 * Generic method to get an attribute of the current item
	 * @param string $attrName
	 * @return mixed
	 */
	public function __get($attrName);
	
	/**
	 * Generic method to set an attribute for the current item
	 * @param string $attrName
	 * @param string $attrValue
	 * @return void
	 */
	public function __set($attrName, $attrValue);
}