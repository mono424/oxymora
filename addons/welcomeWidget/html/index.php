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
    </div>
  </body>
</html>
