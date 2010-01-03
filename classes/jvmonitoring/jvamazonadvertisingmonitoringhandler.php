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
 * Monitoring Handler for JVMonitoring extension
 * @author Jerome Vieilledent
 */
class JVAmazonAdvertisingMonitoringHandler implements IJVMonitoringHandler
{
	private $queryParams;
	private $errorMessage;
	
	public function setParams(array $params)
	{
		$this->queryParams = $params;
	}
	
	public function isUp()
	{
		try
		{
			// If a problem occurs, JVAmazonAdvertisingHandler will raise an exception
			$isUp = true;
			$handler = new JVAmazonAdvertisingHandler();
			$url = $handler->buildQuery($this->queryParams);
			$result = $handler->getResults($url);
		}
		catch(Exception $e)
		{
			$isUp = false;
			$this->errorMessage = $e->getMessage();
		}
		
		return $isUp;
	}
	
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}
}