<?php

class postid extends posts
{
	public static $info = "Read or write data pertaining to a specific postid.";
	
	/* Get info on specified postid.  --Kris */
	public static function GET( $postid )
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
			$data = $sql->query( "SELECT * FROM posts WHERE postid = ?", array( $postid ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		if ( isset( $data["author"] ) && $data["author"] != NULL )
		{
			$data["author_data"] = Dispatch::URI( "userid", $data["author"] );
		}
		
		return array( "status" => 200, "data" => $data );
	}
	
	public static function POST()
	{
		return 405;  // Method not allowed.
	}
	
	/* Update the specified post.  --Kris */
	public static function PUT( $postid, ...$params )
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
		
		$vals[] = $postid;  // For the where clause.  --Kris
		
		try
		{
			$affrows = $sql->query( "UPDATE posts SET $vars WHERE postid = ?", $vals, SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
	
	/* Delete the specified post.  --Kris */
	public static function DELETE( $postid )
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
