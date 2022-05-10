<?php
// Author: Charith Akalanka
// Description: User profile management page
// Date modified: 03/05/2022 by Jordan Junior

define( 'PAGE', 'Home' );
define( 'PAGE_TITLE', 'Home');

require 'inc/header.inc.php';
require 'inc/functions.inc.php';

requireLogin();

$stmt = "select first_name, last_name, email, is_admin, id from \"Users\" u where email = '".$_SESSION['email']."'";
$check = pg_query($db_connection, $stmt);
$result = pg_fetch_row($check);

if ($result) {
  $_SESSION['user_id'] = $result[4];
  $_SESSION['is_admin'] = false;
  if ($result[3]==='t') $_SESSION['is_admin'] = true;
}


// Author: Jordan Junior
// Description: Update profile information
// Date Created: 10/05/2022
$fname = isset($_POST['fname']) ? $_POST['fname'] : "";
$lname = isset($_POST['lname']) ? $_POST['lname'] : "";
$password = isset($_POST['password']) ? $_POST['password'] : "";

if($fname != "" || $lname != "" || $password != ""){
  $fname = $fname == "" ? $_POST['fname'] : $fname;
  $lname = $lname == "" ? $_POST['lname'] : $lname;
  $password = $password == "" ? $_POST['password'] : $password;

  $errors = update_user_validation($fname, $lname, $password);

  if(!$errors){
    update_user($db_connection, $fname, $lname, $password, $result[4]);
  }
}

?>

<div class="content">
  <div class="profile_box">
    <?php
      if ($errors) {
        foreach($errors as $error) {
          echo "<div class=\"error_msg\">*".$error."</div>\n";
        }
      }
    ?>
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
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password">
      </div>
      <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
  </div>
</div>

<?php
require 'inc/footer.inc.php'
?>
