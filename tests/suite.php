<?php

class JVAmazonAdvertisingTestSuite extends ezpTestSuite
{
	public function __construct()
	{
		parent::__construct();
		$this->setName( "JVAmazonAdvertising Test Suite" );
		
		$this->addTestSuite( 'JVAmazonAdvertisingTest' );
	}
	
	public static function suite()
	{
		return new self();
	}
	
}