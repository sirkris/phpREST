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

// TODO - Dispatch the request accordingly.
