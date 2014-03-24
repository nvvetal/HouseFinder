angular.module('app').directive('appSearchMain', ['$route', 'UserService', function ($route, UserService) {
    return {
        restrict: "E",
        replace: true,
        scope: {},

        controller: ['$scope', 'AdvertisementService', function($scope, AdvertisementService){
            $scope.search = function(){
                var params = {'data': $('#mainSearch').serialize()};
                var promise = AdvertisementService.getAdvertisements(params);
                promise.then(function(data){
                    $scope.advertisements = data;
                    $scope.advertisementsAvailable = true;
                });
            }

            $scope.advertisementPage = function(page){
                $scope.advertisementCurrentPage = page;
                $('#advertisement-pager li').each(function(index){
                    $(this).removeClass('active');
                });
                $('#advertisement-pager li').eq(page).addClass('active');
                $scope.search();
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
            scope.advertisements = [];
            scope.advertisementsAvailable = false;
            scope.advertisementCurrentPage = 1;
            scope.$on('userCurrencyChange', function(args){
                scope.currencyShort = UserService.getCurrencyShort();
            });
        }
    }
}]);
