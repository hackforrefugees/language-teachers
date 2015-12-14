'use strict';

angular.module('sweTea.logout', ['ngRoute', 'sweTea.logout-service'])
    .run(function (LogoutService, $rootScope) {
        $rootScope.logout = LogoutService.logout.bind(LogoutService);
    });