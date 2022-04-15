<?php
define( 'PAGE', 'Register' );
define( 'PAGE_TITLE', 'Register');

require 'inc/header.inc.php';
require 'inc/functions.inc.php';

if ($loggedIn) {
  header( 'location: /profile.php' );
  exit();
}

$errors = null;
$errors = new_user_validation();

if (!empty($_POST["fname"]) && !empty($_POST["lname"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !$errors) {
  // Add user to the database
  insert_user($db_connection,$_POST["fname"],$_POST["lname"],$_POST["email"],$_POST["password"]);
}

?>
<div class="content">
  <div class="form-box">
    <?php
      if ($errors) {
        foreach($errors as $error) {
          echo "<div class=\"error_msg\">*".$error."</div>\n";
        }
      }
    ?>
    <h1>Register for a new account</h1>
    <form method="POST" action="register.php">
      <div class="form-group">
          <input type="text" class="form-control" name="fname" placeholder="Your First Name" value="<?=$_POST["fname"];?>">
      </div>
      <div class="form-group">
          <input type="text" class="form-control" name="lname" placeholder="Your Last Name" value="<?=$_POST["lname"];?>">
      </div>
      <div class="form-group">
          <input type="email" class="form-control" name="email" placeholder="Your Email" value="<?=$_POST["email"];?>">
      </div>
      <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Your Password">
      </div>
      <div class="form-group">
          <input type="password" class="form-control" name="password2" placeholder="Re-enter your password" value="<?=$email;?>">
      </div>
      <button type="submit" class="btn btn-secondary">Register</button>
    </form>
    <p></p>
    <p>Or</p>
    <form method="get" action="/login.php">
      <button type="submit" class="btn btn-secondary">Login</button>
    </form>
  </div>
</div>

<?php
require 'inc/footer.inc.php'
?>
