<?php

/* 
 * You can expand this with as many methods as you wish.  Methods for parsing input data 
 * from the body must:
 * 
 * 1. Be marked as public and static.
 * 2. Accept 1 string as an input variable.
 * 3. Return an associative array of $key => $value pairs.  If there is no key, use 0-index increment.
 * 
 * These methods must also be listed in the $supported_request_body_types array in config.class.php in 
 * order to be usable.
 * 
 * Helper functions can be added here, as well, though they should be marked as private.
 * 
 * --Kris
 */
class Parse_Request_Body_Data
{
	public static function JSON( $str )
	{
		return json_decode( $str, TRUE );
	}
	
	// TODO - Needs testing.  --Kris
	public static function XML( $str )
	{
		$p = xml_parser_create();
		xml_parse_into_struct( $p, $str, $arr, $indexes );
		xml_parser_free( $p );
		
		$out = array();
		$is = array();
		foreach ( $arr as $tag )
		{
			switch ( $tag['type'] )
			{
				case 'open':
					$is[] = $tag['tag'];
					self::xml_set_val( $is, array(), $out );
					break;
				case 'close':
					/* Should be able to use array_pop(), but let's be more tolerant and go by name, instead.  --Kris */
					if ( ( $k = array_search( $tag['tag'], $is ) ) !== FALSE )
					{
						unset( $is[$k] );  // If you do <tag1><tag2></tag1></tag2>, you're gonna have a bad time.  --Kris
					}
					break;
				case 'complete':
					self::xml_set_val( $is, $tag['value'], $out );
					break;
			}
		}
		
		return $out;
	}
	
	private static function xml_set_val( $keys, $val, &$arr )
	{
		$key = array_shift( $keys );
		
		if ( !empty( $keys ) )
		{
			self::xml_set_val( $keys, $val, $arr[$key] );
		}
		else
		{
			$arr[$key] = $val;
		}
	}
}
