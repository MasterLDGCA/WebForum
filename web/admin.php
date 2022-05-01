<?php
// Author: Charith Akalanka
// Description: Administrator control panel

define( 'PAGE', 'Admin' );
define( 'PAGE_TITLE', 'Admin');

require 'inc/header.inc.php';
require 'inc/functions.inc.php';
requireLogin();

if ( !$isAdmin ) {
  header( 'location: /index.php' );
  exit();
}

if ($_POST["revoke_admin"]) {
  revoke_admin($db_connection, $_POST["revoke_admin"]);
}

if ($_POST["make_admin"]) {
  make_admin($db_connection, $_POST["make_admin"]);
}

if ($_POST["revoke_approval"]) {
  revoke_approval($db_connection, $_POST["revoke_approval"]);
}

if ($_POST["grant_approval"]) {
  grant_approval($db_connection, $_POST["grant_approval"]);
}

// Retrieve user details
$users = [];
$stmt = "select * from \"Users\" u order by created_at desc";
$check = pg_query($db_connection, $stmt);
while ($user = pg_fetch_row($check)) {
  $users[$user[0]] = $user;
}

?>
<div class="content">
  <!-- Tab links -->
  <div class="tab">
    <button class="tablinks" onclick="openTab(event, 'Tab1')" id="defaultOpen">Manage Users</button>
    <button class="tablinks" onclick="openTab(event, 'Tab2')">Tab2</button>
    <button class="tablinks" onclick="openTab(event, 'Tab3')">Tab3</button>
  </div>

  <!-- Tab content -->
  <div id="Tab1" class="tabcontent">
     <h3>Manage Users</h3>
     <p>Following table presents all the registered users in the descending order of the registered date</p>
     <table class="user_table">
       <tr>
         <th>ID</th>
         <th>Registered on</th>
         <th>First Name</th>
         <th>Last Name</th>
         <th>email</th>
         <th>Administrator</th>
         <th>Approved</th>
       </tr>
       <?php
       foreach($users as $user) {
         $style = "";

         if ($user[6]==="t") {
           // Is an admin
           $style = "style=\"background:red; font-weight:bold; color:white;\"";
         }

         if ($user[7]==="f") {
           $style = "style=\"background:white; color:gray;\"";
         }

         if ($user[6]==="t" && $user[7]==="f") {
           $style = "style=\"background:pink; font-weight:bold; color:gray;\"";
         }

         echo "<tr>";
         echo " <td ".$style.">".$user[0]."</td>";
         echo " <td ".$style.">".date('Y-m-d H:i:s',strtotime($user[1]))."</td>";
         echo " <td ".$style.">".$user[2]."</td>";
         echo " <td ".$style.">".$user[3]."</td>";
         echo " <td ".$style.">".$user[4]."</td>";

         if ($_SESSION["user_id"] === $user[0]) {
           // Meddling with your own admin status is not allowed
           echo ($user[6]==="t") ? " <td ".$style.">Yes</td>" : " <td ".$style.">No</td>";
         } else {
           echo ($user[6]==="t") ? " <td ".$style.">Yes <form method=\"POST\" action=\"admin.php\">
                                                <input type=\"hidden\" name=\"revoke_admin\" value=\"".$user[0]."\">
                                                <button type=\"submit\" class=\"like_button\">Revoke</button>
                                              </form>
                                              </td>" :
                                    " <td ".$style.">No <form method=\"POST\" action=\"admin.php\">
                                               <input type=\"hidden\" name=\"make_admin\" value=\"".$user[0]."\">
                                               <button type=\"submit\" class=\"like_button\">Grant</button>
                                             </form>
                                             </td>";
         }

         if ($_SESSION["user_id"] === $user[0]) {
           // Meddling with your own approval status is not allowed
           echo ($user[7]==="t") ? " <td ".$style.">Yes</td>" : " <td ".$style.">No</td>";
         } else {
           echo ($user[7]==="t") ? " <td ".$style.">Yes <form method=\"POST\" action=\"admin.php\">
                                                <input type=\"hidden\" name=\"revoke_approval\" value=\"".$user[0]."\">
                                                <button type=\"submit\" class=\"like_button\">Revoke</button>
                                              </form>
                                              </td>" :
                                    " <td ".$style.">No <form method=\"POST\" action=\"admin.php\">
                                               <input type=\"hidden\" name=\"grant_approval\" value=\"".$user[0]."\">
                                               <button type=\"submit\" class=\"like_button\">Grant</button>
                                             </form>
                                             </td>";
         }

         echo "</tr>";

       }
       ?>
     </table>
  </div>

  <div id="Tab2" class="tabcontent">
     <h3>Tab2</h3>
     <p>Paris is the capital of France.</p>
  </div>

  <div id="Tab3" class="tabcontent">
     <h3>Tab3</h3>
     <p>Tokyo is the capital of Japan.</p>
  </div>
</div>

<script type="text/javascript">
  function openTab(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
  }
</script>

<script>
  // Get the element with id="defaultOpen" and click on it
  document.getElementById("defaultOpen").click();
</script>

<?php
require 'inc/footer.inc.php'
?>
