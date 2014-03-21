angular.module('app').factory('AdvertisementService', ['$rootScope', '$resource', function($rootScope, $resource) {
    return {
        getAdvertisements: function(params){
            params = params || {};
            var Advertisement = $resource('/api/advertisements', {}, {
                search: {method: 'GET', isArray: true}
            });
            var Advertisements = Advertisement.search(params, function(){
                for(var i = 0; i <= Advertisements.length; i++){
                    var ad = Advertisements[i];
                }
                //console.log(Advertisements);
            });
        }
    };
}]);