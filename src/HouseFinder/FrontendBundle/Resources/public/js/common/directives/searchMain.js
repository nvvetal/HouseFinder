angular.module('app').directive('appSearchMain', ['$route', function ($route) {
    return {
        restrict: "E",
        replace: true,
        scope: {},
        templateUrl: 'searchMain.html',
        link: function (scope, element, attrs) {
            scope.test = 'ololo';
        }
    }
}]);
