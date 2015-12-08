angular.module('myApp').controller('LoginCtrl', function($scope, $http, dataservice){
        var vm = $scope;
        vm.login = function(loginForm){
            dataservice.login(loginForm.email, loginForm.password, loginForm.rememberMe);
        };

    });