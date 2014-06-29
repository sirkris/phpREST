<?php

class comments extends postid
{
	public static $info = "Read or write data pertaining to comments on a given postid.";
	
	/* Get all comments for this post.  --Kris */
	public static function GET( $postid, $limit = 25, $page = 1, $offset = NULL )
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
			$data = $sql->query( "SELECT cid FROM comments WHERE replyto_postid = ? LIMIT ? OFFSET ? ORDER BY ts DESC", 
					array( $limit, $offset ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		foreach ( $data as &$entry )
		{
			$entry = Dispatch::URI( "cid", $entry );  // Translate into API call.  --Kris
		}
		
		return array( "status" => 200, "data" => $data );
	}
	
	/* Create a new comment.  An ID will be automatically generated and returned.  --Kris */
	public static function POST( $postid, $body, $author, $author_ip )
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
		
		// You could add spam checks here.  See if duplicate comments exist, etc.  --Kris
		
		try
		{
			$affrows = $sql->query( "INSERT INTO comments ( body, ts, author, author_ip, replyto_postid ) VALUES ( ?, ?, ?, ?, ? )", 
					array( $body, time(), $author, $author_ip, $postid ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		// Various notification hooks could go here; emails, etc.  --Kris
		
		try
		{
			$data = $sql->query( "SELECT cid FROM comments WHERE body = ? AND author = ? AND author_ip = ? AND replyto_postid = ? LIMIT 1 ORDER BY postid DESC", 
					array( $body, $author, $author_ip, $postid ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		$data["comment_uri"] = Dispatch::URI( "cid", $data["cid"] );
		
		return array( "status" => 201, "data" => $data );
	}
	
	public static function PUT()
	{
		return 405;
	}
	
	/* Delete all comments for this post.  --Kris */
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
			$affrows = $sql->query( "DELETE FROM comments WHERE replyto_postid = ?", array( $postid ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
}
