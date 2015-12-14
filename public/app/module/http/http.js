'use strict';

angular.module('sweTea.http', ['sweTea.interceptor-service'])

    .config(['$httpProvider', function ($httpProvider) {
        $httpProvider.interceptors.push('InterceptorService');
    }]);