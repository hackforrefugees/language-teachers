'use strict';

angular.module('sweTea.login-service', [])
    .factory('LoginService', function ($http) {
        return {
            getLoginForm: function(){
                return $http.get('module/login/login.json');
            }
        }
    });