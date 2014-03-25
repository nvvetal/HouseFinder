angular.module('app').directive('appSearchFilter', ['$route', '$rootScope', 'UserService', function ($route, $rootScope, UserService) {
    return {
        restrict: "E",
        replace: true,
        scope: {},

        controller: ['$scope', 'AdvertisementService', function($scope, AdvertisementService){
            $scope.searchFilterCityChange = function(cityFilter){
                if(cityFilter === null) return false;
                $rootScope.$broadcast('searchFilterCityChange', {'cityId': cityFilter.label});
                return true;
            }

            $scope.searchFilterPeriodChange = function(periodFilter){
                $rootScope.$broadcast('searchFilterPeriodChange', {'period': periodFilter.value});
                return true;
            }
        }],
        templateUrl: 'searchFilter.html',
        link: function (scope, element, attrs) {
            scope.cities = [
                {label: 'Житомир', value: '1'}
            ];
            //TODO: select user city to searchFilterCity if know
            scope.periods = [
                {label: 'За неделю', value: 'week'},
                {label: 'За месяц', value: 'month'}
            ];
            scope.searchFilterPeriod = scope.periods[0];
        }
    }
}]);
