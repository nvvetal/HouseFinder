angular.module('app').directive('appHeader', [
    '$route',
    function ($route) {
        return {
            restrict: "E",
            replace: true,
            scope: {},
            templateUrl: 'header.html',
            link: function (scope, element, attrs) {
                scope.val = 'from header';
                if (attrs.forceType) {
                    scope.headerType = attrs.forceType;
                } else {
                    scope.headerType = ($route.current.locals || {}).headerType;
                    scope.$watch(function () {
                        return ($route.current.locals || {}).headerType;
                    }, function (value) {
                        switch (value) {
                            case 'default':
                            case 'login':
                                scope.headerType = value;
                                break;
                            default:
                                scope.headerType = 'default';
                        }
                    });
                }
            }
        }
    }]);
