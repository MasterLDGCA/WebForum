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

//print_r($_POST);

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
		<div class="form-box">
			<div class="form-group">
				<label for="subjects">Subject of your discussion : </label>
				<select name="subjects" id="subjects" required = "required">
					<option value="1" selected="selected">General</option>
					<?php
						$stmt = 'select * from "Subjects"';
					 	$sql = pg_query($db_connection, $stmt);
					 	while ($row = pg_fetch_assoc($sql)) :?>
					 		<?php if ($row["id"] == 1) continue; ?>
						 	<option value="<?php echo htmlspecialchars($row["id"]); ?>"><?php echo $row["title"] ?></option>;
					 <?php endwhile; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="issue">Title of your discussion : </label><input type="text" name="title" placeholder="your discussion topic" value="<?php echo (isset($_POST['title'])) ? autofocus : "" ?>" required = "required">
			</div>
			<div class="form-group">
				<input style="height:200px; width: 600px; font-size:10pt;" type="text" size="500" id="content" name="content" placeholder="Write your answer of enquiry here..." value="<?php echo (isset($_POST['content'])) ? autofocus: "" ?>" required = "required">
			</div>
			<div class="form-group">
				<button type= "submit">Submit</button>
				<input type= "reset" value="Reset Form"/>
			</div>
		</div>
	</form>
</div>
