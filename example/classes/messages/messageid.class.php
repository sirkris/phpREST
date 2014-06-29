<?php

class messageid extends messages
{
	public static $info = "Read or write data pertaining to a single message ID.";
	
	/* Get contents of the message.  --Kris */
	public static function GET( $messageid )
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
			$data = $sql->query( "SELECT * FROM messages WHERE messageid = ?", array( $messageid ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		if ( isset( $data["replyto_id"] ) && $data["replyto_id"] != NULL )
		{
			$data["parent"] = Dispatch::URI( "messageid", $data["replyto_id"] );
		}
		
		return array( "status" => 200, "data" => $data );
	}
	
	public static function POST()
	{
		return 405;
	}
	
	/* Update the specified message .  --Kris */
	public static function PUT( $messageid, ...$params )
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
		
		$vals[] = $messageid;  // For the where clause.  --Kris
		
		try
		{
			$affrows = $sql->query( "UPDATE messages SET $vars WHERE messageid = ?", $vals, SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
	
	/* Delete the specified message.  --Kris */
	public static function DELETE( $messageid )
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
			$affrows = $sql->query( "DELETE FROM messages WHERE messageid = ?", array( $messageid ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
}
