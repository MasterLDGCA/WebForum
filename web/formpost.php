<!--
filename: Ully Gamarra Martins
author: Ully Gamarra Martins
created: 14/03/2022
last modified: 14/03/2022
description: Assignment 1 Content Management Systems (CMS)
-->

<?php
define( 'PAGE', 'Discussion' );
define( 'PAGE_TITLE', 'Discussion');

require 'inc/header.inc.php';
require 'inc/functions.inc.php';
requireLogin();

$errors = null;
$errors = create_post();

if (!empty($_POST["title"]) && !empty($_POST["content"]) && !$errors) {
  // Add user to the database
  create_post($db_connection,$userID,$_POST["title"],$_POST["content"]);
}

?>
<div class="content">
	<?php
    	  if ($errors) {
       		 foreach($errors as $error) {
        	 	 echo "<div class=\"error_msg\">*".$error."</div>\n";
      	 	 }
    	  }
   	 ?>
	<form method="post" action="form.php">
		<label for="issue">Post your discussion here:</label></br>
	  <input type="text" class="title" name="title" placeholder="Discussion Topic" value="<?=$_POST["title"];?>">
	  <input style="height:200px; width: 600px; font-size:10pt;" type="text" size="500" id="content" name="content" placeholder="Write your answer of enquiry here..." value="<?=$_POST["content"];?>" required = "required"></br>

		<button type= "submit">Submit</button>
		<input type= "reset" value="Reset Form"/>

	</form>
</div>
