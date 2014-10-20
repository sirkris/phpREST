<?php

class Config
{
	/*
	 * Edit these values to suit your needs.
	 * 
	 * --Kris
	 */
	$include_exception_error_messages = TRUE;  // Should be set to FALSE in production.  --Kris
	
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
	 * The supported protocols and their identifiers.
	 * 
	 * --Kris
	 */
	$uri_protocols = array( 
				"HTTP"	=> "http://", 
				"CLI"	=> "cli:/" 
			);
	
	/*
	 * The base URIs for the API.  Can be HTTP or any other format(s).  The array key can be anything you want; it's 
	 * just a label to tell any human reading this what's what.  It makes no difference as far as the code is concerned.  
	 * The array value should be the URI itself.
	 * 
	 * --Kris
	 */
	$base_uri_api = array( 
				"HTTP"	=> "api.some-host.com", 
				"CLI"	=> "api" 
			);
	
	/* The base URIs for the dev help portal.  Same format as the API base URIs array above.  --Kris */
	$base_uri_dev = array( 
				"HTTP"	=> "dev.some-host.com", 
				"CLI"	=> "dev" 
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
	 * The phprest_return_type can also be supplied in the request header, if applicable.
	 * 
	 * --Kris
	 */
	$supported_return_types = array( 
					"JSON"	=> "json", 
					"XML"	=> "xml", 
					"raw"	=> "plaintext" 
				);
	
	/*
	 * Supported body formats.  If a request body is allowed in lieu of / addition to parameters for a given request, the 
	 * body must be in one of the formats specified here.  For each type listed here, a corresponding method must exist 
	 * in the Parse_Request_Body_Data class in body_formats.class.php.  The parsing method will dispatch to each supported 
	 * method to detect and parse the type in the request.  You can bypass this by specifying the reserved named parameter 
	 * "phprest_request_body_type" or by specifying the request header by the same name.
	 * 
	 * --Kris
	 */
	$supported_request_body_types = array( 
					"JSON", 
					"XML" 
				);
	
	/*
	 * Which input types are available for each REST method by default.  If the same key is specified in multiple input types 
	 * of a request, the one corresponding to the top-most member of this array will take precedence over the others.
	 * 
	 * The valid types are "body" and "params".  If a request includes a type not specified for its method here, it will simply 
	 * be ignored; i.e. no error will be thrown.  This is consistent with the REST spec.  
	 * 
	 * These settings can be overridden in the $class_args definition in config.dispatch.php for any given argument by specifying 
	 * the boolean "AllowBody" or "AllowParam" value.  Note also that the "InURI" value overrides these, as well, causing the 
	 * argument to be accepted only in the URI string itself.
	 * 
	 * --Kris
	 */
	$valid_input_types = array( 
					"GET"		=> array( 
								"params" 
							), 
					"POST"		=> array( 
								"body", 
								"params" 
							), 
					"PUT"		=> array( 
								"body", 
								"params" 
							), 
					"DELETE"	=> array( 
								"body", 
								"params" 
							) 
			);
}
