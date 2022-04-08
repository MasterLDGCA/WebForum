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
<div class="content">
  <h1>Administrative Controls</h1>
</div>

<?php
require 'inc/footer.inc.php'
?>
