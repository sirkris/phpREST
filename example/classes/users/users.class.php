<?php

class users extends REST
{
	public static $info = "Read or write data pertaining to users.";
	
	/* Get all users.  --Kris */
	public static function GET()
	{
		
	}
	
	public static function POST()
	{
		return 405;  // Method not allowed.
	}
	
	/* Update all users.  --Kris */
	public static function PUT()
	{
		
	}
}
