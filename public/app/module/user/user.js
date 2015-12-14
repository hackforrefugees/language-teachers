'use strict';

angular.module('sweTea.user', ['ngResource'])
    .factory('User', function ($resource, $rootScope) {
        return $resource('/user/:id', {id: "@idUser"}, {
            'login': {url: $rootScope.appUrl+'/user/login', method: 'POST'},
            'logout': {url: $rootScope.appUrl+'/user/logout', method: 'GET'}
        });
    });