<?php
/**
 * @copyright Copyright (C) 2010 - Jerome Vieilledent. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Jerome Vieilledent
 * @version @@@VERSION@@@
 * @package jvamazonadvertising
 * @subpackage exception
 */

class JVAmazonAWSException extends Exception
{
    const AWS_ERROR_RESPONSE = -1,
          AWS_INVALID_REQUEST = -2,
          AWS_UNEXEPECTED_ERROR = -3,
          AWS_NO_MATCHING_RESULT = -4;
          
    const RESPONSE_GROUP_ERROR = -10;
}