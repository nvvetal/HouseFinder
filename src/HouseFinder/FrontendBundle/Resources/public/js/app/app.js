(function () {
    angular.module('app', [
        'ngResource',
        'ngRoute',
        'app.notfound',
        'app.welcome'
    ]);

    angular.module('app')
        .config([
            '$routeProvider',
            '$locationProvider',
            function ($routeProvider, $locationProvider) {
                $locationProvider.html5Mode(true);
                $routeProvider.when('/', {templateUrl: 'app/welcome/welcome.html', controller: 'WelcomeCtrl'})
                    .otherwise({redirectTo: '/notfound'});
            }])
        .run(['$injector', function ($injector) {
            var $rootScope = $injector.get('$rootScope');
        }]);
})();
