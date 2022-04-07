<?php

require 'inc/postgresql.inc.php';

$pass = 'test';

print_r(password_hash($pass, PASSWORD_DEFAULT));

?>
