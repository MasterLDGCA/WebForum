<?php
// Author: Charith Akalanka
// Description: User profile management page

define( 'PAGE', 'Home' );
define( 'PAGE_TITLE', 'Home');

require 'inc/header.inc.php';
requireLogin();

$stmt = "select first_name, last_name, email, is_admin, id from \"Users\" u where email = '".$_SESSION['email']."'";
$check = pg_query($db_connection, $stmt);
$result = pg_fetch_row($check);

if ($result) {
  $_SESSION['user_id'] = $result[4];
  $_SESSION['is_admin'] = false;
  if ($result[3]==='t') $_SESSION['is_admin'] = true;
}
?>

<div class="content">
  <div class="profile_box">
    <h1>Your user profile</h1>
    <form method="post" action="/profile.php">
      <div class="form-group">
        <label for="">First Name : </label>
        <input name="fname" class="form-control" value="<?php echo $result[0]?>" >
      </div>
      <div class="form-group">
        <label for="">Last Name : </label>
        <input name="lname" class="form-control" value="<?php echo $result[1]?>" >
      </div>
      <div class="form-group">
        <label for="">Email address : </label>
        <label for=""><?php echo $result[2]?></label>
      </div>
      <div class="form-group">
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
  </div>
</div>

<?php
require 'inc/footer.inc.php'
?>
