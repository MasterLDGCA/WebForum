<?php
if( session_status() == PHP_SESSION_NONE ) session_start();

$loggedIn = false;
$isAdmin = false;
$userID = null;
$errors = null;

if( isset( $_SESSION['username'] ) ){ // Already logged in
    $loggedIn = true;
    $isAdmin = $_SESSION['is_admin'];
    $username = $_SESSION['username'];
    $userID = $_SESSION['user_id'];

} elseif( isset($_POST['name']) && isset($_POST['password']) ){ // New login attempt
  // Check DB
  $username = pg_escape_string($_POST['name']);
  $password = pg_escape_string($_POST['password']);

  $stmt = "select first_name, last_name, pass_hash, is_admin, id from \"Users\" u where email = '".$username."'";
  $check = pg_query($db_connection, $stmt);
  $result = pg_fetch_row($check);
  // print_r($result);

  if(password_verify($password, $result[2])) {
    $loggedIn = true;
    $_SESSION['username'] = $result[0]." ".$result[1];
    $_SESSION['email'] = $username;
    $_SESSION['user_id'] = $result[4];
    $_SESSION['is_admin'] = false;

    if ($result[3]==='t') $_SESSION['is_admin'] = true;
  } else {
    $errors = "<div class=\"error_msg\">* Email address or Password is wrong. Please try again.</div>\n";
  }
}

function requireLogin(){
    global $loggedIn;
    global $errors;
    if( !$loggedIn ){
    ?>
    <div class="content">
      <?php if ($errors) echo $errors; ?>
      <div class="form-box">
        <h1>Login to your account</h1>
        <form method="post" action="<?=$_SERVER['REQUEST_URI'];?>">
          <div class="form-group">
            <input type="username" id="username" name="name" placeholder="Email" required autofocus>
          </div>
          <div class="form-group">
            <input type="password" id="inputPassword" name="password" placeholder="Password" required>
          </div>
          <button type="submit" class="btn btn-secondary">Login</button>
        </form>
        <p></p>
        <p>Or</p>
        <form method="get" action="/register.php">
          <button type="submit" class="btn btn-secondary">Register</button>
        </form>
      </div>
    </div>
    <?php
    require 'inc/footer.inc.php'
    ?>

<?php
    exit();
  }
}
?>
