<?php
/**
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 * @subpackage monitoring
 */

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