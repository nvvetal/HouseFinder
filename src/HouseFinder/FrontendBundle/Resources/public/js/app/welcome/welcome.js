angular.module('app.welcome', [])
    .config(['$routeProvider', function ($routeProvider) {
        $routeProvider.when('/welcome', {
            templateUrl: 'app/welcome/welcome.html',
            controller: 'WelcomeCtrl',
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
    controller('WelcomeCtrl', [
        '$scope',
        function ($scope) {
            $scope.loaded = true;
        }]);
