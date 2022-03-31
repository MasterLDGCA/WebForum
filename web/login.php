<?php
define( 'PAGE', 'Login' );
define( 'PAGE_TITLE', 'Login');

require 'inc/header.inc.php';
?>
      <div class="form-box">
        <h1>Login to your account</h1>
        <form method="POST" action="login.php">
            <div class="form-group">
                <input type="email" class="form-control" name="user" placeholder="Your Email" value="<?=$email;?>">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Your Password">
            </div>
            <button type="submit" class="btn btn-secondary">Submit</button>
        </form>
      </div>
    </body>
</html>
