<?php

class userid extends users
{
	public static $info = "Read or write data pertaining to a specific userid.";
	
	/* Get info on specified userid.  --Kris */
	public static function GET( $userid, $cols = "username, registered, lastlogin, lastaction, lastip, bio, gender, status, friends, messages" )
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
		
		/* Sanitize and restrict database columns that can be accessed.  --Kris */
		$cols_split = preg_replace( '/,[ ]*/', $cols );
		$cols = "";
		$valid = FALSE;
		foreach ( $cols_split as $col )
		{
			switch ( trim( $col ) )
			{
				default:
					// Log an error or whatever, if you like.  --Kris
					break;
				// Valid database columns.
				case "userid":
				case "username":
				case "registered":
				case "lastlogin":
				case "lastaction":
				case "lastip":
				case "bio":
				case "gender":
				case "status":
					$cols .= ( $cols != "" ? ", " : "" ) . $col;
					$valid = TRUE;
					break;
				// Valid non-database parameters.
				case "friends":
				case "messages":
					$valid = TRUE;
					break;
			}
		}
		
		if ( $valid == FALSE )
		{
			return 403;
		}
		
		if ( strpos( $cols, "username" ) === FALSE 
			&& ( in_array( $cols_split, "friends" ) || in_array( $cols_split, "messages" ) ) )
		{
			$cols .= ", username";
		}
		
		if ( $cols != "" )
		{
			try
			{
				$data = $sql->query( "SELECT $cols FROM users WHERE userid = ?", array( $userid ) );
			}
			catch ( Exception $e )
			{
				return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
			}
		}
		else
		{
			$data = array();
		}
		
		if ( in_array( $cols_split, "friends" ) )
		{
			$data["friends"] = Dispatch::URI( "friends", $data["username"] );
		}
		
		if ( in_array( $cols_split, "messages" ) )
		{
			$data["messages"] = Dispatch::URI( "user_messages", $data["username"] );
		}
		
		return array( "status" => 200, "data" => $data );
	}
	
	public static function POST( $userid, $password, $gender )
	{
		return 405;
	}
	
	/* Update the specified user.  --Kris */
	public static function PUT( $userid, ...$params )
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
		
		$vars = NULL;
		$vals = array();
		foreach ( $params as $param )
		{
			$pair = explode( '=', $param );
			if ( count( $pair ) == 2 )
			{
				$vars .= ( $vars != NULL ? ', ' : NULL ) . $pair[0] . ' = ?';
				$vals[] = $pair[1];
			}
		}
		
		$vals[] = $userid;  // For the where clause.  --Kris
		
		try
		{
			$affrows = $sql->query( "UPDATE users SET $vars WHERE userid = ?", $vals, SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
	
	/* Delete the specified user.  --Kris */
	public static function DELETE( $userid )
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
			$affrows = $sql->query( "DELETE FROM users WHERE userid = ?", array( $userid ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
}
