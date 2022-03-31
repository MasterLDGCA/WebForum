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
  </head>
  <body>
    <div class="header">
      <div class="title">
        <h2>WebForum</h2>
      </div>
      <div class="menu">
        <ul class="nav-list">
          <li class="nav-item"><a href="/">Home</a></li>
          <?php
          if ($loggedIn) echo "<li class=\"nav-item\"><a href=\"#\">Profile</a></li>";
          else echo "<li class=\"nav-item\"><a href=\"/register.php\">Register</a></li>";
          ?>
          <?php
          if ($loggedIn) echo "<li class=\"nav-item\"><a href=\"/logout.php\">Logout</a></li>";
          else echo "<li class=\"nav-item\"><a href=\"/login.php\">Login</a></li>";
          ?>

        </ul>
      </div>
    </div>
