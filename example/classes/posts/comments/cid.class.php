<?php

class cid extends comments
{
	public static $info = "Read or write data pertaining to a specific comment.";
	
	/* Get info on specified cid.  --Kris */
	public static function GET( $cid )
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
			$data = $sql->query( "SELECT * FROM comments WHERE cid = ?", array( $cid ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		if ( isset( $data["author"] ) && $data["author"] != NULL )
		{
			$data["author_data"] = Dispatch::URI( "userid", $data["author"] );
		}
		
		$data["parent"] = Dispatch::URI( "postid", $data["replyto_postid"] );
		
		return array( "status" => 200, "data" => $data );
	}
	
	public static function POST()
	{
		return 405;  // Method not allowed.
	}
	
	/* Update the specified comment.  --Kris */
	public static function PUT( $cid ...$params )
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
		
		$vals[] = $cid;  // For the where clause.  --Kris
		
		try
		{
			$affrows = $sql->query( "UPDATE comments SET $vars WHERE cid = ?", $vals, SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
	
	/* Delete the specified comment.  --Kris */
	public static function DELETE( $cid )
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
			$affrows = $sql->query( "DELETE FROM posts WHERE postid = ?", array( $postid ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
}
