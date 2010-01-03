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

class JVAmazonAWSException extends Exception
{
	const AWS_ERROR_RESPONSE = -1,
		  AWS_INVALID_REQUEST = -2,
		  AWS_UNEXEPECTED_ERROR = -3,
		  AWS_NO_MATCHING_RESULT = -4;
		  
	const RESPONSE_GROUP_ERROR = -10;
}