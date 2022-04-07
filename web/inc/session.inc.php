<?php
// admin : pass
if( session_status() == PHP_SESSION_NONE ) session_start();

$loggedIn = false;
$isAdmin = false;

if( isset( $_SESSION['username'] ) ){ // Already logged in
    $loggedIn = true;
    $isAdmin = $_SESSION['is_admin'];
    $username = $_SESSION['username'];

} elseif( isset($_POST['name']) && isset($_POST['password']) ){ // New login attempt
  // Check DB
  $username = pg_escape_string($_POST['name']);
  $password = pg_escape_string($_POST['password']);

  $stmt = "select first_name, last_name, pass_hash, is_admin from \"Users\" u where email = '".$username."'";
  $check = pg_query($db_connection, $stmt);
  $result = pg_fetch_row($check);
  print_r($result);

  if(password_verify($password, $result[2])) {
    $loggedIn = true;
    $_SESSION['username'] = $result[0]." ".$result[1];
    $_SESSION['email'] = $username;
    $_SESSION['is_admin'] = false;
    
    if ($result[3]==='t') $_SESSION['is_admin'] = true;
  }
}

function requireLogin(){
    global $loggedIn;
    if( !$loggedIn ){
    ?>
    <div class="form-box">
      <h1>Login to your account</h1>
      <p>admin@webforum.com : pass</p>
      <p>test1@webforum.com : test</p>
      <form method="post" action="<?=$_SERVER['REQUEST_URI'];?>">
        <div class="form-group">
          <input type="username" id="username" name="name" placeholder="Username" required autofocus>
        </div>
        <div class="form-group">
          <input type="password" id="inputPassword" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-secondary">Submit</button>
      </form>
    </div>
  </body>
</html>

<?php
    exit();
  }
}
?>
