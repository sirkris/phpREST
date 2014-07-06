<?php

class Config_Dispatch
{
	public static $classes = array( 
					"messages"		=> "/messages", 
					"messageid"		=> "/messages/<messageid>", 
					"posts"			=> "/posts", 
					"postid"		=> "/posts/<postid>", 
					"comments"		=> "/posts/<postid>/comments", 
					"cid"			=> "/posts/<postid>/comments/<cid>", 
					"users" 		=> "/users", 
					"userid" 		=> "/users/<userid>", 
					"username" 		=> "/users/<username>", 
					"men" 			=> "/users/men", 
					"women" 		=> "/users/women", 
					"friends" 		=> "/users/<username>/friends", 
					"friend_username" 	=> "/users/<username>/friends/<friend_username>", 
					"user_messages"		=> "/users/<username>/messages" 
				);
	
	public static $class_paths = array( 
					"messages"		=> "/messages/messages.class.php", 
					"messageid"		=> "/messages/messageid.class.php", 
					"posts"			=> "/posts/posts.class.php", 
					"postid"		=> "/posts/postid.class.php", 
					"comments"		=> "/posts/comments/comments.class.php", 
					"cid"			=> "/posts/comments/cid.class.php", 
					"users"			=> "/users/users.class.php", 
					"userid"		=> "/users/userid.class.php", 
					"username"		=> "/users/username.class.php", 
					"men"			=> "/users/men.class.php", 
					"women"			=> "/users/women.class.php", 
					"friends"		=> "/users/friends/friends.class.php", 
					"friend_username"	=> "/users/friends/friend_username.class.php", 
					"user_messages"		=> "/users/messages/user_messages.class.php" 
				);
	
	public static $class_manual = array( 
					"messages"		=> array( 
									"GET"		=> "Retrieve all messages.", 
									"POST"		=> "Create a new message.", 
									"DELETE"	=> "Delete all messages." 
								), 
					"messageid"		=> array( 
									"GET"		=> "Retrieve a specific message.", 
									"PUT"		=> "Update a specific message.", 
									"DELETE"	=> "Delete a specific message." 
								), 
					"posts"			=> array( 
									"GET"		=> "Retrieve all posts.", 
									"POST"		=> "Create a new post.", 
									"DELETE"	=> "Delete all posts." 
								), 
					"postid"		=> array( 
									"GET"		=> "Retrieve a specific post.", 
									"PUT"		=> "Update a specific post.", 
									"DELETE"	=> "Delete a specific post." 
								), 
					"comments"		=> array( 
									"GET"		=> "Get all comments for the specified post.", 
									"POST"		=> "Create a new comment under the specified post.", 
									"DELETE"	=> "Delete all comments for the specified post." 
								), 
					"cid"			=> array( 
									"GET"		=> "Retrieve a specific comment for the specified post.", 
									"PUT"		=> "Update a specific comment for the specified post.", 
									"DELETE"	=> "Delete a specific comment for the specified post." 
								), 
					"users"			=> array( 
									"GET"		=> "Retrieve all users.", 
									"PUT"		=> "Update all users." 
								), 
					"userid"		=> array( 
									"GET"		=> "Retrieve a specific user.", 
									"PUT"		=> "Update a specific user.", 
									"DELETE"	=> "Delete a specific user." 
								), 
					"username"		=> array( 
									"GET"		=> "Retrieve a specific user.", 
									"POST"		=> "Create a new user with the specified username.", 
									"PUT"		=> "Update a specific user.", 
									"DELETE"	=> "Delete a specific user." 
								), 
					"men"			=> array( 
									"GET"		=> "Get all male users.", 
									"PUT"		=> "Update all male users.", 
									"DELETE"	=> "Delete all male users." 
								), 
					"women"			=> array( 
									"GET"		=> "Get all female users.", 
									"PUT"		=> "Update all female users.", 
									"DELETE"	=> "Delete all female users." 
								), 
					"friends"		=> array( 
									"GET"		=> "Get all friends of the specified user.", 
									"DELETE"	=> "Remove all entries from the specified user's friends list.  This user will also be removed from all other users' friends lists, as well." 
								), 
					"friend_username"	=> array( 
									"GET"		=> "Get info on a specific entry on the specified user's friends list.", 
									"POST"		=> "Add a specific user to the specified user's friends list.  The other user's friends list will be updated, as well.", 
									"PUT"		=> "Update a specific entry on the specified user's friends list.", 
									"DELETE"	=> "Delete a specific entry from the specified user's friends list.  The corresponding entry will be removed from the other user's friends list, as well." 
								), 
					"user_messages"	=> array( 
									"GET"		=> "Retrieve all messages received and sent by the specified user.", 
									"DELETE"	=> "Delete all messages received (but not sent) by the specified user." 
								) 
				);
	
	public static $class_args = array( 
					"messages"		=> array( 
									
								), 
					"messageid"		=> array( 
									
								), 
					"posts"			=> array( 
									
								), 
					"postid"		=> array( 
									
								), 
					"comments"		=> array( 
									
								), 
					"cid"			=> array( 
									
								), 
					"users"			=> array( 
									"GET"
										=> array( 
											"limit"
												=> array( 
													"Help"		=> "Number of results to be returned.", 
													"Type"		=> "int", 
													"Default"	=> 25, 
													"Min"		=> 1, 
													"Max"		=> 250 
												), 
											"page"
												=> array( 
													"Help"		=> "The page of results to be retrieved.  Offset = limit * (page - 1).  Ignored if offset is supplied.", 
													"Type"		=> "int", 
													"Default"	=> 1, 
												), 
											"offset"
												=> array( 
													"Help"		=> "Overrides page to specify an exact starting record.", 
													"Type"		=> "int", 
													"Min"		=> 1, 
													"Required"	=> FALSE 
												)
										), 
									"PUT"
										=> array( 
											"status"
												=> array( 
													"Help"		=> "An integer corresponding to a numeric status code (i.e. admin, pending, banned, etc).", 
													"Type"		=> "int", 
													"Min"		=> -999, 
													"Max"		=> 999 
												)
										) 
								), 
					"userid"		=> array( 
									"GET"
										=> array( 
											"userid"
												=> array( 
													"Help"		=> "The userid being queried.", 
													"Type"		=> "int", 
													// Specified in the URI instead of in the parameters.  --Kris
													"InURI"		=> TRUE 
												), 
											"cols"
												=> array( 
													"Help" 		=> "The comma-delineated properties to be retrieved from the user.", 
													"Type"		=> "string", 
													"Default" 	=> "username, registered, lastlogin, lastaction, lastip, bio, gender, status, friends, messages" 
												) 
										), 
									"PUT"
										=> array( 
											"userid"
												=> array( 
													"Help"		=> "The userid being updated.", 
													"Type"		=> "int", 
													"InURI"		=> TRUE 
												), 
											// 0 or more unnamed parameters following these rules (denoted by the "...").  --Kris
											"...cols"
												=> array( 
													"Help"		=> "The columns to update with specified values.", 
													"Type"		=> "string", 
													// A regex representation of the required format for this string.  --Kris
													"Format"	=> '/.+=.+/', 
													// Specify the "Min_Params" key to dictate a minimum number.  Same goes for "Max_Params".  --Kris
													"Min_Params"	=> 1 
												) 
										), 
									"DELETE"
										=> array( 
											"userid"
												=> array( 
													"Help"		=> "The userid being deleted.", 
													"Type"		=> "int", 
													"InURI"		=> TRUE 
												) 
										)
								), 
					"username"		=> array( 
									"GET"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The username being queried.", 
													"Type"		=> "string", 
													// Specified in the URI instead of in the parameters.  --Kris
													"InURI"		=> TRUE 
												), 
											"cols"
												=> array( 
													"Help" 		=> "The comma-delineated properties to be retrieved from the user.", 
													"Type"		=> "string", 
													"Default" 	=> "username, registered, lastlogin, lastaction, lastip, bio, gender, status, friends, messages" 
												) 
										), 
									"POST"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The username being created.", 
													"Type"		=> "string", 
													// The following values aren't allowed, regardless of case.  --Kris
													"Disallowed_Case_Insensitive"
															=> array( 
																"admin", 
																"administrator", 
																"moderator", 
																"sysop", 
																"sysadmin", 
																"root", 
																"webmaster" 
															), 
													// The following values aren't allowed if the case matches.  --Kris
													"Disallowed"	=> array( 
																"TESTING", 
																"sPaMb0t", 
																"SpAmB0T" 
																// ....But "spamb0t" and "Spamb0t" are ok.  --Kris
															), 
													"InURI"		=> TRUE 
												), 
											"gender"
												=> array( 
													// I realize there are more gender identities than "man" and "woman".  But this is just an 
													// example and I'd like to keep it as simple as possible for the sake of laziness.  Please 
													// don't consider this a slight against trans and people with other gender identities.  --Kris
													"Help"		=> "The gender of the user being created.", 
													"Type"		=> "string", 
													// Value must match one of these entries or it will be rejected.  --Kris
													"Allowed"	=> array( 
																"values" => array( 
																		"man", 
																		"woman" 
																	), 
																"case_sensitive" => FALSE 
															) 
												), 
											"password"
												=> array( 
													"Help"		=> "The unencrypted password for the new user.  Leave blank to lock the account until the user activates it and creates their own password.", 
													"Type"		=> "string", 
													"Min"		=> 6, 
													"Default"	=> NULL 
												) 
										), 
									"PUT"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The username being updated.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												), 
											// 0 or more unnamed parameters following these rules (denoted by the "...").  --Kris
											"...cols"
												=> array( 
													"Help"		=> "The columns to update with specified values.", 
													"Type"		=> "string", 
													// A regex representation of the required format for this string.  --Kris
													"Format"	=> '/.+=.+/', 
													// Specify the "Min_Params" key to dictate a minimum number.  Same goes for "Max_Params".  --Kris
													"Min_Params"	=> 1 
												) 
										), 
									"DELETE"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The username being deleted.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												) 
										)
								), 
					"men"			=> array( 
									"GET"
										=> array( 
											"limit"
												=> array( 
													"Help"		=> "Number of results to be returned.", 
													"Type"		=> "int", 
													"Default"	=> 25, 
													"Min"		=> 1, 
													"Max"		=> 250 
												), 
											"page"
												=> array( 
													"Help"		=> "The page of results to be retrieved.  Offset = limit * (page - 1).  Ignored if offset is supplied.", 
													"Type"		=> "int", 
													"Default"	=> 1, 
												), 
											"offset"
												=> array( 
													"Help"		=> "Overrides page to specify an exact starting record.", 
													"Type"		=> "int", 
													"Min"		=> 1, 
													"Required"	=> FALSE 
												)
										), 
									"PUT"
										=> array( 
											"status"
												=> array( 
													"Help"		=> "A numeric status code.  I.e. 0 = pending, 1 = active, 2 = admin, 3 = banned, etc.", 
													"Type"		=> "int" 
												) 
										), 
									"DELETE"
										=> array( 
											// No args.  --Kris
										)
								), 
					"women"			=> array( 
									"GET"
										=> array( 
											"limit"
												=> array( 
													"Help"		=> "Number of results to be returned.", 
													"Type"		=> "int", 
													"Default"	=> 25, 
													"Min"		=> 1, 
													"Max"		=> 250 
												), 
											"page"
												=> array( 
													"Help"		=> "The page of results to be retrieved.  Offset = limit * (page - 1).  Ignored if offset is supplied.", 
													"Type"		=> "int", 
													"Default"	=> 1, 
												), 
											"offset"
												=> array( 
													"Help"		=> "Overrides page to specify an exact starting record.", 
													"Type"		=> "int", 
													"Min"		=> 1, 
													"Required"	=> FALSE 
												)
										), 
									"PUT"
										=> array( 
											"status"
												=> array( 
													"Help"		=> "A numeric status code.  I.e. 0 = pending, 1 = active, 2 = admin, 3 = banned, etc.", 
													"Type"		=> "int" 
												) 
										), 
									"DELETE"
										=> array( 
											// No args.  --Kris
										)
								), 
					"friends"		=> array( 
									"GET"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The username whose friends list is being queried.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												), 
											"limit"
												=> array( 
													"Help"		=> "Number of results to be returned.", 
													"Type"		=> "int", 
													"Default"	=> 25, 
													"Min"		=> 1, 
													"Max"		=> 250 
												), 
											"page"
												=> array( 
													"Help"		=> "The page of results to be retrieved.  Offset = limit * (page - 1).  Ignored if offset is supplied.", 
													"Type"		=> "int", 
													"Default"	=> 1, 
												), 
											"offset"
												=> array( 
													"Help"		=> "Overrides page to specify an exact starting record.", 
													"Type"		=> "int", 
													"Min"		=> 1, 
													"Required"	=> FALSE 
												)
										), 
									"DELETE"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The username whose friends list is being deleted.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												)
										)
								), 
					"friend_username"	=> array( 
									"GET"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The username whose friend list entry is being queried.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												), 
											"friend_username"
												=> array( 
													"Help"		=> "The username of the friend list entry being queried.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												) 
										), 
									"POST"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The first of two usernames to be made friends (order doesn't matter).", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												), 
											"friend_username"
												=> array( 
													"Help"		=> "The second of two usernames to be made friends (order doesn't matter).", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												) 
										), 
									"PUT"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The first of two usernames in the friendship entry (order doesn't matter) being updated.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												), 
											"friend_username"
												=> array( 
													"Help"		=> "The second of two usernames in the friendship entry (order doesn't matter) being updated.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												), 
											"since"
												=> array( 
													"Help"		=> "A Unix timestamp denoting when they became friends.  This is the only field that can be updated because I said so.", 
													"Type"		=> "int" 
												) 
										), 
									"DELETE"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The first of two usernames in the friendship entry (order doesn't matter) being deleted.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												), 
											"friend_username"
												=> array( 
													"Help"		=> "The first of two usernames in the friendship entry (order doesn't matter) being deleted.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												) 
										) 
								), 
					"user_messages" 	=> array( 
									"GET"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The username whose messages are being queried.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												), 
											"limit"
												=> array( 
													"Help"		=> "Number of results to be returned.", 
													"Type"		=> "int", 
													"Default"	=> 25, 
													"Min"		=> 1, 
													"Max"		=> 250 
												), 
											"page"
												=> array( 
													"Help"		=> "The page of results to be retrieved.  Offset = limit * (page - 1).  Ignored if offset is supplied.", 
													"Type"		=> "int", 
													"Default"	=> 1, 
												), 
											"offset"
												=> array( 
													"Help"		=> "Overrides page to specify an exact starting record.", 
													"Type"		=> "int", 
													"Min"		=> 1, 
													"Required"	=> FALSE 
												)
										), 
									"DELETE"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The username whose (incoming) messages are being deleted.", 
													"Type"		=> "string", 
													"InURI"		=> TRUE 
												)
										)
								) 
				);
}
