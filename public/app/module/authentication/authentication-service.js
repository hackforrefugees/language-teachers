'use strict';

angular.module('sweTea.authentication-service', ['ngStorage'])
    .factory('AuthenticationService', function ($sessionStorage) {

        var $storage = $sessionStorage.$default({
            authToken: ''
        });

        return {
            getAuthToken: function () {
                return $storage.authToken;
            },
            setAuthToken: function (value, temporary) {
                $storage.authToken = value;
                $storage.temporaryLogin = !!temporary;
            },
            isLoggedIn: function () {
                return $storage.authToken.length > 0 && !$storage.temporaryLogin;
            },
            hasTokenOnHold: function () {
                return !!$storage._authTokenHold;
            },
            logout: function () {
                $storage.authToken = '';
            }
        }
    });

