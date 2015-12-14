'use strict';

angular.module('sweTea.resource-service', ['ngResource'])
    .factory('ResourceService', function($resource) {

        return {
            extend: function (Source, actions) {
                var Extended = angular.copy(Source);
                var Addition = $resource('', {}, actions);
                angular.forEach(actions, function (action, key) {
                    Extended[key] = Addition[key];
                    Extended.prototype['$' + key] = Addition.prototype['$' + key];
                });
                return Extended;
            }
        };
    });