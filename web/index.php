<?php
define( 'PAGE', 'Home' );
define( 'PAGE_TITLE', 'Home');

require 'inc/header.inc.php';
// requireLogin();
?>
<div class="post_row">
  <div class="title">
    <h1>Welcome to WebForum</h1>
  </div>
  <div class="post_box">
    <?php if ($loggedIn) {
      echo "<div class=\"post_node\">";
      echo "<div class=\"post_title\">Create a new post</div>";
      echo "<div class=\"post_content\"><br><br><br></div>";
      echo "</div>";
    }
    ?>
    <?php
      $stmt = 'select p.title , p."content", u.first_name , u.last_name, p.created_at, p.id  from "Posts" p
                left join "Users" u ON p.user_id = u.id
                where p.visible = true and p.approved = true';
      $posts = pg_query($db_connection, $stmt);

      while ($post_row = pg_fetch_row($posts)) {
        echo "<div class=\"post_node\">";
        echo "<div class=\"post_title\">".$post_row[0]."</div>";
        echo "<div class=\"post_content\">".$post_row[1]."</div>";
        echo "<div class=\"post_footer\">";
        echo "<div class=\"post_author\">".$post_row[2]." ".$post_row[3]."</div>";
        echo "<div class=\"post_date\">".$post_row[4]."</div>";
        echo "</div>";

        // Retrieve comments
        $stmt = 'select u.first_name , u.last_name , c."comment" , c.created_at from "Comments" c
                  left join "Users" u on c.user_id = u.id
                  where c.visible = true and c.approved = true and c.post_id = '.$post_row[5];

        $comments = pg_query($db_connection, $stmt);

        while ($comment_row = pg_fetch_row($comments)) {
          echo "<div class=\"comment_node\">";
          echo "<div class=\"comment_author\">".$comment_row[0]." ".$comment_row[1]."</div>";
          echo "<div class=\"comment_content\">".$comment_row[2]."</div>";
          echo "<div class=\"comment_date\">".$comment_row[3]."</div>";
          echo "</div>";
        }
        echo "</div>";
      }
    ?>
  </div>
</div>

<?php
require 'inc/footer.inc.php'
?>
