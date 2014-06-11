angular.module('app').directive('appSearchFilter', ['$route', '$rootScope', 'UserService', function ($route, $rootScope, UserService) {
    return {
        restrict: "E",
        replace: true,
        scope: {},

        controller: ['$scope', 'AdvertisementService', 'AddressService', function($scope, AdvertisementService, AddressService){
            $scope.searchFilterCityChange = function(cityFilter){
                if(cityFilter === null) return false;
                $rootScope.$broadcast('searchFilterCityChange', {'cityId': cityFilter.label});
                return true;
            }

            $scope.searchFilterPeriodChange = function(periodFilter){
                $rootScope.$broadcast('searchFilterPeriodChange', {'period': periodFilter.value});
                return true;
            }

            $scope.$on('userCurrentLocation', function(e, args){
                AddressService.getCityNear(args.latitude, args.longitude).then(function(city){
                    $scope.searchFilterCity = $scope.cities[0];
                    $scope.searchFilterCityChange($scope.cities[0]);
                });
            });

        }],
        templateUrl: 'searchFilter.html',
        link: function (scope, element, attrs) {
            //TODO: select user city to searchFilterCity if know
            scope.cities = [
                {label: 'Житомир'}
            ];
            scope.periods = [
                {label: 'За неделю', value: 'week'},
                {label: 'За месяц', value: 'month'}
            ];
            scope.searchFilterPeriod = scope.periods[0];
        }
    }
}]);
