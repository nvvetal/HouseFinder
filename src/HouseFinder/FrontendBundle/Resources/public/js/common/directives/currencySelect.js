angular.module('app').directive('appCurrencySelect', function(){
    return {
        restrict: 'E',
        replace: true,
        scope: {},
        templateUrl: 'currencySelect.html',
        controller: ['$scope', '$rootScope', 'UserService', function($scope, $rootScope, UserService){
            $scope.changeCurrency = function(currency){
                UserService.setCurrency(currency);
            }
            $scope.fillActive = function(currency){
                if(UserService.getCurrency() == currency) return 'active';
                return '';
            }
        }],
        link: function (scope, element, attrs) {
            scope.currencies = [
                {name: 'USD', value: 'usd'},
                {name: 'EUR', value: 'eur'},
                {name: 'UAH', value: 'uah'}
            ];
        }
    }
})