<?php
// Author: Charith Akalanka
// Description: New user registration page
//Date modified: 03/05/2022 by Jordan Junior

define( 'PAGE', 'Register' );
define( 'PAGE_TITLE', 'Register');

require 'inc/header.inc.php';
require 'inc/functions.inc.php';

//<script src ="functions.inc.js"></script>

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
    <form id="register" method="POST" action="register.php">
      <div class="form-group">
          <input type="text" class="form-control" name="fname" required placeholder="Your First Name" value="<?php echo (isset($_POST['fname'])) ? $_POST['fname'] : "" ?>">
      </div>
      <div class="form-group">
          <input type="text" class="form-control" name="lname" required placeholder="Your Last Name" value="<?php echo (isset($_POST['lname'])) ? $_POST['lname'] : "" ?>">
      </div>
      <div class="form-group">
          <input type="email" class="form-control" name="email" required placeholder="Your Email" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : "" ?>">
      </div>
      <div class="form-group">
          <input type="password" class="form-control" name="password" required placeholder="Your Password">
      </div>
      <div class="form-group">
          <input type="password" class="form-control" name="password2" required placeholder="Re-enter your password">
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
