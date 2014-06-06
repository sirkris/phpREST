<?php

/* Probably be better to store friends by userid in case of changing usernames, but I wanted to demonstrate two usernames in one API call.  --Kris */
class friend_username extends friends
{
	public static $info = "Read or write data pertaining to a specific entry on the user's friends list.";
	
	/* Get info on this friend.  --Kris */
	public static function GET()
	{
		
	}
	
	/* Add entry to the user's friends list.  --Kris */
	public static function POST()
	{
		
	}
	
	/* Update entry on the user's friends list.  --Kris */
	public static function PUT()
	{
		
	}
	
	/* Delete entry from the user's friends list.  --Kris */
	public static function DELETE()
	{
		
	}
}
