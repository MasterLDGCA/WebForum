<?php
require 'inc/postgresql.inc.php';
require 'inc/functions.inc.php';

// echo("<script>console.log('_POST: " . json_encode($_POST) . "');</script>");

$offset = 0;
$limit = 5;

if (isset($_POST["offset"]) && preg_match("/^\d+$/",$_POST["offset"])) {
  $offset = $_POST["offset"];
}

if (isset($_POST["limit"]) && preg_match("/^\d+$/",$_POST["limit"])) {
  $limit = $_POST["limit"];
}

// Retrieve posts
$stmt = ' select p.title , p.content , u.first_name , u.last_name, p.created_at , p.id , likes
          from "Posts" p
          left join "Users" u ON p.user_id = u.id
          left join (	select pl.post_id , count(pl.user_id) as likes
          			from "PostLikes" pl
          			group by pl.post_id ) as likes on likes.post_id = p.id
          left join "PostSubject" ps on ps.post_id = p.id
          left join "Subjects" s on s.id = ps.subj_id
          where p.visible = true and (p.approved is null or p.approved = true)';


if (isset($_POST["subject"]) && preg_match("/^\d+$/",$_POST["subject"])) {
  // A subject is selected
  $stmt .= ' and s.id ='.$_POST["subject"];
} else {
  // Invalid subject code
  unset($_POST["subject"]);
  // $errors[] = "Invalid subject selected";
}

if (isset($_POST["date"]) && preg_match("/^\d{4}-\d{2}-\d{2}$/",$_POST["date"])) {
  // A date is selected
  $stmt .= " and CAST(p.created_at as DATE) = '{$_POST["date"]}'";
} else {
  // invalid date
  unset($_POST["date"]);
  // $errors[] = "Invalid search date provided";
}

if (isset($_POST["searchTerm"]) && strlen($_POST["searchTerm"])>0 && !preg_match("/[^a-zA-Z0-9 ]/",$_POST["searchTerm"])) {
  $stmt .= " and ( to_tsvector(p.\"content\") @@ to_tsquery('{$_POST["searchTerm"]}')
             or to_tsvector(p.\"title\") @@ to_tsquery('{$_POST["searchTerm"]}') )";
} else {
  unset($_POST["searchTerm"]);
  $errors[] = "Invalid characters found in search term";
}

$stmt_end = ' order by p.created_at desc limit '.$limit;
$stmt_end .= ' offset '.$offset;

$posts = pg_query($db_connection, $stmt.$stmt_end);

while ($post_row = pg_fetch_row($posts)) : ?>

    <div class="post_node">
    <div class="post_title_node">
    <img class="post_img" src="images/die.png" alt=""><div class="post_title"><?php echo (isset($_POST["searchTerm"])) ? highlight_text($_POST["searchTerm"],$post_row[0]) : $post_row[0]; ?></div>
    <div class="post_tags">
    <?php
      // Retrieve tags
      $stmt = 'select s.title from "PostSubject" ps
                left join "Subjects" s on ps.subj_id = s.id
                where post_id ='.$post_row[5];
                //'and subj_id =' .$post_row[6]; //changed here


      $tags = pg_query($db_connection, $stmt);

      while ($tag_title = pg_fetch_row($tags)) : ?>
        <div class="tag"><?php echo $tag_title[0]; ?></div>
    <?php endwhile; ?>
    </div>
    </div>
    <div class="post_content">
      <div class="post_content_text"><?php echo (isset($_POST["searchTerm"])) ? highlight_text($_POST["searchTerm"],$post_row[1]) : $post_row[1]; ?></div>
      <div class="forum_button">
      <?php
        // Has the user liked this post?
        $post_liked = false;
        if ($loggedIn) {
          $stmt ='select *
                  from "PostLikes" pl
                  where pl.user_id ='.$_SESSION['user_id'].' and pl.post_id ='.$post_row[5];

          $check_liked = pg_query($db_connection, $stmt);
          $post_liked = pg_num_rows($check_liked);
        }
      ?>
      <form method="POST" action="index.php">
        <input type="hidden" name="post_like" value="<?php echo $post_row[5];?>">
        <button type="submit" class="like_button<?php echo ($post_liked) ? " clicked" : ""; ?>" ><span class="glyphicon glyphicon-thumbs-up"></span> <?php echo ($post_row[6]) ? $post_row[6] : " Like";?></button>
      </form>

      <!-- Hamish Sandys-Renton - Report Button with PHP 02/05/2022
      Date Created: 01/05/2022
      -->
      <form method="POST" action="index.php">
        <input type="hidden" name="post_report" value="<?php echo $post_row[5];?>">
        <button type="submit" class="like_button" id="flag_button"><span class="glyphicon glyphicon-flag"></span></button>
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
              where c.visible = true and (c.approved = true or c.approved is null) and c.post_id = '.$post_row[5].'order by c.created_at';
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
      <div class="comment_author"><?php echo $comment_row[0]." ".$comment_row[1]; ?></div>
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
    <form method="POST" action="index.php">
    <div class="comment_node">
      <div class="comment_author"><?php echo (isset($_SESSION['username'])) ? $_SESSION['username'] : "<a class=\"loginRegisterButton\" href=\"/login.php\">Login</a>"; ?></div>
      <div class="comment_content"><input name="comment_content" placeholder="Add your comment here" ></div>
      <div class="comment_date"><?php echo date('Y-m-d H:i:s') ?></div>
      <div class="forum_button">
        <input type="hidden" name="post_id" value="<?php echo $post_row[5]; ?>">
        <input type="hidden" name="subj_id" value="<?php echo $post_row[5]; ?>">
        <button type="submit" class="like_button"> Comment</button>
      </div>
    </div>
    </form>
  </div>
<?php endwhile; ?>
