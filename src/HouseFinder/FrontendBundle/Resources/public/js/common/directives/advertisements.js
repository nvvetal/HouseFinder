angular.module('app').directive('appAdvertisements', ['$route', '$rootScope', function ($route, $rootScope) {
    return {
        restrict: "E",
        replace: true,
        scope: {

        },
        controller: ['$scope', 'AdvertisementService', function($scope, AdvertisementService){

            $scope.$on('searchComplete', function(e, args){
                $scope.advertisements = args.data;
                $scope.advertisementsAvailable = true;
                $scope.advertisementsPages = $scope.advertisements.pages;
            });

            $scope.$on('searchPageChange', function(e, args){
                $scope.advertisementPage(args.page);
            });

            $scope.advertisementPage = function(page){
                $scope.advertisementCurrentPage = page;
                $('#advertisement-pager li').each(function(index){
                    $('#advertisement-pager li').eq(index).removeClass('active');
                });
                $('#advertisement-pager li').eq(page).addClass('active');
                $rootScope.$broadcast('searchPage', {'page': page});
                $scope.page = page;
            };

            $scope.showBigImage = function(event){
                var el = event.target;
                var url = $(el).data('big-image');
                $('#advertisement-big-image').html('<img src="'+url+'" alt=""/>')
                $('#advertisement-big-image').show();
                $('#advertisement-big-image').css({'left': $(el).offset().left + $(el).width() + 50});
            };

            $scope.hideBigImage = function(){
                $('#advertisement-big-image').hide();
            };
        }],


        templateUrl: 'advertisements.html',
        link: function (scope, element, attrs) {
            scope.advertisements = [];
            scope.advertisementsAvailable = false;
            scope.advertisementCurrentPage = 0;
            scope.advertisementsPages = 0;
            scope.page = 0;
        }
    }
}]);
