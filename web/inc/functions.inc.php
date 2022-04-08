<?php

function insert_user($db_connection, $fname, $lname, $email, $password) {
  // prepare statement for insert
  $pass_hash = password_hash($password, PASSWORD_DEFAULT);

  $query = "insert INTO \"Users\" (first_name,last_name,email,pass_hash) VALUES ('".$fname."','".$lname."','".$email."','".$pass_hash."')";
  $result = pg_query($db_connection, $query );
  if (!$result) echo "<div class=\"error_msg\">Account creation failed. Please contact the administrators</div>";
  else {
    $_SESSION['username'] = $_POST["fname"]." ".$_POST["fname"];
    header( 'location: /profile.php' );
    exit();
  }
}

function new_user_validation() {
  if (!empty($_POST["fname"])    && preg_match("/[^a-zA-Z]/", $_POST["fname"]))          $errors[] = "First name can only contain letters";
  if (!empty($_POST["lname"])    && preg_match("/[^a-zA-Z]/", $_POST["lname"]))          $errors[] = "Last name can only contain letters";
  if (!empty($_POST["email"])    && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email address";
  if (!empty($_POST["password"]) && strlen($_POST["password"]) < 8)                      $errors[] = "Password must contain 8 or more characters";
  if (!empty($_POST["password"]) && !preg_match('/[a-z]/',$_POST["password"]))           $errors[] = "Password must contain lowercase characters";
  if (!empty($_POST["password"]) && !preg_match('/[A-Z]/',$_POST["password"]))           $errors[] = "Password must contain UPPERCASE characters";
  if (!empty($_POST["password"]) && !preg_match('/[0-9]/',$_POST["password"]))           $errors[] = "Password must contain numbers";
  if (!empty($_POST["password"]) && !preg_match('/[\.\?\(\)@#\$%&\*]/',$_POST["password"])) $errors[] = "Password must contain \$pec!al (har@ctors";
  if ($_POST["password"] != $_POST["password2"])                                         $errors[] = "The passwords do not match";

  if ($errors) return $errors;
  else return 0;
}

?>
