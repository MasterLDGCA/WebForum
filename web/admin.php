<?php
define( 'PAGE', 'Admin' );
define( 'PAGE_TITLE', 'Admin');

require 'inc/header.inc.php';
requireLogin();

if ( !$isAdmin ) {
  header( 'location: /index.php' );
  exit();
}
?>

<h1>Administrative Controls</h1>

<?php
require 'inc/footer.inc.php'
?>
