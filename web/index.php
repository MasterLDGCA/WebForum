<?php
define( 'PAGE', 'Home' );
define( 'PAGE_TITLE', 'Home');

require 'inc/header.inc.php';
require 'inc/functions.inc.php';
// requireLogin();

//Author: Ully Martins
//Description: Function for create post
//Date created: 29/04/2022
//Date modified:
  if (!empty($_POST["title"]) && !empty($_POST["content"]) && !$errors) {
    // Add user to the database
    create_post($db_connection, $_POST["title"], $_POST["content"], $userID);
  }


if (!empty($_POST["title"]) && !empty($_POST["content"]) && !$errors) {
    // Add user to the database
    create_comment($db_connection, $_POST["post_id"], $_POST["comment"], $userID);
 }

if (!empty($_POST["post_like"])) {
  requireLogin();
  post_like_clicked($db_connection, $_POST["post_like"], $userID);
}

if (!empty($_POST["comment_like"])) {
  requireLogin();
  comment_like_clicked($db_connection, $_POST["comment_like"], $userID);
}
?>
<div class="content">
  <div class="view">
    <div class="post_box">
      <?php
        $stmt = ' select p.title , p.content , u.first_name , u.last_name, p.created_at , p.id , likes
                  from "Posts" p
                  left join "Users" u ON p.user_id = u.id
                  left join (select pl.post_id , count(pl.user_id) as likes
                  			from "PostLikes" pl
                  			group by pl.post_id ) as likes on likes.post_id = p.id
                  where p.visible = true and p.approved = true
                  order by p.created_at desc';
        $posts = pg_query($db_connection, $stmt);

        while ($post_row = pg_fetch_row($posts)) : ?>
          <div class="post_node">
          <div class="post_title_node">
          <img class="post_img" src="images/die.png" alt=""><div class="post_title"><?php echo $post_row[0]; ?></div>
          <div class="post_tags">
          <?php
          // Retrieve tags
          $stmt = 'select s.title from "PostSubject" ps
                    left join "Subjects" s on ps.subj_id = s.id
                    where post_id ='.$post_row[5];

          $tags = pg_query($db_connection, $stmt);

          while ($tag_title = pg_fetch_row($tags)) {
            echo "<div class=\"tag\">".$tag_title[0]."</div>";
          }

          // Has the user liked this post?
          $post_liked = false;
          if ($loggedIn) {
            $stmt ='select *
                    from "PostLikes" pl
                    where pl.user_id ='.$_SESSION['user_id'].' and pl.post_id ='.$post_row[5];

            $check_liked = pg_query($db_connection, $stmt);
            $post_liked = pg_num_rows($check_liked);
          } ?>

          </div>
          </div>
          <div class="post_content"><?php echo $post_row[1]; ?>
          <div class="forum_button">
            <form method="POST" action="index.php">
              <input type="hidden" name="post_like" value="<?php echo $post_row[5];?>">
              <button type="submit" class="like_button" <?php echo ($post_liked) ? " clicked" : ""; ?>><span class="glyphicon glyphicon-thumbs-up"></span><?php echo ($post_row[6]) ? $post_row[6] : "Like";?></button>
            </form>
          </div>
          </div>
          <div class="post_footer">
            <div class="post_author"><?php echo $post_row[2]." ".$post_row[3]; ?></div>
            <div class="post_date">(<?php echo date('Y-m-d H:i:s',strtotime($post_row[4])) ?>)</div>
          </div>
          <?php
          // Retrieve comments
          $stmt = ' select u.first_name , u.last_name , c."comment" , c.created_at , likes , c.id
                    from "Comments" c
                    left join "Users" u on c.user_id = u.id
                    left join (select cl.comment_id , count(cl.user_id) as likes
                    			from "CommentLikes" cl
                    			group by cl.comment_id ) as likes on likes.comment_id = c.id
                    where c.visible = true and c.approved = true and c.post_id = '.$post_row[5].'order by c.created_at';
          $comments = pg_query($db_connection, $stmt);
          ?>

          <?php while ($comment_row = pg_fetch_row($comments)) : ?>
            <?php
              // Has the user liked this comment?
              $comment_liked = false;
              if ($loggedIn) {
                $stmt ='select *
                        from "CommentLikes" cl
                        where cl.user_id ='.$_SESSION['user_id'].' and cl.comment_id ='.$comment_row[5];

                $check_liked = pg_query($db_connection, $stmt);
                $comment_liked = pg_num_rows($check_liked);
              }
            ?>

            <div class="comment_node">
            <div class="comment_author">"<?php echo $comment_row[0]." ".$comment_row[1]; ?></div>
            <div class="comment_content"><?php echo $comment_row[2] ?></div>
            <div class="comment_date"><?php echo date('Y-m-d H:i:s',strtotime($comment_row[3])) ?></div>
            <div class="forum_button">
              <form method="POST" action="index.php">
                <input type="hidden" name="comment_like" value="<?php echo $comment_row[5]; ?>">
                <button type="submit" class="like_button <?php echo ($comment_liked) ? " clicked" : ""; ?>"><span class="glyphicon glyphicon-thumbs-up"></span><?php echo ($comment_row[4]) ? $comment_row[4] : "Like"; ?></button>
              </form>
            </div>
            </div>
          <?php endwhile;?>

          <div class="comment_node">
            <div class="comment_author"><?php echo (isset($_SESSION['username'])) ? $_SESSION['username'] : "<a href=\"/login.php\">Login/Register</a>"; ?></div>
            <div class="comment_content"><input name="comment_content" placeholder="Add your commnent here"></div>
            <div class="comment_date"><?php echo date('Y-m-d H:i:s') ?></div>
            <div class="forum_button"><button class="like_button">Comment</button></div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
    <div class="leader_box">
      <div class="leaderboard_top">
        <div class="leader_box_title">
          <img src="images/trophy.png" alt="trophy">
          <h2>Leaderboard - This week</h2>
        </div>
        <table>
          <tr>
            <th>User</th>
            <th>Post Likes</th>
            <th>Comment Likes</th>
            <th>Score</th>
          </tr>
          <?php
            $stmt = ' select concat(u2.first_name,\' \',u2.last_name) , post_likes.author, post_likes.post_likes , comment_likes.comment_likes
                      from (select u.id as author , sum(post_likes.likes) as post_likes
                      		from "Users" u
                      		left join "Posts" p on p.user_id = u.id
                      		left join (select pl.post_id, count(*) as likes from "PostLikes" pl where pl.created_at >\''.date('Y-m-d', strtotime('-1 week')).'\' group by pl.post_id) as post_likes on post_likes.post_id = p.id
                      		group by author) as post_likes
                      left join (select u.id as author , sum(comment_likes.likes) as comment_likes
                      		from "Users" u
                      		left join "Comments" c on c.user_id = u.id
                      		left join (select cl.comment_id, count(*) as likes from "CommentLikes" cl where cl.created_at >\''.date('Y-m-d', strtotime('-1 week')).'\' group by cl.comment_id) as comment_likes on comment_likes.comment_id = c.id
                      		group by author) as comment_likes
                      on post_likes.author = comment_likes.author
                      left join "Users" u2 on u2.id = post_likes.author';

            $alltime_leaders = pg_query($db_connection, $stmt);

            while ($alltime_leader_row = pg_fetch_row($alltime_leaders)) {
              // print_r($alltime_leader_row);
              $final_score = $alltime_leader_row[2] + $alltime_leader_row[3];
              if (!$final_score) continue;
              echo "<tr>";
              echo "<td>".$alltime_leader_row[0]."</td>";
              echo "<td>".$alltime_leader_row[2]."</td>";
              echo "<td>".$alltime_leader_row[3]."</td>";
              echo "<td>".$final_score."</td>";
              echo "</tr>";
            }

          ?>
        </table>
      </div>
      <div class="leaderboard_bottom">
        <div class="leader_box_title">
          <img src="images/trophy.png" alt="trophy">
          <h2>Leaderboard - Lifetime</h2>
        </div>
        <table>
          <tr>
            <th>User</th>
            <th>Post Likes</th>
            <th>Comment Likes</th>
            <th>Score</th>
          </tr>
          <?php
            $stmt = ' select concat(u2.first_name,\' \',u2.last_name) , post_likes.author, post_likes.post_likes , comment_likes.comment_likes
                      from (select u.id as author , sum(post_likes.likes) as post_likes
                      		from "Users" u
                      		left join "Posts" p on p.user_id = u.id
                      		left join (select pl.post_id, count(*) as likes from "PostLikes" pl group by pl.post_id) as post_likes on post_likes.post_id = p.id
                      		group by author) as post_likes
                      left join (select u.id as author , sum(comment_likes.likes) as comment_likes
                      		from "Users" u
                      		left join "Comments" c on c.user_id = u.id
                      		left join (select cl.comment_id, count(*) as likes from "CommentLikes" cl group by cl.comment_id) as comment_likes on comment_likes.comment_id = c.id
                      		group by author) as comment_likes
                      on post_likes.author = comment_likes.author
                      left join "Users" u2 on u2.id = post_likes.author';

            $alltime_leaders = pg_query($db_connection, $stmt);
          ?>

          <?php while ($alltime_leader_row = pg_fetch_row($alltime_leaders)) :?>
              <?php if ($alltime_leader_row[4]<1) continue; ?>
              <tr>
                <td><?php echo $alltime_leader_row[0] ?></td>
                <td><?php echo $alltime_leader_row[2] ?></td>
                <td><?php echo $alltime_leader_row[3] ?></td>
                <td><?php echo $alltime_leader_row[4] ?></td>
              </tr>
          <?php endwhile; ?>
        </table>
      </div>
    </div>
  </div>
</div>

<?php
require 'inc/footer.inc.php'
?>
