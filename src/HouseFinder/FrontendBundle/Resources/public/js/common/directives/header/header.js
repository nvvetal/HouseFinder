angular.module('app').directive('appHeader', ['$state', function ($state) {
    return {
        restrict: "E",
        replace: true,
        scope: {},
        templateUrl: 'header.html',
        link: function (scope, element, attrs) {
            scope.val = 'from header';
            scope.headerType = $state.current.data.headerType || 'default';
        }
    }
}]);
