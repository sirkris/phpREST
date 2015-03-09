<?php

require( 'handlers.class.php' );

require( 'config.class.php' );
require( 'uri.class.php' );

require( 'responder.class.php' );

$uri = new URI( $_SERVER );

/* No match for URI.  Return 404.  --Kris */
if ( $uri->class === FALSE )
{
	$devuri = Config::$uri_protocols[$uri->protocol] . Config::$base_uri_dev[$uri->protocol];
	Responder::response( 404, 'The specified URI does not correspond to a defined API class.  See $devuri for current class/URI pairings.' );
	
	die();
}

/* If any of the properties aren't set, it means something went wrong and we need to abort.  --Kris */
$whitelist_vars = array( 'params', 'uri_vars' );
foreach ( get_object_vars( $uri ) as $key => $value )
{
	if ( empty( $value ) && array_search( $key, $whitelist_vars ) === FALSE )
	{
		throw new Exception( 'Property "' . $key . '" not set for URI instance!  Unable to proceed.' );
	}
}

/* Validate the $uri data against config.dispatch.php.  --Kris */
$err = array();
$params_sequential = array();
foreach ( Config_Dispatch::$class_args[$uri->class][$uri->method] as $arg => $rules )
{
	/* Load the appropriate parameters from the URI and/or body.  --Kris */
	$params = array();
	foreach ( array_reverse( Config::$valid_input_types[$uri->method] ) as $type )
	{
		/* FALSE overrides.  --Kris */
		if ( ( $type === 'params' && ( empty( $rules['AllowParam'] ) || $rules['AllowParam'] !== FALSE ) ) 
			|| ( $type === 'body' && ( empty( $rules['AllowBody'] ) || $rules['AllowBody'] !== FALSE ) ) )
		{
			continue;
		}
		
		switch ( $type )
		{
			case 'params':
				$params = array_replace( $params, $uri->params );
				break;
			case 'body':
				$params = array_replace( $params, $uri->body_params );
				break;
		}
	}
	
	/* TRUE overrides.  --Kris */
	if ( !empty( $rules['AllowParam'] ) && $rules['AllowParam'] === TRUE )
	{
		$params = array_replace( $params, $uri->params );
	}
	
	if ( !empty( $rules['AllowBody'] ) && $rules['AllowBody'] === TRUE )
	{
		$params = array_replace( $params, $uri->body_params );
	}
	
	/* If InURI, value is accepted ONLY from the URI string, itself.  --Kris */
	if ( !empty( $rules['InURI'] ) && $rules['InURI'] === TRUE )
	{
		if ( isset( $params[$arg] ) )
		{
			unset( $params[$arg] );
		}
		
		if ( !isset( $uri->uri_vars_assoc[$arg] ) )
		{
			$err[] = 'Missing URI piece : ' . $arg;
			
			continue;
		}
		else
		{
			$params[$arg] = $uri->uri_vars_assoc[$arg];
		}
	}
	
	/* Locate the matching parameter, if present.  If it's required, throw an error.  Note that including a Default value overrides the required flag.  --Kris */
	if ( !isset( $params[$arg] ) && ( empty( $rules['Required'] ) || $rules['Required'] === TRUE ) && empty( $rules['Default'] ) )  // Required defaults to TRUE unless Default is set.
	{
		$err[] = 'Missing required argument : ' . $arg;
		
		continue;
	}
	else if ( !isset( $params[$arg] ) )
	{
		/* Missing parameter is not required.  If there's a default, set it to that and skip validation.  Otherwise, leave it unset.  --Kris */
		if ( !empty( $rules['Default'] ) )
		{
			$params[$arg] = $rules['Default'];
		}
		
		continue;
	}
	
	$param = &$params[$arg];
	
	/* Log a warning if an arg doesn't have a Help rule associated with it.  --Kris */
	if ( empty( $rules['Help'] ) )
	{
		// TODO
	}
	
	/* Rules validation (see config.dispatch.php).  If any rule is violated, add an error and return 400.  --Kris */
	foreach ( $rules as $attribute => $rule )
	{
		/* Some attributes are handled elsewhere; for example, AllowParam and AllowBody are used above.  --Kris */
		switch ( strtolower( $attribute ) )
		{
			// Setting type to 'mixed' means any type will be accepted; same effect as not specifying a type at all.  --Kris
			case 'type':
				$mismatch = FALSE;
				switch ( strtolower( $rule ) )
				{
					case 'string':
					case 'str':
						if ( is_array( $param ) || is_object( $param ) )
						{
							$mismatch = TRUE;
						}
						break;
					case 'int':
					case 'integer':
					case 'intval':
					case 'long':
					case 'longint':
					case 'long int':
					case 'long integer':
						if ( !is_numeric( $param ) || (int) $param != $param )
						{
							$mismatch = TRUE;
						}
						else
						{
							$param = (int) $param;
						}
						break;
					case 'float':
					case 'double':
					case 'long float':
					case 'long double':
					case 'number':
					case 'numeric':
					case 'num':
						if ( !is_numeric( $param ) )
						{
							$mismatch = TRUE;
						}
						else
						{
							$param = (double) $param;
						}
						break;
					case 'array':
					case 'arr':
						if ( !is_array( $param ) )
						{
							$mismatch = TRUE;
						}
						break;
					case 'object':
					case 'obj':
						if ( !is_object( $param ) )
						{
							$mismatch = TRUE;
						}
						break;
					case 'json':
						if ( json_decode( $param, TRUE ) === NULL )
						{
							$mismatch = TRUE;
						}
						break;
				}
				
				if ( $mismatch === TRUE )
				{
					$err[] = 'Type mismatch for ' . $arg . ' : Expected ' . $rule;
				}
				
				break;
			case 'min':
				if ( !is_numeric( $rule ) || (int) $rule != $rule || $rule < 0 )
				{
					throw new Exception( 'Min rule must be a positive number!' );
				}
				
				// If no Type is specified or is not recognized, assume string.  --Kris
				$type = ( !empty( $rules['Type'] ) ? $rules['Type'] : 'string' );
				
				$mismatch = FALSE;
				switch ( strtolower( $type ) )
				{
					default:
					case 'string':
					case 'str':
						if ( strlen( $param ) < $rule )
						{
							$mismatch = TRUE;
						}
						break;
					case 'int':
					case 'integer':
					case 'intval':
					case 'long':
					case 'longint':
					case 'long int':
					case 'long integer':
						if ( $param < $rule )
						{
							$mismatch = TRUE;
						}
						break;
					case 'float':
					case 'double':
					case 'long float':
					case 'long double':
					case 'number':
					case 'numeric':
					case 'num':
						if ( $param < $rule )
						{
							$mismatch = TRUE;
						}
						break;
					case 'array':
					case 'arr':
						if ( count( param ) < $rule )
						{
							$mismatch = TRUE;
						}
						break;
					case 'object':
					case 'obj':
						// Note - Only works with countable objects.  --Kris
						if ( count( param ) < $rule )
						{
							$mismatch = TRUE;
						}
						break;
					case 'json':
						if ( count( json_decode( $param, TRUE ) ) < $rule )
						{
							$mismatch = TRUE;
						}
						break;
				}
				
				if ( $mismatch === TRUE )
				{
					$err[] = 'Argument length out of range for ' . $arg . ' : Minimum = ' . $rule;
				}
				
				break;
			case 'max':
				if ( !is_numeric( $rule ) || (int) $rule != $rule || $rule < 0 )
				{
					throw new Exception( 'Max rule must be a positive number!' );
				}
				
				// If no Type is specified or is not recognized, assume string.  --Kris
				$type = ( !empty( $rules['Type'] ) ? $rules['Type'] : 'string' );
				
				$mismatch = FALSE;
				switch ( strtolower( $type ) )
				{
					default:
					case 'string':
					case 'str':
						if ( strlen( $param ) > $rule )
						{
							$mismatch = TRUE;
						}
						break;
					case 'int':
					case 'integer':
					case 'intval':
					case 'long':
					case 'longint':
					case 'long int':
					case 'long integer':
						if ( $param > $rule )
						{
							$mismatch = TRUE;
						}
						break;
					case 'float':
					case 'double':
					case 'long float':
					case 'long double':
					case 'number':
					case 'numeric':
					case 'num':
						if ( $param > $rule )
						{
							$mismatch = TRUE;
						}
						break;
					case 'array':
					case 'arr':
						if ( count( param ) > $rule )
						{
							$mismatch = TRUE;
						}
						break;
					case 'object':
					case 'obj':
						// Note - Only works with countable objects.  --Kris
						if ( count( param ) > $rule )
						{
							$mismatch = TRUE;
						}
						break;
					case 'json':
						if ( count( json_decode( $param, TRUE ) ) > $rule )
						{
							$mismatch = TRUE;
						}
						break;
				}
				
				if ( $mismatch === TRUE )
				{
					$err[] = 'Argument length out of range for ' . $arg . ' : Maximum = ' . $rule;
				}
				
				break;
			case 'disallowed':
				if ( !is_array( $rule ) )
				{
					throw new Exception( 'Disallowed rule must be an array!' );
				}
				
				if ( is_array( $param ) )
				{
					// TODO
				}
				else if ( is_object( $param ) )
				{
					if ( strtolower( $rules['Type'] ) === 'object' || strtolower( $rules['Type'] ) === 'obj' )
					{
						throw new Exception( 'Object type not compatible with Disallowed attribute!' );
					}
					else
					{
						$err[] = 'Illegal use of object with Disallowed parameter';
					}
				}
				else
				{
					if ( in_array( $param, $rule ) )
					{
						$err[] = 'Value "' . $param . '" is not permitted';
					}
				}
				
				break;
			case 'disallowed_case_insensitive':
				if ( !is_array( $rule ) )
				{
					throw new Exception( 'Disallowed_Case_Insensitive rule must be an array!' );
				}
				
				if ( is_array( $param ) )
				{
					// TODO
				}
				else if ( is_object( $param ) )
				{
					if ( strtolower( $rules['Type'] ) === 'object' || strtolower( $rules['Type'] ) === 'obj' )
					{
						throw new Exception( 'Object type not compatible with Disallowed_Case_Insensitive attribute!' );
					}
					else
					{
						$err[] = 'Illegal use of object with Disallowed_Case_Insensitive parameter';
					}
				}
				else
				{
					if ( in_array( strtolower( $param ), array_map( 'strtolower', $rule ) ) )
					{
						$err[] = 'Value "' . $param . '" is not permitted';
					}
				}
				
				break;
			case '...params':
				// TODO - Should be an array of params?  Variadic shit.  I think Format determines it, actually.
			case 'inurl':
				throw new Exception( 'Unsupported rule "InURL"!  Did you mean "InURI"?' );
				break;
		}
	}
	
	$params_sequential[] = $param;
}

if ( !empty( $err ) )
{
	Responder::response( 400, implode( $err, '; ' ) );  // If there are multiple errors, display them all.  --Kris
}

$res = call_user_func_array( array( $uri->class, $uri->method ), $params_sequential );

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
	throw new Exception( 'API returned empty response!' );
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
// An array was returned.  Both a status code ('status') and a body ('data') are required.
else
{
	if ( !isset( $res['status'] ) || !isset( $res['data'] )
	{
		throw new Exception( 'API returned array with missing status and/or data keys!' );
	}
	else
	{
		Responder::response( $res['status'], call_user_func_array( array( 'Format_Return_Data', $return_formatter ), $res['data'] ) );
	}
}
