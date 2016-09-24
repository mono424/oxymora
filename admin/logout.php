<?php
require_once 'php/admin.php';
use KFall\oxymora\memberSystem\MemberSystem;
MemberSystem::init()->logout();
loginCheck();
?>
