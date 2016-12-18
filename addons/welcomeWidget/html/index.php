<?php
use KFall\oxymora\memberSystem\MemberSystem;
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="css/master.css">
  </head>
  <body>
    <div class="widget">
      <h1>Welcome <?php echo MemberSystem::init()->member->username; ?>!</h1>
      <p>This Widget does nothing but wishing you a great time with Oxymora!</p>
      <p>Just go to "Pages & Navigation" and setup your first Page.</p>
      <p>And feel free to enhance my functionality with addons :)</p>
      <p>To remove this quite useless addon, just click on remove right below it.</p>
    </div>
  </body>
</html>
