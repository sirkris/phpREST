<?php

class Config
{
	/*
	 * Edit these values to suit your needs.
	 * 
	 * --Kris
	 */
	
	/*
	 * If a data element starts with an open tag and ends with a close tag, a phpREST client will treat what's 
	 * between them as an API URI to follow for more data.  The entirety can then be replaced by the retrieved data, 
	 * depending on the client configuration.
	 * 
	 * For example, if an API call asks for data on all users but hypertext URIs are provided instead (which is how 
	 * it should happen), a result set of two users would look something like this (default tags used):
	 * 
	 * 0: "([http://api.some-host.com/users/Kris])", 
	 * 1: "([http://api.some-host.com/users/joyjoyboy])"
	 * 
	 * The client may then query one or both URIs and get the data.  If the client is acting as a bridge, then the 
	 * data it returns to the original client would look something like this (assuming JSON is used):
	 * 
	 * 0: { 
	 * 	username: "Kris", 
	 * 	dob: "1982-05-09", 
	 * 	bio: "I talk to my cat.  There's nothing wrong with that, is there?"
	 * }, 
	 * 1: { 
	 * 	username: "joyjoyboy", 
	 * 	dob: "2001-09-11", 
	 * 	bio: "" 
	 * }
	 * 
	 * When a client queries the base API URI, the correct tags for identifying URIs to follow will be included 
	 * in the return data.  It should never be hard-coded!
	 * 
	 * --Kris
	 */
	$uri_tag_open = '([';
	$uri_tag_close = '])';
	
	/*
	 * The base URIs for the API.  Can be HTTP or any other format(s).  The array key can be anything you want; it's 
	 * just a label to tell any human reading this what's what.  It makes no difference as far as the code is concerned.  
	 * The array value should be the URI itself.
	 * 
	 * --Kris
	 */
	$base_uri_api = array( 
				"HTTP"	=> "http://api.some-host.com", 
				"CLI"	=> "cli:/api" 
			);
	
	/* The base URIs for the dev help portal.  Same format as the API base URIs array above.  --Kris */
	$base_uri_dev = array( 
				"HTTP"	=> "http://dev.some-host.com", 
				"CLI"	=> "cli:/dev" 
			);
	
	/* 
	 * Supported response formats.  The "phprest_return_type" named parameter is reserved for determining the formatting 
	 * of the API response data.  For example, if "phprest_return_type=JSON", then the data will be passed to the function 
	 * named by $supported_return_types["JSON"] value.  All these functions should be in the Format_Return_Data class 
	 * in formatters.class.php.  Please refer to the documentation in that file for more information.
	 * 
	 * If no phprest_return_type is supplied or a type is supplied that doesn't match any of the keys in this array, the 
	 * top value will be used as the default.
	 * 
	 * --Kris
	 */
	$supported_return_types = array( 
					"JSON"	=> "json", 
					"XML"	=> "xml", 
					"raw"	=> "plaintext" 
				);
}
