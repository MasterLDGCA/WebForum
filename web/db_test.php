<?php

require 'inc/postgresql.inc.php';

$username = 'test1@webforum.com';
$stmt = "select first_name, last_name, pass_hash, is_admin from \"Users\" u where email = '".$username."'";
// $stmt = 'insert into "Users" (first_name,last_name,email,pass_hash) values ("Test","User","test4@webforum.com","$2y$10$OCEM/FZeGwNUrjCMg.1j7.dPLa3HHmy1vVBUJ5LphJ6bsO4AlT.EO")';
// $stmt = 'insert INTO "Users" (first_name,last_name,email,pass_hash) VALUES (\'Test\',\'User\',\'test5@webforum.com\',\'$2y$10$SOibbCoSQueGKoxXq3Dk4epL7Yd0wRUAFyVatQrwT6n2IHuQQUgoG\')';
$hash = '$2y$10$SOibbCoSQueGKoxXq3Dk4epL7Yd0wRUAFyVatQrwT6n2IHuQQUgoG';
$stmt = "insert INTO \"Users\" (first_name,last_name,email,pass_hash) VALUES ('Test','User','test4@webforum.com','".$hash."')";
$check = pg_query($db_connection, $stmt);
print_r($check);

?>
