<?php
use KFall\oxymora\memberSystem\MemberSystem;
require_once 'php/admin.php';
loginCheck();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Oxymora | Dashboard</title>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/master.css">
  <link rel="stylesheet" href="css/content.css">
  <link rel="stylesheet" href="css/menuToggle.css">
  <script src="js/jquery-3.1.1.min.js" charset="utf-8"></script>
</head>
<body>

  <!-- ======================================================== -->
  <!--                     HEADER                               -->
  <!-- ======================================================== -->
  <div id="header">
    <div class="container">
      <!-- Menu Toggle -->
      <div id="menuToggle">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <!-- Oxymora Logo -->
      <div class="logo">Oxymora</div>
    </div>
  </div>

  <!-- ======================================================== -->
  <!--                   SIDE MENU                              -->
  <!-- ======================================================== -->
  <div id="sidemenu" class="">
    <div class="userinfo">
      <div class="image">
        <i class="fa fa-user" aria-hidden="true"></i>
      </div>
      <div class="name">
        <?php echo MemberSystem::init()->member->username; ?>
      </div>


    </div>
    <ul>
      <li><a onclick="loadPage('dashboard')"  href="#dashboard"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
      <li><a onclick="loadPage('pages')"      href="#pages"><i class="fa fa-th-list" aria-hidden="true"></i> Pages</a></li>
      <li><a onclick="loadPage('member')"     href="#member"><i class="fa fa-users" aria-hidden="true"></i> Member</a></li>
      <li><a onclick="loadPage('settings')"   href="#settings"><i class="fa fa-cogs" aria-hidden="true"></i> Settings</a></li>
      <li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
    </ul>
  </div>

  <!-- ======================================================== -->
  <!--                     CONTENT                              -->
  <!-- ======================================================== -->

  <div id="content">

  </div>



  <!-- ======================================================== -->
  <!--                    SCRIPTS                               -->
  <!-- ======================================================== -->
  <script src="js/define.js" charset="utf-8"></script>
  <script src="js/interface.js" charset="utf-8"></script>
  <script src="js/master.js" charset="utf-8"></script>
</body>
</html>
