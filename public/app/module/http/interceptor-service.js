'use strict';

angular.module('sweTea.interceptor-service', ['sweTea.authentication-service', 'ngToast', 'ngRoute', 'sweTea.logout-service'])

    .factory('InterceptorService', function (AuthenticationService, $q, $location, ngToast, LogoutService) {

        var prependTransform = function (defaults, transform) {

            // We can't guarantee that the default transformation is an array
            defaults = angular.isArray(defaults) ? defaults : [defaults];
            var defaultsCopy = angular.copy(defaults);

            // Prepend the new transformation to the defaults
            defaultsCopy.unshift(transform);

            return defaultsCopy;
        };

        function dateToUnixTime(data) {
            angular.forEach(data, function (value, key) {
                if(value instanceof Date) {
                    data[key] = value.getTime();
                }
            });
        }

        function emptyObjectsToNull(data) {
            angular.forEach(data, function (value, key) {
                if(angular.toJson(value) == angular.toJson({})) {
                    data[key] = null;
                }
            });
        }

        function isMessage(response) {
            if(response.config.url.charAt(0) != '/') {
                return false;
            }
            if((/^((\/[^\/]+))+\.(html|xhtml|htm|xml|json)$/).test(response.config.url)) {
                return false;
            }
            if(!response.data) {
                return false;
            }
            if(!angular.isString(response.data)) {
                return false;
            }
            return true;
        }

        function convertToFormDataIfContainsFiles(data) {
            var files = {};
            angular.forEach(data, function (value, key) {
                if(value instanceof File || value instanceof Blob) {
                    files[key] = (value);
                }
            });

            if(Object.keys(files).length === 0) {
                return false;
            }

            var formData = new FormData();
            var copiedData = angular.copy(data);
            angular.forEach(files, function (file, key) {
                delete copiedData[key];
                var fileName = getFileNameIfBlobImage(file, key);
                if(angular.isDefined(fileName)) {
                    formData.append(key, file, fileName);
                } else {
                    formData.append(key, file);
                }

            });
            formData.append('data', angular.toJson(copiedData));

            return formData;
        }

        function getFileNameIfBlobImage(file, key) {
            if(file instanceof Blob && file.type && file.type.indexOf('image') === 0) {
                return (file.fileName || key) + '.' + file.type.replace('image/', '');
            }
            return;
        }

        return {
            request: function (config) {

                config.headers.authToken = AuthenticationService.getAuthToken();

                config.transformRequest = prependTransform(config.transformRequest, function (requestValue) {
                    if (!angular.isObject(requestValue)) {
                        return requestValue;
                    }

                    var formData = convertToFormDataIfContainsFiles(requestValue);

                    if(formData !== false) {

                        config.headers['Content-Type'] = undefined;

                        return formData;
                    } else {
                        var requestValueCopy = angular.copy(requestValue);

                        dateToUnixTime(requestValueCopy);
                        emptyObjectsToNull(requestValueCopy);

                        return requestValueCopy;
                    }
                });

                return config;
            },
            response: function (response) {

                if(isMessage(response)) {
                    ngToast.create({
                        className: 'success',
                        content: response.data
                    });
                }

                return response;
            },
            responseError: function (rejection) {

                if (rejection.status === 401) {

                    if($location.path() == '/login') {
                        ngToast.create({
                            className: 'danger',
                            content: 'Log in was unsuccessful with provided credentials'
                        });
                    } else {
                        ngToast.create({
                            className: 'warning',
                            content: 'Please log in to access the content'
                        });
                    }

                    LogoutService.logout();
                } else {

                    var errorMessage = rejection.data || 'An error occured: ' + rejection.status + ' ' + rejection.statusText;

                    ngToast.create({
                        className: 'danger',
                        content: errorMessage
                    });
                }

                return $q.reject(rejection);
            }
        };
    });

