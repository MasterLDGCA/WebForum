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
  $stmt = 'select p.title , p."content", u.first_name , u.last_name, p.created_at  from "Posts" p  
            left join "Users" u ON p.user_id = u.id
            where p.visible = true and p.approved = true';
  $result = pg_query($db_connection, $stmt);

  while ($row = pg_fetch_row($result)) {
    print_r($row);
  }
?>
