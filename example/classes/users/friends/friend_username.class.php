<?php

/* Probably be better to store friends by userid in case of changing usernames, but I wanted to demonstrate two usernames in one API call.  --Kris */
class friend_username extends friends
{
	public static $info = "Read or write data pertaining to a specific entry on the user's friends list.";
	
	/* Get info on this friend.  --Kris */
	public static function GET( $username, $friend_username )
	{
		try
		{
			require_once( "SQL/config_sql.class.php" );
			$sql = (new Config_SQL)->load_db();
		}
		catch ( Exception $e )
		{
			return array( "status" => 503, "error" => "Unable to connect to database : " . $e->getMessage() );
		}
		
		try
		{
			$data = $sql->query( "SELECT * FROM friends WHERE ( username1 = ? AND username2 = ? ) OR ( username1 = ? AND username2 = ? )", 
					array( $username, $friend_username, $friend_username, $username ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		foreach ( $data as &$entry )
		{
			$entry["userdata"] = Dispatch::URI( "username", $friend_username );  // Translate into API call.  --Kris
		}
		
		return array( "status" => 200, "data" => $data );
	}
	
	/* Add entry to the user's friends list.  --Kris */
	public static function POST( $username, $friend_username )
	{
		try
		{
			require_once( "SQL/config_sql.class.php" );
			$sql = (new Config_SQL)->load_db();
		}
		catch ( Exception $e )
		{
			return array( "status" => 503, "error" => "Unable to connect to database : " . $e->getMessage() );
		}
		
		try
		{
			$num = $sql->query( "SELECT fid FROM friends WHERE ( username1 = ? AND username2 = ? ) OR ( username1 = ? AND username2 = ? )", 
					array( $username, $friend_username, $friend_username, $username ), SQL_RETURN_NUMROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		if ( $num > 0 )
		{
			return array( "status" => 409, "error" => "Users '$username' and '$friend_username' are already friends!" );
		}
		
		try
		{
			$affrows = $sql->query( "INSERT INTO friends ( username1, username2, since ) VALUES ( ?, ?, ? )", 
					array( $username, $friend_username, time() ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return 201;
	}
	
	/* Update entry on the user's friends list.  In this example, we'll just update the timestamp because we can.  --Kris */
	public static function PUT( $username, $friend_username, $since = NULL )
	{
		if ( $since == NULL )
		{
			$since = time();
		}
		
		try
		{
			$affrows = $sql->query( "UPDATE friends SET since = ? WHERE ( username1 = ? AND username2 = ? ) OR ( username1 = ? AND username2 = ? )", 
					array( $since, $username, $friend_username, $friend_username, $username ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
	
	/* Delete entry from the user's friends list.  --Kris */
	public static function DELETE( $username, $friend_username )
	{
		try
		{
			require_once( "SQL/config_sql.class.php" );
			$sql = (new Config_SQL)->load_db();
		}
		catch ( Exception $e )
		{
			return array( "status" => 503, "error" => "Unable to connect to database : " . $e->getMessage() );
		}
		
		try
		{
			$affrows = $sql->query( "DELETE FROM friends WHERE ( username1 = ? AND username2 = ? ) OR ( username1 = ? AND username2 = ? )", 
					array( $username, $friend_username, $friend_username, $username ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
}
