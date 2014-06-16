angular.module('app').factory('AdvertisementService', ['$rootScope', '$q', '$resource', function($rootScope, $q, $resource) {
    return {
        getAdvertisements: function(params){
            var api = $resource('/api/advertisements/list');
            var deferred = $q.defer();
            var r = api.get(params, function(){
                deferred.resolve(r.data);
            }, function(error){
                deferred.reject(error);
            });
            return deferred.promise;
        },
        getAdvertisementsMap: function(params){
            var api = $resource('/api/advertisements/map');
            var deferred = $q.defer();
            var r = api.get(params, function(){
                deferred.resolve(r.data);
            }, function(error){
                deferred.reject(error);
            });
            return deferred.promise;
        }

    };
}]);