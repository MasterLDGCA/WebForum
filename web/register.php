<?php
define( 'PAGE', 'Register' );
define( 'PAGE_TITLE', 'Register');

require 'inc/header.inc.php';
?>

<div class="form-box">
  <h1>Register for a new account</h1>
  <form method="POST" action="register.php">
    <div class="form-group">
        <input type="fname" class="form-control" name="fname" placeholder="Your First Name" value="<?=$email;?>">
    </div>
    <div class="form-group">
        <input type="lname" class="form-control" name="lname" placeholder="Your Last Name" value="<?=$email;?>">
    </div>
    <div class="form-group">
        <input type="email" class="form-control" name="email" placeholder="Your Email" value="<?=$email;?>">
    </div>
    <div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="Your Password">
    </div>
    <div class="form-group">
        <input type="password2" class="form-control" name="password2" placeholder="Re-enter your password" value="<?=$email;?>">
    </div>
    <button type="submit" class="btn btn-secondary">Register</button>
  </form>
</div>
</body>
</html>
