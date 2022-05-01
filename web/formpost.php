<!--
File Author: Ully Gamarra Martins
created: 14/03/2022
last modified: 25/04/2022

-->

<?php
define( 'PAGE', 'Discussion' );
define( 'PAGE_TITLE', 'Discussion');

require 'inc/header.inc.php';
require 'inc/functions.inc.php';
requireLogin();

$errors = null;


?>
<div class="content">
	<?php
    	  if ($errors) {
       		 foreach($errors as $error) {
        	 	 echo "<div class=\"error_msg\">*".$error."</div>\n";
      	 	 }
    	  }
   	 ?>
	<form method="post" action="index.php">
	<label for="subjects">Subject of your discussion posts</label>
	<select name="subjects" id="subjects"  >
	<?php
		$stmt = 'select * from "Subjects"';
		 $sql = pg_query($db_connection, $stmt);
		 while ($row = pg_fetch_assoc($sql)) {
			 print_r($row);
			 echo '<option value="'.htmlspecialchars($row["id"]).'">'.$row["title"].'</option>';
		 }
	?>
	</select>
		</br>
		<label for="issue">Post your discussion here:</label>
	  	<input type="text" class="title" name="title" placeholder="your discussion topic" value="<?php echo (isset($_POST['title'])) ? autofocus : "" ?>" required = "required">
	  	<input style="height:200px; width: 600px; font-size:10pt;" type="text" size="500" id="content" name="content" placeholder="Write your answer of enquiry here..." value="<?php echo (isset($_POST['content'])) ? autofocus: "" ?>" required = "required"></br>

		<button type= "submit">Submit</button>
		<input type= "reset" value="Reset Form"/>

	</form>
</div>
