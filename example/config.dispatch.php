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
									
								), 
					"username"		=> array( 
									"GET"
										=> array( 
											"username"
												=> array( 
													"Help"		=> "The username being queried.", 
													"Type"		=> "string", 
													"InURL"		=> TRUE 
												), 
											"cols"
												=> array( 
													"Help" 		=> "The comma-delineated properties to be retrieved from the user.", 
													"Type"		=> "string", 
													"Default" 	=> "username, registered, lastlogin, lastaction, lastip, bio, gender, status, friends, messages" 
												) 
										), 
								), 
					"men"			=> array( 
									
								), 
					"women"			=> array( 
									
								), 
					"friends"		=> array( 
									
								), 
					"friend_username"	=> array( 
									
								), 
					"user_messages"	=> array( 
									
								) 
				);
}
