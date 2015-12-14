'use strict';

angular.module('sweTea.form-functions', [])
    .run(function ($rootScope) {
        $rootScope.applyNumberUnlimited = function(form, value) {
            if(value < 0) {
                form.fieldAddonLeft = "unlimited";
            } else {
                delete form.fieldAddonLeft;
            }
        };

    });