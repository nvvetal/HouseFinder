angular.module('app.welcome', [])
    .controller('WelcomeCtrl', ['$scope', 'UserService', 'MapService', 'AddressService', function ($scope, UserService, MapService, AddressService) {
        $scope.loaded = true;
        UserService.initLocation();

        $scope.$on('searchFilterCityChange', function(e, dt){
            AddressService.getCity(dt.cityId).then(function(city){
                MapService.show('map', city.latitude, city.longitude);
            });
        });

        $scope.$on('searchAdvertisementMap', function(e, data){
            if(data.items == undefined || data.items.length == 0) return false;
            MapService.clearMarkers();
            var bounds = [];
            for(var i in data.items){
                bounds.push([data.items[i].address.latitude, data.items[i].address.longitude])
                MapService.addMarker([data.items[i].address.latitude, data.items[i].address.longitude], data.items[i].address.street + ',' + data.items[i].address.streetNumber);
            }
            if(data.items.length > 3) MapService.setMaxBounds(bounds);
        });
    }]);
