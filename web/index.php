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
  $stmt = 'select p.title , p."content", u.first_name , u.last_name, p.created_at, p.id  from "Posts" p
            left join "Users" u ON p.user_id = u.id
            where p.visible = true and p.approved = true';
  $result = pg_query($db_connection, $stmt);

  while ($row = pg_fetch_row($result)) {
    echo "<div class=\"post_node\">";
    echo "<div class=\"post_title\">".$row[0]."</div>";
    echo "<div class=\"post_content\">".$row[1]."</div>";
    echo "<div class=\"post_footer\">";
    echo "<div class=\"post_author\">".$row[2]." ".$row[3]."</div>";
    echo "<div class=\"post_date\">".$row[4]."</div>";
    echo "</div>";
    ;

    // Retrieve comments
    $stmt = 'select u.first_name , u.last_name , c."comment" , c.created_at from "Comments" c
              left join "Users" u on c.user_id = u.id
              where c.visible = true and c.approved = true and c.post_id = '.$row[5];

    $result = pg_query($db_connection, $stmt);

    while ($row = pg_fetch_row($result)) {
      print_r($row);
    }

    echo "</div>";
    echo "<hr>";
  }
?>
