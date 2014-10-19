<?php

class URI
{
	public $server;
	public $uri;
	public $protocol;
	public $uri_pieces;
	public $method;
	public $params;
	public $body;
	public $class;
	public $uri_vars;
	
	public function __construct( $server = NULL )
	{
		if ( $server === NULL )
		{
			$server = $_SERVER;
		}
		
		$this->server = $server;
		$this->get_protocol();
		$this->get_uri();
		$this->get_method();
		$this->get_params();
		$this->get_body();
		$this->get_class_and_vars();
	}
	
	private function get_uri()
	{
		switch ( $this->protocol )
		{
			// Syntax for CLI is:  php index.php <method> cli:/api-uri <params>
			case "CLI":
				$this->uri = $argv[2];
				break;
			case "HTTP":
				$this->uri = $this->server["REQUEST_URI"];
				break;
		}
	}
	
	private function get_protocol()
	{
		switch ( php_sapi_name() )
		{
			default:
				// If the SAPI name won't tell us, we'll just go by the protocol specified by the user.  --Kris
				require_once( "config.class.php" );
				foreach ( Config::$uri_protocols as $protocol => $identifier )
				{
					// TODO - Should the identifier be case sensitive?  Need to review REST spec on that.  --Kris
					if ( strpos( $this->uri, $identifier ) === 0 )
					{
						$this->protocol = $protocol;
						break;
					}
				}
				
				if ( !isset( $this->protocol ) )
				{
					throw new Exception( "Unable to set protocol!  Unknown SAPI name '" . php_sapi_name() . "' and no protocol identifier match in URI!" );
				}
				
				break;
			case "cli":
				$this->protocol = "CLI";
				break;
			case "apache2handler":
			case "apache":
			case "apache2filter":
			case "cgi":
			case "cgi-fcgi":
			case "cli-server":
				$this->protocol = "HTTP";
				break;
		}
	}
	
	private function get_method()
	{
		$this->method = ( isset( $this->server["REQUEST_METHOD"] ) ? $this->server["REQUEST_METHOD"] : $argv[1] );
	}
	
	private function get_params()
	{
		if ( $this->protocol === "CLI" )
		{
			parse_str( $argv[3], $this->params );
			break;
		}
		else
		{
			switch ( $this->method )
			{
				case "GET":
					$this->params = $_GET;
					break;
				case "POST":
					$this->params = $_POST;
					break;
				case "PUT":
					parse_str( file_get_contents( "php://input", $this->params );
					break;
				case "DELETE":
					parse_str( file_get_contents( "php://input", $this->params );
					break;
			}
		}
	}
	
	private function get_uri_pieces()
	{
		require_once( "config.class.php" );
		
		$path = str_replace( Config::$uri_protocols[$this->protocol], "", $this->uri, 1 );
		
		$this->uri_pieces = explode( "/", $path );
	}
	
	private function get_body()
	{
		$this->body = stream_get_contents( STDIN );
	}
	
	private function get_class_and_vars()
	{
		require_once( "config.dispatch.php" );
		
		$muri = FALSE;
		$uri_vars = array();
		$class = FALSE;
		foreach ( Config_Dispatch::$classes as $class => $curi )
		{
			$uri_pieces = explode( "/", $curi );
			
			if ( count( $uri_pieces ) !== count( $this->uri_pieces ) )
			{
				continue;
			}
			else
			{
				$match = TRUE;
				foreach ( $uri_pieces as $key => $piece )
				{
					if ( $piece !== $this->uri_pieces[$key] 
						&& $piece{0} !== '<' 
						&& $piece{strlen( $piece ) - 1} !== '>' )
					{
						$match = FALSE;
						break;
					}
				}
				
				if ( $match == FALSE )
				{
					continue;
				}
			}
			
			$muri = $curi;
			while ( ( $res = preg_match( '/<\w+>/', $curi, $m, PREG_OFFSET_CAPTURE ) ) === 1 )
			{
				$key = array_search( $m[0][0], $uri_pieces );
				
				if ( $key !== FALSE )
				{
					$muri = preg_replace( '/<\w+>/', $this->uri_pieces[$key], $muri, 1 );
					$uri_vars[] = $this->uri_pieces[$key];
				}
					
				$curi = substr( $URI, strpos( $URI, '>', $m[0][1] ) + 1 );
			}
			
			if ( $muri !== $URI )
			{
				break;
			}
		}
		
		$this->class = $class;
		$this->uri_vars = $uri_vars;
	}
}
