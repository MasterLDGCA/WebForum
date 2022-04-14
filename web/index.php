<?php
define( 'PAGE', 'Home' );
define( 'PAGE_TITLE', 'Home');

require 'inc/header.inc.php';
// requireLogin();
?>
<div class="content">
  <div class="title">
    <h1>Welcome to WebForum</h1>
  </div>
  <div class="view">
    <div class="post_box">
      <?php
        $stmt = ' select p.title , p.content , u.first_name , u.last_name, p.created_at , p.id , likes
                  from "Posts" p
                  left join "Users" u ON p.user_id = u.id
                  left join (select pl.post_id , count(pl.user_id) as likes
                  			from "PostLikes" pl
                  			group by pl.post_id ) as likes on likes.post_id = p.id
                  where p.visible = true and p.approved = true';
        $posts = pg_query($db_connection, $stmt);

        while ($post_row = pg_fetch_row($posts)) {
          echo "<div class=\"post_node\">";
          echo "<div class=\"post_title_node\">";
          echo "<div class=\"post_title\">".$post_row[0]."</div>";
          echo "<div class=\"post_tags\">";
          // Retrieve tags
          $stmt = 'select s.title from "PostSubject" ps
                    left join "Subjects" s on ps.subj_id = s.id
                    where post_id ='.$post_row[5];

          $tags = pg_query($db_connection, $stmt);

          while ($tag_title = pg_fetch_row($tags)) {
            echo "<div class=\"tag\">".$tag_title[0]."</div>";
          }

          echo "</div>";
          echo "</div>";
          echo "<div class=\"post_content\">".$post_row[1];
          echo "<div class=\"forum_button\">
                  <button type=\"button\" class=\"post_like_button\">
                    <span class=\"glyphicon glyphicon-thumbs-up\"></span> ";
          echo ($post_row[5]) ? $post_row[5] : "Like";
          echo "</button></div>";
          echo "</div>"; // post_content
          echo "<div class=\"post_footer\">";
          echo "<div class=\"post_author\">".$post_row[2]." ".$post_row[3]."</div>";
          echo "<div class=\"post_date\">(".$post_row[4].")</div>";
          echo "</div>";

          // Retrieve comments
          $stmt = ' select u.first_name , u.last_name , c."comment" , c.created_at , likes
                    from "Comments" c
                    left join "Users" u on c.user_id = u.id
                    left join (select cl.comment_id , count(cl.user_id) as likes
                    			from "CommentLikes" cl
                    			group by cl.comment_id ) as likes on likes.comment_id = c.id
                    where c.visible = true and c.approved = true and c.post_id = '.$post_row[5];

          $comments = pg_query($db_connection, $stmt);

          while ($comment_row = pg_fetch_row($comments)) {
            echo "<div class=\"comment_node\">";
            echo "<div class=\"comment_author\">".$comment_row[0]." ".$comment_row[1]."</div>";
            echo "<div class=\"comment_content\">".$comment_row[2]."</div>";
            echo "<div class=\"comment_date\">".$comment_row[3]."</div>";
            echo "<div class=\"forum_button\">
                     <button type=\"button\" class=\"comment_like_button\">
                        <span class=\"glyphicon glyphicon-thumbs-up\"></span> ";
            echo ($comment_row[4]) ? $comment_row[4] : "Like";
            echo "</button></div>";
            echo "</div>";
          }
          echo "</div>";
        }
      ?>
    </div>
    <div class="leader_box">

    </div>
  </div>
</div>

<?php
require 'inc/footer.inc.php'
?>
