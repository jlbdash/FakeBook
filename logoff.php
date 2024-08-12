<?php
session_start();

// remove all session variables
session_unset();

//destroy the session
session_destroy();

echo "You have been logged out <br>
<h5> Would you like to <a href='LogIn.php'>Log In</a> ? </h5> <br>
<h5> Or would you like to go back to the <a href='index.php'>first page</a> ? </h5>";
?>