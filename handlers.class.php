<?php

require_once( "responder.class.php" );

set_exception_handler( array( 'Handlers', 'exception_handler' ) );

class Handlers
{
	public static function exception_handler( Exception $e )
	{
		/* Uncaught exceptions return 500.  --Kris */
		Responder::_5xx( 500, 'A server error occurred : ' . $e->getMessage() );
	}
}
