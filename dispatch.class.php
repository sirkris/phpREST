<?php

require_once( "config.dispatch.php" );

class Dispatch extends Config_Dispatch
{
	/*
	 * NOTE - The "..." operator for variadic arguments requires PHP 5.6+.  
	 * You can get around this by removing "...$params" from the declaration, 
	 * then adding, "$params = func_get_args();" to the top of this function.  
	 *
	 * See:  http://docs.php.net/manual/en/migration56.new-features.php#migration56.new-features.variadics
	 * 
	 * --Kris
	 */
	public static function URI( $class, ...$params )
	{
		$URI = self::$classes["class"];
		
		/* Replace all <vars> with $params.  Var name is ignored; $params are applied to vars in the order they appear in the URI.  --Kris */
		foreach ( $params as $param )
		{
			$URI = preg_replace( '/<\i\c*\s*>/', $param, $URI, 1 );  // Standard XML tag characters accepted.  Replace only the left-most instance for each parameter.  --Kris
		}
		
		return Config::$uri_tag_open . $URI . Config::$uri_tag_close;
	}
	
	public static function Call( $URI, $method, $vars = "" )
	{
		/* Convert the vars string into a sanitized params array.  --Kris */
		$vars = explode( '&', $vars );
		
		$params = array();
		foreach ( $vars as $var )
		{
			$pair = explode( '=', $var );
			
			$params[$pair[0]] = $pair[1];
		}
		
		/* Retrieve the class name from the URI.  --Kris */
		$URI = rtrim( $URI, '/' );
		$class = FALSE;
		
		// Check for a literal URI match first for better performance.  --Kris
		if ( $key = array_search( $URI, self::$classes ) !== FALSE )
		{
			$class = $key;
		}
		// Check for a non-literal match with variables in the URI.  --Kris
		else
		{
			$matches = NULL;
			foreach ( self::$class as $class => $cURI )
			{
				$cURIr = preg_replace( '/<\i\c*\s*>/', '([^/]+)', $cURI );
				
				if ( preg_match( '~' . $cURIr . '~', $URI, $matches ) )
				{
					// Add any captured matches to the params.  --Kris
					$upkeys = NULL;
					preg_match( '/<(\i\c*\s*)>/', $cURI, $upkeys );
					
					if ( count( $upkeys ) != count( $matches ) )
					{
						return 500;  // This should never happen!  --Kris
					}
					
					for ( $i = 1; $i < count( $matches ); $i++ )
					{
						$params[$upkeys[$i]] = $matches[$i];
					}
					
					break;
				}
			}
			
			if ( $matches == NULL )
			{
				$class = FALSE;
			}
		}
		
		/* Class not found.  --Kris */
		if ( $class == FALSE )
		{
			return 404;
		}
		
		/* Validate and sort the parameters against the dispatch specification.  --Kris */
		// NOTE - URL parameters must also be defined in that array to avoid coupling.  Set "InURI" to TRUE for those.
		if ( $params = self::Validate_Params( $class, $method, $params ) === FALSE )
		{
			return 400;  // Bad request.
		}
		
		// Function arguments need to conform to $class_args, including being in the same order.  --Kris
		return call_user_func_array( array( $class, $method ), $params );
	}
	
	/* Remove superfluous parameters and put in the correct order or return FALSE if required parameters are either not present or out-of-bounds.  --Kris */
	// TODO - If FALSE, also include an error message explaining why (i.e. password too short, etc).
	public static function Validate_Params( $class, $method, $params )
	{
		$params_s = array();
		foreach ( self::$class_args[$class][$method] as $arg => $properties )
		{
			if ( isset( $params[$arg] ) || 
				( ( !isset( $properties["Required"] ) || $properties["Required"] == FALSE ) 
					&& ( !isset( $properties["InURI"] ) || $properties["InURI"] == FALSE ) ) )
			{
				/* If it's not set and there's a default, set to default.  Otherwise, skip to next arg on the list.  --Kris */
				if ( !isset( $params[$arg] ) )
				{
					if ( !isset( $properties["Default"] ) )
					{
						$params[$arg] = $properties["Default"];
					}
					else
					{
						continue;
					}
				}
				
				/* Cycle through the properties and validate accordingly.  --Kris */
				foreach ( $properties as $property => $value )
				{
					switch ( strtolower( $property ) )
					{
						default:
							// TODO - Log an unrecognized property error.
							break;
						case "type":
							switch ( strtolower( $value ) )
							{
								default:
									// TODO - Log an unrecognized Type.
									break;
								case "int":
									if ( !is_numeric( $params[$arg] ) || intval( $params[$arg] ) != $params[$arg] )
									{
										return FALSE;
									}
									
									break;
								case "string":
									// Nothing to be done, is there?  --Kris
									break;
								case "array":
									if ( !is_array( $params[$arg] ) )
									{
										return FALSE;
									}
									
									break;
								case "bool":
									// TODO
									break;
								case "float":
								case "double":
								case "decimal":
									// TODO
									break;
							}
							
							break;
						case "help":
						case "required":
						case "InURI":
							// Do nothing.  --Kris
							break;
						case "default":
							// Already handled earlier.  --Kris
							break;
						case "min":
							if ( !is_numeric( $value ) )
							{
								// TODO - Log an invalid property value error.
							}
							else
							{
								if ( ( strcasecmp( $properties["Type"], "int" ) == 0 
										|| strcasecmp( $properties["Type"], "float" ) == 0 
										|| strcasecmp( $properties["Type"], "double" ) == 0 
										|| strcasecmp( $properties["Type"], "decimal" ) == 0 ) 
									&& is_numeric( $params[$arg] ) 
									&& $params[$arg] < $value )
								{
									return FALSE;
								}
								else if ( strcasecmp( $properties["Type"], "string" ) == 0 
									&& strlen( $params[$arg] ) < $value )
								{
									return FALSE;
								}
								else if ( strcasecmp( $properties["Type"], "array" ) == 0 
									&& is_array( $params[$arg] ) 
									&& count( $params[$arg] ) < $value )
								{
									return FALSE;
								}
							}
							
							break;
						case "max":
							if ( !is_numeric( $value ) )
							{
								// TODO - Log an invalid property value error.
							}
							else
							{
								if ( ( strcasecmp( $properties["Type"], "int" ) == 0 
										|| strcasecmp( $properties["Type"], "float" ) == 0 
										|| strcasecmp( $properties["Type"], "double" ) == 0 
										|| strcasecmp( $properties["Type"], "decimal" ) == 0 ) 
									&& is_numeric( $params[$arg] ) 
									&& $params[$arg] > $value )
								{
									return FALSE;
								}
								else if ( strcasecmp( $properties["Type"], "string" ) == 0 
									&& strlen( $params[$arg] ) > $value )
								{
									return FALSE;
								}
								else if ( strcasecmp( $properties["Type"], "array" ) == 0 
									&& is_array( $params[$arg] ) 
									&& count( $params[$arg] ) > $value )
								{
									return FALSE;
								}
							}
							
							break;
					}
				}
				
				/* Validation passed.  --Kris */
				$params_s[$arg] = $params[$arg];
			}
			else
			{
				return FALSE;
			}
		}
		
		return $params_s;
	}
}
