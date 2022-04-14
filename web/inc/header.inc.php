<?php
require 'inc/postgresql.inc.php';
require 'inc/session.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <meta name="author" content="Charith Akalanka">
      <title><?php if (defined("PAGE_TITLE")) { echo PAGE_TITLE . " | "; } ?>WebForum</title>
      <link rel="stylesheet" href="css/main.css">
      <link rel="stylesheet" href="css/bootstrap.css" />
  		<link rel="stylesheet" href="css/custom.css" />
  		<script src="js/bootstrap.js"></script>
  		<!-- <script src="js/p5.js"></script> -->
      <!-- <script src="js/sketch.js"></script> -->
  </head>
  <body>
    <div class="header">
      <div class="menu_title">
         <img src="images/logo.png" alt="EduGame">
      </div>
      <div>
  			<!-- LETS PUT CANVAS HERE -->
  			<!-- <p id="cancan"></p> -->
  		</div>
      <div class="menu">
        <ul class="nav-list">
          <li class="nav-item"><a href="/style1.html">Style1</a></li>
          <li class="nav-item"><a href="/style1.html">Style2</a></li>
          <li class="nav-item"><a href="/">Home</a></li>
          <?php
          if ($isAdmin) echo "<li class=\"nav-item\"><a href=\"/admin.php\">Administration</a></li>\n";

          if ($loggedIn) echo "<li class=\"nav-item\"><a href=\"/formpost.php\">Create Post</a></li>\n";

          if ($loggedIn) echo "<li class=\"nav-item\"><a href=\"/profile.php\">Profile</a></li>\n";
          else echo "<li class=\"nav-item\"><a href=\"/register.php\">Register</a></li>\n";

          if ($loggedIn) echo "<li class=\"nav-item\"><a href=\"/logout.php\">Logout</a></li>\n";
          else echo "<li class=\"nav-item\"><a href=\"/login.php\">Login</a></li>\n";
          ?>
        </ul>
      </div>
    </div>
