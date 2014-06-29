<?php

class posts extends REST
{
	public static $info = "Read or write data pertaining to posts.";
	
	/* Get all posts.  --Kris */
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
			$data = $sql->query( "SELECT postid FROM posts LIMIT ? OFFSET ? ORDER BY ts DESC", 
					array( $limit, $offset ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		foreach ( $data as &$entry )
		{
			$entry = Dispatch::URI( "postid", $entry );  // Translate into API call.  --Kris
		}
		
		return array( "status" => 200, "data" => $data );
	}
	
	/* Create a new post.  An ID will be automatically generated and returned.  --Kris */
	public static function POST( $title, $body, $author, $author_ip )
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
		
		// You could add spam checks here.  See if duplicate posts exist, etc.  --Kris
		
		try
		{
			$affrows = $sql->query( "INSERT INTO posts ( title, body, ts, author, author_ip ) VALUES ( ?, ?, ?, ?, ? )", 
					array( $title, $body, time(), $author, $author_ip ), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		// Various notification hooks could go here; emails, etc.  --Kris
		
		try
		{
			$data = $sql->query( "SELECT postid FROM posts WHERE title = ? AND body = ? AND author = ? AND author_ip = ? LIMIT 1 ORDER BY postid DESC", 
					array( $title, $body, $author, $author_ip ) );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		$data["post_uri"] = Dispatch::URI( "postid", $data["postid"] );
		
		return array( "status" => 201, "data" => $data );
	}
	
	public static function PUT()
	{
		return 405;
	}
	
	/* Delete ALL posts.  --Kris */
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
			$affrows = $sql->query( "DELETE FROM posts", array(), SQL_RETURN_AFFECTEDROWS );
		}
		catch ( Exception $e )
		{
			return array( "status" => 500, "error" => "SQL error : " . $e->getMessage() );
		}
		
		return ( $affrows > 0 ? 204 : 404 );
	}
}
