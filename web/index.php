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
  create_post($db_connection, $_POST["title"], $_POST["content"], $userID);
}

if (!empty($_POST["post_like"])) {
  requireLogin();
  post_like_clicked($db_connection, $_POST["post_like"], $userID);
}

if (!empty($_POST["comment_like"])) {
  requireLogin();
  comment_like_clicked($db_connection, $_POST["comment_like"], $userID);
}

//Author: Ully Martins
//Description: Function for create a comment
//Date created: 06/05/2022
//Date modified:
if (!empty($_POST["comment_content"])) {
  requireLogin();
  create_comment($db_connection, $_POST["post_id"], $_POST["comment_content"], $userID);
}

//HAMISH Sandys-Renton
//Call flag post function to report an inapropriate post
//Date created: 02/05/2022
if (!empty($_POST["post_report"])) {
  requireLogin();
  flag_post($db_connection, $_POST["post_report"]);
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

if (isset($_POST["search-button"])) {
  // Search button clicked
  if (isset($_POST["subject"]) && preg_match("/^\d+$/",$_POST["subject"])) {
    // A subject is selected
    $stmt .= ' and s.id ='.$_POST["subject"];
  } else {
    // Invalid subject code
    unset($_POST["subject"]);
  }

  if (isset($_POST["date"]) && preg_match("/^\d{4}-\d{2}-\d{2}$/",$_POST["date"])) {
    // A date is selected
    $stmt .= " and CAST(p.created_at as DATE) = '{$_POST["date"]}'";
  } else {
    // invalid date
    unset($_POST["date"]);
  }
}

$stmt_end = ' order by p.created_at desc limit 5';

$posts = pg_query($db_connection, $stmt.$stmt_end);

?>
<div class="content">
  <div class="view">
    <div class="post_box">
      <div class="search_box">
        <form class="" action="index.php" method="post">
          <input type="text" name="searchTerm" placeholder="Enter a search phrase" value="">
          <select class="" name="subject">
            <option value="all">All</option>
            <?php
              $stmt = ' select id, title from "Subjects" order by id';
              $subjects = pg_query($db_connection, $stmt);
              while ($subject = pg_fetch_row($subjects)) :
            ?>
            <option value="<?php echo $subject[0]; ?>" <?php echo (isset($_POST["subject"]) && $_POST["subject"]===$subject[0]) ? "selected=\"selected\"" : "" ?> ><?php echo $subject[1]; ?></option>
            <?php endwhile; ?>
          </select>
          <input type="date" name="date" value="<?php echo (isset($_POST["date"]) ? $_POST["date"] : "none") ?>">
          <button type="submit" name="search-button" value="1">Search</button>
        </form>
      </div>
      <div id="post_view" class="post_view"></div>
      <input type="hidden" id="offset" value="0">
      <div id="loader" class="loader"></div>
    </div>

    <script type="text/javascript">
      var inProgress = true;
      var limit = 5;

      // Get the first set of results
      var offset = 0;
      $.ajax({
        type: 'POST',
        url: 'get_posts.php',
        data: { offset: offset,
                limit: limit<?php
                echo (isset($_POST["subject"])) ? ", subject: '{$_POST["subject"]}'\n" : "";
                echo (isset($_POST["date"])) ? ", date: '{$_POST["date"]}'\n" : "";?>},
        success: function(data){
            if(data != ''){
                $('#post_view').append(data);
                $('#offset').val(offset);
                inProgress = false;
            } else {
                $("#loader").hide();
                inProgress = false;
            }
        }
      });

      $(window).scroll(function() {
        if ($(this).scrollTop() + $(window).height() > $('#post_view').height() + 100) {
          if (!inProgress) {
            inProgress = true;
            var offset = parseInt($('#offset').val())+limit;
            $.ajax({
              type: 'POST',
              url: 'get_posts.php',
              data: { offset: offset,
                      limit: limit<?php
                      echo (isset($_POST["subject"])) ? ", subject: '{$_POST["subject"]}'\n" : "";
                      echo (isset($_POST["date"])) ? ", date: '{$_POST["date"]}'\n" : "";?>},
              success: function(data){
                  if(data != ''){
                      $('#post_view').append(data);
                      $('#offset').val(offset);
                      inProgress = false;
                  } else {
                      $("#loader").hide();
                      inProgress = false;
                  }
              }
            });
          }
        }
      })

    </script>
    <div class="leader_box">
      <div class="leaderboard_top">
        <p id="cancan"></p>
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
            $stmt = ' select concat(u2.first_name,\' \',u2.last_name) , post_likes.author, post_likes.post_likes , comment_likes.comment_likes , coalesce(post_likes.post_likes, 0) + coalesce(comment_likes.comment_likes, 0) as score
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
                      left join "Users" u2 on u2.id = post_likes.author
                      order by score desc';

            $alltime_leaders = pg_query($db_connection, $stmt);
            $alltime_leader = pg_fetch_result($alltime_leaders, 0, 0);
          ?>
          <!--Author: Hamish Sandys-Renton
           Date Created: 10/05/2022
           Description: Below Saving weekly leader value saving in php variable to use in the P5 Sketch-->
          <input type="hidden" id="leader_all_time" value="<?php echo $alltime_leader ?>">
          <?php while ($alltime_leader_row = pg_fetch_row($alltime_leaders)) :?>
            <?php if ($alltime_leader_row[4]<1) continue; ?>
            <tr>
              <td id="leaderb"><?php echo $alltime_leader_row[0] ?></td>
              <td><?php echo $alltime_leader_row[2] ?></td>
              <td><?php echo $alltime_leader_row[3] ?></td>
              <td><?php echo $alltime_leader_row[4] ?></td>
            </tr>
          <?php endwhile; ?>
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
            $stmt = ' select concat(u2.first_name,\' \',u2.last_name) , post_likes.author, post_likes.post_likes , comment_likes.comment_likes , coalesce(post_likes.post_likes, 0) + coalesce(comment_likes.comment_likes, 0) as score
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
                      left join "Users" u2 on u2.id = post_likes.author
                      order by score desc';

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
