<?php
/**
 * Unit tests for JVAmazonAdvertising
 * @author Jerome Vieilledent
 */
class JVAmazonAdvertisingTest extends ezpTestCase
{
	/**
	 * @var eZINI
	 */
	private $amazonINI;
	
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->amazonINI = eZINI::instance('amazonadvertising.ini');
	}
	
	/**
	 * ResponseGroup Handlers
	 * @return void
	 */
	public function testResponseGroupHandlers()
	{
		$responseGroupHandlers = $this->amazonINI->variable('ResponseGroupSettings', 'ResponseGroupHandlers');
		foreach($responseGroupHandlers as $responseGroup => $handlerClass)
		{
			// Check if class exists
			$this->assertTrue(class_exists($handlerClass));
			
			// Check if each handler implements IJVAmazonAdvertisingResponseGroupHandler
			$aImplInterfaces = class_implements($handlerClass);
        	$this->assertArrayHasKey('IJVAmazonAdvertisingResponseGroupHandler', $aImplInterfaces, 
        							 "ResponseGroup Handler class '$handlerClass' doesn't implement interface 'IJVAmazonAdvertisingResponseGroupHandler'");
		}
	}
	
	/**
	 * Result Items
	 * @return void
	 */
	public function testResultItem()
	{
		$resultItemClass = $this->amazonINI->variable('ResultSettings', 'ResultItemClass');
		// Check if class exists
		$this->assertTrue(class_exists($resultItemClass));
		
		// Check if class implements IJVAmazonAdvertisingItem
		$aImpl = class_implements($resultItemClass);
		$this->assertArrayHasKey('IJVAmazonAdvertisingItem', $aImpl, 
        						 "ResponseGroup Handler class '$resultItemClass' doesn't implement interface 'IJVAmazonAdvertisingItem'");
	}
	
	public function providerInvalidParams()
	{
		$badParams = array(
			'Operation'		=> 'ItemSearch',
			'SearchIndex'	=> 'InvalidSearchIndex',
			'Keywords'		=> 'harry potter',
			'ResponseGroup' => array('Offers', 'ItemAttributes', 'NonExistentResponseGroup')
		);
		
		return array(
			array($badParams)
		);
	}
	
	/**
	 * @dataProvider providerInvalidParams
	 */
	public function testBadQueryParams(array $badParams)
	{
		$handler = new JVAmazonAdvertisingHandler();
		
		$url = $handler->buildQuery($badParams);
		$this->setExpectedException('JVAmazonAWSException');
		$badResult = $handler->getResults($url);
	}
	
	public function providerValidParams()
	{
		$aParams = array(
			'Operation'		=> 'ItemSearch',
			'SearchIndex'	=> 'Books',
			'Keywords'		=> 'harry potter',
			'ResponseGroup' => array('Offers', 'ItemAttributes')
		);
		
		return array(
			array($aParams)
		);
	}
	
	/**
	 * @dataProvider providerValidParams
	 * @param array $aParams
	 */
	public function testBuildQueryNoOperation(array $aParams)
	{
		unset($aParams['Operation']);
		$handler = new JVAmazonAdvertisingHandler();
		
		// No Operation param => should throw a InvalidArgumentException
		$this->setExpectedException('InvalidArgumentException');
		$badURL = $handler->buildQuery($aParams);
	}
	
	/**
	 * @dataProvider providerValidParams
	 * @return multitype:JVAmazonAdvertisingHandler string
	 */
	public function testQuery(array $aParams)
	{
		$handler = new JVAmazonAdvertisingHandler();
		$url = $handler->buildQuery($aParams);
		// Check if URL is string with Signature GET param
		$this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_STRING, $url);
		$this->assertStringStartsWith('http', $url);
		$this->assertTrue(strpos($url, 'Signature=') !== false);
		
		// Check result
		$aResult = $handler->getResults($url);
		$this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $aResult);
		
		$resultItemClass = $this->amazonINI->variable('ResultSettings', 'ResultItemClass');
		foreach($aResult as $result)
		{
			$this->assertType($resultItemClass, $result);
		}
	}
	
	public function providerFetchSearch()
	{
		$aParams = array(
			'search_index'	=> 'Books',
			'keywords'		=> 'harry potter',
			'raw_params'	=> array(
				'ResponseGroup' => array('Offers', 'ItemAttributes')
			)
		);
		
		return array(
			array($aParams)
		);
	}
	
	/**
	 * Tests item_search fetch function
	 * @dataProvider providerFetchSearch 
	 */
	public function testFetchSearch(array $aParams)
	{
		$result = eZFunctionHandler::execute('amazonadvertising', 'item_search', $aParams);
		$this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $result);
	}
	
	public function providerFetchLookup()
	{
		$aParams = array(
			'id'	=> '0545139708',
		);
		
		return array(
			array($aParams)
		);
	}
	
	/**
	 * Tests item_lookup fetch function
	 * @dataProvider providerFetchLookup
	 */
	public function testFetchLookup(array $aParams)
	{
		$result = eZFunctionHandler::execute('amazonadvertising', 'item_lookup', $aParams);
		$resultItemClass = $this->amazonINI->variable('ResultSettings', 'ResultItemClass');
		$this->assertType($resultItemClass, $result);
	}
}