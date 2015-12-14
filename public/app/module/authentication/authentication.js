'use strict';

angular.module('sweTea.authentication', ['sweTea.authentication-service', 'sweTea.authorization-service'])
    .run(function (AuthenticationService, $rootScope) {
        $rootScope.isLoggedIn = AuthenticationService.isLoggedIn.bind(AuthenticationService);
        $rootScope.hasTokenOnHold = AuthenticationService.hasTokenOnHold.bind(AuthenticationService);
    });