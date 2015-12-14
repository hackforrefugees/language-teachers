angular.module('sweTea.profile', ['ngRoute', 'sweTea.profile-service'])
    .config(function ($routeProvider) {
        $routeProvider.when('/profile', {
            templateUrl: 'module/profile/profile.html',
            controller: 'LoginController as loginCtrl'
        });
    })
    .controller('ProfileController', function ($location, ProfileService) {

    });