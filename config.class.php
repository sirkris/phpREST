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
	 * 0: "([http://api.some-host.com/user/Kris])", 
	 * 1: "([http://api.some-host.com/user/joyjoyboy])"
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
	
	
}
