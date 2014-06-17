angular.module('app').directive('appPager', function(){
    return {
        restrict: 'E',
        replace: true,
        scope: {
            page: '@',
            pagesMax: '@',
            select: '&'
        },
        templateUrl: 'app/directives/pager.html',
        controller: ['$scope', function($scope){
            $scope.selected = function(page){
                return page == $scope.page ? true : false;
            }

            $scope.first = function(page){
                return page == 0 ? true : false;
            }

            $scope.last = function(page){
                return page == $scope.pagesMax - 1 ? true : false;
            }

            $scope.refreshPages = function(count){
                if(count <= 1) {
                    $scope.pagesAvailable = false;
                    return false;
                }
                $scope.pages = count;
                $scope.pagesAvailable = true;
                return true;
            }

        }],
        link: function (scope, element, attrs) {
            scope.pagesAvailable = false;
            scope.$watch('pagesMax', function(newValue, oldValue) {
                scope.refreshPages(newValue);
            });
        }
    }
})