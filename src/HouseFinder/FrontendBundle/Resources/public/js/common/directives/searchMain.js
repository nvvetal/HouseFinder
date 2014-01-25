angular.module('app').directive('appSearchMain', ['$route', 'UserService', function ($route, UserService) {
    return {
        restrict: "E",
        replace: true,
        scope: {},

        controller: ['$scope', 'AdvertisementService', function($scope, AdvertisementService){
            $scope.search = function(){
                AdvertisementService.getAdvertisements();
            }
        }],
        templateUrl: 'searchMain.html',
        link: function (scope, element, attrs) {
            scope.currencyShort = UserService.getCurrencyShort();
            scope.types = [
                {name: 'All', value: 'all'},
                {name: 'Brick', value: 'brick'},
                {name: 'Panel', value: 'panel'},
                {name: 'Monolith', value: 'monolith'},
                {name: 'Block', value: 'block'},
                {name: 'wood', value: 'wood'}
            ];
            scope.$on('userCurrencyChange', function(args){
                scope.currencyShort = UserService.getCurrencyShort();
            });
        }
    }
}]);
