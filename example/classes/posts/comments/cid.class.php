<?php

class cid extends comments
{
	public static $info = "Read or write data pertaining to a specific comment.";
	
	/* Get info on specified cid.  --Kris */
	public static function GET()
	{
		
	}
	
	public static function POST()
	{
		return 405;  // Method not allowed.
	}
	
	/* Update the specified comment.  --Kris */
	public static function PUT()
	{
		
	}
	
	/* Delete the specified comment.  --Kris */
	public static function DELETE()
	{
		
	}
}
