'use strict';

angular.module('sweTea.logout-service', ['sweTea.authentication-service', 'sweTea.authorization-service'])
    .factory('LogoutService', function (AuthenticationService, AuthorizationService, $location) {
        return {
            logout: function () {
                AuthenticationService.logout();
                AuthorizationService.logout();
                $location.path('/');
            }
        };
    });