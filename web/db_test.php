<?php

require 'inc/postgresql.inc.php';

$username = 'test1@webforum.com';
$stmt = "select first_name, last_name, pass_hash, is_admin from \"Users\" u where email = '".$username."'";
$check = pg_query($db_connection, $stmt);
$result = pg_fetch_row($check);
print_r($result);

?>
