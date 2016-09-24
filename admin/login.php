<?php
require_once 'php/admin.php';
loginCheck(true);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Oxymora | Login</title>
    <script src="js/jquery-3.1.1.min.js" charset="utf-8"></script>
  </head>
  <body>
    <div id="errorbox">

    </div>

    <!-- Login Form -->
    <form id="loginForm" action="index.html" method="post" onsubmit="return login();">
      <input id="userInput" type="text" name="name" value="" required>
      <input id="passInput" type="password" name="name" value="" required>
      <button id="submitButton" type="submit" name="button">Login</button>
    </form>

    <!-- Scripts -->
    <script src="js/login.js" charset="utf-8"></script>

  </body>
</html>
