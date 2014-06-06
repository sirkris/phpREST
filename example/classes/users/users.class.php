<?php

class users extends REST
{
	public static $info = "Read or write data pertaining to users.";
	
	/* Get all users.  --Kris */
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
		
		try
		{
			$data = $sql->query( "SELECT username FROM users LIMIT ? OFFSET ?", array( $limit, $offset ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		foreach ( $data as &$entry )
		{
			$entry = Dispatch::URI( "username", $entry );  // Translate username into API call for that username.  --Kris
		}
		
		return array( "status" => 200, "data" => $data );
	}
	
	public static function POST()
	{
		return 405;  // Method not allowed.
	}
	
	/* Update all users.  In this example, only a new status value can be set in this manner.  --Kris */
	public static function PUT( $status )
	{
		if ( !is_numeric( $status ) || intval( $status ) != $status || abs( $status ) > 999 )
		{
			return 400;
		}
		
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
			return array( "status" => 200, "data" => array( "AFFECTED_ROWS" => $sql->query( "UPDATE users SET status = ?", array( $status ), SQL_RETURN_AFFECTEDROWS ) ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
	}
	
	// DELETE method not implemented.  --Kris
}
