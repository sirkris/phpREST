<?php

/* 
 * You can expand this with as many methods as you wish.  Methods used for formatting return data 
 * must:
 * 	1. Be marked as public and static.
 * 	2. Accept 1 array input variable.
 * 	3. Return a string.
 * 
 * These methods must also be listed in the $supported_return_types array in config.class.php in 
 * order to be usable.
 * 
 * Helper functions can be added here, as well, though they should be marked as private.
 * 
 * --Kris
 */
class Format_Return_Data
{
	public static function json( $data )
	{
		return json_encode( $data );
	}
	
	public static function xml( $data )
	{
		$xml = new SimpleXMLElement( "<?xml version=\"1.0\"?><phpREST></phpREST>" );
		
		xml_encode( $data, $xml );
		
		return $xml->asXML();
	}
	
	// This is a helper for the xml method only.
	private static function xml_encode( $arr, &$xml )
	{
		foreach ( $arr as $key => $value )
		{
			if ( is_array( $value ) )
			{
				if ( !is_numeric( $key ) )
				{
					$subnode = $xml->addChild( $key );
				}
				else
				{
					$subnode = xml->addChild( "item" . $key );
				}
				
				xml_encode( $value, $subnode );
			}
			else
			{
				$xml->addChild( $key, htmlspecialchars( $value ) );
			}
		}
	}
	
	/* Generally not recommended as keys are lost.  --Kris */
	public static function plaintext( $data )
	{
		return implode( "\r\n\r\n", $data );
	}
}
