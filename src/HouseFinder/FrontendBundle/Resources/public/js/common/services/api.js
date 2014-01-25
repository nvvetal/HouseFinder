angular.module('app').factory('ApiService', ['$rootScope', '$http', function($rootScope, $http) {
    return {
        url: '/api/',
        get: function (service, method, successCallback, errorCallback){
            var requestURL = this.url+service+'/'+method;
            $http({method: 'GET', url: requestURL}).
                success(successCallback).
                error(errorCallback);
        }
    };
}]);