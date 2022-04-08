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
requireLogin();

?>

<form method="post" action="https://mercury.swin.edu.au/it000000/formtest.php">
	<label for="issue">Post your discussion here:</label></br>
  <input style="height:200px; width: 600px; font-size:10pt;" type="text" size="500" id="issue" name="issue" placeholder="Write your answer of enquiry here..." required = "required"></br>

	<input type= "submit" value="Submit"/>
	<input type= "reset" value="Reset Form"/>

</form>


<!--This week leaders and all time leaders

If you’d like to see a lot of user activity you might be tempted to give one point per post.
high quality of post by giving posts for positive reactions, favourite

a new member joins and sees that the top scores are totally
unattainable, they’re unlikely to want to try. Weekly or
monthly totals tend to provide more realistic goal states
for new members.

ex: vanilla forums
--->

<?php
require 'inc/footer.inc.php'
?>
