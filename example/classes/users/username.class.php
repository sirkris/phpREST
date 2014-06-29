<?php

class username extends users
{
	public static $info = "Read or write data pertaining to a specific username.";
	
	/* Get info on specified username.  --Kris */
	public static function GET( $username, $cols = "username, registered, lastlogin, lastaction, lastip, bio, gender, status, friends, messages" )
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
		
		if ( $cols != "" )
		{
			try
			{
				$data = $sql->query( "SELECT $cols FROM users WHERE username = ?", array( $username ) );
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
			$data["friends"] = Dispatch::URI( "friends", $username );
		}
		
		if ( in_array( $cols_split, "messages" ) )
		{
			$data["messages"] = Dispatch::URI( "user_messages", $username );
		}
		
		return array( "status" => 200, "data" => $data );
	}
	
	/* Create a new user with the specified username, password, and gender.  --Kris */
	public static function POST( $username, $password, $gender )
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
			$num = $sql->query( "SELECT username FROM users WHERE username = ?", array( $username ), SQL_RETURN_NUMROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		if ( $num > 0 )
		{
			return array( "status" => 409, "error" => "Username '$username' already exists!" );
		}
		
		try
		{
			$affrows = $sql->query( "INSERT INTO users ( username, password, registered, gender, status ) VALUES ( ?, ?, ?, ?, ? )", 
					array( $username, hash( "sha512", $password ), time(), $gender, 1 ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return 201;
	}
	
	/* Update the specified user.  --Kris */
	public static function PUT( $username, ...$params )
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
		
		$vals[] = $username;  // For the where clause.  --Kris
		
		try
		{
			$affrows = $sql->query( "UPDATE users SET $vars WHERE username = ?", $vals, SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
	
	/* Delete the specified user.  --Kris */
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
			$affrows = $sql->query( "DELETE FROM users WHERE username = ?", array( $username ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
}
