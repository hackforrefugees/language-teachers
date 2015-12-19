'use strict';

angular.module('sweTea.login', ['ngRoute', 'sweTea.login-service', 'sweTea.form-service', 'sweTea.user', 'sweTea.authentication-service', 'sweTea.authorization-service'])
    .controller('LoginController', function (LoginService, FormService, User, $location, $scope, AuthenticationService, AuthorizationService) {
        var self = this;
        var user = new User();
        LoginService.getLoginForm().then(function (response) {
            self.model = user;
            FormService.mapSchema(response.data, self);
        });
        self.submit = function (form) {
            $scope.$broadcast('schemaFormValidate');
            if (form.$valid) {
                user.$login(function (permission) {
                    if (permission.error === 0) {
                        AuthenticationService.setAuthToken(permission.authToken);
                        AuthenticationService.setScripts();
                        if (permission.userGroup === 'admin') {
                            AuthorizationService.setAdmin(1);
                        } else if (permission.userGroup === 'organisation') {
                            AuthorizationService.setOrganisation(1);
                        } else if (permission.userGroup === 'volunteer') {
                            AuthorizationService.setVolunteer(1);
                        } else if (permission.userGroup === 'student') {
                            AuthorizationService.setStudent(1);
                        }
                    } else {

                    }
                });
                $location.path('#/profile');
            }
        }
    });