<?php

require_once( "config.class.php" );

class Responder
{
	private static function send_response( $code, $body, $headers = array() )
	{
		http_response_code( $code );
		
		foreach ( $headers as $header )
		{
			header( $header );
		}
		
		die( "$code $body" );
	}
	
	public static function _generic( $code, $message )
	{
		try
		{
			if ( call_user_func_array( array( self, '_' . $code{0} . 'xx' ), array( $code, $message ) ) === FALSE )
			{
				self::send_response( 500, "No return method defined for code '$code'!" );
			}
		}
		catch ( Exception $e )
		{
			self::send_response( 500, "Responder dispatch error : " . $e->getMessage() );
		}
	}
	
	public static function _4xx( $code, $message )
	{
		self::validate_code_class( 4, $code );
		
		self::send_response( $code, $message );
	}
	
	public static function _5xx( $code, $message, $msg_config_override = FALSE )
	{
		self::validate_code_class( 5, $code );
		
		$message = ( Config::$include_exception_error_messages === TRUE || $msg_config_override === TRUE ? $message : '' );
		
		self::send_response( $code, $message );
	}
	
	private static function validate_code_class( $d, $code )
	{
		if ( !is_numeric( $code ) || $code{0} !== $d )
		{
			self::send_response( 500, "Invalid code '$code' passed to ${d}xx return method!" );
		}
	}
}
