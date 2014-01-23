angular.module('app').factory('UserService', ['$rootScope', function($rootScope) {
    return {
        name : 'anonymous',
        currency: 'usd',

        setCurrency: function(currency){
            this.currency = currency;
            $rootScope.$broadcast('userCurrencyChange', {'currency': currency});
        },

        getCurrency: function(){
            return this.currency;
        },

        getCurrencyShort: function(){
            switch(this.getCurrency()){
                case 'usd':
                    return '$';
                case 'uah':
                    return '₴';
                case 'eur':
                    return '€';
            }
            return '';
        }
    };
}]);