
let mod = angular.module("mainModule", ['angularFileUpload']);

mod.controller("mainController", ['$scope', 'FileUploader', function($scope, FileUploader) {

  $scope.builds = [];

  let uploader = $scope.uploader = new FileUploader({
    url: 'index.php'
  });

  uploader.filters.push({
    name: 'zipFilter',
    fn: function(item, options) {
      var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
      return '|x-zip-compressed|'.indexOf(type) !== -1;
    }
  });

  uploader.onWhenAddingFileFailed = function(item, filter, options) {
    alert('File is not ZIP');
  };
  uploader.onCompleteItem = function(fileItem, response, status, headers) {
    if(response == "1"){
      $scope.newVersion = "";
      $scope.newDescription = "";
    }else{
        alert(response);
    }

    $scope.update();

  };

  uploader.onBeforeUploadItem  = function (item) {
    item.formData.push({api: 'add'});
    item.formData.push({version: $scope.newVersion});
    item.formData.push({description: $scope.newDescription});
  };

  $scope.update = function(cb = null){
    $.post('index.php', {api:'get'}, function(data){
      $scope.builds = data;
      $scope.$apply();
      if(cb) cb(data);
    }, 'json');
  };

  $scope.kickit = function(){
    uploader.uploadAll();
  };

  $scope.update();
}]);
