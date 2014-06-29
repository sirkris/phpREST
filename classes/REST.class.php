<?php

/*
 * All methods return either an array with the status code and returned data or just a numeric status code.
 * 
 * This class should NOT need to be modified!  It just exists to establish defaults for any class that doesn't 
 * contain a given method.  For example, if you send a DELETE query to the users class but that class doesn't 
 * contain a DELETE() method, the API will return what's specified in this class, which is a 501 Not Implemented 
 * code.
 * 
 * --Kris
 */
class REST
{
	public static $info = "The base class from which all other API classes should extend.";
	
	public static function GET()
	{
		return 501; // Not Implemented.  --Kris
	}
	
	public static function POST()
	{
		return 501;
	}
	
	public static function PUT()
	{
		return 501;
	}
	
	public static function DELETE()
	{
		return 501;
	}
}
