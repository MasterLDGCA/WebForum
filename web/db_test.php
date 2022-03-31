<?php

require 'inc/postgresql.inc.php';

$username = 'admin@webforum.com';
$stmt = "select pass_hash from \"Users\" u where email = '".$username."'";
$check = pg_query($db_connection, $stmt);
$result = pg_fetch_row($check);
print_r($result);

?>
