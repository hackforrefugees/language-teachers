(function() {

    var as = angular.module('myApp', ['myApp.services', 'myApp.directives', 'myApp.controllers', 'ngRoute']);

    as.value('version', '1.4.8');

    as.config(function($routeProvider, $httpProvider) {
        $routeProvider
                .when('/login', {
                    templateUrl: 'partials/login.html',
                    controller: 'LoginCtrl'
                })
                .when('/register', {
                    templateUrl: 'partials/register.html',
                    controller: 'RegisterCtrl',
                    resolve: {
                        languages: function(dataservice){
                            return dataservice.getLanguages().then(function (ref) {
                                return ref;
                            });
                        },
                        test: function(){
                            return "test";
                        }
                    }})
                .when('/new', {
                    templateUrl: 'partials/new.html',
                    controller: 'NewAlbumCtrl'})
                .when('/profile', {
                    templateUrl: 'partials/profile.html',
                    controller: 'ProfileCtrl',
                    resolve: {
                        user: function(dataservice){
                            return dataservice.profile().then(function(ref){
                                return ref;
                            });
                        }
                    }
                })
                .when('/album/:id', {
                    templateUrl: 'partials/album.html',
                    controller: 'AlbumCtrl'})
                .otherwise({redirectTo: '/'});


//        $httpProvider.defaults.useXDomain = true;
//        delete $httpProvider.defaults.headers.common["X-Requested-With"];
    });


}());
