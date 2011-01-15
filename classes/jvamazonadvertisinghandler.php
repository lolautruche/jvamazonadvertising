<?php
/**
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 */

class JVAmazonAdvertisingHandler
{
    /**
     * @var eZINI
     */
    private $amazonINI;
    
    /**
     * Host with http(s)
     * @var string
     */
    private $fullHost;
    
    /**
     * Host without http
     * @var string
     */
    private $host;
    
    /**
     * AmazonWebServices access key (as defined in amazonadvertising.ini)
     * @var string
     */
    private $AWSAccessKey;
    
    /**
     * AmazonWebServices secret key (as defined in amazonadvertising.ini)
     * @var string
     */
    private $AWSSecretKey;
    
    /**
     * AmazonWebServices request URI (as defined in amazonadvertising.ini)
     * @var string
     */
    private $requestURI;
    
    private $aSortedParams;
    
    private $aResponseGroup = array();
    
    /**
     * Default XML Namespace for response parsing
     * @var string
     */
    private $xmlNS;
    
    private $NSPrefix;
    
    /**
     * Constructor. Initializes the handler
     * @throws InvalidArgumentException
     * @return JVAmazonAdvertisingHandler
     */
    public function __construct()
    {
        $this->amazonINI = eZINI::instance('amazonadvertising.ini');
        $this->fullHost = $this->amazonINI->variable('AWSSettings', 'Host');
        $this->host = str_replace(array('http://', 'https://'), '', $this->fullHost);
        $this->requestURI = $this->amazonINI->variable('AWSSettings', 'RequestURI');
        
        // Public access key is mandatory
        $this->AWSAccessKey = $this->amazonINI->variable('AWSSettings', 'AccessKeyID');
        if(empty($this->AWSAccessKey))
            throw new InvalidArgumentException('jvAmazonAdvertising : "AccessKeyID" not provided ! Check amazonadvertising.ini');
        
        // Secret access key is also mandatory
        $this->AWSSecretKey = $this->amazonINI->variable('AWSSettings', 'SecretAccessKey');
        if(empty($this->AWSSecretKey))
            throw new InvalidArgumentException('jvAmazonAdvertising : "SecretAccessKey" not provided ! Check amazonadvertising.ini');
    }
    
    /**
     * Builds the query to be sent to AWS.
     * The query will be signed as described in Product Advertising API documentation
     * @see http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/AnatomyOfaRESTRequest.html
     * @see http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/CHAP_ApiReference.html
     * @param array $aParams array of params that will be added to the REST request. "Operation" param is mandatory
     * @throws InvalidArgumentException
     * @return string
     */
    public function buildQuery(array $aParams)
    {
        if(!isset($aParams['Operation']))
            throw new InvalidArgumentException("jvAmazonAdvertising : 'Operation' param not provided !");
        
        ##### Mandatory parameters #####
        $aParams['Service'] = $this->amazonINI->variable('AWSSettings', 'Service');
        $aParams['Version'] = $this->amazonINI->variable('AWSSettings', 'Version');
        $aParams['Timestamp'] = gmdate("Y-m-d\TH:i:s\Z");
        
        ##### Associate tag #####
        $associateEnabled = $this->amazonINI->variable('AssociateSettings', 'AssociateEnabled') === 'true';
        $associateTag = $this->amazonINI->variable('AssociateSettings', 'AssociateTag');
        if($associateEnabled && !empty($associateTag))
            $aParams['AssociateTag'] = $associateTag;
            
        ##### Response Groups #####
        $defaultResponseGroups = $this->amazonINI->variable('ResponseGroupSettings', 'DefaultResponseGroups');
        $this->aResponseGroup = array_merge($this->aResponseGroup, $defaultResponseGroups);
        // Do we have already declared Response Groups ? If so, merge them
        if(isset($aParams['ResponseGroup']))
        {
            if(!is_array($aParams['ResponseGroup']))
                $this->aResponseGroup[] = $aParams['ResponseGroup'];
            else
                $this->aResponseGroup = array_merge($this->aResponseGroup, $aParams['ResponseGroup']);
        }
        $this->aResponseGroup = array_unique($this->aResponseGroup);
        $aParams['ResponseGroup'] = implode(',', $this->aResponseGroup); // ResponseGroup has to be a string, with comma separated values
        
        ##### Params URL encoding and sorting #####
        // Params need to be sorted case insensitive
        // Build a sort key as an array of lowercase param names to use it with array_multisort
        $aSortKey = array();
        foreach($aParams as $param => $value)
        {
            $aParams[$param] = str_replace('%7E', '~', rawurlencode($value)); // URL Encode but keep "~" chars
            $aSortKey[] = strtolower($param);
        }
        
        // Now sort the array with the sort key
        array_multisort($aSortKey, SORT_ASC, SORT_STRING, $aParams);
        // AWSAccessKeyId MUST be the first param
        $aParams = array_merge(array('AWSAccessKeyId' => $this->AWSAccessKey), $aParams);
        $this->aSortedParams = $aParams;
        
        ##### Create the canonic query #####
        // Create the canonicalized query
        $aCanonicalizedQuery = array();
        foreach($aParams as $param => $value)
        {
            $aCanonicalizedQuery[] = $param.'='.$value;
        }
        $canonicalizedQuery = implode('&', $aCanonicalizedQuery);
        
        ##### Query signature #####
        $signature = $this->signQuery($canonicalizedQuery);
        $urlRequest = $this->fullHost.$this->requestURI.'?'.$canonicalizedQuery."&Signature=$signature";
        
        return $urlRequest;
    }
    
    /**
     * Returns the signature for given REST query
     * @param string $query
     * @return string
     */
    private function signQuery($query)
    {
        $stringToSign = "GET\n$this->host\n$this->requestURI\n$query";
        $signature = base64_encode(hash_hmac('sha256', $stringToSign, $this->AWSSecretKey, true));
        $signature = str_replace("%7E", "~", rawurlencode($signature));
        
        return $signature;
    }
    
    public function getSortedParams()
    {
        return $this->aSortedParams;
    }
    
    public function getResponseGroups()
    {
        return $this->aResponseGroup;
    }
    
    /**
     * Get results for request
     * @param string $urlRequest Request URL that has been returned by $this->buildQuery()
     * @return array|SimpleXMLElement
     */
    public function getResults($urlRequest, $asObjects=true)
    {
        $httpResult = $this->getHTTPResult($urlRequest);
        $xml = new SimpleXMLElement($httpResult);
        $aNS = $xml->getDocNamespaces();
        if(isset($aNS[''])) // Check for default NS (no prefix defined in xml doc)
        {
            $this->xmlNS = $aNS[''];
            $this->NSPrefix = 'default:';
            $xml->registerXPathNamespace('default', $aNS['']);
        }
        
        // Check response validity. Will throw an exception if not valid
        try
        {
            $this->checkAWSResponseValidity($xml);
        }
        catch(JVAmazonAWSException $e)
        {
            // If we get a AWS_NO_MATCHING_RESULT, juste write a notice. And empty array will be returned
            if($e->getCode() == JVAmazonAWSException::AWS_NO_MATCHING_RESULT)
                eZDebug::writeNotice($e->getMessage(), 'jvAmazonAdvertising');
            else
                throw $e;
        }
        
        if($asObjects) // Hydrate Result Items
        {
            $items = $xml->Items->Item;
            $result = array();
            
            // Class for result item. Must implement IJVAmazonAdvertisingItem
            $resultItemClass = $this->amazonINI->variable('ResultSettings', 'ResultItemClass');
            $impl = class_implements($resultItemClass);
            if(!in_array('IJVAmazonAdvertisingItem', $impl))
                throw new RuntimeException("ResultItemClass '$resultItemClass' does not implement IJVAmazonAdvertisingItem");
            
            $aResponseGroupHandlers = $this->amazonINI->variable('ResponseGroupSettings', 'ResponseGroupHandlers');
            foreach($items as $i => $item)
            {
                $attributes = array(
                    'id'    => (string)$item->ASIN,
                );
                
                // Search a handler for each ResponseGroup
                foreach($this->aResponseGroup as $responseGroup)
                {
                    try
                    {
                        // Check if a handler is declared
                        if(!isset($aResponseGroupHandlers[$responseGroup]))
                            throw new JVAmazonAWSException("Undeclared ResponseGroupHandler for '$responseGroup' ResponseGroup",
                                                            JVAmazonAWSException::RESPONSE_GROUP_ERROR);
                        
                        $handlerClass = $aResponseGroupHandlers[$responseGroup];
                        $responseGroupHandler = new $handlerClass();
                        if(!$responseGroupHandler instanceof IJVAmazonAdvertisingResponseGroupHandler) // Check interface implementation
                            throw new RuntimeException("ResponseGroup handler '$handlerClass' for '$responseGroup' does not implement IJVAmazonAdvertisingResponseGroupHandler interface");
                        
                        $handlerResult = $responseGroupHandler->handleResult($item);
                        $attributes = array_merge($attributes, $handlerResult);

                        unset($responseGroupHandler, $handlerResult);
                    }
                    catch(Exception $e)
                    {
                        eZDebug::writeError("[$handlerClass] : ".$e->getMessage(), 'jvAmazonAdvertising');
                        eZLog::write("jvAmazonAdvertising [$handlerClass] : ".$e->getMessage(), 'error.log');
                        continue;
                    }
                }
                
                $hydratedItem = new $resultItemClass();
                $hydratedItem->fromArray($attributes);
                $result[] = $hydratedItem;
            }
        }
        else // $asObjects = false => return root SimpleXMLElement for further treatment
        {
            $result = $xml;
        }
        
        return $result;
    }
    
    /**
     * Returns AWS result. Expected result is an XML string
     * @param string $urlRequest URL for the request
     * @throws RuntimeException
     * @return string
     */
    private function getHTTPResult($urlRequest)
    {
        $httpResult = null;
        
        if(extension_loaded('curl')) // Use cURL if extension loaded
        {
            // Proxy config
            $ini = eZINI::instance('site.ini');
            $proxy = $ini->hasVariable( 'ProxySettings', 'ProxyServer' ) ? $ini->variable( 'ProxySettings', 'ProxyServer' ) : false;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlRequest);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            if ( $proxy )
            {
                curl_setopt ( $ch, CURLOPT_PROXY , $proxy );
                $userName = $ini->hasVariable( 'ProxySettings', 'User' ) ? $ini->variable( 'ProxySettings', 'User' ) : false;
                $password = $ini->hasVariable( 'ProxySettings', 'Password' ) ? $ini->variable( 'ProxySettings', 'Password' ) : false;
                if ( $userName )
                    curl_setopt ( $ch, CURLOPT_PROXYUSERPWD, "$userName:$password" );
            }
            
            $httpResult = curl_exec($ch);
            if(!$httpResult)
                throw new RuntimeException("jvAmazonAdvertising : Error occurred while contacting $urlRequest - ".curl_error($ch));
        }
        else // cURL extension not found, use "standard" file_get_contents()
        {
            $httpResult = @file_get_contents($urlRequest);
            if(!$httpResult)
                throw new RuntimeException("jvAmazonAdvertising : Error occurred while contacting $urlRequest");
        }
        
        return $httpResult;
    }
    
    /**
     * Checks if AWS response is valid. Will throw a JVAmazonAWSException if not
     * @param SimpleXMLElement $xml
     * @return void
     * @throws JVAmazonAWSException
     */
    private function checkAWSResponseValidity(SimpleXMLElement $xml)
    {
        // Handle ErrorResponse
        $rootNodeName = $xml->getName();
        if(stripos($rootNodeName, 'ErrorResponse') !== false)
        {
            $errorCode = $xml->Error->Code;
            $errorMessage = $xml->Error->Message;
            $requestID = $xml->RequestID;
            $errMessage = "Got invalid result from request. $errorCode : $errorMessage. Request ID was $requestID";
            throw new JVAmazonAWSException($errMessage, JVAmazonAWSException::AWS_ERROR_RESPONSE);
        }
        
        // Check request validity
        $requestIsValid = strtolower($xml->Items->Request->IsValid) == 'true';
        if(!$requestIsValid) // Invalid request, return the first error message
        {
            $firstError = $xml->Items->Request->Errors->Error[0];
            $errMessage = "Invalid request. $firstError->Code : $firstError->Message";
            throw new JVAmazonAWSException($errMessage, JVAmazonAWSException::AWS_INVALID_REQUEST);
        }
        
        // Check number of matching results
        $nbResults = count($xml->Items->Item);
        if($nbResults == 0)
        {
            throw new JVAmazonAWSException('No matching item found', JVAmazonAWSException::AWS_NO_MATCHING_RESULT);
        }
        
        // Looking for generic error
        $errors = $xml->Items->Request->Errors->Error;
        if($errors)
        {
            $firstError = $errors[0];
            $errorMessage = "An unexpected error has occurred. $firstError->Code : $firstError->Message";
            throw new JVAmazonAWSException($errorMessage, JVAmazonAWSException::AWS_UNEXEPECTED_ERROR);
        }
    }
}