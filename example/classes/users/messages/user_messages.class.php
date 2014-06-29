<?php

class user_messages extends username
{
	public static $info = "Read or write data pertaining to messages received or sent by the specified username.";
	
	/* Get all messages received and sent by this user.  --Kris */
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
		
		/* You'd probably want to sort this and seperate the from's and the to's.  --Kris */
		try
		{
			$data = $sql->query( "SELECT messageid FROM messages WHERE from_username = ? OR to_username = ? LIMIT ? OFFSET ?", 
					array( $username, $username, $limit, $offset ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		foreach ( $data as &$entry )
		{
			$entry = Dispatch::URI( "messageid", $entry );  // Translate into API call.  --Kris
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
	
	/* Delete ALL messages in this user's inbox (received only).  --Kris */
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
			$affrows = $sql->query( "DELETE FROM messages WHERE to_username = ?", array( $username ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
}
