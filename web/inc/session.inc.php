<?php
// admin : pass
if( session_status() == PHP_SESSION_NONE ) session_start();

$loggedIn = false;

if( isset( $_SESSION['username'] ) ){ // Already logged in
    $loggedIn = true;
    $username = $_SESSION['username'];

} elseif( isset($_POST['name']) && isset($_POST['password']) ){ // New login attempt
    if( ($_POST['name'] === 'admin' && $_POST['password'] === 'pass') ) { // FOR TESTING ONLY
        $_SESSION['username'] = $_POST['name'];
        header( 'location: '.$_SERVER['REQUEST_URI'] );

    } else {
      // Check DB
      $username = pg_escape_string($_POST['name']);
      $password = pg_escape_string($_POST['password']);

      $stmt = "select first_name, last_name, pass_hash from \"Users\" u where email = '".$username."'";
      $check = pg_query($db_connection, $stmt);
      $result = pg_fetch_row($check);

      if(password_verify($password, $result[2])) {
        $loggedIn = true;
        $_SESSION['username'] = $result[0]." ".$result[1];
      }
    }
}

function requireLogin(){
    global $loggedIn;
    if( !$loggedIn ){
    ?>
    <div class="form-box">
      <h1>Login to your account</h1>
      <p>admin : pass</p>
      <p>admin@webforum.com : pass</p>
      <form method="post" action="<?=$_SERVER['REQUEST_URI'];?>">
        <div class="form-group">
          <input type="username" id="username" name="name" class="form-control" placeholder="Username" required autofocus>
        </div>
        <div class="form-group">
          <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
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
