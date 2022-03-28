<?php
define( 'PAGE', 'Home' );
define( 'PAGE_TITLE', 'Home');

require 'inc/header.inc.php';
require 'inc/postgresql.inc.php';
?>

<div class="title">
  <h1>Welcome to WebForum</h1>
</div>

<?php
  $stmt = "SELECT * FROM \"Users\"";
  $result = pg_query($db_connection, $stmt);

  while ($row = pg_fetch_row($result)) {
    print_r($row);
  }
?>

<script>
    console.log(<?= json_encode($result); ?>);
</script>
