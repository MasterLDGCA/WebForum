<?php

$db_connection = pg_connect("user=postgres password=thestrongestpasswordever host=db.jjchbfuhucutjdvobzzx.supabase.co port=5432 dbname=postgres");

if ( !$db_connection ) {
  echo "Database Connection failed";
}

?>
