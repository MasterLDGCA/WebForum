<?php
// Author: Charith Akalanka
// Description: Logout mechanism

session_start();
unset($_SESSION["username"]);
session_unset();
session_destroy();
header( 'location: /index.php' );
exit();
?>
