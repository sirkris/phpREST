<?php

class messages extends REST
{
	public static $info = "Read or write data pertaining to all messages between users.";
	
	/* Get all messages.  --Kris */
	public static function GET()
	{
		
	}
	
	public static function POST()
	{
		return 405;
	}
	
	public static function PUT()
	{
		return 405;
	}
	
	/* Delete ALL messages!  --Kris */
	public static function DELETE()
	{
		
	}
}
