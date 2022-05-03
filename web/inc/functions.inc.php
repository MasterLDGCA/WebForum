<?php

// Author: Charith Akalanka
// Description: Create new user account from the register page
function insert_user($db_connection, $fname, $lname, $email, $password) {
  // prepare statement for insert
  $pass_hash = password_hash($password, PASSWORD_DEFAULT);

  $query = "insert INTO \"Users\" (first_name,last_name,email,pass_hash) VALUES ('".$fname."','".$lname."','".$email."','".$pass_hash."')";
  $result = pg_query($db_connection, $query );
  if (!$result) echo "<div class=\"error_msg\">Account creation failed. Please contact the administrators</div>";
  else {
    $_SESSION['username'] = $_POST["fname"]." ".$_POST["lname"];
    $_SESSION['email'] = $_POST["email"];
    header( 'location: /profile.php' );
    exit();
  }
}

// Author: Charith Akalanka
// Description: Validate user data inserted on the register page
// Date modified: 03/05/2022 by Jordan Junior
function new_user_validation() {
  if (!empty($_POST["fname"])    && preg_match("/[^a-zA-Z]/", $_POST["fname"]))          $errors[] = "First name can only contain letters";
  if (!empty($_POST["lname"])    && preg_match("/[^a-zA-Z]/", $_POST["lname"]))          $errors[] = "Last name can only contain letters";
  if (!empty($_POST["email"])    && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address";
  if (!empty($_POST["password"]) && strlen($_POST["password"]) < 8)                      $errors[] = "Password must contain 8 or more characters";
  if (!empty($_POST["password"]) && !preg_match('/[a-z]/',$_POST["password"]))           $errors[] = "Password must contain lowercase characters";
  if (!empty($_POST["password"]) && !preg_match('/[A-Z]/',$_POST["password"]))           $errors[] = "Password must contain UPPERCASE characters";
  if (!empty($_POST["password"]) && !preg_match('/[0-9]/',$_POST["password"]))           $errors[] = "Password must contain numbers";
  if (!empty($_POST["password"]) && !preg_match('/[\.\?\(\)@#\$%&\*]/',$_POST["password"])) $errors[] = "Password must contain \$pec!al (har@ctors";
  if (isset($_POST["password"]) && isset($_POST["password2"]) && $_POST["password"] != $_POST["password2"])  $errors[] = "The passwords do not match";

  if (isset($errors)) 
    return $errors;
  else 
    return 0;
}

// Author: Charith Akalanka
// Description: Record a click on the post like button
function post_like_clicked( $db_connection, $post_id, $user_id ) {
  // echo "Post ID:".$post_id." User ID:".$user_id;

  $stmt = 'select *
            from "PostLikes" pl
            where pl.post_id='.$post_id.' and pl.user_id='.$user_id;

  $already_liked = pg_query($db_connection, $stmt);
  $already_liked = pg_num_rows($already_liked);

  if ($already_liked) {
    // Delete the previous like
    $stmt = 'delete from "PostLikes" pl
              where pl.post_id='.$post_id.' and pl.user_id='.$user_id;
    // echo $stmt;
    $delete_like = pg_query($db_connection, $stmt);

  } else {
    // Create a new like
    $stmt = 'insert into "PostLikes" (post_id, user_id) values (\''.$post_id.'\',\''.$user_id.'\')';
    // echo $stmt;
    $create_like = pg_query($db_connection, $stmt);

  }

}

// Author: Charith Akalanka
// Description: Record a click on the comment like button
function comment_like_clicked( $db_connection, $comment_id, $user_id ) {
  // echo "Comment ID:".$comment_id." User ID:".$user_id;

  $stmt = 'select *
            from "CommentLikes" cl
            where cl.comment_id='.$comment_id.' and cl.user_id='.$user_id;

  $already_liked = pg_query($db_connection, $stmt);
  $already_liked = pg_num_rows($already_liked);

  if ($already_liked) {
    // Delete the previous like
    $stmt = 'delete from "CommentLikes" cl
              where cl.post_id='.$comment_id.' and cl.user_id='.$user_id;
    // echo $stmt;
    $delete_like = pg_query($db_connection, $stmt);

  } else {
    // Create new like
    $stmt = 'insert into "CommentLikes" (comment_id, user_id) values (\''.$comment_id.'\',\''.$user_id.'\')';
    // echo $stmt;
    $create_like = pg_query($db_connection, $stmt);

  }

}

// Author: Charith Akalanka
// Description: Grants a user Admin previleges
function make_admin($db_connection, $user_id) {
  $stmt = 'update "Users" set "is_admin" = \'t\' where id =\''.$user_id.'\'';
  $make_admin = pg_query($db_connection, $stmt);
}

// Author: Charith Akalanka
// Description: Revoke a user's Admin previleges
function revoke_admin($db_connection, $user_id) {
  $stmt = 'update "Users" set "is_admin" = \'f\' where id =\''.$user_id.'\'';
  $make_admin = pg_query($db_connection, $stmt);
}

// Author: Charith Akalanka
// Description: Ban a user
function revoke_approval($db_connection, $user_id) {
  $stmt = 'update "Users" set "approved" = \'f\' where id =\''.$user_id.'\'';
  $make_admin = pg_query($db_connection, $stmt);
}

// Author: Charith Akalanka
// Description: Approve(un-ban) a user
function grant_approval($db_connection, $user_id) {
  $stmt = 'update "Users" set "approved" = \'t\' where id =\''.$user_id.'\'';
  $make_admin = pg_query($db_connection, $stmt);
}

//Author: Ully Martins
//Description: Function for create post
//Date created: 29/04/2022
//Date modified:
function create_post($db_connection, $title, $content, $user_id) {
  //create new post
  $stmt = 'insert into "Posts" (user_id, title, content) values (\''.$user_id.'\',\''.$title.'\',\''.$content.'\')';
  $create_post = pg_query($db_connection, $stmt);
  if (!$create_post) echo "<div class=\"error_msg\">Post creation failed</div>";
}

//Author: Ully Martins
//Description: Function for create comment
//Date created: 29/04/2022
//Date modified:
  function create_comment($db_connection, $comment, $user_id, $post_id) {
    //create new post
    $stmt = 'insert into "Comments" (user_id, post_id, comment) values (\''.$user_id.'\',\''.$post_id.'\',\''.$comment.'\')';

    $create_post = pg_query($db_connection, $stmt);
    //if (!$result) echo "<div class=\"error_msg\">Account creation failed. Please contact the administrators</div>";
  }


//Author: Hamish Sandys-Renton
//Description:Flag/Report Post Function
//Date created: 29/04/2022
//Date modified: 
  function flag_post($db_connection, $id) {
    //var_dump($id);  //dump on screen what is in $id
    $flag = 'UPDATE "Posts" SET visible= false WHERE id = '.$id;
    $flag_p = pg_query($db_connection, $flag);
  }



?>
