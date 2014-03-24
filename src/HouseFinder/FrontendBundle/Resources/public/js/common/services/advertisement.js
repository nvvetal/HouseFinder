angular.module('app').factory('AdvertisementService', ['$rootScope', '$q', '$resource', function($rootScope, $q, $resource) {
    return {
        getAdvertisements: function(params){
            var Advertisement = $resource('/api/advertisements');
            var deferred = $q.defer();
            Advertisement.get(params, function(data){
                deferred.resolve(data);
            });
            return deferred.promise;
        }
    };
}]);