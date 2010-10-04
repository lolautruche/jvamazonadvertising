<?php
/**
 * ezinfo
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 */
class jvamazonadvertisingInfo
{
	static function info()
    {
        return array( 'Name'      => '<a href="http://projects.ez.no/jvamazonadvertising" target="_blank"> jvAmazonAdvertising </a>',
                      'Version'   => '@@@VERSION@@@',
                      'Copyright' => 'Copyright © 2010 - '.date('Y').' Jérôme Vieilledent',
                      'Author'   => '<a href="http://www.lolart.net" target="_blank">Jérôme Vieilledent</a>'
                    );
    }
}