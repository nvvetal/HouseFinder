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
    controller('WelcomeCtrl', ['$scope', 'UserService', 'MapService', 'AddressService', function ($scope, UserService, MapService, AddressService) {
        $scope.loaded = true;
        UserService.initLocation();

        $scope.$on('searchFilterCityChange', function(e, dt){
            AddressService.getCity(dt.cityId).then(function(city){
                MapService.show('map', city.latitude, city.longitude);

            });
        });
    }]);
