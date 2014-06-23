<?php

class messages extends REST
{
	public static $info = "Read or write data pertaining to all messages between users.";
	
	/* Get all messages.  --Kris */
	public static function GET( $limit = 25, $page = 1, $offset = NULL )
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
			$data = $sql->query( "SELECT messageid FROM messages LIMIT ? OFFSET ?", 
					array( $limit, $offset ) );
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
	
	/* Create a new message.  --Kris */
	public static function POST( $from_username, $to_username, $subject, $body, $from_ip, $replyto_id = NULL )
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
		
		// You could add spam checks here.  See if duplicate messages exist, etc.  --Kris
		
		try
		{
			$affrows = $sql->query( "INSERT INTO messages ( from_username, to_username, subject, body, ts, from_ip, replyto_id ) VALUES ( ?, ?, ?, ?, ?, ?, ? )", 
					array( $from_username, $to_username, $subject, $body, time(), $from_ip, $replyto_id ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		// Various notification hooks could go here; emails, etc.  --Kris
		
		return 201;
	}
	
	public static function PUT()
	{
		return 405;
	}
	
	/* Delete ALL messages!  --Kris */
	public static function DELETE()
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
			$affrows = $sql->query( "DELETE FROM messages", array(), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
}
