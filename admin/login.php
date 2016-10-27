<?php
require_once 'php/admin.php';
loginCheck(true);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <title>Oxymora | Login</title>
  <link rel="stylesheet" href="css/login.css" media="screen">
  <script src="js/lib/jquery-3.1.1.min.js" charset="utf-8"></script>
</head>
<body>

  <!-- Background Container -->
  <div class="background"></div>

  <!-- Wrapper -->
  <div class="wrapper">
    <div class="login_container">

      <!-- Header -->
      <object width="50px" height="50px" class="logo" data="img/oxy.svg" type="image/svg+xml">
        <p>It would look nice, but your browser denies is! Want a nice look? Get newest Chrome Browser ;)</p>
      </object>
      <h2>Oxymora</h2>

      <!-- Error Box -->
      <div id="errorbox"></div>

      <!-- Error Box -->
      <div id="successbox"></div>

      <!-- Login Form -->
      <form id="loginForm" action="" method="post" onsubmit="return login();">
        <input id="userInput" type="text" name="name" value="" required>
        <input id="passInput" type="password" name="name" value="" required>
        <button id="submitButton" type="submit" name="button">Login</button>
      </form>

    </div>
  </div>

  <!-- Scripts -->
  <script src="js/pages/login.js" charset="utf-8"></script>

</body>
</html>
