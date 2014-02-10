angular.module('app').factory('AdvertisementService', ['$rootScope', '$resource', function($rootScope, $resource) {
    return {
        getAdvertisements: function(params){
            params = params || {};
            params.hoho = 'hmmm';
            var Advertisements = $resource('/api/advertisements', {},{
                search: { method: "POST", isArray: true}
            });
            var zzz = Advertisements.search(params);
            if(zzz.$resolved) console.log(zzz);
        }
    };
}]);