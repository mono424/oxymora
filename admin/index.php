<?php
use KFall\oxymora\memberSystem\MemberSystem;
use KFall\oxymora\addons\AddonManager;
use KFall\oxymora\permissions\UserPermissionSystem;
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
  <title>Oxymora | Dashboard</title>
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/master.css">
  <link rel="stylesheet" href="css/content.css">
  <link rel="stylesheet" href="css/menuToggle.css">
  <script src="js/lib/jquery-3.1.1.min.js" charset="utf-8"></script>
  <?php
  if(isset($_GET['p'])){
    ?>
    <script type="text/javascript">
    let START_PAGE = '<?php echo htmlspecialchars($_GET['p']); ?>';
    </script>
    <?php
  }
  ?>
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
        <li><a class="nav" onclick="event.preventDefault();loadPage('files')" href="#"><i class="fa fa-archive" aria-hidden="true"></i> File-Manager</a></li>
        <li><a class="nav" onclick="event.preventDefault();loadPage('member')" href="#"><i class="fa fa-users" aria-hidden="true"></i> Member</a></li>
        <li><a class="nav" onclick="event.preventDefault();loadPage('addons')" href="#"><i class="fa fa-puzzle-piece" aria-hidden="true"></i> Addon-Manager</a></li>
        <li><a class="nav" onclick="event.preventDefault();loadPage('settings')" href="#"><i class="fa fa-cogs" aria-hidden="true"></i> Settings</a></li>
        <li id="addonTopic" style="display:none;" class="topic">Addons</li>
        <li class="topic">Other</li>
        <li><a href="../" target="_blank"><i class="fa fa-external-link" aria-hidden="true"></i> Open Website</a></li>
        <li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></li>
      </ul>
      <footer>Oxymora v<?php echo OXY_VERSION; ?> Development Edition</footer>
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
  <!-- SETUP -->
  <script src="js/other/browserOld.js" charset="utf-8"></script>
  <script src="js/master/define.js" charset="utf-8"></script>
  <!-- Greensock Animation -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js"></script>
  <!-- PRELOADER -->
  <!-- <script src="https://code.createjs.com/createjs-2015.11.26.min.js"></script> -->
  <!-- <script src="js/other/preloader.js"></script> -->
  <!-- <script src="js/other/oxymora_zahnrad_short.js"></script> -->
  <!-- OTHER -->
  <script src="js/master/functions.js" charset="utf-8"></script>
  <script src="js/master/master.js" charset="utf-8"></script>
  <!-- DASHBOARD -->
  <script src="js/pages/dashboard.js" charset="utf-8"></script>
  <!-- PAGES -->
  <script src="js/pages/pageEditor.js" charset="utf-8"></script>
  <!-- FILES -->
  <script src="js/pages/fileManager.js" charset="utf-8"></script>
  <!-- MEMBER -->
  <script src="js/pages/memberManager.js" charset="utf-8"></script>
  <!-- ADDONS -->
  <script src="js/pages/addonManager.js" charset="utf-8"></script>
  <!-- COMPONENTS -->
  <script src="js/components/fileSelector.js" charset="utf-8"></script>
</body>
</html>
