<div class="footer_box">
  <div class="footer_content">
    <div class="">
      @technology enquiry project G1
    </div>
    <div class="">
      <?php
        if ($loggedIn) echo "Welcome ".$_SESSION['username'];
        else echo "Not Logged in";
      ?>
    </div>
  </div>
</div>
</body>
</html>
