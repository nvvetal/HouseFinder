angular.module('app').factory('AddressService', ['$rootScope', '$q', '$resource', function($rootScope, $q, $resource) {
    return {
        getCity: function(name){
            var api = $resource('/api/address/city/name/:name', {'name': name});
            var deferred = $q.defer();
            var r = api.get({}, function(){
                deferred.resolve(r.data);
            }, function(error){
                deferred.reject(error);
            });
            return deferred.promise;
        },

        getCityNear: function(lat, long){
            var api = $resource('/api/address/city/near/lat/:lat/long/:long', {'lat': lat, 'long': long});
            var deferred = $q.defer();
            var r = api.get({}, function(){
                deferred.resolve(r.data);
            }, function(error){
                deferred.reject(error);
            });
            return deferred.promise;
        },

        getCities: function(params){
            var api = $resource('/api/address/cities');
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