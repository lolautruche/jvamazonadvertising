<?php
/**
 * Script for extension debugging.
 * Usage :
 * php bin/php/ezexec.php extension/jvamazonadvertising/tests/amazontester.php 
 */
$result = eZFunctionHandler::execute('amazonadvertising', 'item_search', array(
	'search_index'	=> 'MP3Downloads',
	'keywords'		=> 'billy talent',
	'raw_params'	=> array(
		'ResponseGroup' => array('Offers', 'ItemAttributes')
	)
));
if($result)
{
	$cli->warning($result[0]);
	$cli->warning('Default image variation : '.$result[0]->image);
	$cli->warning('Small image variation : '.$result[0]->image->small['url']);
	$cli->notice($result[0]->url);
}
else
{
	$cli->warning('No result !');
}