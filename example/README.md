This directory contains a working example of the phpREST API being used against a database for a fictional web forum.  If you're new to phpREST and have installed 
it for the first time, I'd strongly recommend you install this example to verify that phpREST is working on your server correctly.  It'll also give you an 
opportunity to see how it works, which would definitely be beneficial if you're planning on creating your own API using phpREST.

----

Installation of phpREST Example API:

1. Database preparation (MySQL):
	mysql> CREATE DATABASE phpREST_Example;
	mysql> CREATE USER 'phpREST_Example'@'localhost' IDENTIFIED BY 'oSERi08pXsQtmYw5Q5TU';
	mysql> GRANT ALL PRIVILEGES ON phpREST_Example.* TO 'phpREST_Example'@'localhost';
	mysql> FLUSH PRIVILEGES;
	mysql> EXIT;

2. Import the sample database (MySQL):
	> mysql -uphpREST_Example -poSERi08pXsQtmYw5Q5TU -DphpREST_Example <SQL/phpREST_Example.sql

3. Copy the example/classes directory to the main directory, overwriting the existing classes directory.  The only file conflict, REST.class.php, is identical
in both directories, so it doesn't matter whether you overwrite it or not.
	> cd (phpREST Main Dir)/example
	> cp -arf classes ../

4. TODO - Copy dispatch table/etc stuff to top-level dir.

5. TODO - Copy the test client script(s) to some web-accessible location.

6. TODO - Point web browser to client test script(s) and observe results.  Ideally, script should compare returned data/statuses with expected to let the user 
know whether or not there are any errors.

7. When you're done testing, delete everything you copied from the example subdirectory.  The top-level directory should contain (TODO).  The top-level classes 
subdirectory should contain REST.class.php and nothing else.

8. Unless you plan on using it later, it would probably be a good idea to delete the phpREST_Example database you imported.

----

As far as error handling goes, you may wish to come up with something that doesn't require redundant try/catch blocks all over the place.  A custom handler 
might be one option.  Simply having DB functions return FALSE on failure could be another way to go.  So long as your API methods can tell whether a data 
modification/retrieval was successful or not so an appropriate status code can be returned, that's all that really matters.  The rest is up to you.  For 
the sake of this example, I decided to forgo any fancy error handling and just stick with the redundant try/catch blocks.
