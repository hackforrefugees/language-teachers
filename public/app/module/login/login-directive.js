'use strict';

angular.module('sweTea.login-directive', ['sweTea.login'])
    .directive('sweTeaLoginForm', function(){
        return {
            templateUrl: 'module/login/login.html',
            controller: 'LoginController',
            controllerAs: 'loginCtrl'
        }
    });