<?php
define( 'PAGE', 'Login' );
define( 'PAGE_TITLE', 'Login');

require 'inc/header.inc.php';

requireLogin();

if ($loggedIn) {
  header( 'location: /index.php' );
  exit();
}

?>
