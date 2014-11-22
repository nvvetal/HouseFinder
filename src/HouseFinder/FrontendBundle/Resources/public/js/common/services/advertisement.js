angular.module('app').factory('AdvertisementService', ['$rootScope', '$q', '$resource', function($rootScope, $q, $resource) {
    return {
        getAdvertisements: function(params){
            var api = $resource('/api/advertisement/list');
            var deferred = $q.defer();
            var r = api.get(params, function(){
                deferred.resolve(r.data);
            }, function(error){
                deferred.reject(error);
            });
            return deferred.promise;
        },
        getAdvertisementsMap: function(params){
            var api = $resource('/api/advertisement/map');
            var deferred = $q.defer();
            var r = api.get(params, function(){
                deferred.resolve(r.data);
            }, function(error){
                deferred.reject(error);
            });
            return deferred.promise;
        },
        getAdvertisement: function(id){
            var api = $resource('/api/advertisement/:id', {'id': id});
            var deferred = $q.defer();
            var r = api.get({}, function(){
                deferred.resolve(r.data);
            }, function(error){
                deferred.reject(error);
            });
            return deferred.promise;
        }
    };
}]);