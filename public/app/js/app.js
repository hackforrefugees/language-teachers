'use strict';

angular.module('sweTea', [
        'ngRoute',
        'schemaForm',
        'mm.acl',
        'sweTea.authentication',
        'sweTea.authorization',
        'sweTea.login',
        'sweTea.logout',
        'sweTea.profile',
        'sweTea.http',
        'ngToast',
        'angular-loading-bar',
        'sweTea.login-directive',
    ])
    .config(function ($routeProvider, AclServiceProvider) {
        var myConfig = {
            storage: 'sessionStorage',
            storageKey: 'SweTea'
        };
        AclServiceProvider.config(myConfig);
    })
    .run(function (AclService, $rootScope, AuthorizationService, AuthenticationService) {
        var aclData = {
            guest: ['login'],
            student: ['logout', 'profile', 'searchEvents', 'applyForEvent'],
            volunteer: ['logout', 'profile', 'searchEvents', 'applyForEvent', 'createEvent'],
            organisation: ['logout', 'profile', 'createEvent'],
            admin: ['logout', 'profile']
        };

        AclService.setAbilities(aclData);
        if ($rootScope.isLoggedIn()) {
            AclService.attachRole(AuthorizationService.getAclRole());
        } else {
            AclService.attachRole('guest');
        }
        AuthenticationService.setScripts();
        $rootScope.can = AclService.can;
        $rootScope.appUrl = "http://language.teacher.backend.se";
    });