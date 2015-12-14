'use strict';

angular.module('sweTea.profile', ['ngRoute', 'sweTea.profile-service', 'mm.acl'])
    .config(function ($routeProvider) {
        $routeProvider.when('/profile', {
            templateUrl: 'module/profile/profile.html',
            controller: 'ProfileController as profileCtrl'
        });
    })
    .controller('ProfileController', function (ProfileService, $location, $scope, AclService) {
        var self = this;
        ProfileService.getProfileData().then(function(response){
            self.user = response.data;
        });
    });