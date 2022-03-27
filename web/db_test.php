<?php

require './inc/postgresql.inc.php';

$result = pg_query($db_connection, "SELECT * FROM \"Users\"");

while ($row = pg_fetch_row($result)) {
  print_r($row);
}

?>
