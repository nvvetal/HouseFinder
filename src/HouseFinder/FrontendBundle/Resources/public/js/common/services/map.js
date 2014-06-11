angular.module('app').factory('MapService', ['$rootScope', function($rootScope) {
    return {
        map: null,
        mapZoom: 14,
        show: function(id, lat, long){
            if(this.map == null) {
                this.init(id, lat, long);
            }else{
                this.map.setView([lat, long], this.mapZoom);
            }
        },

        init: function(id, lat, long){
            this.map = L.map(id).setView([lat, long], this.mapZoom);
            L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                maxZoom: 18
            }).addTo(this.map);
        }
    };
}]);