'use strict';

angular.module('sweTea.authorization', ['sweTea.authorization-service'])
    .run(function (AuthorizationService, $rootScope) {
        $rootScope.isAdmin = AuthorizationService.isAdmin.bind(AuthorizationService);
        $rootScope.isStudent = AuthorizationService.isStudent.bind(AuthorizationService);
        $rootScope.isVolunteer = AuthorizationService.isVolunteer.bind(AuthorizationService);
        $rootScope.isOrganisation = AuthorizationService.isOrganisation.bind(AuthorizationService);
    });