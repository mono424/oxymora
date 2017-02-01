<?php

// DIRS
$uploadDir = FILE_DIR."/_builds";

// TABLES
$table_builds = "oxymora_builds";

// THE API
if(isset($_POST['api'])){
  require __DIR__."/api/index.php";
  die();
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.1/angular.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-file-upload/2.4.1/angular-file-upload.min.js"></script>
</head>
<body>
  <div id="app" ng-app="mainModule" ng-controller="mainController">

    <div class="Upload New">
      <input type="text" ng-model="newVersion" placeholder="Version e.g. 1000">
      <textarea ng-model="newDescription" placeholder="Description e.g. Bugfix XY. HTML is allowed!">
      </textarea>
      <input type="file" nv-file-select="" uploader="uploader" />
      <span ng-class="{active: fullpack}" ng-click="toggleFullPack()" class="toggle"><i class="fa fa-square" aria-hidden="true"></i> Full Oxymora Package</span>
      <button type="button" ng-click="kickit()">Kick it!</button>
    </div>

    <div ng-if="newVersion || newDescription" class="item inactive">
      <h1><i class="fa fa-gift" aria-hidden="true"></i> Oxymora v{{ newVersion }}</h1>
      <h2>{{ uploader.progress }}% Uploading ...</h2>
      <p>{{ newDescription }}</p>
    </div>

    <div class="item" ng-repeat="build in builds">
      <h1><i class="fa fa-gift" aria-hidden="true"></i> Oxymora v{{ build.version }}</h1>
      <h2>Hash: {{ build.hash }}</h2>
      <h2>Size: {{ build.filesize / 1024 / 1024 | number }} MB</h2>
      <p>{{ build.description }}</p>
      <span class="delete" ng-click="delete(build.id)"><i class="fa fa-trash" aria-hidden="true"></i></span>
    </div>

  </div>
  <script src="js/app.js" charset="utf-8"></script>
</body>
</html>
