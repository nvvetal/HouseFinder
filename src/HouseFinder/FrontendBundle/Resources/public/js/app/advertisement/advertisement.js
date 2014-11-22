angular.module('app.advertisement', [])
    .controller('AdvertisementCtrl', ['$scope', 'advertisement', function ($scope, advertisement) {
        $scope.loaded = true;
        $scope.advertisement = advertisement;
    }]);
