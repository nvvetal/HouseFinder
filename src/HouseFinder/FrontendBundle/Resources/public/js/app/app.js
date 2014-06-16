(function () {
    angular.module('app', [
        'ngResource',
        'ngRoute',
        'ui.router',
        'app.notfound',
        'app.welcome',
        'app.advertisement'
    ]);

    angular.module('app')
        .config([
            '$routeProvider',
            '$locationProvider',
            '$stateProvider',
            '$urlRouterProvider',
            function ($routeProvider, $locationProvider, $stateProvider, $urlRouterProvider) {
                $locationProvider.html5Mode(true);

                $urlRouterProvider.otherwise('notfound');

                $stateProvider
                    .state('welcome', {
                        url: "/",
                        templateUrl: 'app/welcome/welcome.html',
                        controller: 'WelcomeCtrl',
                        data: {
                            headerType: 'default',
                            footerType: 'default'
                        }
                    })
                    .state('advertisement', {
                        url: "/advertisement/:id",
                        templateUrl: 'app/advertisement/advertisement.html',
                        controller: 'AdvertisementCtrl',
                        data: {
                            headerType: 'default',
                            footerType: 'default'
                        }
                    }).state('notfound', {
                        url: "/notfound",
                        templateUrl: 'app/notfound/notfound.html',
                        controller: 'NotfoundCtrl',
                        data: {
                            headerType: 'default',
                            footerType: 'default'
                        }
                    });
            }])
        .run(['$injector', function ($injector) {
            var $rootScope = $injector.get('$rootScope');
        }]);
})();
