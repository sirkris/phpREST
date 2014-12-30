<?php

require( "handlers.class.php" );

require( "config.class.php" );
require( "uri.class.php" );

require( "responder.class.php" );

$uri = new URI( $_SERVER );

/* No match for URI.  Return 404.  --Kris */
if ( $uri->class === FALSE )
{
	$devuri = Config::$uri_protocols[$uri->protocol] . Config::$base_uri_dev[$uri->protocol];
	Responder::response( 404, "The specified URI does not correspond to a defined API class.  See $devuri for current class/URI pairings." );
	
	die();
}

/* If any of the properties aren't set, it means something went wrong and we need to abort.  --Kris */
$whitelist_vars = array( 'params', 'uri_vars' );
foreach ( get_object_vars( $uri ) as $key => $value )
{
	if ( empty( $value ) && array_search( $key, $whitelist_vars ) === FALSE )
	{
		throw new Exception( "Property '$key' not set for URI instance!  Unable to proceed." );
	}
}

// TODO - Validate $uri data against requirements set in config.dispatch.php.  --Kris

$res = call_user_func_array( array( $uri->class, $uri->method ), $uri->params );

/* If no return type is specified, default to the top one in config.class.php.  --Kris */
if ( empty( $uri->params['phprest_return_type'] ) )
{
	$return_formatter = current( Config::$supported_return_types );
}
else
{
	$return_formatter = Config::$supported_return_types[$uri->params['phprest_return_type']];
}

// API should never return NULL, FALSE, or an empty array/object.  Use a numeric status code instead.
if ( empty( $res ) )
{
	throw new Exception( "API returned empty response!" );
}
// A status code was returned without a body.
else if ( is_numeric( $res ) )
{
	Responder::response( $res );
}
// A body was returned without a status code.  Will assume 200.  Not a recommended practice but is supported.
else if ( !is_array( $res ) )
{
	Responder::response( 200, call_user_func_array( array( 'Format_Return_Data', $return_formatter ), $res ) );
}
// An array was returned.  Both a status code ("status") and a body ("data") are required.
else
{
	if ( !isset( $res['status'] ) || !isset( $res['data'] )
	{
		throw new Exception( "API returned array with missing status and/or data keys!" );
	}
	else
	{
		Responder::response( $res['status'], call_user_func_array( array( 'Format_Return_Data', $return_formatter ), $res['data'] ) );
	}
}
