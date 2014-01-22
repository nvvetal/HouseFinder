angular.module('app.notfound', [])
    .config(['$routeProvider', function ($routeProvider) {
        $routeProvider.when('/notfound', {
            templateUrl: 'app/notfound/notfound.html',
            controller: 'NotfoundCtrl',
            resolve: {
                headerType: function () {
                    return 'default';
                },
                footerType: function () {
                    return 'default';
                }
            }
        });
    }]).
    controller('NotfoundCtrl', ['$scope', function ($scope) {
        $scope.loaded = true;
    }]);
