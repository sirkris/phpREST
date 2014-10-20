<?php

require( "handlers.class.php" );

require( "config.class.php" );
require( "uri.class.php" );

$uri = new URI( $_SERVER );

/* No match for URI.  Return 404.  --Kris */
if ( $this->class === FALSE )
{
	// TODO - Send 404 response to client.
	
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
