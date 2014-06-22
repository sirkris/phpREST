<?php

class friends extends username
{
	public static $info = "Read or write data pertaining to the specified username's friends list.";
	
	/* Get all the user's friends.  --Kris */
	public static function GET( $username, $limit = 25, $page = 1, $offset = NULL )
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
		
		if ( $offset === NULL )
		{
			$offset = $limit * ($page - 1);
		}
		
		try
		{
			$data = $sql->query( "SELECT username1, username2 FROM friends WHERE username1 = ? OR username2 = ? LIMIT ? OFFSET ?", 
					array( $username, $username, $limit, $offset ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		foreach ( $data as &$entry )
		{
			$entry = Dispatch::URI( "friend_username", $entry[( strcasecmp( $username, $entry["username1"] ) ? "username1" ? "username2" )] );  // Translate into API call.  --Kris
		}
		
		return array( "status" => 200, "data" => $data );
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
	public static function DELETE( $username )
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
			$affrows = $sql->query( "DELETE FROM friends WHERE username1 = ? OR username2 = ?", array( $username, $username ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
}
