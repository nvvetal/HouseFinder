angular.module('app').factory('MapService', ['$rootScope', function($rootScope) {
    return {
        map: null,
        markers: [],
        mapZoom: 14,
        show: function(id, lat, long){
            if(this.map == null) {
                this.init(id, lat, long);
            }else{
                this.map.setView([lat, long], this.mapZoom);
            }
        },

        init: function(id, lat, long){
            L.Icon.Default.imagePath = 'http://leafletjs.com/dist/images/';
            this.map = L.map(id).setView([lat, long], this.mapZoom);
            L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                maxZoom: 18
            }).addTo(this.map);
        },

        fitBounds: function(arrayOfLatLngs, options){
            var bounds = new L.LatLngBounds(arrayOfLatLngs);
            this.map.fitBounds(bounds, options);
        },

        setMaxBounds: function(arrayOfLatLngs){
            var bounds = new L.LatLngBounds(arrayOfLatLngs);
            this.map.setMaxBounds(bounds);
        },

        addMarker: function(LatLng, popup){
            var marker = L.marker(LatLng).addTo(this.map);
            marker.bindPopup(popup);
            this.markers.push(marker);
            return marker;
        },

        clearMarkers: function(){
            for(var i = 0; i < this.markers.length; i++){
                this.map.removeLayer(this.markers[i]);
            }
            this.markers = [];
        }
    };
}]);