'use strict';

angular.module('sweTea.profile-service', [])
    .factory('ProfileService', function ($http, $rootScope) {
        return {
            getProfileData: function(){
                return $http({
                    method: 'GET',
                    url: $rootScope.appUrl+'/user'
                });
            }
        }
    });