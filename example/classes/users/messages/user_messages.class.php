<?php

class user_messages extends username
{
	public static $info = "Read or write data pertaining to messages received or sent by the specified username.";
	
	/* Get all messages received and sent by this user.  --Kris */
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
	
	/* Delete ALL messages in this user's inbox (received only).  --Kris */
	public static function DELETE()
	{
		
	}
}
