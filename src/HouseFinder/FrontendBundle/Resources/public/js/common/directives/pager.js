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

            $scope.calcPrevNext = function (page, count){
                $scope.prevPage = page > 0 ? page-1 : 0;
                $scope.nextPage = page < count - 1 ? page+1 : count - 1;
            }

            $scope.getPagesRange = function(){
                var maxShowPages = 10;
                var range = [];
                var from = 0;
                var to = $scope.pages > maxShowPages ? maxShowPages : $scope.pages;

                if($scope.currentPage == $scope.pages - 1){
                    from = $scope.pages - maxShowPages - 1;
                    to = $scope.pages;
                }else if($scope.currentPage + 1 > maxShowPages - 1){
                    from = $scope.currentPage - parseInt(maxShowPages / 2);
                    to = $scope.pages > from + maxShowPages ? from + maxShowPages : $scope.pages;
                }
                for (var i = from; i < to; i++)
                    range.push(i);
                return range;
            }

        }],
        link: function (scope, element, attrs) {
            scope.pagesAvailable = false;
            scope.currentPage = 0;
            scope.prevPage = 0;
            scope.nextPage = 0;
            scope.$watch('page', function(newValue, oldValue) {
                scope.currentPage = parseInt(newValue);
                scope.calcPrevNext(scope.currentPage, scope.pages);
            });
            scope.$watch('pagesMax', function(newValue, oldValue) {
                scope.refreshPages(parseInt(newValue));
                scope.calcPrevNext(scope.currentPage, scope.pages);
            });
        }
    }
})