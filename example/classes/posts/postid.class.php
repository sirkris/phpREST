<?php

class postid extends posts
{
	public static $info = "Read or write data pertaining to a specific postid.";
	
	/* Get info on specified postid.  --Kris */
	public static function GET()
	{
		
	}
	
	public static function POST()
	{
		return 405;  // Method not allowed.
	}
	
	/* Update the specified post.  --Kris */
	public static function PUT()
	{
		
	}
	
	/* Delete the specified post.  --Kris */
	public static function DELETE()
	{
		
	}
}
