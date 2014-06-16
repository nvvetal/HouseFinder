angular.module('app').directive('appSearchFilter', ['$route', '$rootScope', 'UserService', 'AddressService', function ($route, $rootScope, UserService, AddressService) {
    return {
        restrict: "E",
        replace: true,
        scope: {

        },
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

            $scope.searchFilterTypeChange = function(typeFilter){
                $rootScope.$broadcast('searchFilterTypeChange', {'adType': typeFilter.value});
                return true;
            }


            $scope.$on('userCurrentLocation', function(e, args){
                AddressService.getCityNear(args.latitude, args.longitude).then(function(city){
                    for(var i = 0; i < $scope.cities.length; i++){
                        if(city.locality != $scope.cities[i].label) continue;
                        $scope.searchFilterCity = $scope.cities[i];
                        //TODO: call ngchange???
                        $scope.searchFilterCityChange($scope.cities[i]);
                        break;
                    }
                });
            });

        }],
        templateUrl: 'searchFilter.html',
        link: function (scope, element, attrs) {
            scope.cities = [];
            AddressService.getCities().then(function(cities){
                for(var i in cities){
                    scope.cities.push({'label': cities[i].locality});
                }
            });
            scope.periods = [
                {label: 'За неделю', value: 'week'},
                {label: 'За месяц', value: 'month'}
            ];
            scope.searchFilterPeriod = scope.periods[0];
            scope.types = [
                {label: 'Buy', value: 'sell'},
                {label: 'Rent', value: 'rent'}
            ];
            scope.searchFilterType = scope.types[0];
            //$rootScope.$broadcast('searchFilterTypeChange', {'adType': scope.types[0].value});
        }
    }
}]);
