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
      <link rel="shortcut icon" href="images/trophy.png" />
      <link rel="stylesheet" href="css/main.css">
      <link rel="stylesheet" href="css/bootstrap.css" />
  		<script src="js/bootstrap.js"></script>
  		<script src="js/p5.js"></script>
      <script src="js/sketch.js"></script>
  </head>
  <body>
    <div class="header">
      <div class="menu_title">
        <a href="/"><img src="images/logo.png" alt="EduGame"></a>
      </div>
      <div>

  		</div>
      <div class="menu">
        <ul class="nav-list">
          <li class="nav-item"><a href="/">Home</a></li>
          <?php
          if ($isAdmin) echo "<li class=\"nav-item\"><a href=\"/admin.php\">Administration</a></li>\n";

          echo "<li class=\"nav-item\"><a href=\"/formpost.php\">Create Post</a></li>\n";

          if ($loggedIn) echo "<li class=\"nav-item\"><a href=\"/profile.php\">Profile</a></li>\n";
          else echo "<li class=\"nav-item\"><a href=\"/register.php\">Register</a></li>\n";

          if ($loggedIn) echo "<li class=\"nav-item\"><a href=\"/logout.php\">Logout</a></li>\n";
          else echo "<li class=\"nav-item\"><a href=\"/login.php\">Login</a></li>\n";
          ?>
        </ul>
      </div>
    </div>
