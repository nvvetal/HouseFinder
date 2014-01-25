angular.module('app').factory('AdvertisementService', ['$rootScope', 'ApiService', function($rootScope, ApiService) {
    return {
        getAdvertisements: function(params){
            params = params || {};
            ApiService.get('advertisement', 'advertisements',
                function(data, status, headers, config) {
                    console.log(1);
                },
                function(data, status, headers, config) {
                    console.log(2);
                }
            );
        }

    };
}]);