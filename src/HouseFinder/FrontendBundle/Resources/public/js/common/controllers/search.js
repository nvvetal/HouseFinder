angular.module('app').controller("appSearchCtrl", ["$scope", "$rootScope", "$timeout", "AdvertisementService", function($scope, $rootScope, $timeout, AdvertisementService) {
    $scope.adType = 'sell';
    $scope.period = 'week';
    $scope.city = '';
    $scope.page = 0;

    $scope.getCtrlAdType = function(){
        return $scope.adType;
    };

    $scope.setCtrlAdType = function(adType){
        $scope.adType = adType;
    };

    $scope.getCtrlPeriod = function(){
        return $scope.period;
    };

    $scope.setCtrlPeriod = function(period){
        $scope.period = period;
    };

    $scope.getCtrlCity = function(){
        return $scope.city;
    };

    $scope.setCtrlCity = function(city){
        $scope.city = city;
    };

    $scope.getCtrlPage = function(){
        return $scope.page;
    };

    $scope.setCtrlPage = function(page){
        $scope.page = page;
    };

    $scope.$on('searchFilterTypeChange', function(e, args){
        $scope.setCtrlAdType(args.adType);
        $scope.setCtrlPage(0);
        $scope.search();
    });

    $scope.$on('searchFilterPeriodChange', function(e, args){
        $scope.setCtrlPeriod(args.period);
        $scope.setCtrlPage(0);
        $scope.search();
    });

    $scope.$on('searchFilterCityChange', function(e, args){
        $scope.setCtrlCity(args.cityId);
        $scope.setCtrlPage(0);
        $scope.search();
    });

    $scope.$on('searchPage', function(e, args){
        $scope.setCtrlPage(args.page);
        $scope.search();
    });

    $scope.$on('search', function(e, args){
        $scope.search();
    });

    $scope.search = function(){
        $timeout(function(){
            var params = $('#mainSearch').serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});
            AdvertisementService.getAdvertisements(params).then(function(data){
                $rootScope.$broadcast('searchComplete', {'data': data});
                AdvertisementService.getAdvertisementsMap({'ad_type': params.ad_type, 'period': params.period, 'city_id': params.city_id}).then(function(data){
                    $rootScope.$broadcast('searchAdvertisementMap', data);
                });
            });
        }, 50);
    }

}])
