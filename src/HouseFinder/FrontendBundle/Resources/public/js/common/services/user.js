angular.module('app').factory('UserService', ['$rootScope', function($rootScope) {
    return {
        name : 'anonymous',
        currency: 'usd',
        location: null,

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
        },

        initLocation: function(){
            var self = this;
            if (Modernizr.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position){
                    self.location = {};
                    self.location.latitude = position.coords.latitude;
                    self.location.longitude = position.coords.longitude;
                    $rootScope.$broadcast('userCurrentLocation', self.location);
                });
            }
        }
    };
}]);