'use strict';

angular.module('sweTea.form-validation-service', ['ngToast'])
    .factory('FormValidationService', function(ngToast) {

        function generateInvalidMessage() {
            ngToast.create({
                className: 'danger',
                content: 'Error while processing the form. Please check for errors and try again.'
            });
        }

        return {
            isFormValid: function (form) {
                if(form.$invalid) {
                    generateInvalidMessage();
                    return false;
                }

                return true;
            }
        };
    });