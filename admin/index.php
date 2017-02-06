<?php
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\permissions\UserPermissionSystem;
define('WEB_REL_ROOT', '../');
require_once 'php/admin.php';
loginCheck();
AddonManager::triggerEvent(ADDON_EVENT_OPEN, null);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
  <link href="img/icns/192.png" rel="apple-touch-icon" sizes="192x192" />
  <link href="img/icns/192.png" rel="icon" sizes="192x192" />

  <title>Oxymora | Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/dist/css/dashboard.min.css" media="screen">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js" integrity="sha256-eVNjHw5UeU0jUqPPpZHAkU1z4U+QFBBY488WvueTm88=" crossorigin="anonymous"></script>
  <script type="text/javascript">
  let ROOT_DIR = '<?php echo dirname($_SERVER['PHP_SELF']); ?>';
  <?php
  if(isset($_GET['p'])){
    ?>
    let START_PAGE = '<?php echo htmlspecialchars($_GET['p']); ?>';
    <?php
  }
  ?>
  </script>
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
    </div>
  </div>

  <!-- ======================================================== -->
  <!--                   SIDE MENU                              -->
  <!-- ======================================================== -->
  <div id="sidemenu" class="">
    <!-- Oxymora Logo -->
    <div class="headbar">Oxymora</div>
    <!-- Side Menu Container -->
    <div class="side-container">
      <!-- User Info -->
      <div class="userinfo">
        <div style="background-image: url(<?php echo MemberSystem::init()->member->image; ?>)" class="image"></div>
        <div class="name">
          <?php echo MemberSystem::init()->member->username; ?>
        </div>


      </div>
      <ul>
        <li class="topic">Management</li>
        <li><a class="nav active" onclick="event.preventDefault();loadPage('dashboard')"  href="#"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
        <li><a class="nav" onclick="event.preventDefault();loadPage('pages')" href="#"><i class="fa fa-th-list" aria-hidden="true"></i> Pages & Navigation</a></li>
        <li><a class="nav" onclick="event.preventDefault();loadPage('files')" href="#"><i class="fa fa-archive" aria-hidden="true"></i> Files</a></li>
        <li><a class="nav" onclick="event.preventDefault();loadPage('member')" href="#"><i class="fa fa-users" aria-hidden="true"></i> Member</a></li>
        <li><a class="nav" onclick="event.preventDefault();loadPage('addons')" href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> Addons</a></li>
        <li><a class="nav" onclick="event.preventDefault();loadPage('settings')" href="#"><i class="fa fa-cogs" aria-hidden="true"></i> Settings</a></li>
        <li id="addonTopic" style="display:none;" class="topic">Addons</li>
        <li class="topic">Other</li>
        <li><a href="../" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> Open Website</a></li>
        <li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
      </ul>
      <footer>Oxymora Version <?php echo OXY_VERSION[0].".".OXY_VERSION[1].".".OXY_VERSION[2].".".OXY_VERSION[3]; ?></footer>
    </div>
  </div>

  <!-- ======================================================== -->
  <!--                     CONTENT                              -->
  <!-- ======================================================== -->

  <div id="wrapper">


    <!-- ======================================================== -->
    <!--                    PRELOADER                             -->
    <!-- ======================================================== -->
    <div id="preloader">
      <img src="img/loading.gif" alt="">
      <!-- <canvas id="canvas" width="480" height="270" style="display: block; background-color:rgb(255, 255, 255)"></canvas> -->
    </div>


    <!-- ======================================================== -->
    <!--                    CONTENT BOX                           -->
    <!-- ======================================================== -->
    <div id="content"></div>


  </div>


  <!-- ======================================================== -->
  <!--                    LIGHTBOX                              -->
  <!-- ======================================================== -->
  <div id="lightbox">
    <div class="container">
      <div class="dialog">
        <div class="content"></div>
        <div class="footer">
          <button class="success">Ok</button>
          <button class="cancel">Cancel</button>
        </div>
      </div>
    </div>
  </div>


  <!-- ======================================================== -->
  <!--                   NOTIFICATIONS                          -->
  <!-- ======================================================== -->
  <div id="notify"></div>


  <!-- ======================================================== -->
  <!--                    FALLBACK                              -->
  <!-- ======================================================== -->
  <div id="fallback">
    <a href="https://www.google.de/chrome/browser/desktop/"><img src="img/browserOld.gif" alt="" /></a>
  </div>


  <!-- ======================================================== -->
  <!--                    SCRIPTS                               -->
  <!-- ======================================================== -->
  <script src="assets/dist/js/dashboard.min.js" charset="utf-8"></script>
</body>
</html>
