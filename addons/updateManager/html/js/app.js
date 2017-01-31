
let mod = angular.module("mainModule", ['angularFileUpload']);

mod.controller("mainController", ['$scope', 'FileUploader', function($scope, FileUploader) {

  $scope.builds = [];
  $scope.fullpack = 0;

  $scope.setupUploader = function(){

    $scope.uploader = new FileUploader({
      url: 'index.php'
    });

    $scope.uploader.filters.push({
      name: 'zipFilter',
      fn: function(item, options) {
        var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
        return '|x-zip-compressed|'.indexOf(type) !== -1;
      }
    });

    $scope.uploader.onWhenAddingFileFailed = function(item, filter, options) {
      alert('File is not ZIP');
    };
    $scope.uploader.onCompleteItem = function(fileItem, response, status, headers) {
      if(response == "1"){
        $scope.newVersion = "";
        $scope.newDescription = "";
        $scope.fullpack = 0;
      }else{
        alert(response);
      }
      $scope.setupUploader();
      $scope.update();

    };
    $scope.uploader.onBeforeUploadItem  = function (item) {
      item.formData.push({api: 'add'});
      item.formData.push({version: $scope.newVersion});
      item.formData.push({description: $scope.newDescription});
      item.formData.push({fullpack: $scope.fullpack});
    };

  };

  $scope.update = function(cb = null){
    $.post('index.php', {api:'get'}, function(data){
      $scope.builds = data;
      $scope.$apply();
      if(cb) cb(data);
    }, 'json');
  };

  $scope.toggleFullPack = function(){
    $scope.fullpack = ($scope.fullpack) ? 0 : 1;
  }

  $scope.kickit = function(){
    $scope.uploader.uploadAll();
  };

  $scope.setupUploader();
  $scope.update();
}]);
