<?php

class friends extends username
{
	public static $info = "Read or write data pertaining to the specified username's friends list.";
	
	/* Get all the user's friends.  --Kris */
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
	
	/* Delete ALL of this user's friends.  That's not very friendly, ya know.  --Kris */
	public static function DELETE()
	{
		
	}
}
