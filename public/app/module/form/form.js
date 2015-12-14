'use strict';

angular.module('sweTea.form', ['ngResource'])
    .factory('Form', function ($resource) {
        return $resource('/forms/:type');
    });
