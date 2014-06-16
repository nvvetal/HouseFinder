angular.module('app').directive('appSearchMain', ['$route', '$rootScope', 'UserService', function ($route, $rootScope, UserService) {
    return {
        restrict: "E",
        replace: true,
        transclude: true,
        scope: {
            ctrlAdType: '&onAdtype',
            ctrlPeriod: '&onPeriod',
            ctrlCity: '&onCity',
            ctrlPage: '&onPage'
        },
        controller: ['$scope', 'AdvertisementService', function($scope, AdvertisementService){
            $scope.$on('userCurrencyChange', function(e, args){
                $scope.currencyShort = UserService.getCurrencyShort();
            });
            $scope.search = function(){
                $rootScope.$broadcast('searchPageChange', {'page': 0});
            }
        }],
        templateUrl: 'searchMain.html',
        link: function (scope, element, attrs, appSearchCtrl) {
            scope.currencyShort = UserService.getCurrencyShort();
            scope.types = [
                {name: 'All', value: 'all'},
                {name: 'Brick', value: 'brick'},
                {name: 'Panel', value: 'panel'},
                {name: 'Monolith', value: 'monolith'},
                {name: 'Block', value: 'block'},
                {name: 'wood', value: 'wood'}
            ];

        }
    }
}]);
